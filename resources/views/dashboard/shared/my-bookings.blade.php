@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10 px-4">
    <div class="max-w-4xl mx-auto">

        <div class="mb-8">
            <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-1">Dashboard</p>
            <h1 class="text-3xl font-black text-slate-900 uppercase italic tracking-tight">My Bookings</h1>
            <p class="text-slate-400 text-sm font-medium mt-1">All your garage appointments and EV slot requests.</p>
        </div>

        {{-- ── Garage appointments ─────────────────────────────────── --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-sm font-black text-slate-900 uppercase italic tracking-tight">Garage Appointments</h2>
                <a href="{{ route('map_location') }}"
                   class="text-[10px] font-black text-emerald-600 uppercase tracking-widest hover:underline">
                    Book new →
                </a>
            </div>

            @if ($appointments->isEmpty())
                <div class="px-6 py-10 text-center">
                    <p class="text-slate-400 font-bold text-sm">No appointments yet.</p>
                    <p class="text-xs text-slate-300 mt-1">Find a garage on the map and book a service.</p>
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach ($appointments as $appt)
                    <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <p class="text-sm font-black text-slate-900">{{ $appt->garage->name }}</p>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">
                                {{ $appt->service_description }}
                            </p>
                            <p class="text-xs text-slate-400 mt-0.5">
                                {{ $appt->requested_at->format('D, d M Y — h:i A') }}
                                @if ($appt->estimated_finish_at)
                                    · Est. finish {{ $appt->estimated_finish_at->format('h:i A') }}
                                @endif
                            </p>
                            @if ($appt->garage_note)
                                <p class="text-xs text-slate-500 italic mt-1">Note: {{ $appt->garage_note }}</p>
                            @endif
                            @if ($appt->isRejected() && $appt->rejection_reason)
                                <p class="text-xs text-red-500 font-bold mt-1">Reason: {{ $appt->rejection_reason }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full border {{ $appt->statusColour() }}">
                                {{ ucfirst($appt->status) }}
                            </span>
                            {{-- Cancel button — only shown while still pending --}}
                            @if ($appt->isPending())
                                <form method="POST" action="{{ route('booking.garage.cancel', $appt->id) }}"
                                      onsubmit="return confirm('Cancel this appointment?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full border border-red-200 bg-red-50 text-red-600 hover:bg-red-100 transition">
                                        Cancel
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ── EV slot requests ─────────────────────────────────────── --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-sm font-black text-slate-900 uppercase italic tracking-tight">EV Slot Requests</h2>
                <a href="{{ route('map_location') }}"
                   class="text-[10px] font-black text-emerald-600 uppercase tracking-widest hover:underline">
                    Find station →
                </a>
            </div>

            @if ($slotRequests->isEmpty())
                <div class="px-6 py-10 text-center">
                    <p class="text-slate-400 font-bold text-sm">No slot requests yet.</p>
                    <p class="text-xs text-slate-300 mt-1">Find an EV station on the map and request a charging slot.</p>
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach ($slotRequests as $slot)
                    <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <p class="text-sm font-black text-slate-900">{{ $slot->station->name }}</p>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">Port #{{ $slot->slot_number }}</p>
                            @if ($slot->free_at)
                                <p class="text-xs text-amber-500 font-bold mt-0.5">
                                    Slot free by {{ $slot->free_at->format('h:i A, d M Y') }}
                                </p>
                            @endif
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full border
                               {{ $slot->isAvailable() ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : '' }}
                               {{ $slot->isPending()   ? 'bg-amber-50 text-amber-600 border-amber-200'       : '' }}
                               {{ $slot->isBooked()    ? 'bg-blue-50 text-blue-600 border-blue-200'          : '' }}
                               {{ $slot->isOccupied()  ? 'bg-red-50 text-red-600 border-red-200'             : '' }}">
                               {{ ucfirst($slot->status) }}
                            </span>
                            {{-- Cancel button — only shown while still pending --}}
                            @if ($slot->isPending())
                                <form method="POST" action="{{ route('booking.slot.cancel', $slot->id) }}"
                                      onsubmit="return confirm('Cancel this slot request? The slot will be freed immediately.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full border border-red-200 bg-red-50 text-red-600 hover:bg-red-100 transition">
                                        Cancel
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>
@endsection