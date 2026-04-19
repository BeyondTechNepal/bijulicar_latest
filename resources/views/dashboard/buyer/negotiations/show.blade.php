@extends('dashboard.buyer.layout')
@section('title', 'Negotiation')
@section('page-title', 'Negotiation')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div>
            <a href="{{ route('buyer.negotiations.index') }}" class="text-[11px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-600 transition-colors">← Back to Negotiations</a>
            <p class="text-lg font-black text-slate-900 mt-1">{{ $negotiation->car?->displayName() ?? 'Deleted Listing' }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm font-bold">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        {{-- ── LEFT: negotiation thread ─────────────────────────── --}}
        <div class="xl:col-span-2 space-y-4">

            {{-- Status card --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Negotiation Status</p>
                        @php
                            $colors = [
                                'pending_seller' => 'bg-yellow-100 text-yellow-700',
                                'pending_buyer'  => 'bg-blue-100 text-blue-700',
                                'accepted'       => 'bg-green-100 text-green-700',
                                'declined'       => 'bg-red-100 text-red-600',
                                'expired'        => 'bg-slate-100 text-slate-500',
                                'cancelled'      => 'bg-slate-100 text-slate-500',
                                'ordered'        => 'bg-green-100 text-green-700',
                            ];
                        @endphp
                        <span class="text-[11px] font-black px-3 py-1.5 rounded-full uppercase tracking-wider {{ $colors[$negotiation->status] ?? 'bg-slate-100 text-slate-500' }}">
                            {{ $negotiation->statusLabel() }}
                        </span>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Round</p>
                        <p class="text-2xl font-black text-slate-900">{{ $negotiation->rounds }}<span class="text-sm text-slate-400">/3</span></p>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-slate-100 grid grid-cols-3 gap-4">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Listed Price</p>
                        <p class="text-base font-black text-slate-500 line-through mt-0.5">NRs {{ number_format($negotiation->listed_price) }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Current Offer</p>
                        <p class="text-base font-black text-slate-900 mt-0.5">NRs {{ number_format($negotiation->offered_price) }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Discount</p>
                        <p class="text-base font-black text-green-600 mt-0.5">-{{ $negotiation->discountPercent() }}%</p>
                    </div>
                </div>

                @if($negotiation->message)
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Last Message</p>
                    <p class="text-sm text-slate-600 font-medium italic">"{{ $negotiation->message }}"</p>
                </div>
                @endif

                @if($negotiation->expires_at && $negotiation->isActive())
                <div class="mt-3">
                    <p class="text-[10px] text-amber-500 font-bold">⏰ Expires {{ $negotiation->expires_at->diffForHumans() }}</p>
                </div>
                @endif
            </div>

            {{-- ── BUYER ACTIONS ── --}}
            @if($negotiation->isPendingBuyer())
            <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">The seller countered at NRs {{ number_format($negotiation->offered_price) }} — what would you like to do?</p>

                {{-- Accept seller's counter --}}
                <form method="POST" action="{{ route('buyer.negotiations.accept', $negotiation) }}">
                    @csrf @method('PATCH')
                    <button type="submit"
                        class="w-full py-3.5 rounded-xl bg-green-600 text-white text-[12px] font-black uppercase italic tracking-widest hover:bg-green-700 transition-all">
                        ✓ Accept NRs {{ number_format($negotiation->offered_price) }}
                    </button>
                </form>

                {{-- Counter back (only if rounds remain) --}}
                @if($negotiation->canCounter())
                <div>
                    <button onclick="document.getElementById('counterForm').classList.toggle('hidden')"
                        class="w-full py-3 rounded-xl bg-slate-100 text-slate-700 text-[12px] font-black uppercase tracking-widest hover:bg-slate-200 transition-all">
                        Counter with Different Price
                    </button>
                    <div id="counterForm" class="hidden mt-3 bg-slate-50 border border-slate-200 rounded-xl p-4">
                        <form method="POST" action="{{ route('buyer.negotiations.counter', $negotiation) }}">
                            @csrf @method('PATCH')
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1.5">Your Counter Offer (NRs)</label>
                            <input type="number" name="offered_price" required min="1" max="{{ $negotiation->listed_price - 1 }}"
                                placeholder="Enter your price"
                                class="w-full bg-white border border-slate-200 rounded-xl py-2.5 px-3 text-sm text-slate-800 font-medium focus:outline-none focus:border-green-400 mb-2">
                            <textarea name="message" rows="2" placeholder="Optional message..."
                                class="w-full bg-white border border-slate-200 rounded-xl p-3 text-sm text-slate-800 font-medium focus:outline-none focus:border-green-400 resize-none mb-3"></textarea>
                            <button type="submit"
                                class="w-full py-3 rounded-xl bg-slate-900 text-white text-[12px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all">
                                Send Counter Offer
                            </button>
                        </form>
                    </div>
                </div>
                @endif

                {{-- Cancel --}}
                <form method="POST" action="{{ route('buyer.negotiations.cancel', $negotiation) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="w-full py-2.5 text-[11px] font-black text-red-400 uppercase tracking-widest hover:text-red-600 transition-colors">
                        Withdraw Negotiation
                    </button>
                </form>
            </div>

            @elseif($negotiation->isPendingSeller())
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <div class="text-center py-4">
                    <p class="text-2xl mb-2">⏳</p>
                    <p class="text-sm font-black text-slate-900">Waiting for seller to respond</p>
                    <p class="text-[11px] text-slate-400 font-medium mt-1">You'll be notified once they accept, counter, or decline.</p>
                </div>
                <form method="POST" action="{{ route('buyer.negotiations.cancel', $negotiation) }}" class="mt-4">
                    @csrf @method('PATCH')
                    <button type="submit" class="w-full py-2.5 text-[11px] font-black text-red-400 uppercase tracking-widest hover:text-red-600 transition-colors">
                        Withdraw Offer
                    </button>
                </form>
            </div>

            @elseif($negotiation->isAccepted())
            <div class="bg-green-50 border border-green-200 rounded-2xl p-6 text-center">
                <p class="text-2xl mb-2">🎉</p>
                <p class="text-sm font-black text-green-800">Deal agreed at NRs {{ number_format($negotiation->offered_price) }}!</p>
                <p class="text-[11px] text-green-600 font-medium mt-1 mb-4">Head to the listing to place your order at this price.</p>
                @if($negotiation->car)
                <a href="{{ route('cars.show', $negotiation->car) }}#place-order"
                    class="inline-flex items-center gap-2 bg-green-600 text-white px-5 py-3 rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-green-700 transition-all">
                    Place Order Now →
                </a>
                @endif
            </div>

            @elseif($negotiation->isDeclined())
            <div class="bg-red-50 border border-red-200 rounded-2xl p-6 text-center">
                <p class="text-2xl mb-2">✕</p>
                <p class="text-sm font-black text-red-800">Offer declined</p>
                <p class="text-[11px] text-red-500 font-medium mt-1 mb-4">The seller declined your offer. You can still order at the listed price.</p>
                @if($negotiation->car)
                <a href="{{ route('cars.show', $negotiation->car) }}"
                    class="inline-flex items-center gap-2 bg-slate-900 text-white px-5 py-3 rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all">
                    View Listing
                </a>
                @endif
            </div>
            @endif

        </div>

        {{-- ── RIGHT: car summary ───────────────────────────────── --}}
        <div class="space-y-4">
            @if($negotiation->car)
            <div class="bg-white border border-slate-200 rounded-2xl p-5">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Listing</p>
                <p class="text-sm font-black text-slate-900">{{ $negotiation->car->displayName() }}</p>
                <p class="text-[11px] text-slate-400 font-medium mt-0.5">{{ ucfirst($negotiation->car->condition) }} · {{ $negotiation->car->location }}</p>
                <p class="text-lg font-black text-slate-800 mt-2">NRs {{ number_format($negotiation->car->price) }}</p>
                <a href="{{ route('cars.show', $negotiation->car) }}"
                    class="block mt-3 w-full py-2.5 rounded-xl bg-slate-100 text-slate-700 text-[11px] font-black uppercase tracking-widest text-center hover:bg-slate-200 transition-all">
                    View Listing
                </a>
            </div>
            @endif

            <div class="bg-white border border-slate-200 rounded-2xl p-5">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Seller</p>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-slate-900 flex items-center justify-center text-white text-[12px] font-black uppercase shrink-0">
                        {{ strtoupper(substr($negotiation->seller->name ?? '?', 0, 2)) }}
                    </div>
                    <div>
                        <p class="text-sm font-black text-slate-900">{{ $negotiation->seller->name ?? 'Unknown' }}</p>
                        <p class="text-[11px] text-slate-400 font-medium">{{ ucfirst($negotiation->seller?->getRoleNames()->first() ?? 'seller') }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection