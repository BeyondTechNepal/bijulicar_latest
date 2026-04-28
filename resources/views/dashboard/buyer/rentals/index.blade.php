@extends('dashboard.buyer.layout')
@section('title', 'My Rentals')
@section('page-title', 'My Rentals')

@section('content')

    <div class="flex flex-col gap-4 mb-6 
            md:flex-row md:items-center md:justify-between">

        {{-- Left --}}
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                Buyer Portal
            </p>
            <p class="text-sm font-bold text-slate-600 mt-0.5">
                All rental bookings you have placed on BijuliCar.
            </p>
        </div>

        {{-- CTA --}}
        <a href="{{ route('rent') }}"
            class="inline-flex items-center justify-center gap-2 
               w-full md:w-auto
               bg-slate-900 text-white px-4 py-2.5 rounded-xl 
               text-[11px] font-black uppercase italic tracking-widest 
               hover:bg-blue-600 transition-all shadow-lg">

            <i class="fa-solid fa-car text-xs"></i>
            Browse Rentals
        </a>

    </div>

    @if ($rentals->isNotEmpty())
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">

            {{-- Table header --}}
            <div class="grid grid-cols-12 gap-4 px-6 py-3 border-b border-slate-100 bg-slate-50">
                <div class="col-span-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Vehicle</div>
                <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Dates</div>
                <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Total</div>
                <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</div>
                <div class="col-span-1 text-[10px] font-black text-slate-400 uppercase tracking-widest">Days</div>
                <div class="col-span-1 text-[10px] font-black text-slate-400 uppercase tracking-widest">Review</div>
                <div class="col-span-1"></div>
            </div>

            {{-- Rows --}}
            @foreach ($rentals as $rental)
                <div
                    class="border border-slate-100 rounded-xl p-4 
                flex flex-col gap-3
                md:grid md:grid-cols-12 md:gap-4 md:px-6 md:py-4 md:items-center 
                md:border-0 md:rounded-none md:border-b md:last:border-0
                hover:bg-slate-50/50 transition-colors">

                    {{-- Vehicle --}}
                    <div class="flex items-center gap-3 md:col-span-3">
                        <div class="w-16 h-16 bg-slate-100 rounded-2xl overflow-hidden shrink-0 flex items-center justify-center">
                        @if($rental->car->primaryImage)
                            <img src="{{ Storage::url($rental->car->primaryImage->path) }}" class="w-full h-full object-cover" alt="{{ $rental->carDisplayName() }}">
                        @else
                            <span class="text-2xl opacity-20">🚗</span>
                        @endif
                    </div>

                        <div>
                            <p class="text-sm font-black text-slate-900">
                                {{ $rental->carDisplayName() }}
                            </p>
                            <p class="text-[11px] text-slate-400 font-medium mt-0.5">
                                NRs {{ number_format($rental->price_per_day) }} / day
                            </p>
                        </div>
                    </div>

                    {{-- Dates (mobile stacked nicely) --}}
                    <div class="flex items-center justify-between md:block md:col-span-2">
                        <p class="text-[11px] font-bold text-slate-700">
                            {{ $rental->pickup_date->format('d M Y') }}
                        </p>
                        <p class="text-[10px] text-slate-400 font-medium">
                            → {{ $rental->return_date->format('d M Y') }}
                        </p>
                    </div>

                    {{-- Price + Deposit + Status --}}
                    <div class="flex items-center justify-between md:block md:col-span-4">

                        <div>
                            <p class="text-sm font-black text-slate-800">
                                NRs {{ number_format($rental->total_price) }}
                            </p>
                            @if ($rental->deposit_amount)
                                <p class="text-[10px] text-slate-400 font-medium">
                                    +NRs {{ number_format($rental->deposit_amount) }} deposit
                                </p>
                            @endif
                        </div>

                        <span @class([
                            'text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider',
                            'bg-yellow-100 text-yellow-700' => $rental->status === 'pending',
                            'bg-blue-100 text-blue-700' => $rental->status === 'confirmed',
                            'bg-green-100 text-green-700' => $rental->status === 'active',
                            'bg-slate-100 text-slate-600' => $rental->status === 'completed',
                            'bg-red-100 text-red-600' => $rental->status === 'cancelled',
                        ])>
                            {{ $rental->statusLabel() }}
                        </span>
                    </div>

                    {{-- Days + Review (grouped on mobile) --}}
                    <div class="flex items-center justify-between md:col-span-2">

                        {{-- Days --}}
                        <p class="text-sm font-black text-slate-700">
                            {{ $rental->total_days }}d
                        </p>

                        {{-- Review --}}
                        <div>
                            @if ($rental->isCompleted())
                                @php $existingReview = $rental->reviews->first(); @endphp

                                @if ($existingReview)
                                    <div class="flex items-center gap-0.5"
                                        title="Your rating: {{ $existingReview->rating }}/5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-3 h-3 {{ $i <= $existingReview->rating ? 'text-yellow-400' : 'text-slate-200' }}"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                    </div>
                                @else
                                    <a href="{{ route('buyer.reviews.create', ['rental_id' => $rental->id]) }}"
                                        class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider text-blue-600 hover:text-blue-800 transition-colors whitespace-nowrap">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                        </svg>
                                        Rate
                                    </a>
                                @endif
                            @else
                                <span class="text-[10px] text-slate-300 font-medium">—</span>
                            @endif
                        </div>
                    </div>

                    {{-- Action --}}
                    <div class="flex justify-end md:col-span-1">
                        <a href="{{ route('buyer.rentals.show', $rental) }}"
                            class="w-8 h-8 bg-slate-100 hover:bg-slate-900 hover:text-white rounded-xl flex items-center justify-center transition-all group">
                            <svg class="w-4 h-4 text-slate-500 group-hover:text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>

                </div>
            @endforeach
        </div>

        @if ($rentals->hasPages())
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
