@extends('dashboard.buyer.layout')
@section('title', 'Pre-Order Detail')
@section('page-title', 'Pre-Order Detail')

@section('content')

    <a href="{{ route('buyer.preorders.index') }}"
        class="inline-flex items-center gap-2 text-[11px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-700 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Pre-Orders
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Car + deposit info --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Car card --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Vehicle</p>

                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 bg-slate-100 rounded-2xl overflow-hidden shrink-0 flex items-center justify-center">
                        @if($preOrder->car->primaryImage ?? false)
                            <img src="{{ $preOrder->car->primaryImage->url() }}" class="w-full h-full object-cover" alt="{{ $preOrder->car->displayName() }}">
                        @else
                            <span class="text-2xl opacity-20">⚡</span>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-black text-slate-900 uppercase italic tracking-tight">
                            {{ $preOrder->car->displayName() }}
                        </h2>
                        <p class="text-sm text-slate-500 font-medium mt-1">
                            {{ ucfirst($preOrder->car->condition) }} ·
                            {{ $preOrder->car->color ?? '—' }} ·
                            {{ $preOrder->car->location }}
                        </p>
                        @if($preOrder->car->expected_arrival_date)
                        <p class="text-xs font-black text-[#16a34a] mt-2">
                            Expected: {{ $preOrder->car->expected_arrival_date->format('F Y') }}
                        </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Deposit details --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Deposit Details</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Deposit Amount</p>
                        <p class="text-lg font-black text-slate-900 mt-1">{{ $preOrder->formattedDeposit() }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Payment Method</p>
                        <p class="text-sm font-black text-slate-900 mt-1 capitalize">{{ str_replace('_', ' ', $preOrder->payment_method) }}</p>
                    </div>
                    @if($preOrder->transaction_ref)
                    <div class="col-span-2">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Transaction Reference</p>
                        <p class="text-sm font-mono text-slate-700 mt-1">{{ $preOrder->transaction_ref }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Buyer contact --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Your Contact Info</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Name</p>
                        <p class="text-sm font-bold text-slate-800 mt-1">{{ $preOrder->buyer_name }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Phone</p>
                        <p class="text-sm font-bold text-slate-800 mt-1">{{ $preOrder->buyer_phone }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Email</p>
                        <p class="text-sm font-bold text-slate-800 mt-1">{{ $preOrder->buyer_email }}</p>
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            @if($preOrder->notes)
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Your Note to Seller</p>
                <p class="text-sm text-slate-600 font-medium leading-relaxed italic">"{{ $preOrder->notes }}"</p>
            </div>
            @endif

            {{-- Converted to order --}}
            @if($preOrder->order)
            <div class="bg-[#4ade80]/10 border border-[#4ade80]/20 rounded-2xl p-6">
                <p class="text-[10px] font-black text-[#16a34a] uppercase tracking-widest mb-3">✓ Converted to Order</p>
                <p class="text-sm text-slate-600 font-medium mb-3">Your pre-order has been converted to a full order.</p>
                <a href="{{ route('buyer.orders.show', $preOrder->order) }}"
                    class="inline-flex items-center gap-2 bg-slate-900 text-white px-4 py-2.5 rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all">
                    View Order →
                </a>
            </div>
            @endif

        </div>

        {{-- Right: Summary + actions --}}
        <div class="space-y-5">

            {{-- Summary --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Pre-Order Summary</p>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-slate-400">Pre-Order ID</p>
                        <p class="text-xs font-mono text-slate-700">#{{ str_pad($preOrder->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-slate-400">Placed On</p>
                        <p class="text-xs font-bold text-slate-700">{{ $preOrder->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-slate-400">Seller</p>
                        <p class="text-xs font-bold text-slate-700">{{ $preOrder->car->seller->name }}</p>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                        <p class="text-xs font-bold text-slate-400">Car Price</p>
                        <p class="text-base font-black text-slate-900">{{ $preOrder->car->formattedPrice() }}</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-slate-400">Status</p>
                        <span @class([
                            'text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider',
                            'bg-yellow-100 text-yellow-700'  => $preOrder->status === 'pending_deposit',
                            'bg-blue-100 text-blue-700'      => $preOrder->status === 'deposit_paid',
                            'bg-[#4ade80]/15 text-[#16a34a]' => $preOrder->status === 'converted',
                            'bg-red-100 text-red-600'        => $preOrder->status === 'cancelled',
                            'bg-purple-100 text-purple-700'  => $preOrder->status === 'refunded',
                        ])>{{ str_replace('_', ' ', ucfirst($preOrder->status)) }}</span>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            @if($preOrder->isCancellable())
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Actions</p>
                <form method="POST" action="{{ route('buyer.preorders.cancel', $preOrder) }}"
                    onsubmit="return confirm('Cancel this pre-order? Contact the seller about your deposit refund.')">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-red-50 text-red-600 border border-red-100 py-3 rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-red-100 transition-all">
                        Cancel Pre-Order
                    </button>
                </form>
            </div>
            @endif

            {{-- Status guide --}}
            @if($preOrder->status === 'pending_deposit')
            <div class="bg-slate-900 rounded-2xl p-5">
                <p class="text-[10px] font-black text-[#4ade80] uppercase tracking-widest mb-3">⚡ Next Steps</p>
                <ul class="space-y-2">
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-[#4ade80] mt-0.5 shrink-0">→</span>
                        Pay the deposit of <strong class="text-white">{{ $preOrder->formattedDeposit() }}</strong> to the seller.
                    </li>
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-[#4ade80] mt-0.5 shrink-0">→</span>
                        The seller will confirm receipt and update your status to <strong class="text-white">Deposit Paid</strong>.
                    </li>
                </ul>
            </div>
            @elseif($preOrder->status === 'deposit_paid')
            <div class="bg-slate-900 rounded-2xl p-5">
                <p class="text-[10px] font-black text-[#4ade80] uppercase tracking-widest mb-3">✓ Deposit Confirmed</p>
                <ul class="space-y-2">
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-[#4ade80] mt-0.5 shrink-0">→</span>
                        Your deposit has been confirmed by the seller.
                    </li>
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-[#4ade80] mt-0.5 shrink-0">→</span>
                        Once the car arrives, the seller will convert this to a full order.
                    </li>
                </ul>
            </div>
            @endif

        </div>
    </div>

@endsection