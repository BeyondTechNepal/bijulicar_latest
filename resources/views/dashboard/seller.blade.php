@extends('dashboard.seller.layout')
@section('title', 'Seller Dashboard')
@section('page-title', 'My Dashboard')

@section('content')
    @php
        $user           = auth()->user();
        $totalCars      = $user->listedCars()->count();
        $availableCars  = $user->listedCars()->where('status', 'available')->count();
        $soldCars       = $user->listedCars()->where('status', 'sold')->count();
        $pendingOrders  = \App\Models\Order::whereHas('car', fn($q) => $q->where('seller_id', $user->id))->where('status', 'pending')->count();
        $totalOrders    = \App\Models\Order::whereHas('car', fn($q) => $q->where('seller_id', $user->id))->count();
        $recentOrders   = \App\Models\Order::whereHas('car', fn($q) => $q->where('seller_id', $user->id))
                            ->with('car', 'buyer')
                            ->latest('ordered_at')
                            ->take(4)
                            ->get();
    @endphp

    {{-- Welcome banner --}}
    <div class="bg-slate-900 rounded-2xl p-6 mb-6 flex items-center justify-between relative overflow-hidden">
        <div class="absolute inset-0 opacity-5" style="background-image: radial-gradient(#4ade80 1px, transparent 1px); background-size: 20px 20px;"></div>
        <div class="relative">
            <p class="text-[10px] font-black text-[#4ade80] uppercase tracking-widest mb-1">Welcome back</p>
            <h2 class="text-2xl font-black text-white uppercase italic tracking-tight">{{ $user->name }}</h2>
            <p class="text-slate-400 text-sm font-medium mt-1">Manage your listings and track incoming orders.</p>
        </div>
        <span class="relative text-6xl opacity-10 hidden md:block">🚗</span>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-slate-200 rounded-2xl p-5">
            <div class="text-2xl font-black text-slate-900">{{ $totalCars }}</div>
            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Total Listings</div>
        </div>
        <div class="bg-[#4ade80]/10 border border-[#4ade80]/20 rounded-2xl p-5">
            <div class="text-2xl font-black text-[#16a34a]">{{ $availableCars }}</div>
            <div class="text-[10px] font-black text-[#16a34a]/70 uppercase tracking-widest mt-1">Available</div>
        </div>
        <div class="bg-yellow-50 border border-yellow-100 rounded-2xl p-5">
            <div class="text-2xl font-black text-yellow-600">{{ $pendingOrders }}</div>
            <div class="text-[10px] font-black text-yellow-500 uppercase tracking-widest mt-1">Pending Orders</div>
        </div>
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5">
            <div class="text-2xl font-black text-slate-700">{{ $soldCars }}</div>
            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Sold</div>
        </div>
    </div>

    {{-- Quick actions --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <a href="{{ route('seller.cars.index') }}"
            class="bg-white border border-slate-200 rounded-2xl p-5 hover:shadow-md hover:border-slate-300 transition-all block group">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 bg-blue-50 border border-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p class="font-black text-slate-900 text-sm uppercase italic tracking-tight">My Listings</p>
            <p class="text-xs text-slate-500 font-medium mt-0.5">View and manage all your cars</p>
        </a>

        <a href="{{ route('seller.orders.index') }}"
            class="bg-white border border-slate-200 rounded-2xl p-5 hover:shadow-md hover:border-slate-300 transition-all block group">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 bg-yellow-50 border border-yellow-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p class="font-black text-slate-900 text-sm uppercase italic tracking-tight">Incoming Orders</p>
            <p class="text-xs text-slate-500 font-medium mt-0.5">
                @if($pendingOrders > 0)
                    <span class="text-yellow-600 font-black">{{ $pendingOrders }} need{{ $pendingOrders === 1 ? 's' : '' }} your response</span>
                @else
                    No pending orders right now
                @endif
            </p>
        </a>
    </div>

    {{-- Recent orders --}}
    @if($recentOrders->isNotEmpty())
    <div class="bg-white border border-slate-200 rounded-2xl p-6">
        <div class="flex items-center justify-between mb-5">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Recent Orders</p>
            <a href="{{ route('seller.orders.index') }}" class="text-[10px] font-black text-[#16a34a] uppercase tracking-widest hover:underline">View All →</a>
        </div>
        <div class="space-y-3">
            @foreach($recentOrders as $order)
            <div class="flex items-center justify-between py-3 border-b border-slate-100 last:border-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-slate-100 rounded-xl flex items-center justify-center text-[10px] font-black text-slate-500 uppercase shrink-0">EV</div>
                    <div>
                        <p class="text-sm font-black text-slate-900">{{ $order->car->displayName() }}</p>
                        <p class="text-[11px] text-slate-400 font-medium mt-0.5">by {{ $order->buyer->name }} · {{ $order->ordered_at->diffForHumans() }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span @class([
                        'text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider',
                        'bg-yellow-100 text-yellow-700'  => $order->status === 'pending',
                        'bg-blue-100 text-blue-700'      => $order->status === 'confirmed',
                        'bg-[#4ade80]/15 text-[#16a34a]' => $order->status === 'completed',
                        'bg-red-100 text-red-600'        => $order->status === 'cancelled',
                    ])>{{ ucfirst($order->status) }}</span>
                    <a href="{{ route('seller.orders.show', $order) }}"
                        class="w-8 h-8 bg-slate-100 hover:bg-slate-900 hover:text-white rounded-xl flex items-center justify-center transition-all group">
                        <svg class="w-4 h-4 text-slate-500 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-white border border-dashed border-slate-200 rounded-2xl p-10 text-center">
        <p class="text-4xl mb-3">📋</p>
        <p class="font-black text-slate-900 uppercase italic tracking-tight">No orders yet</p>
        <p class="text-sm text-slate-500 font-medium mt-1 mb-5">List your first car to start receiving orders.</p>
        <a href="{{ route('seller.cars.create') }}"
            class="inline-flex items-center gap-2 bg-slate-900 text-white px-5 py-2.5 rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all">
            + Create Listing →
        </a>
    </div>
    @endif

@endsection