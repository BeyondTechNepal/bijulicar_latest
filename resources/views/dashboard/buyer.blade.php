@extends('dashboard.buyer.layout')
@section('title', 'Buyer Dashboard')
@section('page-title', 'My Dashboard')

@section('content')
    @php
        $user = auth()->user();
        $totalOrders        = $user->orders()->count();
        $pendingOrders      = $user->orders()->where('status', 'pending')->count();
        $totalPurchases     = $user->purchases()->count();
        $totalReviews       = $user->reviews()->count();
        $totalRentals       = $user->rentalBookings()->count();
        $activeRentals      = $user->rentalBookings()->whereIn('status', ['confirmed', 'active'])->count();
        $pendingRentals     = $user->rentalBookings()->where('status', 'pending')->count();
        $recentOrders       = $user->orders()->with(['car' => fn($q) => $q->withTrashed()])->latest('ordered_at')->take(4)->get();
        $recentRentals      = $user->rentalBookings()->with(['car' => fn($q) => $q->withTrashed()])->latest()->take(3)->get();
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
    <div class="grid grid-cols-2 lg:grid-cols-6 gap-4 mb-6">
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
        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5">
            <div class="text-2xl font-black text-blue-700">{{ $totalRentals }}</div>
            <div class="text-[10px] font-black text-blue-400 uppercase tracking-widest mt-1">Total Rentals</div>
        </div>
        <div class="{{ $activeRentals > 0 ? 'bg-green-50 border-green-200' : 'bg-slate-50 border-slate-200' }} border rounded-2xl p-5">
            <div class="text-2xl font-black {{ $activeRentals > 0 ? 'text-green-600' : 'text-slate-400' }}">{{ $activeRentals }}</div>
            <div class="text-[10px] font-black {{ $activeRentals > 0 ? 'text-green-500' : 'text-slate-400' }} uppercase tracking-widest mt-1">Active Rentals</div>
        </div>
    </div>

    {{-- Quick action cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
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

        @can('manage own orders')
            <a href="{{ route('buyer.rentals.index') }}"
                class="bg-white border border-slate-200 rounded-2xl p-5 hover:shadow-md hover:border-blue-200 transition-all block group">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-9 h-9 bg-blue-50 border border-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($pendingRentals > 0)
                            <span class="text-[10px] bg-yellow-100 text-yellow-700 border border-yellow-200 px-1.5 py-0.5 rounded-full font-black">{{ $pendingRentals }} pending</span>
                        @elseif($activeRentals > 0)
                            <span class="text-[10px] bg-green-100 text-green-700 border border-green-200 px-1.5 py-0.5 rounded-full font-black">{{ $activeRentals }} active</span>
                        @endif
                        <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-500 transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>
                <p class="font-black text-slate-900 text-sm uppercase italic tracking-tight">My Rentals</p>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Track and manage rental bookings</p>
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
                                <p class="text-sm font-black text-slate-900">{{ $order->car ? $order->car->displayName() : 'Listing removed' }}</p>
                                <p class="text-[11px] text-slate-400 font-medium mt-0.5">
                                    {{ $order->ordered_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <p class="text-sm font-black text-slate-700 hidden sm:block">
                                {{ $order->car ? $order->car->formattedPrice() : 'NRs ' . number_format($order->total_price) }}</p>
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

    {{-- Recent Rentals --}}
    @if($recentRentals->isNotEmpty())
        <div class="bg-white border border-slate-200 rounded-2xl p-6 mt-6">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-2">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Recent Rentals</p>
                    @if($pendingRentals > 0)
                        <span class="text-[10px] bg-yellow-100 text-yellow-700 border border-yellow-200 px-2 py-0.5 rounded-full font-black">{{ $pendingRentals }} pending</span>
                    @endif
                    @if($activeRentals > 0)
                        <span class="text-[10px] bg-green-100 text-green-700 border border-green-200 px-2 py-0.5 rounded-full font-black">{{ $activeRentals }} active</span>
                    @endif
                </div>
                <a href="{{ route('buyer.rentals.index') }}"
                    class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline">View All →</a>
            </div>
            <div class="space-y-3">
                @foreach($recentRentals as $rental)
                    <a href="{{ route('buyer.rentals.show', $rental) }}"
                        class="flex items-center justify-between py-3 px-4 -mx-4 rounded-xl border border-transparent hover:border-slate-100 hover:bg-slate-50/60 transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-blue-50 border border-blue-100 rounded-xl flex items-center justify-center text-base shrink-0">🚗</div>
                            <div>
                                <p class="text-sm font-black text-slate-900">{{ $rental->carDisplayName() }}</p>
                                <p class="text-[11px] text-slate-400 font-medium mt-0.5">
                                    {{ $rental->pickup_date->format('d M') }} → {{ $rental->return_date->format('d M Y') }}
                                    · {{ $rental->total_days }} days
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <p class="text-sm font-black text-slate-700 hidden sm:block">NRs {{ number_format($rental->total_price) }}</p>
                            <span @class([
                                'text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider',
                                'bg-yellow-100 text-yellow-700' => $rental->status === 'pending',
                                'bg-blue-100 text-blue-700'     => $rental->status === 'confirmed',
                                'bg-green-100 text-green-700'   => $rental->status === 'active',
                                'bg-slate-100 text-slate-600'   => $rental->status === 'completed',
                                'bg-red-100 text-red-600'       => $rental->status === 'cancelled',
                            ])>{{ $rental->statusLabel() }}</span>
                            <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-500 transition-colors hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

@endsection