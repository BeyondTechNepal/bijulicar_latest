@extends('dashboard.buyer.layout')
@section('title', 'Rental Booking Detail')
@section('page-title', 'Rental Detail')

@section('content')

    <a href="{{ route('buyer.rentals.index') }}"
        class="inline-flex items-center gap-2 text-[11px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-700 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to My Rentals
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Car details + rental notes --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Car card --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Vehicle</p>

                @if($carRental->car)
                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 bg-slate-100 rounded-2xl overflow-hidden shrink-0 flex items-center justify-center">
                        @if($carRental->car->primaryImage)
                            <img src="{{ Storage::url($carRental->car->primaryImage->path) }}" class="w-full h-full object-cover" alt="{{ $carRental->carDisplayName() }}">
                        @else
                            <span class="text-2xl opacity-20">🚗</span>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-black text-slate-900 uppercase italic tracking-tight">
                            {{ $carRental->car->displayName() }}
                        </h2>
                        <p class="text-sm text-slate-500 font-medium mt-1">
                            {{ ucfirst($carRental->car->condition) }} ·
                            {{ $carRental->car->color ?? '—' }} ·
                            {{ $carRental->car->location }}
                        </p>
                        <div class="flex flex-wrap gap-2 mt-3">
                            <span class="text-[10px] font-black px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg uppercase tracking-wider">
                                {{ number_format($carRental->car->mileage) }} km
                            </span>
                            <span class="text-[10px] font-black px-2.5 py-1 bg-blue-50 text-blue-700 rounded-lg uppercase tracking-wider border border-blue-100">
                                {{ $carRental->car->rentDurationLabel() }}
                            </span>
                        </div>
                    </div>
                </div>

                @if($carRental->car->description)
                <div class="mt-5 pt-5 border-t border-slate-100">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">About this car</p>
                    <p class="text-sm text-slate-600 font-medium leading-relaxed">{{ $carRental->car->description }}</p>
                </div>
                @endif

                @else
                <div class="flex items-center gap-4 py-2">
                    <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center shrink-0">
                        <span class="text-2xl opacity-20">🚗</span>
                    </div>
                    <div>
                        <p class="text-sm font-black text-slate-700">{{ $carRental->car_snapshot_name }}</p>
                        <p class="text-xs text-slate-400 font-medium mt-1">This listing has been removed. Your booking record is preserved.</p>
                    </div>
                </div>
                @endif
            </div>

            {{-- Rental period card --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Rental Period</p>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Pickup Date</p>
                        <p class="text-lg font-black text-slate-900">{{ $carRental->pickup_date->format('d M Y') }}</p>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">{{ $carRental->pickup_date->format('l') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Return Date</p>
                        <p class="text-lg font-black text-slate-900">{{ $carRental->return_date->format('d M Y') }}</p>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">{{ $carRental->return_date->format('l') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Duration</p>
                        <p class="text-lg font-black text-slate-900">{{ $carRental->total_days }} days</p>
                    </div>
                    @if($carRental->actual_return_date)
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Actual Return</p>
                        <p class="text-lg font-black text-slate-900">{{ $carRental->actual_return_date->format('d M Y') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Renter note --}}
            @if($carRental->notes)
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Your Note to Owner</p>
                <p class="text-sm text-slate-600 font-medium leading-relaxed italic">"{{ $carRental->notes }}"</p>
            </div>
            @endif

            {{-- Cancellation info --}}
            @if($carRental->isCancelled() && $carRental->cancellation_reason)
            <div class="bg-red-50 border border-red-100 rounded-2xl p-6">
                <p class="text-[10px] font-black text-red-500 uppercase tracking-widest mb-2">Cancellation</p>
                <p class="text-sm text-slate-700 font-medium">{{ $carRental->cancellation_reason }}</p>
                @if($carRental->cancelled_by)
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-2">
                        Cancelled by: {{ ucfirst($carRental->cancelled_by) }}
                    </p>
                @endif
            </div>
            @endif

        </div>

        {{-- Right: Booking summary + actions --}}
        <div class="space-y-5">

            {{-- Summary card --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Booking Summary</p>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-slate-400">Booking ID</p>
                        <p class="text-xs font-mono text-slate-700">#{{ str_pad($carRental->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-slate-400">Booked On</p>
                        <p class="text-xs font-bold text-slate-700">{{ $carRental->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-slate-400">Owner</p>
                        <p class="text-xs font-bold text-slate-700">{{ $carRental->owner?->name ?? '—' }}</p>
                    </div>

                    <div class="pt-3 border-t border-slate-100 space-y-2">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-bold text-slate-400">Daily Rate</p>
                            <p class="text-sm font-black text-slate-900">NRs {{ number_format($carRental->price_per_day) }}</p>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-bold text-slate-400">Duration</p>
                            <p class="text-sm font-black text-slate-900">× {{ $carRental->total_days }} days</p>
                        </div>
                        @if($carRental->deposit_amount)
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-bold text-slate-400">Security Deposit</p>
                            <p class="text-sm font-black text-slate-900">NRs {{ number_format($carRental->deposit_amount) }}</p>
                        </div>
                        @endif
                        <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                            <p class="text-xs font-black text-slate-700 uppercase tracking-wider">Total Rental</p>
                            <p class="text-base font-black text-slate-900">NRs {{ number_format($carRental->total_price) }}</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                        <p class="text-xs font-bold text-slate-400">Status</p>
                        <span @class([
                            'text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider',
                            'bg-yellow-100 text-yellow-700' => $carRental->status === 'pending',
                            'bg-blue-100 text-blue-700'     => $carRental->status === 'confirmed',
                            'bg-green-100 text-green-700'   => $carRental->status === 'active',
                            'bg-slate-100 text-slate-600'   => $carRental->status === 'completed',
                            'bg-red-100 text-red-600'       => $carRental->status === 'cancelled',
                        ])>{{ $carRental->statusLabel() }}</span>
                    </div>
                </div>
            </div>

            {{-- Actions card --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-3">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Actions</p>

                @if($carRental->isCancellable())
                <form method="POST" action="{{ route('buyer.rentals.cancel', $carRental) }}"
                    onsubmit="return confirm('Are you sure you want to cancel this rental booking?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-red-50 text-red-600 border border-red-100 py-3 rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-red-100 transition-all">
                        Cancel Booking
                    </button>
                </form>
                @endif

                @if($carRental->isCancelled())
                <p class="text-center text-[11px] font-black text-slate-400 uppercase tracking-widest py-2">
                    This booking was cancelled
                </p>
                <a href="{{ route('rent') }}"
                    class="w-full flex items-center justify-center gap-2 bg-slate-900 text-white py-3 rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-blue-600 transition-all block text-center">
                    Browse Other Cars
                </a>
                @endif

                @if($carRental->isCompleted())
                @php
                    $hasReviewed = \App\Models\Review::where('buyer_id', auth()->id())
                        ->where('car_rental_id', $carRental->id)
                        ->exists();
                @endphp
                <p class="text-center text-[11px] font-black text-green-600 uppercase tracking-widest py-2">
                    ✓ Rental Completed
                </p>
                @can('write reviews')
                    @if(!$hasReviewed)
                        <a href="{{ route('buyer.reviews.create', ['rental_id' => $carRental->id]) }}"
                            class="w-full flex items-center justify-center gap-2 bg-slate-900 text-white py-3 rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all block text-center">
                            ⭐ Write a Review
                        </a>
                    @else
                        <p class="w-full flex items-center justify-center gap-2 bg-slate-50 text-slate-400 border border-slate-200 py-3 rounded-xl text-[11px] font-black uppercase italic tracking-widest text-center">
                            ✓ Review Submitted
                        </p>
                    @endif
                @endcan
                @endif
            </div>

            {{-- Status guide for active statuses --}}
            @if(in_array($carRental->status, ['pending', 'confirmed', 'active']))
            <div class="bg-slate-900 rounded-2xl p-5">
                <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-3">
                    @if($carRental->status === 'pending') ⏳ Awaiting Confirmation
                    @elseif($carRental->status === 'confirmed') ✅ Booking Confirmed
                    @else 🚗 Rental In Progress
                    @endif
                </p>
                <ul class="space-y-2">
                    @if($carRental->status === 'pending')
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-blue-400 mt-0.5">→</span>
                        Your booking request is waiting for the owner to confirm.
                    </li>
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-blue-400 mt-0.5">→</span>
                        You can cancel at any time while it's pending.
                    </li>
                    @elseif($carRental->status === 'confirmed')
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-blue-400 mt-0.5">→</span>
                        The owner has confirmed your rental dates.
                    </li>
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-blue-400 mt-0.5">→</span>
                        Pick up the car on <strong class="text-white">{{ $carRental->pickup_date->format('d M Y') }}</strong>.
                    </li>
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-blue-400 mt-0.5">→</span>
                        Contact {{ $carRental->owner?->name ?? 'the owner' }} to arrange pickup details.
                    </li>
                    @else
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-blue-400 mt-0.5">→</span>
                        Your rental is active. Return the car by <strong class="text-white">{{ $carRental->return_date->format('d M Y') }}</strong>.
                    </li>
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-blue-400 mt-0.5">→</span>
                        The owner will mark it complete once the car is returned.
                    </li>
                    @endif
                </ul>
            </div>
            @endif

        </div>
    </div>

@endsection