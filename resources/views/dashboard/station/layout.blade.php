<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Station Panel') — BijuliCar</title>
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
                    <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center shadow-[0_0_15px_rgba(16,185,129,0.4)]">
                        <svg class="w-5 h-5 text-slate-900" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span class="text-white font-black tracking-tighter uppercase text-sm">Bijuli<span class="text-emerald-500">Car</span></span>
                </a>
                <div class="text-slate-500 text-[10px] font-black uppercase tracking-widest mt-1">Station Network</div>
            </div>

            {{-- User info --}}
            <div class="px-5 py-4 border-b border-slate-800 bg-slate-800/30">
                <div class="text-[10px] text-slate-500 font-black uppercase tracking-widest mb-1">Station Manager</div>
                <div class="text-sm text-white font-bold truncate">{{ auth()->user()->name }}</div>
                <span class="inline-block mt-1 text-[9px] px-2 py-0.5 rounded-full font-black uppercase tracking-wider bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    EV Partner
                </span>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">

                <p class="text-[9px] font-black text-slate-600 uppercase tracking-widest px-3 py-2">Main</p>

                <a href="{{ route('station.dashboard') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all
                {{ request()->routeIs('station.dashboard') ? 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>

                <p class="text-[9px] font-black text-slate-600 uppercase tracking-widest px-3 py-2 mt-4">Station</p>

                <a href="{{ route('profile.edit') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all
                {{ request()->routeIs('profile.edit') ? 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    </svg>
                    Update Profile
                </a>

                {{-- Feature Placeholder (Coming Soon) --}}
                {{-- <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold text-slate-600 cursor-not-allowed select-none group" title="Analytics coming soon">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Insights
                    <span class="ml-auto text-[8px] bg-slate-800 text-slate-500 border border-slate-700 px-1.5 py-0.5 rounded-full font-black uppercase">Soon</span>
                </div> --}}

                <a href="{{ route('station.location.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all
                    {{ request()->routeIs('station.location.*') ? 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>

                    Map Location
                </a>

                <p class="text-[9px] font-black text-slate-600 uppercase tracking-widest px-3 py-2 mt-4">Explore</p>


                

                {{-- <a href="{{ route('marketplace') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all text-slate-400 hover:bg-slate-800 hover:text-white">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Marketplace
                </a> --}}

            </nav>

            {{-- Logout --}}
            <div class="px-3 py-4 border-t border-slate-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold text-slate-500 hover:bg-red-900/20 hover:text-red-400 transition-all">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>

        </aside>

        {{-- ── Main content ─────────────────────────────────────────── --}}
        <div class="flex-1 ml-60">

            {{-- Top bar --}}
            <header class="bg-white border-b border-slate-200 px-8 py-4 flex items-center justify-between sticky top-0 z-30">
                <h1 class="text-lg font-black text-slate-900 uppercase italic tracking-tight">
                    @yield('page-title', 'Station Dashboard')
                </h1>
                <div class="flex items-center gap-4">
                    <span class="flex items-center gap-2 text-[10px] font-black uppercase text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                        Network Active
                    </span>
                </div>
            </header>

            {{-- Flash messages --}}
            @if (session('success'))
                <div class="mx-8 mt-5 bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 rounded-xl px-4 py-3 text-sm font-bold shadow-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mx-8 mt-5 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm font-bold shadow-sm">
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