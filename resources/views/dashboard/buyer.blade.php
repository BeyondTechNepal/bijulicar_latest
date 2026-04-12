@extends('dashboard.buyer.layout')
@section('title', 'Buyer Dashboard')
@section('page-title', 'My Dashboard')

@section('content')
    @php
        $user = auth()->user();
        $totalOrders = $user->orders()->count();
        $pendingOrders = $user->orders()->where('status', 'pending')->count();
        $totalPurchases = $user->purchases()->count();
        $totalReviews = $user->reviews()->count();
        $recentOrders = $user->orders()->with('car')->latest('ordered_at')->take(4)->get();
    @endphp

    {{-- Welcome banner --}}
    <div class="bg-slate-900 rounded-2xl p-6 mb-6 flex items-center justify-between relative overflow-hidden">
        <div class="absolute inset-0 opacity-5"
            style="background-image: radial-gradient(#4ade80 1px, transparent 1px); background-size: 20px 20px;"></div>
        <div class="relative">
            <p class="text-[10px] font-black text-[#4ade80] uppercase tracking-widest mb-1">Welcome back</p>
            <h2 class="text-2xl font-black text-white uppercase italic tracking-tight">{{ $user->name }}</h2>
            <p class="text-slate-400 text-sm font-medium mt-1">Here's what's happening with your account.</p>
        </div>
        <span class="relative text-6xl opacity-10 hidden md:block">⚡</span>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-slate-200 rounded-2xl p-5">
            <div class="text-2xl font-black text-slate-900">{{ $totalOrders }}</div>
            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Total Orders</div>
        </div>
        <div class="bg-yellow-50 border border-yellow-100 rounded-2xl p-5">
            <div class="text-2xl font-black text-yellow-600">{{ $pendingOrders }}</div>
            <div class="text-[10px] font-black text-yellow-500 uppercase tracking-widest mt-1">Pending</div>
        </div>
        <div class="bg-[#4ade80]/10 border border-[#4ade80]/20 rounded-2xl p-5">
            <div class="text-2xl font-black text-[#16a34a]">{{ $totalPurchases }}</div>
            <div class="text-[10px] font-black text-[#16a34a]/70 uppercase tracking-widest mt-1">Purchased</div>
        </div>
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5">
            <div class="text-2xl font-black text-slate-700">{{ $totalReviews }}</div>
            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Reviews Written</div>
        </div>
    </div>

    {{-- Quick action cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        @can('manage own orders')
            <a href="{{ route('buyer.orders.index') }}"
                class="bg-white border border-slate-200 rounded-2xl p-5 hover:shadow-md hover:border-slate-300 transition-all block group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-9 h-9 bg-yellow-50 border border-yellow-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-500 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
                <p class="font-black text-slate-900 text-sm uppercase italic tracking-tight">My Orders</p>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Track and manage all orders</p>
            </a>
        @endcan

        @can('purchase vehicle')
            <a href="{{ route('buyer.purchases.index') }}"
                class="bg-white border border-slate-200 rounded-2xl p-5 hover:shadow-md hover:border-slate-300 transition-all block group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-9 h-9 bg-[#4ade80]/10 border border-[#4ade80]/20 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#16a34a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-500 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
                <p class="font-black text-slate-900 text-sm uppercase italic tracking-tight">My Purchases</p>
                <p class="text-xs text-slate-500 font-medium mt-0.5">View completed purchases</p>
            </a>
        @endcan

        @can('write reviews')
            <a href="{{ route('buyer.reviews.index') }}"
                class="bg-white border border-slate-200 rounded-2xl p-5 hover:shadow-md hover:border-slate-300 transition-all block group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-9 h-9 bg-slate-100 border border-slate-200 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                    <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-500 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
                <p class="font-black text-slate-900 text-sm uppercase italic tracking-tight">My Reviews</p>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Manage your written reviews</p>
            </a>
        @endcan
    </div>

    {{-- Recent orders table --}}
    @if ($recentOrders->isNotEmpty())
        <div class="bg-white border border-slate-200 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-5">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Recent Orders</p>
                <a href="{{ route('buyer.orders.index') }}"
                    class="text-[10px] font-black text-[#16a34a] uppercase tracking-widest hover:underline">View All →</a>
            </div>
            <div class="space-y-3">
                @foreach ($recentOrders as $order)
                    <div class="flex items-center justify-between py-3 border-b border-slate-100 last:border-0">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-9 h-9 bg-slate-100 rounded-xl flex items-center justify-center text-[10px] font-black text-slate-500 uppercase">
                                EV</div>
                            <div>
                                <p class="text-sm font-black text-slate-900">{{ $order->car->displayName() }}</p>
                                <p class="text-[11px] text-slate-400 font-medium mt-0.5">
                                    {{ $order->ordered_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <p class="text-sm font-black text-slate-700 hidden sm:block">
                                {{ $order->car->formattedPrice() }}</p>
                            <span @class([
                                'text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider',
                                'bg-yellow-100 text-yellow-700' => $order->status === 'pending',
                                'bg-blue-100 text-blue-700' => $order->status === 'confirmed',
                                'bg-[#4ade80]/15 text-[#16a34a]' => $order->status === 'completed',
                                'bg-red-100 text-red-600' => $order->status === 'cancelled',
                            ])>{{ ucfirst($order->status) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="bg-white border border-dashed border-slate-200 rounded-2xl p-10 text-center">
            <p class="text-4xl mb-3">⚡</p>
            <p class="font-black text-slate-900 uppercase italic tracking-tight">No orders yet</p>
            <p class="text-sm text-slate-500 font-medium mt-1 mb-5">Browse the marketplace to find your first EV.</p>
            <a href="{{ route('marketplace') }}"
                class="inline-flex items-center gap-2 bg-slate-900 text-white px-5 py-2.5 rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all">
                Browse Marketplace →
            </a>
        </div>
    @endif

@endsection
