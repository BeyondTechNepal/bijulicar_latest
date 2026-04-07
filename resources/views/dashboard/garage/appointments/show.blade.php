@extends('dashboard.garage.layout')
@section('title', 'Appointment Detail — BijuliCar')
@section('page-title', 'Appointment Detail')

@section('content')

<div class="mb-6">
    <a href="{{ route('garage.appointments.index') }}"
        class="inline-flex items-center gap-2 text-xs font-black text-slate-500 uppercase tracking-widest hover:text-slate-900 transition-colors">
        ← Back to Appointments
    </a>
</div>

<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden max-w-2xl">

    {{-- Header --}}
    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h2 class="text-base font-black text-slate-900 uppercase italic tracking-tight">
                {{ $appointment->customer->name }}
            </h2>
            <p class="text-xs text-slate-400 font-medium mt-0.5">{{ $appointment->customer->email }}</p>
        </div>
        <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full border {{ $appointment->statusColour() }}">
            {{ ucfirst($appointment->status) }}
        </span>
    </div>

    {{-- Details --}}
    <div class="px-6 py-6 space-y-4">
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Service Requested</p>
            <p class="text-sm font-bold text-slate-900">{{ $appointment->service_description }}</p>
        </div>

        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Requested At</p>
            <p class="text-sm font-bold text-slate-900">{{ $appointment->requested_at->format('D, d M Y — h:i A') }}</p>
        </div>

        @if ($appointment->bay_number)
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Assigned Bay</p>
            <p class="text-sm font-bold text-slate-900">Bay #{{ $appointment->bay_number }}</p>
        </div>
        @endif

        @if ($appointment->estimated_finish_at)
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Est. Finish Time</p>
            <p class="text-sm font-bold text-emerald-700">{{ $appointment->estimated_finish_at->format('D, d M Y — h:i A') }}</p>
        </div>
        @endif

        @if ($appointment->garage_note)
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Garage Note</p>
            <p class="text-sm font-bold text-slate-900">{{ $appointment->garage_note }}</p>
        </div>
        @endif

        @if ($appointment->rejection_reason)
        <div>
            <p class="text-[10px] font-black text-red-400 uppercase tracking-widest mb-1">Rejection Reason</p>
            <p class="text-sm font-bold text-red-700">{{ $appointment->rejection_reason }}</p>
        </div>
        @endif
    </div>

    {{-- Actions --}}
    @if ($appointment->isPending() || $appointment->isApproved())
    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex flex-wrap gap-3">
        @if ($appointment->isPending())
            <a href="{{ route('garage.appointments.index') }}"
               class="px-4 py-2 bg-emerald-500 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-emerald-600 transition-colors">
                ← Manage from Dashboard
            </a>
        @endif

        @if ($appointment->isApproved())
            <form method="POST" action="{{ route('garage.appointments.complete', $appointment) }}">
                @csrf
                <button type="submit"
                    class="px-4 py-2 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-emerald-700 transition-colors">
                    Mark Complete
                </button>
            </form>
        @endif
    </div>
    @endif

</div>

@endsection