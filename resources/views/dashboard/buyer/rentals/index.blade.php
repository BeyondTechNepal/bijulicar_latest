@extends('dashboard.buyer.layout')
@section('title', 'My Rentals')
@section('page-title', 'My Rentals')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Buyer Portal</p>
            <p class="text-sm font-bold text-slate-600 mt-0.5">All rental bookings you have placed on BijuliCar.</p>
        </div>
        <a href="{{ route('rent') }}"
            class="inline-flex items-center gap-2 bg-slate-900 text-white px-4 py-2.5 rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-blue-600 transition-all shadow-lg">
            🚗 Browse Rentals
        </a>
    </div>

    @if($rentals->isNotEmpty())
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">

        {{-- Table header --}}
        <div class="grid grid-cols-12 gap-4 px-6 py-3 border-b border-slate-100 bg-slate-50">
            <div class="col-span-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Vehicle</div>
            <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Dates</div>
            <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Total</div>
            <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</div>
            <div class="col-span-1 text-[10px] font-black text-slate-400 uppercase tracking-widest">Days</div>
            <div class="col-span-1"></div>
        </div>

        {{-- Rows --}}
        @foreach($rentals as $rental)
        <div class="grid grid-cols-12 gap-4 px-6 py-4 border-b border-slate-100 last:border-0 items-center hover:bg-slate-50/50 transition-colors">

            {{-- Vehicle --}}
            <div class="col-span-4 flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-[10px] font-black text-blue-500 uppercase shrink-0">
                    🚗
                </div>
                <div>
                    <p class="text-sm font-black text-slate-900">{{ $rental->carDisplayName() }}</p>
                    <p class="text-[11px] text-slate-400 font-medium mt-0.5">
                        NRs {{ number_format($rental->price_per_day) }} / day
                    </p>
                </div>
            </div>

            {{-- Dates --}}
            <div class="col-span-2">
                <p class="text-[11px] font-bold text-slate-700">{{ $rental->pickup_date->format('d M Y') }}</p>
                <p class="text-[10px] text-slate-400 font-medium">→ {{ $rental->return_date->format('d M Y') }}</p>
            </div>

            {{-- Total --}}
            <div class="col-span-2">
                <p class="text-sm font-black text-slate-800">NRs {{ number_format($rental->total_price) }}</p>
                @if($rental->deposit_amount)
                    <p class="text-[10px] text-slate-400 font-medium">+NRs {{ number_format($rental->deposit_amount) }} deposit</p>
                @endif
            </div>

            {{-- Status --}}
            <div class="col-span-2">
                <span @class([
                    'text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider',
                    'bg-yellow-100 text-yellow-700' => $rental->status === 'pending',
                    'bg-blue-100 text-blue-700'     => $rental->status === 'confirmed',
                    'bg-green-100 text-green-700'   => $rental->status === 'active',
                    'bg-slate-100 text-slate-600'   => $rental->status === 'completed',
                    'bg-red-100 text-red-600'       => $rental->status === 'cancelled',
                ])>{{ $rental->statusLabel() }}</span>
            </div>

            {{-- Days --}}
            <div class="col-span-1">
                <p class="text-sm font-black text-slate-700">{{ $rental->total_days }}d</p>
            </div>

            {{-- Action --}}
            <div class="col-span-1 flex justify-end">
                <a href="{{ route('buyer.rentals.show', $rental) }}"
                    class="w-8 h-8 bg-slate-100 hover:bg-slate-900 hover:text-white rounded-xl flex items-center justify-center transition-all group">
                    <svg class="w-4 h-4 text-slate-500 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

        </div>
        @endforeach
    </div>

    @if($rentals->hasPages())
    <div class="mt-5">{{ $rentals->links() }}</div>
    @endif

    @else
    {{-- Empty state --}}
    <div class="bg-white border border-dashed border-slate-200 rounded-2xl p-14 text-center">
        <p class="text-5xl mb-4">🚗</p>
        <p class="font-black text-slate-900 uppercase italic tracking-tight text-lg">No rentals yet</p>
        <p class="text-sm text-slate-500 font-medium mt-2 mb-6">
            Browse rentable cars and book your first rental.
        </p>
        <a href="{{ route('rent') }}"
            class="inline-flex items-center gap-2 bg-slate-900 text-white px-6 py-3 rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-blue-600 transition-all shadow-lg">
            🚗 Browse Rentals
        </a>
    </div>
    @endif

@endsection