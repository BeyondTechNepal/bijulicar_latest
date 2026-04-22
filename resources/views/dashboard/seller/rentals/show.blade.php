@extends($layout)
@section('title', 'Rental Booking Detail')
@section('page-title', 'Rental Detail')

@section('content')

    <a href="{{ route($prefix . '.rentals.index') }}"
        class="inline-flex items-center gap-2 text-[11px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-700 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Rentals
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Car + renter details --}}
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
                                NRs {{ number_format($carRental->price_per_day) }} / day
                            </span>
                        </div>
                    </div>
                </div>
                @else
                <div class="flex items-center gap-4 py-2">
                    <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center shrink-0">
                        <span class="text-2xl opacity-20">🚗</span>
                    </div>
                    <div>
                        <p class="text-sm font-black text-slate-700">{{ $carRental->car_snapshot_name }}</p>
                        <p class="text-xs text-slate-400 font-medium mt-1">This listing has been removed. Booking record is preserved.</p>
                    </div>
                </div>
                @endif
            </div>

            {{-- Rental period --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Rental Period</p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Pickup</p>
                        <p class="text-base font-black text-slate-900">{{ $carRental->pickup_date->format('d M Y') }}</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">{{ $carRental->pickup_date->format('l') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Return</p>
                        <p class="text-base font-black text-slate-900">{{ $carRental->return_date->format('d M Y') }}</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">{{ $carRental->return_date->format('l') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Duration</p>
                        <p class="text-base font-black text-slate-900">{{ $carRental->total_days }} days</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Actual Return</p>
                        <p class="text-base font-black text-slate-900">
                            {{ $carRental->actual_return_date?->format('d M Y') ?? '—' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Renter contact --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Renter Contact</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Name</p>
                        <p class="text-sm font-bold text-slate-900">{{ $carRental->renter_name }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Phone</p>
                        <a href="tel:{{ $carRental->renter_phone }}"
                            class="text-sm font-bold text-blue-600 hover:underline">{{ $carRental->renter_phone }}</a>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Email</p>
                        <a href="mailto:{{ $carRental->renter_email }}"
                            class="text-sm font-bold text-blue-600 hover:underline truncate block">{{ $carRental->renter_email }}</a>
                    </div>
                </div>
                @if($carRental->notes)
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Renter's Note</p>
                    <p class="text-sm text-slate-600 font-medium leading-relaxed italic">"{{ $carRental->notes }}"</p>
                </div>
                @endif
            </div>

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

        {{-- Right: Summary + actions --}}
        <div class="space-y-5">

            {{-- Booking summary --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Booking Summary</p>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-slate-400">Booking ID</p>
                        <p class="text-xs font-mono text-slate-700">#{{ str_pad($carRental->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-slate-400">Requested On</p>
                        <p class="text-xs font-bold text-slate-700">{{ $carRental->created_at->format('d M Y') }}</p>
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

                {{-- Confirm (pending only) --}}
                @if($carRental->isPending())
                <form method="POST" action="{{ route($prefix . '.rentals.confirm', $carRental) }}"
                    onsubmit="return confirm('Confirm this rental booking? The renter will be notified.')">
                    @csrf @method('PATCH')
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-slate-900 text-white py-3 rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-blue-600 transition-all shadow-lg">
                        ✓ Confirm Booking
                    </button>
                </form>
                @endif

                {{-- Activate (confirmed only) --}}
                @if($carRental->isConfirmed())
                <form method="POST" action="{{ route($prefix . '.rentals.activate', $carRental) }}"
                    onsubmit="return confirm('Mark car as picked up? This will set the rental as active.')">
                    @csrf @method('PATCH')
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-green-600 text-white py-3 rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-green-700 transition-all shadow-lg">
                        🚗 Mark as Picked Up
                    </button>
                </form>
                @endif

                {{-- Complete (active only) --}}
                @if($carRental->isActive())
                <form method="POST" action="{{ route($prefix . '.rentals.complete', $carRental) }}"
                    onsubmit="return confirm('Mark car as returned and complete the rental?')">
                    @csrf @method('PATCH')
                    <div class="mb-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">
                            Actual Return Date
                            <span class="normal-case font-medium text-slate-300">(optional, defaults to today)</span>
                        </label>
                        <input type="date" name="actual_return_date"
                            value="{{ today()->format('Y-m-d') }}"
                            max="{{ today()->format('Y-m-d') }}"
                            class="w-full border border-slate-200 rounded-xl py-2.5 px-4 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-200 outline-none">
                    </div>
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-slate-900 text-white py-3 rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all shadow-lg">
                        ✓ Mark as Returned
                    </button>
                </form>
                @endif

                {{-- Cancel (pending or confirmed only) --}}
                @if($carRental->isCancellable())
                <div x-data="{ open: false }">
                    <button type="button" @click="open = !open"
                        class="w-full flex items-center justify-center gap-2 bg-red-50 text-red-600 border border-red-100 py-3 rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-red-100 transition-all">
                        Cancel Booking
                    </button>
                    <div x-show="open" x-cloak class="mt-3">
                        <form method="POST" action="{{ route($prefix . '.rentals.cancel', $carRental) }}">
                            @csrf @method('PATCH')
                            <textarea name="cancellation_reason" rows="3" placeholder="Reason for cancellation (optional)"
                                class="w-full border border-slate-200 rounded-xl py-2.5 px-4 text-sm font-medium text-slate-700 placeholder:text-slate-300 focus:ring-2 focus:ring-red-100 outline-none resize-none mb-2"></textarea>
                            <button type="submit"
                                class="w-full py-2.5 bg-red-600 text-white rounded-xl text-[11px] font-black uppercase tracking-widest hover:bg-red-700 transition-all">
                                Confirm Cancel
                            </button>
                        </form>
                    </div>
                </div>
                @endif

                {{-- Terminal states --}}
                @if($carRental->isCompleted())
                <p class="text-center text-[11px] font-black text-green-600 uppercase tracking-widest py-2">
                    ✓ Rental Completed
                </p>
                @endif

                @if($carRental->isCancelled())
                <p class="text-center text-[11px] font-black text-slate-400 uppercase tracking-widest py-2">
                    This booking was cancelled
                </p>
                @endif
            </div>

            {{-- Lifecycle progress indicator --}}
            @if(!$carRental->isCancelled())
            <div class="bg-slate-900 rounded-2xl p-5">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4">Booking Progress</p>
                @php
                    $steps = [
                        ['label' => 'Pending',   'status' => 'pending'],
                        ['label' => 'Confirmed', 'status' => 'confirmed'],
                        ['label' => 'Active',    'status' => 'active'],
                        ['label' => 'Completed', 'status' => 'completed'],
                    ];
                    $order = ['pending' => 0, 'confirmed' => 1, 'active' => 2, 'completed' => 3];
                    $current = $order[$carRental->status] ?? 0;
                @endphp
                <div class="space-y-2">
                    @foreach($steps as $i => $step)
                    <div class="flex items-center gap-3">
                        @if($i < $current)
                            <span class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </span>
                        @elseif($i === $current)
                            <span class="w-5 h-5 rounded-full bg-blue-500 flex items-center justify-center shrink-0">
                                <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                            </span>
                        @else
                            <span class="w-5 h-5 rounded-full border border-slate-600 shrink-0"></span>
                        @endif
                        <span class="text-xs font-bold {{ $i <= $current ? 'text-white' : 'text-slate-600' }}">
                            {{ $step['label'] }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>

@endsection