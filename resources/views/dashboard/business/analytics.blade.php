@extends('dashboard.business.layout')
@section('title', 'Analytics')
@section('page-title', 'Analytics')

@section('content')

    <div class="mb-6">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Business Portal</p>
        <p class="text-sm font-bold text-slate-600 mt-0.5">A snapshot of your inventory, orders and revenue.</p>
    </div>

    {{-- ── Revenue & orders ──────────────────────────────────────────── --}}
    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Revenue & Orders</p>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        <div class="bg-green-50 border border-green-100 rounded-2xl p-5 col-span-2 lg:col-span-1">
            <div class="text-xl font-black text-green-700 leading-tight">NRs {{ number_format($totalRevenue) }}</div>
            <div class="text-[10px] font-black text-green-500 uppercase tracking-widest mt-1">Total Revenue</div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5">
            <div class="text-2xl font-black text-slate-900">{{ $totalOrders }}</div>
            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Total Orders</div>
        </div>

        <div class="bg-green-50 border border-green-100 rounded-2xl p-5">
            <div class="text-2xl font-black text-green-700">{{ $completedOrders }}</div>
            <div class="text-[10px] font-black text-green-500 uppercase tracking-widest mt-1">Completed</div>
        </div>

        <div class="bg-red-50 border border-red-100 rounded-2xl p-5">
            <div class="text-2xl font-black text-red-600">{{ $cancelledOrders }}</div>
            <div class="text-[10px] font-black text-red-400 uppercase tracking-widest mt-1">Cancelled</div>
        </div>

    </div>

    {{-- ── Order pipeline ────────────────────────────────────────────── --}}
    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Order Pipeline</p>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        <div class="bg-yellow-50 border border-yellow-100 rounded-2xl p-5">
            <div class="text-2xl font-black text-yellow-600">{{ $pendingOrders }}</div>
            <div class="text-[10px] font-black text-yellow-500 uppercase tracking-widest mt-1">Pending</div>
            <p class="text-[11px] text-yellow-600/70 font-medium mt-2">Awaiting your confirmation</p>
        </div>

        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5">
            <div class="text-2xl font-black text-blue-700">{{ $confirmedOrders }}</div>
            <div class="text-[10px] font-black text-blue-400 uppercase tracking-widest mt-1">Confirmed</div>
            <p class="text-[11px] text-blue-600/70 font-medium mt-2">Awaiting payment</p>
        </div>

        <div class="bg-green-50 border border-green-100 rounded-2xl p-5">
            <div class="text-2xl font-black text-green-700">{{ $completedOrders }}</div>
            <div class="text-[10px] font-black text-green-500 uppercase tracking-widest mt-1">Completed</div>
            <p class="text-[11px] text-green-600/70 font-medium mt-2">Payment received</p>
        </div>

        <div class="bg-red-50 border border-red-100 rounded-2xl p-5">
            <div class="text-2xl font-black text-red-600">{{ $cancelledOrders }}</div>
            <div class="text-[10px] font-black text-red-400 uppercase tracking-widest mt-1">Cancelled</div>
            <p class="text-[11px] text-red-600/70 font-medium mt-2">By buyer or you</p>
        </div>

    </div>

    {{-- ── Inventory & drivetrain ─────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

        {{-- Inventory breakdown --}}
        <div>
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Inventory</p>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white border border-slate-200 rounded-2xl p-5">
                    <div class="text-2xl font-black text-slate-900">{{ $totalListings }}</div>
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Total Listings</div>
                </div>
                <div class="bg-purple-50 border border-purple-100 rounded-2xl p-5">
                    <div class="text-2xl font-black text-purple-700">{{ $activeListings }}</div>
                    <div class="text-[10px] font-black text-purple-400 uppercase tracking-widest mt-1">Active</div>
                </div>
                <!-- <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5">
                    <div class="text-2xl font-black text-slate-700">{{ $soldListings }}</div>
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Sold</div>
                </div> -->
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5">
                    <div class="text-2xl font-black text-slate-500">{{ $inactiveListings }}</div>
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Inactive</div>
                </div>
            </div>
        </div>

        {{-- Drivetrain breakdown --}}
        <div>
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Drivetrain Breakdown</p>
            @if(!empty($drivetrainBreakdown))
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
                @php
                    $drivetrainLabels = ['ev' => 'Electric (EV)', 'hybrid' => 'Hybrid', 'petrol' => 'Petrol', 'diesel' => 'Diesel'];
                    $drivetrainColors = [
                        'ev'     => 'bg-green-100 text-green-700',
                        'hybrid' => 'bg-blue-100 text-blue-700',
                        'petrol' => 'bg-orange-100 text-orange-700',
                        'diesel' => 'bg-slate-100 text-slate-600',
                    ];
                @endphp
                @foreach($drivetrainBreakdown as $type => $count)
                <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-100 last:border-0">
                    <div class="flex items-center gap-3">
                        <span class="text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider {{ $drivetrainColors[$type] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ strtoupper($type) }}
                        </span>
                        <span class="text-sm font-bold text-slate-700">{{ $drivetrainLabels[$type] ?? ucfirst($type) }}</span>
                    </div>
                    <span class="text-sm font-black text-slate-900">{{ $count }} listing{{ $count !== 1 ? 's' : '' }}</span>
                </div>
                @endforeach
            </div>
            @else
            <div class="bg-white border border-dashed border-slate-200 rounded-2xl p-8 text-center">
                <p class="text-sm font-bold text-slate-400">No listings yet</p>
            </div>
            @endif
        </div>

    </div>

    {{-- ── Recent orders ──────────────────────────────────────────────── --}}
    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Recent Orders</p>
    @if($recentOrders->isNotEmpty())
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">

        <div class="grid grid-cols-12 gap-4 px-6 py-3 border-b border-slate-100 bg-slate-50">
            <div class="col-span-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Vehicle</div>
            <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Buyer</div>
            <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Price</div>
            <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</div>
            <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Date</div>
        </div>

        @foreach($recentOrders as $order)
        <a href="{{ route('business.orders.show', $order) }}"
            class="grid grid-cols-12 gap-4 px-6 py-4 border-b border-slate-100 last:border-0 items-center hover:bg-slate-50/50 transition-colors">

            <div class="col-span-4 flex items-center gap-3">
                <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-[10px] font-black text-slate-500 uppercase shrink-0">
                    {{ strtoupper($order->car->drivetrain) }}
                </div>
                <div>
                    <p class="text-sm font-black text-slate-900 uppercase italic tracking-tight leading-tight">
                        {{ $order->car->displayName() }}
                    </p>
                    <p class="text-[11px] text-slate-400 font-medium mt-0.5">
                        Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                    </p>
                </div>
            </div>

            <div class="col-span-2">
                <p class="text-sm font-bold text-slate-700">{{ $order->buyer->name }}</p>
            </div>

            <div class="col-span-2">
                <p class="text-sm font-black text-slate-800">{{ $order->car->formattedPrice() }}</p>
            </div>

            <div class="col-span-2">
                <span @class([
                    'text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider',
                    'bg-yellow-100 text-yellow-700' => $order->status === 'pending',
                    'bg-blue-100 text-blue-700'     => $order->status === 'confirmed',
                    'bg-green-100 text-green-700'   => $order->status === 'completed',
                    'bg-red-100 text-red-600'       => $order->status === 'cancelled',
                ])>{{ ucfirst($order->status) }}</span>
            </div>

            <div class="col-span-2">
                <p class="text-[11px] text-slate-400 font-medium">{{ $order->ordered_at->format('d M Y') }}</p>
                <p class="text-[10px] text-slate-300 font-medium">{{ $order->ordered_at->diffForHumans() }}</p>
            </div>

        </a>
        @endforeach

    </div>

    <div class="mt-4 text-right">
        <a href="{{ route('business.orders.index') }}" class="text-[10px] font-black text-purple-600 uppercase tracking-widest hover:underline">View All Orders →</a>
    </div>

    @else
    <div class="bg-white border border-dashed border-slate-200 rounded-2xl p-10 text-center">
        <p class="text-4xl mb-3">📊</p>
        <p class="font-black text-slate-900 uppercase italic tracking-tight">No order data yet</p>
        <p class="text-sm text-slate-500 font-medium mt-1 mb-5">Orders will appear here once buyers start placing them.</p>
        <a href="{{ route('business.cars.create') }}"
            class="inline-flex items-center gap-2 bg-slate-900 text-white px-5 py-2.5 rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-purple-700 transition-all">
            + Create Listing →
        </a>
    </div>
    @endif

    {{-- ── Pre-Orders ─────────────────────────────────────────────────── --}}
    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 mt-10">Pre-Orders</p>

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white border border-slate-200 rounded-2xl p-5">
            <div class="text-2xl font-black text-slate-900">{{ $totalPreOrders }}</div>
            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Total</div>
        </div>
        <div class="bg-amber-50 border border-amber-100 rounded-2xl p-5">
            <div class="text-2xl font-black text-amber-600">{{ $pendingDepositPreOrders }}</div>
            <div class="text-[10px] font-black text-amber-400 uppercase tracking-widest mt-1">Awaiting Deposit</div>
        </div>
        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5">
            <div class="text-2xl font-black text-blue-700">{{ $depositPaidPreOrders }}</div>
            <div class="text-[10px] font-black text-blue-400 uppercase tracking-widest mt-1">Deposit Paid</div>
        </div>
        <div class="bg-green-50 border border-green-100 rounded-2xl p-5">
            <div class="text-2xl font-black text-green-700">{{ $convertedPreOrders }}</div>
            <div class="text-[10px] font-black text-green-500 uppercase tracking-widest mt-1">Converted</div>
        </div>
        <div class="bg-red-50 border border-red-100 rounded-2xl p-5">
            <div class="text-2xl font-black text-red-600">{{ $cancelledPreOrders }}</div>
            <div class="text-[10px] font-black text-red-400 uppercase tracking-widest mt-1">Cancelled</div>
        </div>
    </div>

    @if($recentPreOrders->isNotEmpty())
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">

        <div class="grid grid-cols-12 gap-4 px-6 py-3 border-b border-slate-100 bg-slate-50">
            <div class="col-span-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Vehicle</div>
            <div class="col-span-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Buyer</div>
            <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Deposit</div>
            <div class="col-span-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</div>
        </div>

        @foreach($recentPreOrders as $preOrder)
        <a href="{{ route('business.preorders.show', $preOrder) }}"
            class="grid grid-cols-12 gap-4 px-6 py-4 border-b border-slate-100 last:border-0 items-center hover:bg-slate-50/50 transition-colors">

            <div class="col-span-4 flex items-center gap-3">
                <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-[10px] font-black text-slate-500 uppercase shrink-0">
                    {{ strtoupper($preOrder->car->drivetrain) }}
                </div>
                <div>
                    <p class="text-sm font-black text-slate-900 uppercase italic tracking-tight leading-tight">
                        {{ $preOrder->car->displayName() }}
                    </p>
                    <p class="text-[11px] text-slate-400 font-medium mt-0.5">
                        Pre-Order #{{ str_pad($preOrder->id, 5, '0', STR_PAD_LEFT) }}
                    </p>
                </div>
            </div>

            <div class="col-span-3">
                <p class="text-sm font-bold text-slate-700">{{ $preOrder->buyer->name }}</p>
                <p class="text-[11px] text-slate-400 font-medium mt-0.5">{{ $preOrder->buyer_phone }}</p>
            </div>

            <div class="col-span-2">
                <p class="text-sm font-black text-slate-800">{{ $preOrder->formattedDeposit() }}</p>
            </div>

            <div class="col-span-3">
                <span @class([
                    'text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider',
                    'bg-amber-100 text-amber-700'  => $preOrder->status === 'pending_deposit',
                    'bg-blue-100 text-blue-700'    => $preOrder->status === 'deposit_paid',
                    'bg-green-100 text-green-700'  => $preOrder->status === 'converted',
                    'bg-red-100 text-red-600'      => $preOrder->status === 'cancelled',
                ])>{{ ucfirst(str_replace('_', ' ', $preOrder->status)) }}</span>
            </div>

        </a>
        @endforeach

    </div>

    <div class="mt-4 text-right">
        <a href="{{ route('business.preorders.index') }}" class="text-[10px] font-black text-purple-600 uppercase tracking-widest hover:underline">View All Pre-Orders →</a>
    </div>

    @else
    <div class="bg-white border border-dashed border-slate-200 rounded-2xl p-10 text-center">
        <p class="text-4xl mb-3">⏳</p>
        <p class="font-black text-slate-900 uppercase italic tracking-tight">No pre-orders yet</p>
        <p class="text-sm text-slate-500 font-medium mt-1">Pre-orders will appear here once buyers place deposits on upcoming listings.</p>
    </div>
    @endif

@endsection