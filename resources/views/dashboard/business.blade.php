@extends('dashboard.business.layout')
@section('title', 'Business Dashboard')
@section('page-title', 'My Dashboard')

@section('content')
    @php
        $user            = auth()->user();
        $totalCars       = $user->listedCars()->count();
        $availableCars   = $user->listedCars()->where('status', 'available')->count();
        $pendingOrders   = \App\Models\Order::where('seller_id', $user->id)->where('status', 'pending')->count();
        $totalRevenue    = \App\Models\Purchase::whereHas('order', fn($q) => $q->where('seller_id', $user->id))->where('payment_status', 'paid')->sum('amount_paid');
        $recentOrders    = \App\Models\Order::where('seller_id', $user->id)
                            ->with(['car' => fn($q) => $q->withTrashed(), 'buyer'])
                            ->latest('ordered_at')
                            ->take(4)
                            ->get();

        // Rental stats
        $pendingRentals  = \App\Models\CarRental::where('owner_id', $user->id)->where('status', 'pending')->count();
        $activeRentals   = \App\Models\CarRental::where('owner_id', $user->id)->where('status', 'active')->count();
        $recentRentals   = \App\Models\CarRental::where('owner_id', $user->id)
                            ->with(['car' => fn($q) => $q->withTrashed(), 'renter'])
                            ->latest()
                            ->take(4)
                            ->get();
    @endphp

    {{-- Welcome banner --}}
    <div class="bg-slate-900 rounded-2xl p-6 mb-6 flex items-center justify-between relative overflow-hidden">
        <div class="absolute inset-0 opacity-5" style="background-image: radial-gradient(#a855f7 1px, transparent 1px); background-size: 20px 20px;"></div>
        <div class="relative">
            <p class="text-[10px] font-black text-[#a855f7] uppercase tracking-widest mb-1">Welcome back</p>
            <h2 class="text-2xl font-black text-white uppercase italic tracking-tight">{{ $user->name }}</h2>
            <p class="text-slate-400 text-sm font-medium mt-1">Manage your inventory, track orders and grow your business.</p>
        </div>
        <span class="relative text-6xl opacity-10 hidden md:block">🏢</span>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white border border-slate-200 rounded-2xl p-5">
            <div class="text-2xl font-black text-slate-900">{{ $totalCars }}</div>
            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Total Listings</div>
        </div>
        <div class="bg-purple-50 border border-purple-100 rounded-2xl p-5">
            <div class="text-2xl font-black text-purple-700">{{ $availableCars }}</div>
            <div class="text-[10px] font-black text-purple-400 uppercase tracking-widest mt-1">Available</div>
        </div>
        <div class="bg-yellow-50 border border-yellow-100 rounded-2xl p-5">
            <div class="text-2xl font-black text-yellow-600">{{ $pendingOrders }}</div>
            <div class="text-[10px] font-black text-yellow-500 uppercase tracking-widest mt-1">Pending Orders</div>
        </div>
        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5">
            <div class="flex items-center gap-2">
                <div class="text-2xl font-black text-blue-700">{{ $pendingRentals }}</div>
                @if($activeRentals > 0)
                <span class="text-[10px] font-black bg-green-100 text-green-700 px-2 py-0.5 rounded-full uppercase tracking-wider">{{ $activeRentals }} active</span>
                @endif
            </div>
            <div class="text-[10px] font-black text-blue-400 uppercase tracking-widest mt-1">Pending Rentals</div>
        </div>
        <div class="bg-green-50 border border-green-100 rounded-2xl p-5 col-span-2 lg:col-span-1">
            <div class="text-xl font-black text-green-700">NRs {{ number_format($totalRevenue) }}</div>
            <div class="text-[10px] font-black text-green-500 uppercase tracking-widest mt-1">Total Revenue</div>
        </div>
    </div>

    {{-- Quick actions --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <a href="{{ route('business.cars.index') }}"
            class="bg-white border border-slate-200 rounded-2xl p-5 hover:shadow-md hover:border-slate-300 transition-all block group">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 bg-purple-50 border border-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p class="font-black text-slate-900 text-sm uppercase italic tracking-tight">My Listings</p>
            <p class="text-xs text-slate-500 font-medium mt-0.5">View and manage all your inventory</p>
        </a>

        <a href="{{ route('business.orders.index') }}"
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

        <a href="{{ route('business.rentals.index') }}"
            class="bg-white border border-slate-200 rounded-2xl p-5 hover:shadow-md hover:border-slate-300 transition-all block group">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 bg-blue-50 border border-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p class="font-black text-slate-900 text-sm uppercase italic tracking-tight">Rentals</p>
            <p class="text-xs text-slate-500 font-medium mt-0.5">
                @if($pendingRentals > 0)
                    <span class="text-blue-600 font-black">{{ $pendingRentals }} booking{{ $pendingRentals === 1 ? '' : 's' }} need{{ $pendingRentals === 1 ? 's' : '' }} confirmation</span>
                @elseif($activeRentals > 0)
                    <span class="text-green-600 font-black">{{ $activeRentals }} vehicle{{ $activeRentals === 1 ? '' : 's' }} currently out</span>
                @else
                    No active rentals right now
                @endif
            </p>
        </a>

        <a href="{{ route('business.analytics') }}"
            class="bg-white border border-slate-200 rounded-2xl p-5 hover:shadow-md hover:border-slate-300 transition-all block group">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 bg-green-50 border border-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <svg class="w-4 h-4 text-slate-300 group-hover:text-slate-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <p class="font-black text-slate-900 text-sm uppercase italic tracking-tight">Analytics</p>
            <p class="text-xs text-slate-500 font-medium mt-0.5">Revenue, orders and inventory breakdown</p>
        </a>
    </div>

    {{-- Recent Orders + Recent Rentals side by side on large screens --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Recent Orders --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-5">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Recent Orders</p>
                <a href="{{ route('business.orders.index') }}" class="text-[10px] font-black text-purple-600 uppercase tracking-widest hover:underline">View All →</a>
            </div>
            @if($recentOrders->isNotEmpty())
            <div class="space-y-3">
                @foreach($recentOrders as $order)
                <div class="flex items-center justify-between py-3 border-b border-slate-100 last:border-0">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-slate-100 rounded-xl flex items-center justify-center text-[10px] font-black text-slate-500 uppercase shrink-0">
                            {{ strtoupper(substr($order->car->drivetrain ?? 'NA', 0, 2)) }}
                        </div>
                        <div>
                            <p class="text-sm font-black text-slate-900">{{ $order->car->displayName() }}</p>
                            <p class="text-[11px] text-slate-400 font-medium mt-0.5">by {{ $order->buyer->name }} · {{ $order->ordered_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <span @class([
                            'text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider',
                            'bg-yellow-100 text-yellow-700' => $order->status === 'pending',
                            'bg-blue-100 text-blue-700'     => $order->status === 'confirmed',
                            'bg-green-100 text-green-700'   => $order->status === 'completed',
                            'bg-red-100 text-red-600'       => $order->status === 'cancelled',
                        ])>{{ ucfirst($order->status) }}</span>
                        <a href="{{ route('business.orders.show', $order) }}"
                            class="w-8 h-8 bg-slate-100 hover:bg-slate-900 rounded-xl flex items-center justify-center transition-all group">
                            <svg class="w-4 h-4 text-slate-500 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="py-10 text-center">
                <p class="text-3xl mb-2">📋</p>
                <p class="font-black text-slate-900 uppercase italic tracking-tight text-sm">No orders yet</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Orders will appear once buyers start purchasing.</p>
            </div>
            @endif
        </div>

        {{-- Recent Rentals --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-5">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Recent Rentals</p>
                <a href="{{ route('business.rentals.index') }}" class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline">View All →</a>
            </div>
            @if($recentRentals->isNotEmpty())
            <div class="space-y-3">
                @foreach($recentRentals as $rental)
                <div class="flex items-center justify-between py-3 border-b border-slate-100 last:border-0">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center text-[10px] font-black text-blue-500 uppercase shrink-0">
                            {{ strtoupper(substr($rental->car->drivetrain ?? 'NA', 0, 2)) }}
                        </div>
                        <div>
                            <p class="text-sm font-black text-slate-900">{{ $rental->carDisplayName() }}</p>
                            <p class="text-[11px] text-slate-400 font-medium mt-0.5">
                                {{ $rental->renter_name }} · {{ $rental->pickup_date->format('d M') }} → {{ $rental->return_date->format('d M') }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <span @class([
                            'text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider',
                            'bg-yellow-100 text-yellow-700'  => $rental->status === 'pending',
                            'bg-blue-100 text-blue-700'      => $rental->status === 'confirmed',
                            'bg-purple-100 text-purple-700'  => $rental->status === 'active',
                            'bg-green-100 text-green-700'    => $rental->status === 'completed',
                            'bg-red-100 text-red-600'        => $rental->status === 'cancelled',
                        ])>{{ $rental->statusLabel() }}</span>
                        <a href="{{ route('business.rentals.show', $rental) }}"
                            class="w-8 h-8 bg-slate-100 hover:bg-blue-600 rounded-xl flex items-center justify-center transition-all group">
                            <svg class="w-4 h-4 text-slate-500 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="py-10 text-center">
                <p class="text-3xl mb-2">🚗</p>
                <p class="font-black text-slate-900 uppercase italic tracking-tight text-sm">No rentals yet</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Rental bookings will appear here once renters start booking.</p>
            </div>
            @endif
        </div>

    </div>

@endsection