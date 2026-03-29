@extends('frontend.app')

<title>Marketplace | BijuliCar</title>

@section('content')

    {{-- ── Hero + Filter section (untouched design) ──────────────────── --}}
    <section class="relative pt-32 pb-20 lg:pt-38 lg:pb-32 min-h-screen flex flex-col justify-end overflow-hidden bg-[#0a0f1e] text-white">

        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/marketplace_header.jpg') }}"
                class="w-full h-full object-cover scale-105 blur-[8px] opacity-20 lg:opacity-20" alt="Background">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,_rgba(15,23,42,0.1)_0%,_#0a0f1e_100%)]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 relative z-10 w-full">

            <div class="mb-10 lg:mb-16">
                <div class="flex items-center gap-3 mb-4 lg:mb-6">
                    <span class="w-8 lg:w-10 h-[2px] bg-[#4ade80]"></span>
                    <span class="text-[8px] lg:text-[10px] uppercase tracking-[0.4em] text-[#4ade80] font-bold">Global Marketplace</span>
                </div>
                <h1 class="text-5xl md:text-7xl lg:text-8xl font-black tracking-tighter text-white uppercase italic leading-[0.85]">
                    <span class="block">Digital</span>
                    <span class="text-slate-500">Showroom</span>
                </h1>
                <p class="mt-6 lg:mt-8 text-slate-400 text-xs lg:text-base font-medium max-w-xs lg:max-w-sm leading-relaxed">
                    Browse our verified inventory of high-performance electric, hybrid, and precision traditional machines.
                </p>
            </div>

            {{-- Quick search bar --}}
            <form method="GET" action="{{ route('marketplace') }}" id="quick-form">
                <div class="bg-white rounded-[2rem] lg:rounded-full p-2 lg:p-2.5 shadow-[0_40px_100px_-20px_rgba(0,0,0,0.6)] border border-white/10 backdrop-blur-md">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2 items-center">
                        <div class="w-full relative group">
                            <div class="absolute inset-y-0 left-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-[#4ade80] transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search BYD, Tesla..."
                                class="w-full bg-slate-100/80 lg:bg-slate-100/50 border-none rounded-2xl lg:rounded-full py-4 lg:py-6 pl-14 pr-8 text-sm font-bold placeholder:text-slate-400 text-slate-900 focus:ring-2 focus:ring-[#4ade80]/20 transition-all">
                        </div>
                        <div class="w-full relative">
                            <select name="drivetrain" class="w-full bg-slate-100/80 lg:bg-slate-100/50 border-none rounded-2xl lg:rounded-full py-4 lg:py-6 px-8 text-sm font-black text-slate-900 appearance-none cursor-pointer focus:ring-2 focus:ring-[#4ade80]/20 uppercase tracking-tight">
                                <option value="all">Drivetrain</option>
                                <option value="ev"     {{ request('drivetrain') === 'ev'     ? 'selected' : '' }}>EV Power</option>
                                <option value="hybrid" {{ request('drivetrain') === 'hybrid' ? 'selected' : '' }}>Hybrid Sync</option>
                                <option value="petrol" {{ request('drivetrain') === 'petrol' ? 'selected' : '' }}>Petrol</option>
                                <option value="diesel" {{ request('drivetrain') === 'diesel' ? 'selected' : '' }}>Diesel</option>
                            </select>
                            <div class="absolute inset-y-0 right-6 flex items-center pointer-events-none text-slate-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M19 9l-7 7-7-7"/></svg></div>
                        </div>
                        <div class="w-full relative">
                            <select name="location" class="w-full bg-slate-100/80 lg:bg-slate-100/50 border-none rounded-2xl lg:rounded-full py-4 lg:py-6 px-8 text-sm font-black text-slate-900 appearance-none cursor-pointer focus:ring-2 focus:ring-[#4ade80]/20 uppercase tracking-tight">
                                <option value="all">Location</option>
                                @foreach($locations as $loc)
                                <option value="{{ $loc }}" {{ request('location') === $loc ? 'selected' : '' }}>{{ $loc }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-6 flex items-center pointer-events-none text-slate-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M19 9l-7 7-7-7"/></svg></div>
                        </div>
                        <button type="submit" class="w-full px-12 py-4 lg:py-6 bg-black text-white rounded-2xl lg:rounded-full font-black uppercase italic tracking-widest text-sm hover:bg-[#4ade80] hover:text-black transition-all duration-500 active:scale-95 shadow-xl shadow-black/20">
                            Search Units
                        </button>
                    </div>
                </div>

                {{-- Advanced filters --}}
                <div class="mt-6">
                    <button type="button" onclick="toggleFilters()" class="group flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 hover:text-[#4ade80] transition-all">
                        <span class="p-2.5 bg-white/10 border border-white/10 rounded-xl group-hover:border-[#4ade80]/50 text-white group-hover:text-[#4ade80] backdrop-blur-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        </span>
                        Advanced Parameters
                    </button>

                    <div id="advanced-panel" class="{{ request()->hasAny(['brand','model_name','year_from','year_to','price_min','price_max']) ? '' : 'hidden' }} relative mt-6 p-8 lg:p-10 bg-[#0f172a]/90 border border-white/10 rounded-[2rem] lg:rounded-[3rem] shadow-2xl backdrop-blur-xl overflow-hidden">
                        <div class="absolute inset-0 z-0 opacity-[0.05] pointer-events-none" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 24px 24px;"></div>
                        <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                            <div class="space-y-3">
                                <label class="text-[10px] font-black uppercase tracking-widest text-[#4ade80]">Vehicle brand</label>
                                <input type="text" name="brand" value="{{ request('brand') }}" placeholder="e.g. Tesla, BYD" class="w-full bg-white/5 border border-white/10 rounded-xl py-4 px-6 text-sm font-bold text-white placeholder:text-slate-600 focus:ring-2 focus:ring-[#4ade80]/20 outline-none">
                            </div>
                            <div class="space-y-3">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Model Name</label>
                                <input type="text" name="model_name" value="{{ request('model_name') }}" placeholder="e.g. Model 3" class="w-full bg-white/5 border border-white/10 rounded-xl py-4 px-6 text-sm font-bold text-white placeholder:text-slate-600 focus:ring-2 focus:ring-[#4ade80]/20 outline-none">
                            </div>
                            <div class="space-y-3 lg:col-span-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Year Range</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <input type="number" name="year_from" value="{{ request('year_from') }}" placeholder="From" class="w-full bg-white/5 border border-white/10 rounded-xl py-4 px-4 text-sm font-bold text-white placeholder:text-slate-600 outline-none">
                                    <input type="number" name="year_to"   value="{{ request('year_to') }}"   placeholder="To"   class="w-full bg-white/5 border border-white/10 rounded-xl py-4 px-4 text-sm font-bold text-white placeholder:text-slate-600 outline-none">
                                </div>
                            </div>
                            <div class="space-y-3 lg:col-span-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Price Structure (NRs)</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <input type="text" name="price_min" value="{{ request('price_min') }}" placeholder="Min" class="w-full bg-white/5 border border-white/10 rounded-xl py-4 px-4 text-sm font-bold text-white placeholder:text-slate-600 outline-none">
                                    <input type="text" name="price_max" value="{{ request('price_max') }}" placeholder="Max" class="w-full bg-white/5 border border-white/10 rounded-xl py-4 px-4 text-sm font-bold text-white placeholder:text-slate-600 outline-none">
                                </div>
                            </div>
                            <div class="flex items-end pb-1 lg:justify-center">
                                <label class="relative inline-flex items-center cursor-pointer group">
                                    <input type="checkbox" name="only_available" value="1" class="sr-only peer" {{ request('only_available') ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-white/10 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-400 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#4ade80] peer-checked:after:bg-black"></div>
                                    <span class="ml-3 text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-white transition-colors">Only Available</span>
                                </label>
                            </div>
                            <div class="flex items-end justify-end gap-3">
                                <a href="{{ route('marketplace') }}" class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 hover:text-white">Clear</a>
                                <button type="submit" class="px-10 py-4 bg-[#4ade80] text-black rounded-xl text-[10px] font-black uppercase tracking-widest italic hover:bg-white transition-all shadow-lg shadow-black/40">Apply</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Stats + sort --}}
                <div class="mt-10 flex flex-col md:flex-row items-center justify-between gap-6 px-4">
                    <div class="flex flex-wrap justify-center items-center gap-4 lg:gap-6">
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#4ade80] animate-pulse"></span>
                            <span class="text-[9px] lg:text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $totalActive }} Active Units</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-600"></span>
                            <span class="text-[9px] lg:text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $cars->total() }} Results</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-[9px] lg:text-[10px] font-bold text-slate-600 uppercase italic">Sort:</span>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" class="text-[9px] lg:text-[10px] font-black uppercase tracking-widest transition-colors {{ request('sort','newest') === 'newest' ? 'text-white underline decoration-[#4ade80] decoration-2 underline-offset-4' : 'text-slate-500 hover:text-white' }}">Newest</a>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" class="text-[9px] lg:text-[10px] font-black uppercase tracking-widest transition-colors {{ request('sort') === 'price_asc' ? 'text-white underline decoration-[#4ade80] decoration-2 underline-offset-4' : 'text-slate-500 hover:text-white' }}">Price ↑</a>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" class="text-[9px] lg:text-[10px] font-black uppercase tracking-widest transition-colors {{ request('sort') === 'price_desc' ? 'text-white underline decoration-[#4ade80] decoration-2 underline-offset-4' : 'text-slate-500 hover:text-white' }}">Price ↓</a>
                    </div>
                </div>
            </form>
        </div>
    </section>

     {{-- ── Sponsored banners (marketplace placement) ─────────────────── --}}
    @if ($marketplaceAds->isNotEmpty())
        <section class="bg-[#f1f5f9] pt-10 pb-0">
            <div class="max-w-7xl mx-auto px-6 space-y-5">
                @foreach ($marketplaceAds as $ad)
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

                            {{-- Right: banner image (if provided) --}}
                            @if ($ad->image)
                                <div class="md:w-2/5 shrink-0 relative min-h-[180px] md:min-h-0">
                                    <img src="{{ asset('storage/' . $ad->image) }}" alt="{{ $ad->title }}"
                                        class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-700 absolute inset-0">
                                    {{-- Gradient fade into text side --}}
                                    <div
                                        class="hidden md:block absolute inset-y-0 left-0 w-24 bg-gradient-to-r from-slate-900 to-transparent z-10">
                                    </div>
                                </div>
                            @else
                                {{-- No image: subtle dot pattern background --}}
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

    {{-- ── Car listings grid ───────────────────────────────────────────── --}}
    <section class="py-20 bg-[#f1f5f9]">
        <div class="max-w-7xl mx-auto px-6">

            @if($cars->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($cars as $car)
                <div class="group bg-white rounded-[2rem] overflow-hidden shadow-[0_4px_20px_-4px_rgba(0,0,0,0.06)] hover:shadow-[0_30px_60px_-12px_rgba(0,0,0,0.12)] transition-all duration-500 border border-transparent hover:border-slate-100 flex flex-col">

                    {{-- Image --}}
                    <div class="relative h-48 bg-slate-900 overflow-hidden">
                        @if($car->primary_image)
                            <img src="{{ asset('storage/' . $car->primary_image) }}"
                                class="w-full h-full object-cover opacity-80 group-hover:scale-105 transition-transform duration-700"
                                alt="{{ $car->displayName() }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="text-6xl opacity-10 group-hover:opacity-20 transition-opacity">⚡</span>
                            </div>
                        @endif
                        <div class="absolute top-4 left-4">
                            <span @class([
                                'text-[9px] font-black px-2.5 py-1 rounded-full uppercase tracking-widest',
                                'bg-[#4ade80] text-black' => $car->drivetrain === 'ev',
                                'bg-blue-500 text-white'  => $car->drivetrain === 'hybrid',
                                'bg-slate-600 text-white' => in_array($car->drivetrain, ['petrol','diesel']),
                            ])>{{ $car->drivetrain === 'ev' ? '⚡ EV' : ucfirst($car->drivetrain) }}</span>
                        </div>
                        <div class="absolute top-4 right-4">
                            <span class="text-[9px] font-black px-2.5 py-1 rounded-full uppercase tracking-widest bg-black/60 text-white backdrop-blur-sm">
                                {{ ucfirst($car->condition) }}
                            </span>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="p-6 flex flex-col flex-1">

                        {{-- Title & location --}}
                        <div class="mb-4">
                            <h3 class="text-[15px] font-black text-slate-900 uppercase italic tracking-tight leading-snug">{{ $car->displayName() }}</h3>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $car->location }}
                            </p>
                        </div>

                        {{-- Spec badges --}}
                        <div class="flex flex-wrap gap-2 mb-5">
                            <span class="text-[10px] font-black px-2 py-1 bg-slate-100 text-slate-600 rounded-lg uppercase tracking-wider">{{ number_format($car->mileage) }} km</span>
                            @if($car->range_km)
                                <span class="text-[10px] font-black px-2 py-1 bg-[#4ade80]/10 text-[#16a34a] rounded-lg uppercase tracking-wider border border-[#4ade80]/20">{{ $car->range_km }} km range</span>
                            @endif
                            @if($car->battery_kwh)
                                <span class="text-[10px] font-black px-2 py-1 bg-slate-100 text-slate-600 rounded-lg uppercase tracking-wider">{{ $car->battery_kwh }} kWh</span>
                            @endif
                        </div>

                        {{-- Price + actions --}}
                        <div class="mt-auto pt-4 border-t border-slate-100">

                            {{-- Price row --}}
                            <div class="mb-4">
                                <span class="block text-[10px] uppercase tracking-widest text-slate-400 font-bold mb-0.5">Price</span>
                                <span class="text-xl font-black text-slate-900 italic tracking-tight whitespace-nowrap">NRs {{ number_format($car->price) }}</span>
                                @if($car->price_negotiable)
                                    <span class="block text-[9px] font-black text-[#16a34a] uppercase tracking-widest mt-0.5">Negotiable</span>
                                @endif
                            </div>

                            {{-- Action buttons --}}
                            <div class="flex items-center gap-2">
                                <a href="{{ route('cars.show', $car) }}"
                                    class="flex items-center gap-1.5 bg-slate-100 text-slate-700 px-3 py-2.5 rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-slate-200 transition-all shrink-0">
                                    Details
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>

                                @auth
                                    @if(auth()->user()->hasRole('buyer'))
                                        @php
                                            $alreadyOrdered = auth()->user()->orders()
                                                ->where('car_id', $car->id)
                                                ->whereIn('status', ['pending','confirmed','completed'])
                                                ->exists();
                                        @endphp
                                        @if($alreadyOrdered)
                                            <span class="text-[10px] font-black text-[#16a34a] uppercase tracking-widest">✓ Ordered</span>
                                        @else
                                            <form method="POST" action="{{ route('buyer.orders.store') }}">
                                                @csrf
                                                <input type="hidden" name="car_id" value="{{ $car->id }}">
                                                <button type="submit"
                                                    class="flex items-center gap-2 bg-slate-900 text-white px-4 py-2.5 rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all active:scale-95 shadow-lg">
                                                    Order
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                    {{-- Sellers & business: no order button, no login nudge --}}
                                @else
                                    {{-- Guest: nudge to login --}}
                                    <a href="{{ route('login') }}"
                                        class="flex items-center gap-1.5 bg-slate-900 text-white px-4 py-2.5 rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all">
                                        Login to Order
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($cars->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $cars->links() }}
            </div>
            @endif

            @else
            <div class="text-center py-20">
                <p class="text-6xl mb-6">🔍</p>
                <h3 class="text-2xl font-black text-slate-900 uppercase italic tracking-tight mb-3">No vehicles found</h3>
                <p class="text-slate-500 font-medium mb-8">Try adjusting your search or filters.</p>
                <a href="{{ route('marketplace') }}" class="inline-flex items-center gap-2 bg-slate-900 text-white px-6 py-3 rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all shadow-lg">Clear Filters →</a>
            </div>
            @endif

        </div>
    </section>

    <script>
        function toggleFilters() {
            document.getElementById('advanced-panel').classList.toggle('hidden');
        }
    </script>

@endsection