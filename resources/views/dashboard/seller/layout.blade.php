<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — BijuliCar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        #sidebar {
            transform: translateX(-100%);
            transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #sidebar.open {
            transform: translateX(0);
        }

        @media (min-width: 1024px) {
            #sidebar {
                transform: translateX(0) !important;
            }

            #sidebar-overlay {
                display: none !important;
            }
        }

        /* Custom Scrollbars */
        .overflow-y-auto {
            scrollbar-width: thin;
            scrollbar-color: #94a3b8 transparent;
        }

        .overflow-y-auto::-webkit-scrollbar {
            width: 6px;
            display: block;
        }

        .overflow-y-auto::-webkit-scrollbar-track {
            background: transparent;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 20px;
        }

        #sidebar nav::-webkit-scrollbar-thumb {
            background-color: #334155;
        }

        #sidebar nav::-webkit-scrollbar-thumb:hover,
        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background-color: #4ade80;
        }
    </style>
</head>

<body class="bg-[#f1f5f9] h-screen overflow-hidden">
    <div class="flex h-screen">

        {{-- Sidebar Overlay (mobile) --}}
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 z-30 hidden" onclick="closeSidebar()"></div>

        {{-- Sidebar --}}
        <aside id="sidebar" class="w-64 bg-slate-900 flex flex-col fixed inset-y-0 left-0 z-40 lg:translate-x-0">

            {{-- Logo --}}
            <div class="px-5 py-5 border-b border-slate-700 flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div
                        class="w-8 h-8 bg-slate-800 border border-slate-700 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-[#4ade80]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span class="text-white font-black tracking-tighter uppercase text-sm">Bijuli<span
                            class="text-[#4ade80]">Car</span></span>
                </a>
                <button onclick="closeSidebar()"
                    class="lg:hidden text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="px-5 pt-3">
                <div class="text-slate-500 text-[10px] font-black uppercase tracking-widest">Seller Portal</div>
            </div>

            {{-- User info --}}
            <div class="px-5 py-3 border-b border-slate-700">
                <div class="text-[10px] text-slate-500 font-black uppercase tracking-widest mb-1">Logged in as</div>
                <div class="text-sm text-white font-bold truncate">{{ auth()->user()->name }}</div>
                <span
                    class="inline-block mt-1 text-[10px] px-2 py-0.5 rounded-full font-black uppercase tracking-wider bg-blue-500/10 text-blue-400 border border-blue-500/20">
                    Seller
                </span>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">

                <p class="text-[9px] font-black text-slate-600 uppercase tracking-widest px-3 py-2">Overview</p>

                <a href="{{ route('seller.dashboard') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all
                {{ request()->routeIs('seller.dashboard') ? 'bg-[#4ade80]/10 text-[#4ade80] border border-[#4ade80]/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>

                @can('manage car listing(seller)')
                    <p class="text-[9px] font-black text-slate-600 uppercase tracking-widest px-3 py-2 mt-2">My Listings</p>

                    <a href="{{ route('seller.cars.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all
                {{ request()->routeIs('seller.cars*') ? 'bg-[#4ade80]/10 text-[#4ade80] border border-[#4ade80]/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        My Cars
                        @php $totalCars = auth()->user()->listedCars()->count(); @endphp
                        @if ($totalCars > 0)
                            <span
                                class="ml-auto text-[10px] bg-slate-700 text-slate-300 px-1.5 py-0.5 rounded-full font-black">{{ $totalCars }}</span>
                        @endif
                    </a>
                    <a href="{{ route('seller.cars.create') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all
                {{ request()->routeIs('seller.cars.create') ? 'bg-[#4ade80]/10 text-[#4ade80] border border-[#4ade80]/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        New Listing
                    </a>
                @endcan

                @can('manage own orders')
                    <p class="text-[9px] font-black text-slate-600 uppercase tracking-widest px-3 py-2 mt-2">Sales</p>

                    <a href="{{ route('seller.orders.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all
                {{ request()->routeIs('seller.orders*') ? 'bg-[#4ade80]/10 text-[#4ade80] border border-[#4ade80]/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
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

                    <a href="{{ route('seller.preorders.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all
                {{ request()->routeIs('seller.preorders*') ? 'bg-[#4ade80]/10 text-[#4ade80] border border-[#4ade80]/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Pre-Orders
                        @php $pendingDeposits = \App\Models\PreOrder::whereHas('car', fn($q) => $q->where('seller_id', auth()->id()))->where('status', 'pending_deposit')->count(); @endphp
                        @if ($pendingDeposits > 0)
                            <span
                                class="ml-auto text-[10px] bg-yellow-500/20 text-yellow-400 border border-yellow-500/20 px-1.5 py-0.5 rounded-full font-black">{{ $pendingDeposits }}</span>
                        @endif
                    </a>

                    <a href="{{ route('seller.rentals.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all
                {{ request()->routeIs('seller.rentals*') ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Rentals
                        @php
                            $pendingRentals = \App\Models\CarRental::where('owner_id', auth()->id())
                                ->where('status', 'pending')
                                ->count();
                        @endphp
                        @if ($pendingRentals > 0)
                            <span
                                class="ml-auto text-[10px] bg-blue-500/20 text-blue-400 border border-blue-500/20 px-1.5 py-0.5 rounded-full font-black">{{ $pendingRentals }}</span>
                        @endif
                    </a>
                @endcan

                <a href="{{ route('seller.negotiations.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all
                {{ request()->routeIs('seller.negotiations*') ? 'bg-[#4ade80]/10 text-[#4ade80] border border-[#4ade80]/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-3 3v-3z" />
                    </svg>
                    Negotiations
                    @php
                        $pendingOffers = \App\Models\Negotiation::where('seller_id', auth()->id())
                            ->where('status', 'pending_seller')
                            ->count();
                    @endphp
                    @if ($pendingOffers > 0)
                        <span
                            class="ml-auto text-[10px] bg-yellow-500/20 text-yellow-400 border border-yellow-500/20 px-1.5 py-0.5 rounded-full font-black">{{ $pendingOffers }}</span>
                    @endif
                </a>

                <p class="text-[9px] font-black text-slate-600 uppercase tracking-widest px-3 py-2 mt-2">Visibility</p>

                <a href="{{ route('seller.location.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all
                {{ request()->routeIs('seller.location*') ? 'bg-[#4ade80]/10 text-[#4ade80] border border-[#4ade80]/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Map Location
                    @php $hasLoc = auth()->user()->location()->exists(); @endphp
                    @if ($hasLoc)
                        <span class="ml-auto w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    @endif
                </a>

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

        {{-- Main content --}}
        <div class="flex-1 lg:ml-64 w-full max-w-full flex flex-col h-[100dvh] overflow-hidden">

            {{-- Top bar --}}
            <header
                class="bg-white border-b border-slate-200 px-4 py-3 sm:px-6 lg:px-8 sm:py-4 flex items-center justify-between gap-2 sticky top-0 z-20 w-full">
                <div class="flex items-center gap-3 min-w-0 flex-1">
                    <button id="hamburger-btn" onclick="openSidebar()"
                        class="lg:hidden flex items-center justify-center w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 class="text-sm sm:text-lg font-black text-slate-900 uppercase italic tracking-tight truncate">
                        @yield('page-title', 'Dashboard')
                    </h1>
                </div>

                <a href="{{ route('seller.cars.create') }}"
                    class="inline-flex items-center gap-2 bg-slate-900 text-white px-4 py-2 rounded-xl text-[10px] sm:text-[12px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all shadow-lg shrink-0">
                    <span class="hidden xs:inline">+ New</span> Listing
                </a>
            </header>

            {{-- Flash messages --}}
            <div class="px-4 sm:px-6 lg:px-8">
                @if (session('success'))
                    <div
                        class="mt-4 bg-[#4ade80]/10 border border-[#4ade80]/30 text-[#16a34a] rounded-xl px-4 py-3 text-sm font-bold">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div
                        class="mt-4 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm font-bold">
                        {{ session('error') }}
                    </div>
                @endif
            </div>

            {{-- Content Area --}}
            <div class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                @yield('content')
            </div>

        </div>

    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            if (window.innerWidth >= 1024) return;
            sidebar.classList.remove('open');
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
        }

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeSidebar();
        });
    </script>

</body>

</html>
