@extends('dashboard.station.layout')
@section('title', 'Slot Manager — BijuliCar')
@section('page-title', 'Charging Slot Manager')

@section('content')

{{-- ── Configure total ports ──────────────────────────────────────── --}}
<div class="bg-white border border-slate-200 rounded-2xl p-6 mb-8 shadow-sm">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
        <div>
            <h2 class="text-base font-black text-slate-900 uppercase italic tracking-tight">Station Configuration</h2>
            <p class="text-xs text-slate-400 font-medium mt-0.5">Set how many charging ports your station has.</p>
        </div>
        @if ($location)
            <span class="text-xs font-black text-emerald-600 bg-emerald-50 border border-emerald-100 px-3 py-1.5 rounded-lg">
                {{ $slots->count() }} port(s) configured
            </span>
        @endif
    </div>

    @if (!$location)
        <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 text-sm text-amber-700 font-bold">
            ⚠ You need to add your map location before configuring slots.
            <a href="{{ route('station.location.create') }}" class="underline ml-1">Add location →</a>
        </div>
    @else
        <form method="POST" action="{{ route('station.slots.configure') }}" class="flex items-end gap-4">
            @csrf
            <div class="flex-1 max-w-xs">
                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">
                    Total Charging Ports
                </label>
                <input type="number" name="total_slots" min="1" max="50"
                    value="{{ $location->total_slots ?: $slots->count() ?: 1 }}"
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-900 focus:outline-none focus:border-emerald-400 transition-colors">
            </div>
            <button type="submit"
                class="px-5 py-2.5 bg-slate-900 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-emerald-600 transition-colors">
                Save
            </button>
        </form>
    @endif
</div>

@if ($slots->count())

    @php
        $availableSlots = $slots->where('status', 'available');
        $pendingSlots   = $slots->where('status', 'pending');
        $occupiedSlots  = $slots->where('status', 'occupied');
    @endphp

    {{-- ── Summary bar ────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-5">
            <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-1">Available</p>
            <p class="text-3xl font-black text-emerald-700">{{ $availableSlots->count() }}</p>
        </div>
        <div class="bg-amber-50 border border-amber-100 rounded-2xl p-5 relative">
            <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest mb-1">Pending Requests</p>
            <p class="text-3xl font-black text-amber-700">{{ $pendingSlots->count() }}</p>
            @if ($pendingSlots->count())
                <span class="absolute top-4 right-4 w-2.5 h-2.5 bg-amber-400 rounded-full animate-pulse"></span>
            @endif
        </div>
        <div class="bg-red-50 border border-red-100 rounded-2xl p-5">
            <p class="text-[10px] font-black text-red-500 uppercase tracking-widest mb-1">Occupied</p>
            <p class="text-3xl font-black text-red-600">{{ $occupiedSlots->count() }}</p>
        </div>
        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5">
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Total Ports</p>
            <p class="text-3xl font-black text-slate-700">{{ $slots->count() }}</p>
        </div>
    </div>

    {{-- ── Pending requests — action required ─────────────────────── --}}
    @if ($pendingSlots->count())
    <div class="bg-white border border-amber-200 rounded-2xl shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-amber-100 bg-amber-50/60 flex items-center gap-2">
            <span class="w-2 h-2 bg-amber-400 rounded-full animate-pulse"></span>
            <h2 class="text-sm font-black text-amber-700 uppercase italic tracking-tight">
                Pending Requests — Action Required
            </h2>
        </div>

        <div class="divide-y divide-slate-100">
            @foreach ($pendingSlots as $slot)
            <div class="px-6 py-5">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">

                    {{-- Customer info --}}
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-black text-sm shrink-0">
                            #{{ $slot->slot_number }}
                        </div>
                        <div>
                            <p class="text-sm font-black text-slate-900">
                                {{ $slot->occupant->name ?? 'Unknown customer' }}
                            </p>
                            <p class="text-xs text-slate-400 font-medium mt-0.5">
                                {{ $slot->occupant->email ?? '—' }}
                            </p>
                            <p class="text-xs text-amber-600 font-bold mt-1">
                                Requesting Port #{{ $slot->slot_number }} · Submitted {{ $slot->updated_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>

                    {{-- Approve / Reject actions --}}
                    <div class="flex flex-wrap items-center gap-2 shrink-0">

                        {{-- Approve — opens inline form to set free_at time --}}
                        <button type="button"
                            onclick="document.getElementById('approve-form-{{ $slot->id }}').classList.toggle('hidden')"
                            class="px-4 py-2 bg-emerald-500 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-emerald-600 transition-colors">
                            Approve
                        </button>

                        {{-- Reject --}}
                        <button type="button"
                            onclick="document.getElementById('reject-modal-{{ $slot->id }}').classList.remove('hidden')"
                            class="px-4 py-2 border border-red-200 text-red-600 text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-red-50 transition-colors">
                            Reject
                        </button>

                    </div>
                </div>

                {{-- Inline approve form --}}
                <div id="approve-form-{{ $slot->id }}" class="hidden mt-4 bg-emerald-50 border border-emerald-100 rounded-xl p-5">
                    <form method="POST" action="{{ route('station.slots.approve', $slot) }}" class="flex flex-wrap items-end gap-4">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">
                                Estimated free-by time (optional)
                            </label>
                            <input type="datetime-local" name="free_at"
                                class="border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-900 focus:outline-none focus:border-emerald-400"
                                min="{{ now()->addMinutes(5)->format('Y-m-d\TH:i') }}">
                        </div>
                        <button type="submit"
                            class="px-5 py-2.5 bg-emerald-600 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-emerald-700 transition-colors">
                            Confirm &amp; Send Email
                        </button>
                        <button type="button"
                            onclick="document.getElementById('approve-form-{{ $slot->id }}').classList.add('hidden')"
                            class="px-5 py-2.5 border border-slate-200 text-slate-500 text-xs font-black uppercase tracking-widest rounded-xl hover:bg-slate-50 transition-colors">
                            Cancel
                        </button>
                    </form>
                </div>
            </div>

            {{-- Reject modal --}}
            <div id="reject-modal-{{ $slot->id }}"
                 class="hidden fixed inset-0 z-50 bg-black/40 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md">
                    <h3 class="text-sm font-black text-slate-900 uppercase italic mb-1">
                        Reject request — Port #{{ $slot->slot_number }}
                    </h3>
                    <p class="text-xs text-slate-400 font-medium mb-4">
                        Customer: {{ $slot->occupant->name ?? '—' }}
                    </p>
                    <form method="POST" action="{{ route('station.slots.reject', $slot) }}">
                        @csrf
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">
                            Reason (sent to customer by email)
                        </label>
                        <textarea name="rejection_reason" rows="3"
                            class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:border-red-400 mb-4"
                            placeholder="e.g. Port reserved for maintenance, all slots fully booked..."></textarea>
                        <div class="flex gap-3">
                            <button type="submit"
                                class="flex-1 py-2.5 bg-red-500 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-red-600 transition-colors">
                                Reject &amp; Email Customer
                            </button>
                            <button type="button"
                                onclick="document.getElementById('reject-modal-{{ $slot->id }}').classList.add('hidden')"
                                class="flex-1 py-2.5 border border-slate-200 text-slate-600 text-xs font-black uppercase tracking-widest rounded-xl hover:bg-slate-50 transition-colors">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @endforeach
        </div>
    </div>
    @endif

    {{-- ── All ports grid ──────────────────────────────────────────── --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-sm font-black text-slate-900 uppercase italic tracking-tight">All Charging Ports</h2>
            <p class="text-xs text-slate-400 font-medium mt-0.5">Manually toggle port status. Pending ports are locked until you approve or reject.</p>
        </div>

        <div class="divide-y divide-slate-100">
            @foreach ($slots as $slot)
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-6 py-4
                        {{ $slot->isPending()  ? 'bg-amber-50/40' : '' }}
                        {{ $slot->isOccupied() ? 'bg-red-50/30'   : '' }}">

                {{-- Port identity --}}
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-sm
                        {{ $slot->isAvailable() ? 'bg-emerald-100 text-emerald-700' : '' }}
                        {{ $slot->isPending()   ? 'bg-amber-100 text-amber-700'   : '' }}
                        {{ $slot->isOccupied()  ? 'bg-red-100 text-red-600'       : '' }}">
                        #{{ $slot->slot_number }}
                    </div>
                    <div>
                        <p class="text-sm font-black text-slate-900">Port {{ $slot->slot_number }}</p>

                        @if ($slot->isPending() && $slot->occupant)
                            <p class="text-xs text-amber-600 font-bold mt-0.5">
                                ⏳ {{ $slot->occupant->name }} requested · awaiting your approval
                            </p>

                        @elseif ($slot->isOccupied() && $slot->occupant)
                            <p class="text-xs text-slate-400 font-medium mt-0.5">
                                {{ $slot->occupant->name }}
                                @if ($slot->free_at)
                                    — free by {{ $slot->free_at->format('h:i A, d M') }}
                                    <span class="text-amber-500 font-bold">({{ $slot->freeAtLabel() }})</span>
                                @endif
                            </p>

                        @elseif ($slot->isOccupied())
                            <p class="text-xs text-slate-400 font-medium mt-0.5">Manually occupied</p>
                        @endif
                    </div>
                </div>

                {{-- Status badge --}}
                <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full border {{ $slot->statusColour() }}">
                    {{ ucfirst($slot->status) }}
                </span>

                {{-- Manual toggle — disabled for pending slots (must use approve/reject above) --}}
                @if ($slot->isPending())
                    <span class="text-[10px] text-amber-500 font-bold italic">
                        Approve or reject above ↑
                    </span>

                @else
                    <form method="POST" action="{{ route('station.slots.update', $slot) }}"
                          class="flex flex-wrap items-center gap-2">
                        @csrf @method('PATCH')

                        @if ($slot->isAvailable())
                            <input type="hidden" name="status" value="occupied">
                            <input type="datetime-local" name="free_at"
                                class="border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-bold text-slate-700 focus:outline-none focus:border-red-400"
                                min="{{ now()->addMinutes(5)->format('Y-m-d\TH:i') }}">
                            <button type="submit"
                                class="px-3 py-1.5 bg-red-500 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-red-600 transition-colors">
                                Mark Occupied
                            </button>

                        @else
                            <input type="hidden" name="status" value="available">
                            <button type="submit"
                                class="px-3 py-1.5 bg-emerald-500 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-emerald-600 transition-colors">
                                Mark Available
                            </button>
                        @endif
                    </form>
                @endif

            </div>
            @endforeach
        </div>
    </div>

@elseif ($location)
    <div class="bg-slate-50 border border-slate-200 rounded-2xl px-6 py-12 text-center">
        <p class="text-slate-400 font-bold text-sm">No ports configured yet.</p>
        <p class="text-slate-400 text-xs mt-1">Set the number of charging ports above and click Save.</p>
    </div>
@endif

@endsection