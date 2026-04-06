@extends('dashboard.garage.layout')
@section('title', 'Appointments — BijuliCar')
@section('page-title', 'Appointment Manager')

@section('content')

{{-- ── Bay configuration ────────────────────────────────────────────── --}}
<div class="bg-white border border-slate-200 rounded-2xl p-6 mb-8 shadow-sm">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
        <div>
            <h2 class="text-base font-black text-slate-900 uppercase italic tracking-tight">Garage Configuration</h2>
            <p class="text-xs text-slate-400 font-medium mt-0.5">Set how many service bays your garage has.</p>
        </div>
        @if ($location && $location->total_slots)
            <span class="text-xs font-black text-purple-600 bg-purple-50 border border-purple-100 px-3 py-1.5 rounded-lg">
                {{ $location->total_slots }} bay(s) configured
            </span>
        @endif
    </div>

    @if (!$location)
        <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 text-sm text-amber-700 font-bold">
            ⚠ Add your map location first before configuring bays.
            <a href="{{ route('garage.location.create') }}" class="underline ml-1">Add location →</a>
        </div>
    @else
        <form method="POST" action="{{ route('garage.bays.configure') }}" class="flex flex-wrap items-end gap-4">
            @csrf
            <div>
                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">
                    Total Service Bays
                </label>
                <input type="number" name="total_slots" min="1" max="30"
                    value="{{ $location->total_slots ?: 1 }}"
                    class="w-32 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-900 focus:outline-none focus:border-purple-400 transition-colors">
            </div>
            <div class="flex items-center gap-2 pb-2">
                <input type="checkbox" name="accepts_walkins" id="walkins" value="1"
                    {{ $location->accepts_walkins ? 'checked' : '' }}
                    class="w-4 h-4 rounded border-slate-300 text-purple-600">
                <label for="walkins" class="text-xs font-bold text-slate-600">Accept walk-ins</label>
            </div>
            <button type="submit"
                class="px-5 py-2.5 bg-slate-900 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-purple-700 transition-colors">
                Save
            </button>
        </form>
    @endif
</div>

{{-- ── Summary bar ──────────────────────────────────────────────────── --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-amber-50 border border-amber-100 rounded-2xl p-5">
        <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest mb-1">Pending</p>
        <p class="text-3xl font-black text-amber-700">{{ $pending->count() }}</p>
    </div>
    <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-5">
        <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-1">Approved</p>
        <p class="text-3xl font-black text-emerald-700">{{ $approved->count() }}</p>
    </div>
    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5">
        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Completed</p>
        <p class="text-3xl font-black text-slate-700">{{ $completed->count() }}</p>
    </div>
    <div class="bg-red-50 border border-red-100 rounded-2xl p-5">
        <p class="text-[10px] font-black text-red-500 uppercase tracking-widest mb-1">Rejected</p>
        <p class="text-3xl font-black text-red-600">{{ $rejected->count() }}</p>
    </div>
</div>

{{-- ── Pending appointments — action required ──────────────────────── --}}
@if ($pending->count())
<div class="bg-white border border-amber-200 rounded-2xl shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-amber-100 bg-amber-50/50 flex items-center gap-2">
        <span class="w-2 h-2 bg-amber-400 rounded-full animate-pulse"></span>
        <h2 class="text-sm font-black text-amber-700 uppercase italic tracking-tight">Pending — Action Required</h2>
    </div>

    <div class="divide-y divide-slate-100">
        @foreach ($pending as $appt)
        <div class="px-6 py-5">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-black text-slate-900">{{ $appt->customer->name }}</p>
                    <p class="text-xs text-slate-400 font-medium mt-0.5">{{ $appt->customer->email }}</p>
                    <p class="text-xs text-slate-600 font-bold mt-2">
                        Service: <span class="text-slate-900">{{ $appt->service_description }}</span>
                    </p>
                    <p class="text-xs text-slate-600 font-bold mt-0.5">
                        Requested: <span class="text-slate-900">{{ $appt->requested_at->format('D, d M Y — h:i A') }}</span>
                    </p>
                </div>

                <div class="flex flex-wrap gap-2 shrink-0">
                    {{-- Approve button (opens inline form) --}}
                    <button type="button"
                        onclick="document.getElementById('approve-form-{{ $appt->id }}').classList.toggle('hidden')"
                        class="px-3 py-1.5 bg-emerald-500 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-emerald-600 transition-colors">
                        Approve
                    </button>

                    {{-- Reject button --}}
                    <button type="button"
                        onclick="document.getElementById('reject-modal-{{ $appt->id }}').classList.remove('hidden')"
                        class="px-3 py-1.5 border border-red-200 text-red-600 text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-red-50 transition-colors">
                        Reject
                    </button>
                </div>
            </div>

            {{-- Inline approve form --}}
            <div id="approve-form-{{ $appt->id }}" class="hidden mt-4 bg-emerald-50 border border-emerald-100 rounded-xl p-5">
                <form method="POST" action="{{ route('garage.appointments.approve', $appt) }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Bay Number</label>
                            <input type="number" name="bay_number" min="1"
                                max="{{ $location->total_slots ?? 10 }}"
                                class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold focus:outline-none focus:border-emerald-400"
                                placeholder="e.g. 2">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Est. Finish Time</label>
                            <input type="datetime-local" name="estimated_finish_at"
                                class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold focus:outline-none focus:border-emerald-400"
                                min="{{ $appt->requested_at->format('Y-m-d\TH:i') }}">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Note to customer</label>
                            <input type="text" name="garage_note"
                                class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold focus:outline-none focus:border-emerald-400"
                                placeholder="Optional note...">
                        </div>
                    </div>
                    <button type="submit"
                        class="px-5 py-2.5 bg-emerald-600 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-emerald-700 transition-colors">
                        Confirm &amp; Send Email
                    </button>
                </form>
            </div>

            {{-- Reject modal --}}
            <div id="reject-modal-{{ $appt->id }}"
                 class="hidden fixed inset-0 z-50 bg-black/40 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md">
                    <h3 class="text-sm font-black text-slate-900 uppercase italic mb-4">Reject appointment</h3>
                    <form method="POST" action="{{ route('garage.appointments.reject', $appt) }}">
                        @csrf
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">
                            Reason (sent to customer) <span class="text-red-400">*</span>
                        </label>
                        <textarea name="rejection_reason" rows="3" required
                            class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 focus:outline-none focus:border-red-400 mb-4"
                            placeholder="e.g. Fully booked on that day..."></textarea>
                        <div class="flex gap-3">
                            <button type="submit"
                                class="flex-1 py-2.5 bg-red-500 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-red-600 transition-colors">
                                Reject &amp; Email Customer
                            </button>
                            <button type="button"
                                onclick="document.getElementById('reject-modal-{{ $appt->id }}').classList.add('hidden')"
                                class="flex-1 py-2.5 border border-slate-200 text-slate-600 text-xs font-black uppercase tracking-widest rounded-xl hover:bg-slate-50 transition-colors">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ── Approved / in-progress ───────────────────────────────────────── --}}
@if ($approved->count())
<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-slate-100">
        <h2 class="text-sm font-black text-slate-900 uppercase italic tracking-tight">In Progress</h2>
    </div>
    <div class="divide-y divide-slate-100">
        @foreach ($approved as $appt)
        <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <p class="text-sm font-black text-slate-900">{{ $appt->customer->name }}</p>
                <p class="text-xs text-slate-500 font-medium mt-0.5">
                    {{ $appt->service_description }}
                    @if ($appt->bay_number) — Bay #{{ $appt->bay_number }} @endif
                </p>
                @if ($appt->estimated_finish_at)
                <p class="text-xs text-emerald-600 font-bold mt-0.5">
                    Est. finish: {{ $appt->estimated_finish_at->format('h:i A, d M') }}
                </p>
                @endif
            </div>
            <form method="POST" action="{{ route('garage.appointments.complete', $appt) }}">
                @csrf
                <button type="submit"
                    class="px-3 py-1.5 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-emerald-700 transition-colors">
                    Mark Complete
                </button>
            </form>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ── Completed & rejected history ────────────────────────────────── --}}
@if ($completed->count() || $rejected->count())
<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100">
        <h2 class="text-sm font-black text-slate-500 uppercase italic tracking-tight">History</h2>
    </div>
    <div class="divide-y divide-slate-100">
        @foreach ($completed->concat($rejected)->sortByDesc('updated_at') as $appt)
        <div class="px-6 py-4 flex items-center justify-between gap-3">
            <div>
                <p class="text-sm font-bold text-slate-700">{{ $appt->customer->name }}</p>
                <p class="text-xs text-slate-400 font-medium">{{ $appt->service_description }} — {{ $appt->requested_at->format('d M Y') }}</p>
            </div>
            <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full border {{ $appt->statusColour() }}">
                {{ ucfirst($appt->status) }}
            </span>
        </div>
        @endforeach
    </div>
</div>
@endif

@if ($appointments->isEmpty())
<div class="bg-slate-50 border border-slate-200 rounded-2xl px-6 py-12 text-center">
    <p class="text-slate-400 font-bold text-sm">No appointment requests yet.</p>
    <p class="text-slate-400 text-xs mt-1">Customers will be able to book from the map once your location is active.</p>
</div>
@endif

@endsection