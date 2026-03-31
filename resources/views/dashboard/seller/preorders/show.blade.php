@extends($layout)
@section('title', 'Pre-Order Detail')
@section('page-title', 'Pre-Order Detail')

@section('content')

    <a href="{{ route($prefix . '.preorders.index') }}"
        class="inline-flex items-center gap-2 text-[11px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-700 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Pre-Orders
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Vehicle + buyer details --}}
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
                            {{ ucfirst($preOrder->car->condition) }} · {{ $preOrder->car->color ?? '—' }} · {{ $preOrder->car->location }}
                        </p>
                        @if($preOrder->car->expected_arrival_date)
                        <p class="text-xs font-black text-[#16a34a] mt-2">
                            Expected arrival: {{ $preOrder->car->expected_arrival_date->format('F Y') }}
                        </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Buyer contact --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Buyer Contact</p>
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center font-black text-slate-500 text-lg uppercase shrink-0">
                        {{ substr($preOrder->buyer_name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-black text-slate-900">{{ $preOrder->buyer_name }}</p>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">Registered: {{ $preOrder->buyer->name }}</p>
                    </div>
                </div>

                <div class="space-y-2.5 pt-4 border-t border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Phone</p>
                            <a href="tel:{{ $preOrder->buyer_phone }}" class="text-sm font-black text-slate-900 hover:text-green-600 transition-colors">
                                {{ $preOrder->buyer_phone }}
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Email</p>
                            <a href="mailto:{{ $preOrder->buyer_email }}" class="text-sm font-black text-slate-900 hover:text-blue-600 transition-colors">
                                {{ $preOrder->buyer_email }}
                            </a>
                        </div>
                    </div>
                </div>

                @if($preOrder->notes)
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Buyer's Note</p>
                    <p class="text-sm text-slate-600 font-medium leading-relaxed italic">"{{ $preOrder->notes }}"</p>
                </div>
                @endif
            </div>

            {{-- Deposit record — shown after confirmation --}}
            @if($preOrder->status !== 'pending_deposit' && $preOrder->payment_method)
            <div class="bg-[#4ade80]/10 border border-[#4ade80]/20 rounded-2xl p-6">
                <p class="text-[10px] font-black text-[#16a34a] uppercase tracking-widest mb-4">✓ Deposit Received</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Amount</p>
                        <p class="text-lg font-black text-slate-900 mt-1">{{ $preOrder->formattedDeposit() }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Method</p>
                        <p class="text-sm font-black text-slate-900 mt-1 capitalize">{{ str_replace('_', ' ', $preOrder->payment_method) }}</p>
                    </div>
                    @if($preOrder->transaction_ref)
                    <div class="col-span-2">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Transaction Ref</p>
                        <p class="text-sm font-mono text-slate-700 mt-1">{{ $preOrder->transaction_ref }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Converted to order --}}
            @if($preOrder->order)
            <div class="bg-slate-100 border border-slate-200 rounded-2xl p-6">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Converted to Order</p>
                <a href="{{ route($prefix . '.orders.show', $preOrder->order) }}"
                    class="inline-flex items-center gap-2 bg-slate-900 text-white px-4 py-2.5 rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all">
                    View Order #{{ str_pad($preOrder->order->id, 5, '0', STR_PAD_LEFT) }} →
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
                        <p class="text-xs font-bold text-slate-400">Buyer</p>
                        <p class="text-xs font-bold text-slate-700">{{ $preOrder->buyer->name }}</p>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                        <p class="text-xs font-bold text-slate-400">Car Price</p>
                        <p class="text-base font-black text-slate-900">{{ $preOrder->car->formattedPrice() }}</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-slate-400">Deposit</p>
                        <p class="text-sm font-black text-[#16a34a]">{{ $preOrder->formattedDeposit() }}</p>
                    </div>
                    <div class="flex items-center justify-between pt-2 border-t border-slate-100">
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
            <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-3">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Actions</p>

                {{-- Confirm deposit — pending only --}}
                @if($preOrder->status === 'pending_deposit')
                <a href="{{ route($prefix . '.preorders.confirm_deposit.form', $preOrder) }}"
                    class="w-full flex items-center justify-center gap-2 bg-slate-900 text-white py-3 rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all shadow-lg block text-center">
                    💰 Confirm Deposit Received
                </a>
                <p class="text-center text-[10px] font-medium text-slate-400">
                    Use this once you have collected the deposit from the buyer.
                </p>
                @endif

                {{-- Convert to full order — deposit_paid only --}}
                @if($preOrder->status === 'deposit_paid')
                <form method="POST" action="{{ route($prefix . '.preorders.convert', $preOrder) }}"
                    onsubmit="return confirm('Convert this pre-order to a full order? The car will be marked as available and a confirmed order will be created.')">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-[#16a34a] text-white py-3 rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-[#15803d] transition-all shadow-lg">
                        ⚡ Convert to Order
                    </button>
                </form>
                <p class="text-center text-[10px] font-medium text-slate-400">
                    Use this when the car has arrived and is ready for the buyer.
                </p>
                @endif

                {{-- Converted state --}}
                @if($preOrder->status === 'converted')
                <div class="w-full flex items-center justify-center gap-2 bg-[#4ade80]/10 text-[#16a34a] border border-[#4ade80]/20 py-3 rounded-xl text-[11px] font-black uppercase italic tracking-widest">
                    ✓ Converted to Order
                </div>
                @endif

                {{-- Cancel --}}
                @if($preOrder->isCancellable())
                <form method="POST" action="{{ route($prefix . '.preorders.cancel', $preOrder) }}"
                    onsubmit="return confirm('Cancel this pre-order? Remember to refund the deposit if it was already paid.')">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-red-50 text-red-600 border border-red-100 py-3 rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-red-100 transition-all">
                        Cancel Pre-Order
                    </button>
                </form>
                @endif

                @if($preOrder->status === 'cancelled')
                <p class="text-center text-[11px] font-black text-slate-400 uppercase tracking-widest py-2">
                    This pre-order was cancelled
                </p>
                @endif
            </div>

            {{-- Next steps guide --}}
            @if($preOrder->status === 'pending_deposit')
            <div class="bg-slate-900 rounded-2xl p-5">
                <p class="text-[10px] font-black text-[#4ade80] uppercase tracking-widest mb-3">⚡ Next Steps</p>
                <ul class="space-y-2">
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-[#4ade80] mt-0.5 shrink-0">→</span>
                        Contact <strong class="text-white">{{ $preOrder->buyer_name }}</strong> on {{ $preOrder->buyer_phone }} to arrange deposit collection.
                    </li>
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-[#4ade80] mt-0.5 shrink-0">→</span>
                        Collect the deposit of <strong class="text-white">{{ $preOrder->formattedDeposit() }}</strong> in person.
                    </li>
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-[#4ade80] mt-0.5 shrink-0">→</span>
                        Click <strong class="text-white">Confirm Deposit Received</strong> and fill in the payment details.
                    </li>
                </ul>
            </div>
            @elseif($preOrder->status === 'deposit_paid')
            <div class="bg-slate-900 rounded-2xl p-5">
                <p class="text-[10px] font-black text-[#4ade80] uppercase tracking-widest mb-3">✓ Deposit Secured</p>
                <ul class="space-y-2">
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-[#4ade80] mt-0.5 shrink-0">→</span>
                        Deposit has been recorded. The buyer is locked in.
                    </li>
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-[#4ade80] mt-0.5 shrink-0">→</span>
                        Once the car arrives, click <strong class="text-white">Convert to Order</strong> — a confirmed order will be created automatically.
                    </li>
                </ul>
            </div>
            @endif

        </div>
    </div>

@endsection