<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Garage Panel') — BijuliCar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-[#f1f5f9] min-h-screen">
    <div class="flex min-h-screen">

        {{-- ── Sidebar ──────────────────────────────────────────────── --}}
        <aside class="w-60 bg-slate-900 flex flex-col fixed inset-y-0 z-40 shadow-2xl">

            {{-- Logo --}}
            <div class="px-5 py-5 border-b border-slate-800">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div
                        class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center shadow-[0_0_15px_rgba(245,158,11,0.4)]">
                        <svg class="w-5 h-5 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <span class="text-white font-black tracking-tighter uppercase text-sm">Bijuli<span
                            class="text-amber-500">Car</span></span>
                </a>
                <div class="text-slate-500 text-[10px] font-black uppercase tracking-widest mt-1">Service Network</div>
            </div>

            {{-- User info --}}
            <div class="px-5 py-4 border-b border-slate-800 bg-slate-800/30">
                <div class="text-[10px] text-slate-500 font-black uppercase tracking-widest mb-1">Workshop Manager</div>
                <div class="text-sm text-white font-bold truncate">{{ auth()->user()->name }}</div>
                <span
                    class="inline-block mt-1 text-[9px] px-2 py-0.5 rounded-full font-black uppercase tracking-wider bg-amber-500/10 text-amber-400 border border-amber-500/20">
                    Garage Partner
                </span>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">

                <p class="text-[9px] font-black text-slate-600 uppercase tracking-widest px-3 py-2">Main</p>

                <a href="{{ route('garage.dashboard') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all
                {{ request()->routeIs('garage.dashboard') ? 'bg-amber-500/10 text-amber-500 border border-amber-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>

                <p class="text-[9px] font-black text-slate-600 uppercase tracking-widest px-3 py-2 mt-4">Workshop</p>

                <a href="{{ route('profile.edit') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all
                {{ request()->routeIs('profile.edit') ? 'bg-amber-500/10 text-amber-500 border border-amber-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                    Update Profile
                </a>

                <a href="{{ route('garage.location.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all
                {{ request()->routeIs('garage.location.*') ? 'bg-amber-500/10 text-amber-500 border border-amber-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Map Location
                </a>

                {{-- Feature Placeholder (Coming Soon) --}}
                {{-- <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold text-slate-600 cursor-not-allowed select-none group"
                    title="Bookings coming soon">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Bookings
                    <span
                        class="ml-auto text-[8px] bg-slate-800 text-slate-500 border border-slate-700 px-1.5 py-0.5 rounded-full font-black uppercase">Soon</span>
                </div> --}}

                <p class="text-[9px] font-black text-slate-600 uppercase tracking-widest px-3 py-2 mt-4">Explore</p>

                <a href="{{ route('marketplace') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all text-slate-400 hover:bg-slate-800 hover:text-white">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Marketplace
                </a>

            </nav>

            {{-- Logout --}}
            <div class="px-3 py-4 border-t border-slate-800">
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
            <header
                class="bg-white border-b border-slate-200 px-8 py-4 flex items-center justify-between sticky top-0 z-30">
                <h1 class="text-lg font-black text-slate-900 uppercase italic tracking-tight">
                    @yield('page-title', 'Garage Dashboard')
                </h1>
                <div class="flex items-center gap-4">
                    <span
                        class="flex items-center gap-2 text-[10px] font-black uppercase text-amber-600 bg-amber-50 px-3 py-1.5 rounded-lg border border-amber-100">
                        <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                        Garage Online
                    </span>
                </div>
            </header>

            {{-- Flash messages --}}
            @if (session('success'))
                <div
                    class="mx-8 mt-5 bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 rounded-xl px-4 py-3 text-sm font-bold shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div
                    class="mx-8 mt-5 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm font-bold shadow-sm">
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
