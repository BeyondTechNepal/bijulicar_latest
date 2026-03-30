@extends($layout)
@section('title', 'Order Detail')
@section('page-title', 'Order Detail')

@section('content')

    <a href="{{ route($prefix . '.orders.index') }}"
        class="inline-flex items-center gap-2 text-[11px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-700 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Orders
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Car + buyer details --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Car card --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Vehicle</p>
                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 bg-slate-100 rounded-2xl overflow-hidden shrink-0 flex items-center justify-center">
                        @if($order->car->primaryImage ?? false)
                            <img src="{{ $order->car->primaryImage->url() }}" class="w-full h-full object-cover" alt="{{ $order->car->displayName() }}">
                        @else
                            <span class="text-2xl opacity-20">⚡</span>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-black text-slate-900 uppercase italic tracking-tight">
                            {{ $order->car->displayName() }}
                        </h2>
                        <p class="text-sm text-slate-500 font-medium mt-1">
                            {{ ucfirst($order->car->condition) }} ·
                            {{ $order->car->color ?? '—' }} ·
                            {{ $order->car->location }}
                        </p>
                        <div class="flex flex-wrap gap-2 mt-3">
                            <span class="text-[10px] font-black px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg uppercase tracking-wider">
                                {{ number_format($order->car->mileage) }} km
                            </span>
                            @if($order->car->range_km)
                            <span class="text-[10px] font-black px-2.5 py-1 bg-[#4ade80]/10 text-[#16a34a] rounded-lg uppercase tracking-wider border border-[#4ade80]/20">
                                ⚡ {{ $order->car->range_km }} km range
                            </span>
                            @endif
                            @if($order->car->battery_kwh)
                            <span class="text-[10px] font-black px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg uppercase tracking-wider">
                                {{ $order->car->battery_kwh }} kWh
                            </span>
                            @endif
                            <span class="text-[10px] font-black px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg uppercase tracking-wider">
                                Stock: {{ $order->car->stock_quantity }} left
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Buyer info --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Buyer Contact</p>
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center font-black text-slate-500 text-lg uppercase shrink-0">
                        {{ substr($order->buyer_name ?? $order->buyer->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-black text-slate-900">{{ $order->buyer_name ?? $order->buyer->name }}</p>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">Registered: {{ $order->buyer->name }}</p>
                    </div>
                </div>

                <div class="space-y-2.5 pt-4 border-t border-slate-100">
                    {{-- Phone --}}
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Phone</p>
                            @if($order->buyer_phone)
                                <a href="tel:{{ $order->buyer_phone }}" class="text-sm font-black text-slate-900 hover:text-green-600 transition-colors">
                                    {{ $order->buyer_phone }}
                                </a>
                            @else
                                <p class="text-sm text-slate-400 font-medium italic">Not provided</p>
                            @endif
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Email</p>
                            @if($order->buyer_email)
                                <a href="mailto:{{ $order->buyer_email }}" class="text-sm font-black text-slate-900 hover:text-blue-600 transition-colors truncate block">
                                    {{ $order->buyer_email }}
                                </a>
                            @else
                                <p class="text-sm text-slate-400 font-medium italic">Not provided</p>
                            @endif
                        </div>
                    </div>
                </div>

                @if($order->notes)
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Buyer's Note</p>
                    <p class="text-sm text-slate-600 font-medium leading-relaxed italic">"{{ $order->notes }}"</p>
                </div>
                @endif
            </div>

            {{-- Payment record (shown after completion) --}}
            @if($order->purchase)
            <div class="bg-[#4ade80]/10 border border-[#4ade80]/20 rounded-2xl p-6">
                <p class="text-[10px] font-black text-[#16a34a] uppercase tracking-widest mb-4">Payment Record</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Amount Received</p>
                        <p class="text-lg font-black text-slate-900 mt-1">{{ $order->purchase->formattedAmount() }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Method</p>
                        <p class="text-sm font-black text-slate-900 mt-1">{{ $order->purchase->paymentMethodLabel() }}</p>
                    </div>
                    @if($order->purchase->transaction_ref)
                    <div class="col-span-2">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Transaction Ref</p>
                        <p class="text-sm font-mono text-slate-700 mt-1">{{ $order->purchase->transaction_ref }}</p>
                    </div>
                    @endif
                    @if($order->purchase->remarks)
                    <div class="col-span-2">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Remarks</p>
                        <p class="text-sm text-slate-600 font-medium mt-1">{{ $order->purchase->remarks }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Completed On</p>
                        <p class="text-sm font-black text-slate-900 mt-1">{{ $order->purchase->purchased_at->format('d M Y, h:i A') }}</p>
                    </div>
                </div>
            </div>
            @endif

        </div>

        {{-- Right: Order summary + actions --}}
        <div class="space-y-5">

            {{-- Summary --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Order Summary</p>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-slate-400">Order ID</p>
                        <p class="text-xs font-mono text-slate-700">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-slate-400">Placed On</p>
                        <p class="text-xs font-bold text-slate-700">{{ $order->ordered_at->format('d M Y') }}</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-slate-400">Buyer</p>
                        <p class="text-xs font-bold text-slate-700">{{ $order->buyer->name }}</p>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                        <p class="text-xs font-bold text-slate-400">Listed Price</p>
                        <p class="text-base font-black text-slate-900">{{ $order->car->formattedPrice() }}</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-slate-400">Status</p>
                        <span @class([
                            'text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider',
                            'bg-yellow-100 text-yellow-700'  => $order->status === 'pending',
                            'bg-blue-100 text-blue-700'      => $order->status === 'confirmed',
                            'bg-[#4ade80]/15 text-[#16a34a]' => $order->status === 'completed',
                            'bg-red-100 text-red-600'        => $order->status === 'cancelled',
                        ])>{{ ucfirst($order->status) }}</span>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-3">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Actions</p>

                {{-- Confirm button — pending only --}}
                @if($order->status === 'pending')
                <form method="POST" action="{{ route($prefix . '.orders.confirm', $order) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-slate-900 text-white py-3 rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all shadow-lg">
                        ✓ Confirm Order
                    </button>
                </form>
                <p class="text-center text-[10px] font-medium text-slate-400">
                    Confirming lets the buyer know you accepted their order.
                </p>
                @endif

                {{-- Mark as completed — confirmed only --}}
                @if($order->status === 'confirmed' && !$order->purchase)
                <a href="{{ route($prefix . '.orders.complete.form', $order) }}"
                    class="w-full flex items-center justify-center gap-2 bg-[#16a34a] text-white py-3 rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-[#15803d] transition-all shadow-lg block text-center">
                    💰 Mark as Completed
                </a>
                <p class="text-center text-[10px] font-medium text-slate-400">
                    Use this once you have received payment from the buyer.
                </p>
                @endif

                {{-- Completed state --}}
                @if($order->status === 'completed')
                <div class="w-full flex items-center justify-center gap-2 bg-[#4ade80]/10 text-[#16a34a] border border-[#4ade80]/20 py-3 rounded-xl text-[11px] font-black uppercase italic tracking-widest">
                    ✓ Sale Completed
                </div>
                @endif

                {{-- Cancel button --}}
                @if($order->isCancellable())
                <form method="POST" action="{{ route($prefix . '.orders.cancel', $order) }}"
                    onsubmit="return confirm('Cancel this order? The listing will become available again.')">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-red-50 text-red-600 border border-red-100 py-3 rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-red-100 transition-all">
                        Cancel Order
                    </button>
                </form>
                @endif

                {{-- Cancelled state --}}
                @if($order->status === 'cancelled')
                <p class="text-center text-[11px] font-black text-slate-400 uppercase tracking-widest py-2">
                    This order was cancelled
                </p>
                @endif

                {{-- Edit listing link --}}
                <a href="{{ route($prefix . '.cars.edit', $order->car) }}"
                    class="w-full flex items-center justify-center gap-2 bg-slate-50 text-slate-600 border border-slate-200 py-3 rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-slate-100 transition-all block text-center">
                    Edit Listing
                </a>

            </div>

            {{-- Next steps guide for pending --}}
            @if($order->status === 'pending')
            <div class="bg-slate-900 rounded-2xl p-5">
                <p class="text-[10px] font-black text-[#4ade80] uppercase tracking-widest mb-3">⚡ Next Steps</p>
                <ul class="space-y-2">
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-[#4ade80] mt-0.5">→</span>
                        Review the buyer's details above.
                    </li>
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-[#4ade80] mt-0.5">→</span>
                        Confirm the order to accept it.
                    </li>
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-[#4ade80] mt-0.5">→</span>
                        Arrange payment with the buyer offline.
                    </li>
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-[#4ade80] mt-0.5">→</span>
                        Once paid, mark the order as completed.
                    </li>
                </ul>
            </div>
            @endif

            {{-- Next steps for confirmed --}}
            @if($order->status === 'confirmed' && !$order->purchase)
            <div class="bg-slate-900 rounded-2xl p-5">
                <p class="text-[10px] font-black text-[#4ade80] uppercase tracking-widest mb-3">⚡ Waiting for Payment</p>
                <ul class="space-y-2">
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-[#4ade80] mt-0.5">→</span>
                        Coordinate with {{ $order->buyer->name }} for payment.
                    </li>
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-[#4ade80] mt-0.5">→</span>
                        Accept cash, bank transfer.
                    </li>
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-[#4ade80] mt-0.5">→</span>
                        Click "Mark as Completed" once you receive it.
                    </li>
                </ul>
            </div>
            @endif

        </div>
    </div>

@endsection