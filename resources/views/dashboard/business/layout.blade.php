<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — BijuliCar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-[#f1f5f9] min-h-screen">
    <div class="flex min-h-screen">

        {{-- ── Sidebar ──────────────────────────────────────────────── --}}
        <aside class="w-60 bg-slate-900 flex flex-col fixed inset-y-0 z-40">

            {{-- Logo --}}
            <div class="px-5 py-5 border-b border-slate-700">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div
                        class="w-8 h-8 bg-slate-800 border border-slate-700 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#a855f7]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span class="text-white font-black tracking-tighter uppercase text-sm">Bijuli<span
                            class="text-[#a855f7]">Car</span></span>
                </a>
                <div class="text-slate-500 text-[10px] font-black uppercase tracking-widest mt-1">Business Portal</div>
            </div>

            {{-- User info --}}
            <div class="px-5 py-4 border-b border-slate-700">
                <div class="text-[10px] text-slate-500 font-black uppercase tracking-widest mb-1">Logged in as</div>
                <div class="text-sm text-white font-bold truncate">{{ auth()->user()->name }}</div>
                <span
                    class="inline-block mt-1 text-[10px] px-2 py-0.5 rounded-full font-black uppercase tracking-wider bg-purple-500/10 text-purple-400 border border-purple-500/20">
                    Business
                </span>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">

                <p class="text-[9px] font-black text-slate-600 uppercase tracking-widest px-3 py-2">Overview</p>

                <a href="{{ route('business.dashboard') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all
                {{ request()->routeIs('business.dashboard') ? 'bg-[#a855f7]/10 text-[#a855f7] border border-[#a855f7]/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>

                @can('browse listings')
                    <p class="text-[9px] font-black text-slate-600 uppercase tracking-widest px-3 py-2 mt-2">Inventory</p>

                    <a href="{{ route('business.cars.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all
                {{ request()->routeIs('business.cars*') ? 'bg-[#a855f7]/10 text-[#a855f7] border border-[#a855f7]/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        My Listings
                        @php $totalCars = auth()->user()->listedCars()->count(); @endphp
                        @if ($totalCars > 0)
                            <span
                                class="ml-auto text-[10px] bg-slate-700 text-slate-300 px-1.5 py-0.5 rounded-full font-black">{{ $totalCars }}</span>
                        @endif
                    </a>

                    <a href="{{ route('business.cars.create') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all
                {{ request()->routeIs('business.cars.create') ? 'bg-[#a855f7]/10 text-[#a855f7] border border-[#a855f7]/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        New Listing
                    </a>
                @endcan

                @can('manage own orders')
                    <p class="text-[9px] font-black text-slate-600 uppercase tracking-widest px-3 py-2 mt-2">Sales</p>

                    <a href="{{ route('business.orders.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all
                {{ request()->routeIs('business.orders*') ? 'bg-[#a855f7]/10 text-[#a855f7] border border-[#a855f7]/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Orders
                        @php $pendingOrders = \App\Models\Order::whereHas('car', fn($q) => $q->where('seller_id', auth()->id()))->where('status', 'pending')->count(); @endphp
                        @if ($pendingOrders > 0)
                            <span
                                class="ml-auto text-[10px] bg-yellow-500/20 text-yellow-400 border border-yellow-500/20 px-1.5 py-0.5 rounded-full font-black">{{ $pendingOrders }}</span>
                        @endif
                    </a>

                    <a href="{{ route('business.preorders.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all
                {{ request()->routeIs('business.preorders*') ? 'bg-[#a855f7]/10 text-[#a855f7] border border-[#a855f7]/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Pre-Orders
                        @php $pendingPreOrders = \App\Models\PreOrder::whereHas('car', fn($q) => $q->where('seller_id', auth()->id()))->where('status', 'pending_deposit')->count(); @endphp
                        @if ($pendingPreOrders > 0)
                            <span
                                class="ml-auto text-[10px] bg-amber-500/20 text-amber-400 border border-amber-500/20 px-1.5 py-0.5 rounded-full font-black">{{ $pendingPreOrders }}</span>
                        @endif
                    </a>
                @endcan


                @can('view business analytics')
                    <p class="text-[9px] font-black text-slate-600 uppercase tracking-widest px-3 py-2 mt-2">Business</p>

                    <a href="{{ route('business.analytics') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all
                {{ request()->routeIs('business.analytics') ? 'bg-[#a855f7]/10 text-[#a855f7] border border-[#a855f7]/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Analytics
                    </a>
                @endcan

                {{-- Advertisements --}}
                @can('create advertisements')
                    <a href="{{ route('business.advertisements.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all
                {{ request()->routeIs('business.advertisements*') ? 'bg-[#a855f7]/10 text-[#a855f7] border border-[#a855f7]/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                        Advertisements
                    </a>
                @endcan

                {{-- Advertisements --}}
                <a href="{{ route('business.news.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all
                {{ request()->routeIs('business.news*') ? 'bg-[#a855f7]/10 text-[#a855f7] border border-[#a855f7]/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 12h6m-6-4h2" />
                    </svg>
                    My News
                    @php $newsCount = \App\Models\BusinessNews::where('user_id', auth()->id())->count(); @endphp
                    @if($newsCount > 0)
                        <span class="ml-auto text-[10px] bg-slate-700 text-slate-300 px-1.5 py-0.5 rounded-full font-black">{{ $newsCount }}</span>
                    @endif
                </a>

                <!-- {{-- Bulk Operations — coming soon --}}
            <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold text-slate-600 cursor-not-allowed select-none"
                 title="Coming soon">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/></svg>
                Bulk Operations
                <span class="ml-auto text-[9px] bg-slate-800 text-slate-500 border border-slate-700 px-1.5 py-0.5 rounded-full font-black uppercase tracking-wider">Soon</span>
            </div> -->

                <p class="text-[9px] font-black text-slate-600 uppercase tracking-widest px-3 py-2 mt-2">Explore</p>

                <a href="{{ route('marketplace') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all text-slate-400 hover:bg-slate-800 hover:text-white">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Marketplace
                </a>

            </nav>

            {{-- Logout --}}
            <div class="px-3 py-4 border-t border-slate-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold text-slate-500 hover:bg-red-900/20 hover:text-red-400 transition-all">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>

        </aside>

        {{-- ── Main content ─────────────────────────────────────────── --}}
        <div class="flex-1 ml-60">

            {{-- Top bar --}}
            <header class="bg-white border-b border-slate-200 px-8 py-4 flex items-center justify-between">
                <h1 class="text-lg font-black text-slate-900 uppercase italic tracking-tight">
                    @yield('page-title', 'Dashboard')
                </h1>
                <a href="{{ route('business.cars.create') }}"
                    class="inline-flex items-center gap-2 bg-slate-900 text-white px-4 py-2 rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-purple-700 transition-all shadow-lg">
                    + New Listing
                </a>
            </header>

            {{-- Flash messages --}}
            @if (session('success'))
                <div
                    class="mx-8 mt-5 bg-purple-500/10 border border-purple-500/30 text-purple-700 rounded-xl px-4 py-3 text-sm font-bold">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div
                    class="mx-8 mt-5 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm font-bold">
                    {{ session('error') }}
                </div>
            @endif

            <div class="p-8">
                @yield('content')
            </div>

        </div>

    </div>
</body>

</html>