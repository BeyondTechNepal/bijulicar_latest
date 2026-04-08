@extends('frontend.app')

<title>BijuliCar | Home</title>

@section('content')

        {{-- ══════════════════════════════════════════════════════════
         HERO SLIDER
    ══════════════════════════════════════════════════════════════ --}}
        <section class="relative h-screen w-full overflow-hidden bg-slate-900">
            <div class="swiper mySwiper h-full w-full">
                <div class="swiper-wrapper">

                    {{-- Slide 1: Marketplace --}}
                    <div class="swiper-slide relative">
                        <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&q=80&w=2070"
                            class="w-full h-full object-cover brightness-[0.35]" alt="EV Car">
                        <div class="absolute inset-0 flex flex-col items-center justify-center md:pb-32 text-center px-4 pt-10">
                            <div
                                class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/10 backdrop-blur-xl border border-white/15 rounded-full mb-6">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#4ade80] animate-pulse"></span>
                                <span class="text-[10px] font-black text-white uppercase tracking-[0.3em]">Live
                                    Marketplace</span>
                            </div>
                            <h1 class="text-white text-5xl md:text-7xl font-black tracking-tighter mb-4 uppercase italic">
                                Find Your <span class="text-[#4ade80]">Perfect</span> Vehicle
                            </h1>
                            <p class="text-slate-300 text-base md:text-xl max-w-2xl mb-10 font-medium leading-relaxed">
                                Nepal's largest marketplace for electric, hybrid, and traditional vehicles. Buy, sell, compare -
                                all in one place.
                            </p>
                            <div class="flex flex-wrap justify-center gap-4">
                                <a href="{{ route('marketplace') }}"
                                    class="bg-[#4ade80] text-black px-10 py-4 rounded-xl font-black uppercase italic tracking-widest hover:bg-[#22c55e] transition-all transform hover:scale-105 shadow-lg shadow-green-500/20">
                                    Browse Marketplace
                                </a>
                                <a href="{{ route('loan_calculator') }}"
                                    class="bg-white/10 backdrop-blur-md border border-white/20 text-white px-10 py-4 rounded-xl font-black uppercase italic tracking-widest hover:bg-white/20 transition-all">
                                    Loan Calculator
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Slide 2: Compare --}}
                    <div class="swiper-slide relative">
                        <img src="https://images.unsplash.com/photo-1560958089-b8a1929cea89?auto=format&fit=crop&q=80&w=2071"
                            class="w-full h-full object-cover brightness-[0.35]" alt="EV Car 2">
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4 pt-15">
                            <h1 class="text-white text-5xl md:text-8xl font-black tracking-tighter mb-4 uppercase italic">
                                Smart <span class="text-[#4ade80]">Comparisons</span>
                            </h1>
                            <p class="text-slate-300 text-base md:text-xl max-w-2xl mb-10 font-medium leading-relaxed">
                                Side-by-side technical specs for up to 3 vehicles. Make data-driven decisions before you buy.
                            </p>
                            <div class="flex flex-wrap justify-center gap-4">
                                <a href="{{ route('compare_cars') }}"
                                    class="bg-[#4ade80] text-black px-10 py-4 rounded-xl font-black uppercase italic tracking-widest hover:bg-[#22c55e] transition-all transform hover:scale-105 shadow-lg shadow-green-500/20">
                                    Start Comparing
                                </a>
                                <a href="{{ route('news') }}"
                                    class="bg-white/10 backdrop-blur-md border border-white/20 text-white px-10 py-4 rounded-xl font-black uppercase italic tracking-widest hover:bg-white/20 transition-all">
                                    Latest News
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Slide 3: Auth CTA --}}
                    <div class="swiper-slide relative">
                        <img src="https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&q=80&w=2071"
                            class="w-full h-full object-cover brightness-[0.35]" alt="EV Car 3">
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4 pt-15">
                            <h1 class="text-white text-5xl md:text-8xl font-black tracking-tighter mb-2 uppercase italic">
                                Find Your <span class="text-[#4ade80]">Type</span>
                            </h1>
                            <p class="text-slate-300 text-base md:text-xl max-w-2xl mb-10 font-medium leading-relaxed">
                                Electric, hybrid, petrol, diesel - whatever you drive, BijuliCar has it. Join the community
                                today.
                            </p>
                            <div class="flex flex-wrap justify-center gap-4">
                                @auth
                                    <a href="{{ route('dashboard') }}"
                                        class="bg-[#4ade80] text-black px-10 py-4 rounded-xl font-black uppercase italic tracking-widest hover:bg-[#22c55e] transition-all transform hover:scale-105 shadow-lg shadow-green-500/20">
                                        Go to Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}"
                                        class="bg-[#4ade80] text-black px-10 py-4 rounded-xl font-black uppercase italic tracking-widest hover:bg-[#22c55e] transition-all transform hover:scale-105 shadow-lg shadow-green-500/20">
                                        Log In
                                    </a>
                                    <a href="{{ route('register') }}"
                                        class="bg-white/10 backdrop-blur-md border border-white/20 text-white px-10 py-4 rounded-xl font-black uppercase italic tracking-widest hover:bg-white/20 transition-all">
                                        Sign Up Free
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>

                </div>

                <div class="absolute bottom-10 left-1/2 -translate-x-1/2 w-max flex flex-col items-center gap-6 z-50">

                    <div
                        class="hidden md:flex flex-col items-center gap-2 animate-bounce opacity-40 hover:opacity-100 transition-opacity pointer-events-none">
                        <span class="text-[9px] font-black uppercase tracking-[0.4em] text-white/60">Scroll Down</span>
                        <div class="w-5 h-8 border-2 border-white/20 rounded-full flex justify-center p-1">
                            <div class="w-1 h-2 bg-[#4ade80] rounded-full"></div>
                        </div>
                    </div>

                    <div class="swiper-pagination"></div>

                </div>

                <div class="swiper-button-next !text-white !right-6 md:!right-10 after:!text-xl hidden md:flex"></div>
                <div class="swiper-button-prev !text-white !left-6 md:!left-10 after:!text-xl hidden md:flex"></div>
                </div>
                </section>

        {{-- ══════════════════════════════════════════════════════════
         FLEET CLASSIFICATION  (live counts from DB)
    ══════════════════════════════════════════════════════════════ --}}
        <section class="py-24 bg-slate-50">
            <div class="max-w-7xl mx-auto px-6">

                <div class="mb-16">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="h-[2px] w-8 bg-[#4ade80]"></span>
                        <span class="text-xs font-bold tracking-[0.3em] uppercase text-slate-400">Inventory
                            Classification</span>
                    </div>
                    <h2 class="text-5xl md:text-6xl font-black tracking-tighter text-slate-900 uppercase italic">
                        The <span class="text-[#4ade80]">BijuliCar</span> Fleet
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                    {{-- EV --}}
                    <a href="{{ route('marketplace', ['drivetrain' => 'ev']) }}"
                        class="group relative bg-white rounded-[2.5rem] p-10 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-[0_30px_60px_-12px_rgba(0,0,0,0.12)] transition-all duration-500 border border-transparent hover:border-slate-100 flex flex-col justify-between h-full">
                        <div>
                            <div
                                class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-[#4ade80]/10 transition-colors">
                                <svg class="w-6 h-6 text-slate-900 group-hover:text-[#4ade80] transition-colors" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-900 mb-4 tracking-tight">Full Electric</h3>
                            <p class="text-slate-500 text-sm leading-relaxed font-medium">
                                Next-generation lithium propulsion. Engineered for maximum torque and zero-emission urban
                                efficiency.
                            </p>
                        </div>
                        <div class="mt-10 flex items-center justify-between border-t border-slate-50 pt-8">
                            <div>
                                <span class="block text-[10px] uppercase tracking-widest text-slate-400 font-bold">Available
                                    Now</span>
                                <span class="text-sm font-black text-slate-900 italic">
                                    {{ str_pad($evCount, 2, '0', STR_PAD_LEFT) }} Units
                                </span>
                            </div>
                            <div
                                class="w-12 h-12 rounded-full bg-slate-900 flex items-center justify-center text-white transform group-hover:scale-110 group-hover:bg-[#16a34a] transition-all duration-300">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </div>
                        </div>
                    </a>

                    {{-- Hybrid --}}
                    <a href="{{ route('marketplace', ['drivetrain' => 'hybrid']) }}"
                        class="group relative bg-white rounded-[2.5rem] p-10 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-[0_30px_60px_-12px_rgba(0,0,0,0.12)] transition-all duration-500 border border-transparent hover:border-slate-100 flex flex-col justify-between h-full">
                        <div>
                            <div
                                class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-[#4ade80]/10 transition-colors">
                                <svg class="w-6 h-6 text-slate-900 group-hover:text-[#4ade80] transition-colors" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 6v6l4 2" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-900 mb-4 tracking-tight">Hybrid Drive</h3>
                            <p class="text-slate-500 text-sm leading-relaxed font-medium">
                                Dual-core synchronization. High-efficiency combustion paired with responsive electric power.
                            </p>
                        </div>
                        <div class="mt-10 flex items-center justify-between border-t border-slate-50 pt-8">
                            <div>
                                <span class="block text-[10px] uppercase tracking-widest text-slate-400 font-bold">Available
                                    Now</span>
                                <span class="text-sm font-black text-slate-900 italic">
                                    {{ str_pad($hybridCount, 2, '0', STR_PAD_LEFT) }} Units
                                </span>
                            </div>
                            <div
                                class="w-12 h-12 rounded-full bg-slate-900 flex items-center justify-center text-white transform group-hover:scale-110 group-hover:bg-[#16a34a] transition-all duration-300">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </div>
                        </div>
                    </a>

                    {{-- Classic --}}
                    <a href="{{ route('marketplace', ['drivetrain' => 'classic']) }}"
                        class="group relative bg-white rounded-[2.5rem] p-10 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-[0_30px_60px_-12px_rgba(0,0,0,0.12)] transition-all duration-500 border border-transparent hover:border-slate-100 flex flex-col justify-between h-full">
                        <div>
                            <div
                                class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-[#4ade80]/10 transition-colors">
                                <svg class="w-6 h-6 text-slate-900 group-hover:text-[#4ade80] transition-colors"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path d="M3 22V8a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v14M9 22V12h6v10M12 2v4" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-slate-900 mb-4 tracking-tight">Classic Combustion</h3>
                            <p class="text-slate-500 text-sm leading-relaxed font-medium">
                                Proven mechanical reliability. Refined petrol and diesel engines for long-distance travel.
                            </p>
                        </div>
                        <div class="mt-10 flex items-center justify-between border-t border-slate-50 pt-8">
                            <div>
                                <span class="block text-[10px] uppercase tracking-widest text-slate-400 font-bold">Available
                                    Now</span>
                                <span class="text-sm font-black text-slate-900 italic">
                                    {{ str_pad($classicCount, 2, '0', STR_PAD_LEFT) }} Units
                                </span>
                            </div>
                            <div
                                class="w-12 h-12 rounded-full bg-slate-900 flex items-center justify-center text-white transform group-hover:scale-110 group-hover:bg-[#16a34a] transition-all duration-300">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </div>
                        </div>
                    </a>

                </div>
            </div>
        </section>

        {{-- ── Sponsored banners (home placement) ────────────────────────── --}}
        @if (isset($homeAds) && $homeAds->isNotEmpty())
            <section class="py-10 bg-slate-50">
                <div class="max-w-7xl mx-auto px-6 space-y-5">
                    @foreach ($homeAds as $ad)
                        @php $target = $ad->link_url ?: ($ad->car_id ? route('marketplace') : null); @endphp
                        <div class="relative rounded-2xl overflow-hidden border border-slate-200 shadow-sm group bg-slate-900">
                            <div class="flex flex-col md:flex-row min-h-[200px]">

                                {{-- Left: text content --}}
                                <div class="flex-1 flex flex-col justify-center px-8 py-8 md:py-10 z-10">
                                    <p class="text-[9px] font-black text-purple-400 uppercase tracking-widest mb-3">Sponsored
                                    </p>
                                    <h3
                                        class="text-2xl md:text-3xl font-black text-white uppercase italic tracking-tight leading-tight mb-3">
                                        {{ $ad->title }}
                                    </h3>
                                    @if ($ad->description)
                                        <p class="text-slate-400 text-sm font-medium mb-4 max-w-md leading-relaxed">
                                            {{ $ad->description }}</p>
                                    @endif

                                    {{-- Car details if linked --}}
                                    @if ($ad->car)
                                        <div class="flex flex-wrap gap-2 mb-5">
                                            <span
                                                class="text-[10px] font-black px-3 py-1.5 bg-white/10 text-white rounded-lg uppercase tracking-wider">
                                                {{ $ad->car->displayName() }}
                                            </span>
                                            <span
                                                class="text-[10px] font-black px-3 py-1.5 bg-[#4ade80]/20 text-[#4ade80] rounded-lg uppercase tracking-wider border border-[#4ade80]/20">
                                                {{ $ad->car->formattedPrice() }}
                                            </span>
                                            <span
                                                class="text-[10px] font-black px-3 py-1.5 bg-white/10 text-white rounded-lg uppercase tracking-wider">
                                                {{ strtoupper($ad->car->drivetrain) }}
                                            </span>
                                            @if ($ad->car->range_km)
                                                <span
                                                    class="text-[10px] font-black px-3 py-1.5 bg-white/10 text-white rounded-lg uppercase tracking-wider">
                                                    {{ $ad->car->range_km }} km range
                                                </span>
                                            @endif
                                        </div>
                                    @endif

                                    @if ($target)
                                        <a href="{{ $target }}"
                                            class="self-start px-6 py-2.5 bg-white text-slate-900 rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-[#4ade80] transition-all">
                                            {{ $ad->car ? 'View Listing →' : 'Learn More →' }}
                                        </a>
                                    @endif
                                </div>

                                {{-- Right: banner image --}}
                                @if ($ad->image)
                                    <div class="md:w-2/5 shrink-0 relative min-h-[180px] md:min-h-0">
                                        <img src="{{ Storage::url($ad->image) }}" alt="{{ $ad->title }}"
                                            class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-700 absolute inset-0">
                                        <div
                                            class="hidden md:block absolute inset-y-0 left-0 w-24 bg-gradient-to-r from-slate-900 to-transparent z-10">
                                        </div>
                                    </div>
                                @else
                                    <div class="hidden md:block md:w-1/4 shrink-0 opacity-5"
                                        style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;">
                                    </div>
                                @endif

                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif


        {{-- ══════════════════════════════════════════════════════════
         STATS BAR  (real numbers)
    ══════════════════════════════════════════════════════════════ --}}
        <section class="py-10 bg-[#0f172a]">
            <div class="max-w-9xl mx-auto px-6">
                <div
                    class="relative overflow-hidden rounded-[3rem] bg-white/5 border border-white/10 backdrop-blur-sm px-8 py-16">
                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-[#4ade80]/10 blur-[100px] rounded-full"></div>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-y-12 relative z-10">

                        <div class="flex flex-col items-center text-center group px-4">
                            <div class="mb-5 text-[#4ade80] group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <circle cx="11" cy="11" r="8" />
                                    <path d="m21 21-4.3-4.3" />
                                </svg>
                            </div>
                            <h4 class="text-4xl md:text-5xl font-black tracking-tighter text-white mb-2">2,547</h4>
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#4ade80] animate-pulse"></span>
                                <p class="text-[10px] uppercase tracking-[0.3em] font-bold text-slate-400">Active Listings</p>
                            </div>
                        </div>

                        <div class="flex flex-col items-center text-center group px-4 border-l border-white/10">
                            <div class="mb-5 text-[#4ade80] group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                </svg>
                            </div>
                            <h4 class="text-4xl md:text-5xl font-black tracking-tighter text-white mb-2">156</h4>
                            <p class="text-[10px] uppercase tracking-[0.3em] font-bold text-slate-400">Verified Dealers</p>
                        </div>

                        <div class="flex flex-col items-center text-center group px-4 border-l border-white/10">
                            <div class="mb-5 text-[#4ade80] group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                </svg>
                            </div>
                            <h4 class="text-4xl md:text-5xl font-black tracking-tighter text-white mb-2">12,890</h4>
                            <p class="text-[10px] uppercase tracking-[0.3em] font-bold text-slate-400">Happy Customers</p>
                        </div>

                        <div class="flex flex-col items-center text-center group px-4 border-l border-white/10">
                            <div class="mb-5 text-[#4ade80] group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <circle cx="12" cy="8" r="7" />
                                    <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88" />
                                </svg>
                            </div>
                            <h4 class="text-4xl md:text-5xl font-black tracking-tighter text-white mb-2">8+</h4>
                            <p class="text-[10px] uppercase tracking-[0.3em] font-bold text-slate-400">Years Experience</p>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        {{-- ══════════════════════════════════════════════════════════
         RECENTLY ADDED  (live from DB)
    ══════════════════════════════════════════════════════════════ --}}
        <section class="py-20 bg-slate-50">
            <div class="max-w-7xl mx-auto px-6">

                <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <span class="w-2 h-2 rounded-full bg-[#4ade80] animate-pulse"></span>
                            <span class="text-[10px] uppercase tracking-[0.3em] text-slate-400 font-bold">Marketplace
                                Live</span>
                        </div>
                        <h2 class="text-4xl md:text-5xl font-black tracking-tighter text-slate-900 uppercase italic">
                            Recently <span class="text-slate-300">Added</span>
                        </h2>
                    </div>
                    <a href="{{ route('marketplace') }}"
                        class="group flex items-center gap-3 text-sm font-bold uppercase tracking-widest text-slate-900 hover:text-[#16a34a] transition-colors">
                        Explore All Inventory
                        <span
                            class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center group-hover:bg-[#16a34a] group-hover:border-[#16a34a] group-hover:text-white transition-all">→</span>
                    </a>
                </div>

                @if ($recentCars->isNotEmpty())
                    {{-- Carousel wrapper --}}
                    <div class="relative">
                        {{-- Prev / Next buttons --}}
                        <button id="carousel-prev"
                            class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-5 z-20 w-11 h-11 rounded-full bg-white shadow-lg border border-slate-100 flex items-center justify-center hover:bg-slate-900 hover:text-white transition-all group hidden md:flex">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button id="carousel-next"
                            class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-5 z-20 w-11 h-11 rounded-full bg-white shadow-lg border border-slate-100 flex items-center justify-center hover:bg-slate-900 hover:text-white transition-all group hidden md:flex">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        {{-- Scrollable track --}}
                        <div id="carousel-track" class="flex gap-6 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-4"
                            style="scrollbar-width:none; -ms-overflow-style:none;">

                            @foreach ($recentCars as $car)
                                @php
        $dtColors = [
            'ev' => ['badge' => 'bg-[#4ade80] text-black', 'label' => '⚡ EV'],
            'hybrid' => ['badge' => 'bg-blue-500 text-white', 'label' => '🔋 Hybrid'],
            'petrol' => [
                'badge' => 'bg-black/60 backdrop-blur-md text-white',
                'label' => 'Petrol',
            ],
            'diesel' => [
                'badge' => 'bg-black/60 backdrop-blur-md text-white',
                'label' => 'Diesel',
            ],
        ];
        $dtc = $dtColors[$car->drivetrain] ?? $dtColors['petrol'];
        $sellerRole = $car->seller?->getRoleNames()->first();
                                @endphp

                                <div
                                    class="group relative bg-white rounded-[2.5rem] overflow-hidden shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] hover:shadow-[0_30px_60px_-12px_rgba(0,0,0,0.12)] transition-all duration-500 flex-none w-[85vw] sm:w-[42vw] lg:w-[30rem] snap-start">

                                    {{-- Image --}}
                                    <div class="relative h-64 overflow-hidden bg-slate-100">
                                        <div class="absolute top-5 left-5 z-10 flex gap-2 flex-wrap">
                                            <span
                                                class="text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-wider {{ $dtc['badge'] }}">
                                                {{ $dtc['label'] }}
                                            </span>
                                            @if ($sellerRole === 'business')
                                                <span
                                                    class="bg-[#4ade80] text-black text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-wider">
                                                    Verified Dealer
                                                </span>
                                            @endif
                                        </div>

                                        @if ($car->primary_image)
                                            <img src="{{ Storage::url($car->primary_image) }}"
                                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                                alt="{{ $car->displayName() }}">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-slate-900">
                                                <span
                                                    class="text-6xl opacity-10 group-hover:opacity-20 transition-opacity">🚗</span>
                                            </div>
                                        @endif

                                        {{-- Condition badge --}}
                                        <div class="absolute top-5 right-5 z-10">
                                            <span
                                                class="bg-black/60 backdrop-blur-sm text-white text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wider">
                                                {{ ucfirst($car->condition) }}
                                            </span>
                                        </div>

                                        {{-- Time ago --}}
                                        <div
                                            class="absolute bottom-4 right-4 bg-white/90 backdrop-blur-md px-3 py-1 rounded-lg shadow-sm">
                                            <span class="text-slate-500 text-[10px] font-bold">🕐
                                                {{ $car->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>

                                    {{-- Body --}}
                                    <div class="p-8">
                                        <div class="flex justify-between items-start mb-4 gap-3">
                                            <div class="min-w-0">
                                                <p
                                                    class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-1 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    {{ $car->location }}
                                                </p>
                                                <h3
                                                    class="text-xl font-black text-slate-900 tracking-tight italic uppercase leading-tight truncate">
                                                    {{ $car->displayName() }}
                                                </h3>
                                                @if ($car->variant)
                                                    <p class="text-[11px] text-slate-400 font-medium mt-0.5">
                                                        {{ $car->variant }}</p>
                                                @endif
                                            </div>
                                            <p class="text-lg font-black text-[#16a34a] shrink-0">NRs
                                                {{ number_format($car->price) }}</p>
                                        </div>

                                        <div class="grid grid-cols-2 gap-3 mb-6">
                                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 text-center">
                                                <p class="text-[10px] text-slate-400 font-bold uppercase">Mileage</p>
                                                <p class="text-sm font-black text-slate-900">
                                                    {{ number_format($car->mileage) }} km</p>
                                            </div>
                                            @if ($car->range_km)
                                                <div class="bg-green-50 p-3 rounded-xl border border-green-100 text-center">
                                                    <p class="text-[10px] text-green-600 font-bold uppercase">EV Range</p>
                                                    <p class="text-sm font-black text-green-800">{{ $car->range_km }} km</p>
                                                </div>
                                            @elseif ($car->battery_kwh)
                                                <div class="bg-blue-50 p-3 rounded-xl border border-blue-100 text-center">
                                                    <p class="text-[10px] text-blue-600 font-bold uppercase">Battery</p>
                                                    <p class="text-sm font-black text-blue-800">{{ $car->battery_kwh }} kWh
                                                    </p>
                                                </div>
                                            @else
                                                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 text-center">
                                                    <p class="text-[10px] text-slate-400 font-bold uppercase">Year</p>
                                                    <p class="text-sm font-black text-slate-900">{{ $car->year }}</p>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex gap-3">
                                            <a href="{{ route('cars.show', $car) }}"
                                                class="flex-[2] flex items-center justify-center bg-slate-900 text-white py-3.5 rounded-2xl font-black text-sm uppercase italic tracking-widest hover:bg-[#16a34a] transition-all active:scale-95">
                                                View Details
                                            </a>
                                            <a href="{{ route('compare_cars', ['cars[]' => $car->id]) }}"
                                                class="flex-1 flex items-center justify-center border border-slate-200 text-slate-700 py-3.5 rounded-2xl font-black text-sm hover:bg-slate-50 hover:border-slate-300 transition-all">
                                                Compare
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>{{-- /carousel-track --}}
                    </div>{{-- /carousel wrapper --}}

                    <style>
                        #carousel-track::-webkit-scrollbar {
                            display: none
                        }
                    </style>

                    <script>
                        (function() {
                            const track = document.getElementById('carousel-track');
                            const btnPrev = document.getElementById('carousel-prev');
                            const btnNext = document.getElementById('carousel-next');
                            const CARD_W = () => track.querySelector('div')?.offsetWidth + 24 || 500; // card + gap
                            let autoTimer;

                            function scrollBy(dir) {
                                track.scrollBy({
                                    left: dir * CARD_W(),
                                    behavior: 'smooth'
                                });
                            }

                            function startAuto() {
                                autoTimer = setInterval(() => {
                                    // if near end, loop back
                                    if (track.scrollLeft + track.clientWidth >= track.scrollWidth - 10) {
                                        track.scrollTo({
                                            left: 0,
                                            behavior: 'smooth'
                                        });
                                    } else {
                                        scrollBy(1);
                                    }
                                }, 3000);
                            }

                            function resetAuto() {
                                clearInterval(autoTimer);
                                startAuto();
                            }

                            btnNext?.addEventListener('click', () => {
                                scrollBy(1);
                                resetAuto();
                            });
                            btnPrev?.addEventListener('click', () => {
                                scrollBy(-1);
                                resetAuto();
                            });
                            track.addEventListener('touchstart', () => clearInterval(autoTimer), {
                                passive: true
                            });
                            track.addEventListener('touchend', () => startAuto(), {
                                passive: true
                            });

                            startAuto();
                        })();
                    </script>
                @else
                    {{-- Empty state --}}
                    <div class="text-center py-16">
                        <div class="w-20 h-20 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-5 text-4xl">
                            🚗</div>
                        <h3 class="text-2xl font-black text-slate-900 uppercase italic tracking-tight mb-2">No Listings Yet
                        </h3>
                        <p class="text-slate-500 text-sm mb-6">Be the first to list a vehicle on BijuliCar.</p>
                        @auth
                            @if (auth()->user()->hasRole('seller') || auth()->user()->hasRole('business'))
                                <a href="{{ route('seller.cars.create') }}"
                                    class="inline-flex items-center gap-2 px-8 py-3.5 bg-slate-900 text-white rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all">
                                    + List Your Car
                                </a>
                            @endif
                        @else
                            <a href="{{ route('register') }}"
                                class="inline-flex items-center gap-2 px-8 py-3.5 bg-slate-900 text-white rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all">
                                Join & List Now
                            </a>
                        @endauth
                    </div>
                @endif

            </div>
        </section>

        {{-- ══════════════════════════════════════════════════════════
         FEATURED BUSINESSES
    ══════════════════════════════════════════════════════════════ --}}
        @if (isset($featuredBusinesses) && $featuredBusinesses->isNotEmpty())
            <!-- <section class="py-20 bg-[#0a0f1e] text-white overflow-hidden relative">
                    <div
                        class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_rgba(74,222,128,0.06)_0%,_transparent_60%)]">
                    </div>
                    <div class="absolute inset-0 opacity-[0.03]"
                        style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 28px 28px;"></div>

                    <div class="max-w-7xl mx-auto px-6 relative z-10">
                        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
                            <div>
                                <div class="flex items-center gap-3 mb-4">
                                    <span class="h-[2px] w-8 bg-[#4ade80]"></span>
                                    <span class="text-[10px] uppercase tracking-[0.4em] text-[#4ade80] font-bold">Verified
                                        Network</span>
                                </div>
                                <h2 class="text-4xl md:text-5xl font-black tracking-tighter text-white uppercase italic">
                                    Featured <span class="text-slate-500">Businesses</span>
                                </h2>
                            </div>
                            <a href="{{ route('businesses.index') }}"
                                class="inline-flex items-center gap-2 text-sm font-black text-slate-400 hover:text-[#4ade80] transition-colors group">
                                View All Businesses
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach ($featuredBusinesses as $biz)
                                <a href="{{ $biz['profile_url'] }}"
                                    class="group relative bg-white/5 border border-white/10 rounded-3xl p-6 hover:bg-white/10 hover:border-[#4ade80]/30 transition-all duration-300 overflow-hidden">

                                    {{-- Subtle glow on hover --}}
                                    <div
                                        class="absolute inset-0 bg-[radial-gradient(circle_at_50%_0%,_rgba(74,222,128,0.08),_transparent_70%)] opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none">
                                    </div>

                                    <div class="relative z-10">
                                        <div class="flex items-start justify-between mb-5">
                                            <div
                                                class="w-14 h-14 rounded-2xl bg-white/10 border border-white/10 flex items-center justify-center text-xl font-black text-white group-hover:bg-[#4ade80]/20 group-hover:border-[#4ade80]/30 transition-all duration-300">
                                                {{ $biz['initials'] }}
                                            </div>
                                            <span
                                                class="flex items-center gap-1.5 text-[10px] font-black text-[#4ade80] bg-[#4ade80]/10 border border-[#4ade80]/20 px-2.5 py-1 rounded-full">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Verified
                                            </span>
                                        </div>

                                        <h3
                                            class="text-lg font-black text-white leading-tight mb-1 group-hover:text-[#4ade80] transition-colors">
                                            {{ $biz['name'] }}
                                        </h3>
                                        <p class="text-xs font-bold text-slate-500 flex items-center gap-1.5 mb-5">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            </svg>
                                            {{ $biz['location'] }}
                                            <span class="mx-1 text-slate-600">·</span>
                                            {{ $biz['specialization'] }}
                                        </p>

                                        <div class="flex items-center gap-5 py-4 border-t border-white/5">
                                            <div>
                                                <p class="text-xl font-black text-white">{{ $biz['active_listings'] }}</p>
                                                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wide mt-0.5">
                                                    Listings</p>
                                            </div>
                                            <div class="w-px h-8 bg-white/10"></div>
                                            <div>
                                                <p class="text-xl font-black text-white flex items-center gap-1.5">
                                                    {{ $biz['avg_rating'] > 0 ? number_format($biz['avg_rating'], 1) : '—' }}
                                                    @if ($biz['avg_rating'] > 0)
                                                        <svg class="w-4 h-4 text-amber-400" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path
                                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                        </svg>
                                                    @endif
                                                </p>
                                                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wide mt-0.5">
                                                    Rating</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </section>
            -->

            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

            <section class="py-20 bg-[#0a0f1e] text-white overflow-hidden relative">
                <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_rgba(74,222,128,0.06)_0%,_transparent_60%)]">
                </div>
                <div class="absolute inset-0 opacity-[0.03]"
                    style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 28px 28px;"></div>

                <div class="max-w-7xl mx-auto px-6 relative z-10">
                    <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <span class="h-[2px] w-8 bg-[#4ade80]"></span>
                                <span class="text-[10px] uppercase tracking-[0.4em] text-[#4ade80] font-bold">Verified
                                    Network</span>
                            </div>
                            <h2 class="text-4xl md:text-5xl font-black tracking-tighter text-white uppercase italic">
                                Featured <span class="text-slate-500">Businesses</span>
                            </h2>
                        </div>
                        <a href="{{ route('businesses.index') }}"
                            class="inline-flex items-center gap-2 text-sm font-black text-slate-400 hover:text-[#4ade80] transition-colors group">
                            View All Businesses
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>

                    <div class="swiper business-swiper">
                        <div class="swiper-wrapper">
                            @foreach ($featuredBusinesses as $biz)
                                <div class="swiper-slide h-auto">
                                    <a href="{{ $biz['profile_url'] }}"
                                        class="group relative block h-full bg-white/5 border border-white/10 rounded-3xl p-6 hover:bg-white/10 hover:border-[#4ade80]/30 transition-all duration-300 overflow-hidden">

                                        <div
                                            class="absolute inset-0 bg-[radial-gradient(circle_at_50%_0%,_rgba(74,222,128,0.08),_transparent_70%)] opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none">
                                        </div>

                                        <div class="relative z-10">
                                            <div class="flex items-start justify-between mb-5">
                                                <div
                                                    class="w-14 h-14 rounded-2xl bg-white/10 border border-white/10 flex items-center justify-center text-xl font-black text-white group-hover:bg-[#4ade80]/20 group-hover:border-[#4ade80]/30 transition-all duration-300">
                                                    {{ $biz['initials'] }}
                                                </div>
                                                <span
                                                    class="flex items-center gap-1.5 text-[10px] font-black text-[#4ade80] bg-[#4ade80]/10 border border-[#4ade80]/20 px-2.5 py-1 rounded-full">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Verified
                                                </span>
                                            </div>

                                            <h3
                                                class="text-lg font-black text-white leading-tight mb-1 group-hover:text-[#4ade80] transition-colors">
                                                {{ $biz['name'] }}
                                            </h3>
                                            <p class="text-xs font-bold text-slate-500 flex items-center gap-1.5 mb-5">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                </svg>
                                                {{ $biz['location'] }}
                                                <span class="mx-1 text-slate-600">·</span>
                                                {{ $biz['specialization'] }}
                                            </p>

                                            <div class="flex items-center gap-5 py-4 border-t border-white/5">
                                                <div>
                                                    <p class="text-xl font-black text-white">{{ $biz['active_listings'] }}</p>
                                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wide mt-0.5">
                                                        Listings</p>
                                                </div>
                                                <div class="w-px h-8 bg-white/10"></div>
                                                <div>
                                                    <p class="text-xl font-black text-white flex items-center gap-1.5">
                                                        {{ $biz['avg_rating'] > 0 ? number_format($biz['avg_rating'], 1) : '—' }}
                                                        @if ($biz['avg_rating'] > 0)
                                                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                                                <path
                                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                            </svg>
                                                        @endif
                                                    </p>
                                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wide mt-0.5">
                                                        Rating</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <div class="swiper-pagination !relative !bottom-0 mt-8"></div>
                    </div>
                </div>
            </section>
        @endif

        {{-- ══════════════════════════════════════════════════════════
         PLATFORM FEATURES
    ══════════════════════════════════════════════════════════════ --}}
        <section class="py-24 bg-slate-50">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-20 gap-8">
                    <div class="max-w-xl">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="h-[2px] w-8 bg-[#4ade80]"></span>
                            <span class="text-[10px] uppercase tracking-[0.4em] text-slate-400 font-bold">The Platform
                                Advantage</span>
                        </div>
                        <h2 class="text-4xl md:text-6xl font-black tracking-tighter text-slate-900 uppercase italic">
                            Why <span class="text-slate-300">BijuliCar?</span>
                        </h2>
                    </div>
                    <p class="text-slate-500 text-sm max-w-xs leading-relaxed font-medium">
                        The most comprehensive ecosystem for vehicle trading in Nepal.
                    </p>
                </div>

                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-px bg-slate-200 border border-slate-200 rounded-[3rem] overflow-hidden shadow-2xl shadow-slate-200/50">

                    <a href="{{ route('marketplace') }}"
                        class="group bg-white p-10 transition-all duration-500 hover:bg-slate-50 block">
                        <div
                            class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center mb-10 group-hover:bg-[#4ade80]/10 transition-colors">
                            <svg class="w-6 h-6 text-slate-400 group-hover:text-[#4ade80] transition-colors" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21V12.914c0-.597-.237-1.17-.659-1.591L3.659 5.891A2.25 2.25 0 013 4.3v-1.044c0-.54.384-1.006.917-1.096A48.33 48.33 0 0112 3z" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-extrabold text-slate-900 uppercase tracking-tight mb-4 italic">Smart Search
                        </h4>
                        <p class="text-slate-500 text-sm leading-relaxed">Advanced filters covering EV range, technical specs,
                            location, and powertrain efficiency.</p>
                    </a>

                    <a href="{{ route('compare_cars') }}"
                        class="group bg-white p-10 transition-all duration-500 hover:bg-slate-50 block">
                        <div
                            class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center mb-10 group-hover:bg-[#4ade80]/10 transition-colors">
                            <svg class="w-6 h-6 text-slate-400 group-hover:text-[#4ade80] transition-colors" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-extrabold text-slate-900 uppercase tracking-tight mb-4 italic">Comparison</h4>
                        <p class="text-slate-500 text-sm leading-relaxed">Side-by-side technical teardowns. Compare up to 3
                            vehicles with complete spec accuracy.</p>
                    </a>

                    <a href="{{ route('map_location') }}"
                        class="group bg-white p-10 transition-all duration-500 hover:bg-slate-50 block">
                        <div
                            class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center mb-10 group-hover:bg-[#4ade80]/10 transition-colors">
                            <svg class="w-6 h-6 text-slate-400 group-hover:text-[#4ade80] transition-colors" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25s-7.5-4.108-7.5-11.25a7.5 7.5 0 1115 0z" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-extrabold text-slate-900 uppercase tracking-tight mb-4 italic">Geo-Search</h4>
                        <p class="text-slate-500 text-sm leading-relaxed">Locate nearby dealerships, charging stations, and
                            service centres on an interactive map.</p>
                    </a>

                    <a href="{{ route('news') }}"
                        class="group bg-white p-10 transition-all duration-500 hover:bg-slate-50 block">
                        <div
                            class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center mb-10 group-hover:bg-[#4ade80]/10 transition-colors">
                            <svg class="w-6 h-6 text-slate-400 group-hover:text-[#4ade80] transition-colors" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-extrabold text-slate-900 uppercase tracking-tight mb-4 italic">Auto News</h4>
                        <p class="text-slate-500 text-sm leading-relaxed">Real-time market insights and deep-dive reviews
                            across the automotive landscape.</p>
                    </a>

                </div>
            </div>
        </section>

        {{-- ══════════════════════════════════════════════════════════
         FINAL CTA
    ══════════════════════════════════════════════════════════════ --}}
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-6">
                <div
                    class="relative overflow-hidden bg-[#0f172a] rounded-[3rem] px-8 py-16 text-center shadow-2xl shadow-slate-900/20">

                    <div class="absolute top-0 left-0 w-full h-full">
                        <div
                            class="absolute -top-1/2 -left-1/4 w-[150%] h-[200%] bg-[radial-gradient(circle_at_center,_rgba(74,222,128,0.08)_0%,_transparent_50%)]">
                        </div>
                        <div class="absolute inset-0 opacity-[0.03]"
                            style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 40px 40px;">
                        </div>
                    </div>

                    <div class="relative z-10 max-w-3xl mx-auto">
                        <div class="flex items-center justify-center gap-3 mb-8">
                            <span class="h-[1px] w-12 bg-[#4ade80]"></span>
                            <span class="text-[10px] uppercase tracking-[0.4em] text-[#4ade80] font-bold">Evolution of
                                Driving</span>
                            <span class="h-[1px] w-12 bg-[#4ade80]"></span>
                        </div>

                        <h2 class="text-4xl md:text-6xl font-black tracking-tight text-white uppercase italic mb-6">
                            Ready to Find Your <br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-500">Next
                                Vehicle?</span>
                        </h2>

                        <p class="text-slate-400 text-lg md:text-xl font-medium mb-12 leading-relaxed">
                            Join thousands of satisfied buyers and sellers who found their perfect match through <span
                                class="text-white">BijuliCar.</span>
                        </p>

                        <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                            <a href="{{ route('marketplace') }}"
                                class="group relative px-10 py-5 bg-[#4ade80] rounded-2xl overflow-hidden transition-all hover:bg-[#22c55e] active:scale-95 shadow-[0_0_30px_rgba(74,222,128,0.3)]">
                                <span class="relative z-10 text-black font-black uppercase italic tracking-wider">Start
                                    Shopping</span>
                            </a>
                            @auth
                                @if (auth()->user()->hasRole('seller') || auth()->user()->hasRole('business'))
                                    <a href="{{ route('seller.cars.create') }}"
                                        class="px-10 py-5 bg-white/5 border border-white/10 rounded-2xl text-white font-black uppercase italic tracking-wider hover:bg-white/10 hover:border-white/20 transition-all active:scale-95 backdrop-blur-sm">
                                        + List Your Vehicle
                                    </a>
                                @else
                                    <a href="{{ route('buyer.dashboard') }}"
                                        class="px-10 py-5 bg-white/5 border border-white/10 rounded-2xl text-white font-black uppercase italic tracking-wider hover:bg-white/10 hover:border-white/20 transition-all active:scale-95 backdrop-blur-sm">
                                        My Dashboard
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('register') }}"
                                    class="px-10 py-5 bg-white/5 border border-white/10 rounded-2xl text-white font-black uppercase italic tracking-wider hover:bg-white/10 hover:border-white/20 transition-all active:scale-95 backdrop-blur-sm">
                                    List Your Vehicle
                                </a>
                            @endauth
                        </div>

                        <div class="mt-10 flex items-center justify-center gap-8 border-t border-white/5 pt-10 flex-wrap">
                            <div class="text-center">
                                <p class="text-white text-lg font-black italic uppercase leading-none">100%</p>
                                <p class="text-[8px] text-slate-500 uppercase font-bold tracking-[0.2em] mt-1">Verified Sellers
                                </p>
                            </div>
                            <div class="w-px h-8 bg-white/10"></div>
                            <div class="text-center">
                                <p class="text-white text-lg font-black italic uppercase leading-none">Safe</p>
                                <p class="text-[8px] text-slate-500 uppercase font-bold tracking-[0.2em] mt-1">Transactions</p>
                            </div>
                            <div class="w-px h-8 bg-white/10"></div>
                            <div class="text-center">
                                <p class="text-white text-lg font-black italic uppercase leading-none">No</p>
                                <p class="text-[8px] text-slate-500 uppercase font-bold tracking-[0.2em] mt-1">Hidden Costs</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <script>
            var swiper = new Swiper(".mySwiper", {
                loop: true,
                effect: "fade",
                fadeEffect: {
                    crossFade: true
                },
                autoplay: {
                    delay: 6000,
                    disableOnInteraction: false
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev"
                },
            });
        </script>

        <style>
        /* Reset Swiper defaults to allow our wrapper to control layout */
        .swiper-pagination {
            position: static !important;
            width: auto !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 6px !important;
        }

        .swiper-pagination-bullet {
            background: #fff !important;
            opacity: 0.3;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            margin: 0 !important;
            flex-shrink: 0 !important;
            width: 8px;
            height: 8px;
        }

        .swiper-pagination-bullet-active {
            background: #4ade80 !important;
            width: 30px !important; /* Slightly smaller for better mobile/tablet fit */
            border-radius: 99px !important;
            opacity: 1;
        }

        /* MD Breakpoint tweaks for the bullets */
        @media (min-width: 768px) {
            .swiper-pagination-bullet-active {
                width: 40px !important;
            }
            .swiper-pagination-bullet {
                width: 10px;
                height: 10px;
            }
        }

        .swiper-button-next, .swiper-button-prev {
            top: 50% !important;
            transform: translateY(-50%) !important;
            margin-top: 0 !important;
            opacity: 0.5;
            transition: opacity 0.3s;
        }

        .swiper-button-next:hover, .swiper-button-prev:hover {
            opacity: 1;
        }
    </style>

    {{-- swiper and style for the featured business --}}
    <script>
        const swiper = new Swiper('.business-swiper', {
            slidesPerView: 1,
            spaceBetween: 24,
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true, // This handles the "pause on hover"
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            // Responsive breakpoints
            breakpoints: {
                // When window width is >= 768px (iPad/Desktop)
                768: {
                    slidesPerView: 3,
                    spaceBetween: 24
                }
            }
        });
    </script>

    <style>
        /* Ensure all slides are the same height */
        .swiper-slide {
            height: auto !important;
        }
        /* Customize the pagination dots to match your brand */
        .swiper-pagination-bullet {
            background: #475569 !important;
            opacity: 0.5;
        }
        .swiper-pagination-bullet-active {
            background: #4ade80 !important;
            opacity: 1;
        }
    </style>
@endsection
