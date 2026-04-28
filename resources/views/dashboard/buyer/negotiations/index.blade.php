@extends('dashboard.buyer.layout')
@section('title', 'My Negotiations')
@section('page-title', 'My Negotiations')

@section('content')

    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                Buyer Portal
            </p>
            <p class="text-sm font-bold text-slate-600 mt-0.5">
                Price negotiations you have started on listings.
            </p>
        </div>

        <a href="{{ route('marketplace') }}"
            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 
                       bg-slate-900 text-white px-4 py-2.5 rounded-xl 
                       text-[11px] font-black uppercase italic tracking-widest 
                       hover:bg-[#16a34a] transition-all shadow-lg">
            ⚡ Browse EVs
        </a>

    </div>

    @if ($negotiations->isNotEmpty())
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">

            <div class="grid grid-cols-12 gap-4 px-6 py-3 border-b border-slate-100 bg-slate-50">
                <div class="col-span-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Vehicle</div>
                <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Listed</div>
                <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Your Offer</div>
                <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</div>
                <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Date</div>
            </div>

            @foreach ($negotiations as $neg)
                <a href="{{ route('buyer.negotiations.show', $neg) }}"
                    class="border border-slate-100 rounded-xl p-4 
                   flex flex-col gap-3
                   md:grid md:grid-cols-12 md:gap-4 md:px-6 md:py-4 md:items-center 
                   md:border-0 md:rounded-none md:border-b md:last:border-0
                   hover:bg-slate-50/50 transition-colors block">

                    {{-- Vehicle --}}
                    <div class="flex items-center gap-3 md:col-span-4">
                        <div
                            class="w-10 h-10 bg-slate-100 rounded-xl overflow-hidden shrink-0 flex items-center justify-center border border-slate-200">
                            @if ($neg->car && $neg->car->primaryImage)
                                <img src="{{ $neg->car->primaryImage->url() }}" class="w-full h-full object-cover"
                                    alt="{{ $neg->car->displayName() }}">
                            @else
                                <span class="text-base opacity-20">⚡</span>
                            @endif
                        </div>

                        <div>
                            <p class="text-sm font-black text-slate-900">
                                {{ $neg->car?->displayName() ?? $neg->car_id }}
                            </p>
                            <p class="text-[11px] text-slate-400 font-medium mt-0.5">
                                {{ $neg->rounds }} round{{ $neg->rounds !== 1 ? 's' : '' }}
                            </p>
                        </div>
                    </div>

                    {{-- Prices (grouped on mobile) --}}
                    <div class="flex items-center justify-between md:block md:col-span-4">

                        <div>
                            <p class="text-sm font-black text-slate-500 line-through">
                                NRs {{ number_format($neg->listed_price) }}
                            </p>
                            <p class="text-sm font-black text-slate-800">
                                NRs {{ number_format($neg->offered_price) }}
                            </p>
                        </div>

                        <p class="text-[11px] text-green-600 font-bold">
                            -{{ $neg->discountPercent() }}%
                        </p>
                    </div>

                    {{-- Status --}}
                    <div class="md:col-span-2">
                        @php
                            $colors = [
                                'pending_seller' => 'bg-yellow-100 text-yellow-700',
                                'pending_buyer' => 'bg-blue-100 text-blue-700',
                                'accepted' => 'bg-green-100 text-green-700',
                                'declined' => 'bg-red-100 text-red-600',
                                'expired' => 'bg-slate-100 text-slate-500',
                                'cancelled' => 'bg-slate-100 text-slate-500',
                            ];
                        @endphp

                        <span
                            class="text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider {{ $colors[$neg->status] ?? 'bg-slate-100 text-slate-500' }}">
                            {{ $neg->statusLabel() }}
                        </span>
                    </div>

                    {{-- Date --}}
                    <div class="flex items-center justify-between md:block md:col-span-2">
                        <p class="text-[11px] text-slate-500 font-medium">
                            {{ $neg->created_at->format('d M Y') }}
                        </p>
                        <p class="text-[10px] text-slate-400">
                            {{ $neg->created_at->diffForHumans() }}
                        </p>
                    </div>

                </a>
            @endforeach
        </div>

        <div class="mt-4">{{ $negotiations->links() }}</div>
    @else
        <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center">
            <p class="text-2xl mb-2">🤝</p>
            <p class="text-sm font-black text-slate-900 mb-1">No negotiations yet</p>
            <p class="text-[12px] text-slate-400 font-medium mb-4">Find a listing with a negotiable price and make an offer.
            </p>
            <a href="{{ route('marketplace') }}"
                class="inline-flex items-center gap-2 bg-slate-900 text-white px-5 py-3 rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all">
                Browse Listings
            </a>
        </div>
    @endif

@endsection