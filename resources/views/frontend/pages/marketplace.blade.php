@extends('frontend.app')

<title>Marketplace | BijuliCar</title>

@section('content')

    {{-- ── Hero + Filter section (untouched design) ──────────────────── --}}
    <section class="relative pt-32 pb-20 lg:pt-38 lg:pb-15 min-h-60vh flex flex-col justify-end overflow-hidden bg-[#0a0f1e] text-white">

        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/marketplace_header.jpg') }}"
                class="w-full h-full object-cover scale-105 blur-[8px] opacity-20 lg:opacity-20" alt="Background">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,_rgba(15,23,42,0.1)_0%,_#0a0f1e_100%)]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 relative z-10 w-full">

            <div class="mb-6 lg:mb-10"> <div class="flex items-center gap-3 mb-2 lg:mb-3"> <span class="w-8 lg:w-10 h-[2px] bg-[#4ade80]"></span>
                <span class="text-[8px] lg:text-[10px] uppercase tracking-[0.4em] text-[#4ade80] font-bold">Global Marketplace</span>
            </div>
            
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-black tracking-tighter text-white uppercase italic leading-none whitespace-nowrap">
                Digital <span class="text-slate-500">Showroom</span>
            </h1>

            <p class="mt-3 lg:mt-4 text-slate-400 text-xs lg:text-base font-medium max-w-sm lg:max-w-md leading-relaxed">
                Browse our verified inventory of high-performance electric, hybrid, and precision traditional machines.
            </p>
        </div>

            {{-- Quick search bar --}}
            <form method="GET" action="{{ route('marketplace') }}" id="quick-form">
                <div class="bg-white rounded-[2rem] lg:rounded-full p-2 lg:p-2.5 shadow-[0_40px_100px_-20px_rgba(0,0,0,0.6)] border border-white/10 backdrop-blur-md">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2 items-center">

                        {{-- Search with autocomplete --}}
                        <div class="w-full relative group" x-data>
                            <div class="absolute inset-y-0 left-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-[#4ade80] transition-colors z-10">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <input type="text" name="search" id="search-input" value="{{ request('search') }}" placeholder="Search BYD, Tesla..."
                                autocomplete="off"
                                class="w-full bg-slate-100/80 lg:bg-slate-100/50 border-none rounded-2xl lg:rounded-full py-4 lg:py-6 pl-14 pr-8 text-sm font-bold placeholder:text-slate-400 text-slate-900 focus:ring-2 focus:ring-[#4ade80]/20 transition-all">
                            <ul id="search-suggestions" class="hidden absolute left-0 right-0 top-full mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 z-[200] overflow-hidden max-h-56 overflow-y-auto"></ul>
                        </div>

                        {{-- Custom drivetrain dropdown --}}
                        <div class="w-full relative" id="dt-wrapper">
                            <input type="hidden" name="drivetrain" id="dt-value" value="{{ request('drivetrain', 'all') }}">
                            <button type="button" id="dt-btn"
                                class="w-full flex items-center justify-between bg-slate-100/80 lg:bg-slate-100/50 border-none rounded-2xl lg:rounded-full py-4 lg:py-6 px-8 text-sm font-black text-slate-900 cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#4ade80]/20 uppercase tracking-tight transition-all">
                                <span id="dt-label">{{ collect(['ev'=>'EV Power','hybrid'=>'Hybrid Sync','classic'=>'Classic Combustion','petrol'=>'Petrol','diesel'=>'Diesel'])->get(request('drivetrain'), 'Drivetrain') }}</span>
                                <svg id="dt-chevron" class="w-4 h-4 text-slate-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            {{-- dropdown list appended to body via JS --}}
                            <ul id="dt-list" class="hidden bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden py-1">
                                <li data-value="all"     class="dt-opt px-8 py-3 text-sm font-bold text-slate-500 hover:bg-[#4ade80]/10 hover:text-[#16a34a] cursor-pointer uppercase tracking-tight transition-colors">Drivetrain (All)</li>
                                <li data-value="ev"      class="dt-opt px-8 py-3 text-sm font-bold text-slate-900 hover:bg-[#4ade80]/10 hover:text-[#16a34a] cursor-pointer uppercase tracking-tight transition-colors">EV Power</li>
                                <li data-value="hybrid"  class="dt-opt px-8 py-3 text-sm font-bold text-slate-900 hover:bg-[#4ade80]/10 hover:text-[#16a34a] cursor-pointer uppercase tracking-tight transition-colors">Hybrid Sync</li>
                                <li data-value="classic" class="dt-opt px-8 py-3 text-sm font-bold text-slate-900 hover:bg-[#4ade80]/10 hover:text-[#16a34a] cursor-pointer uppercase tracking-tight transition-colors">Classic Combustion</li>
                                <li data-value="petrol"  class="dt-opt px-8 py-3 text-sm font-bold text-slate-900 hover:bg-[#4ade80]/10 hover:text-[#16a34a] cursor-pointer uppercase tracking-tight transition-colors">Petrol</li>
                                <li data-value="diesel"  class="dt-opt px-8 py-3 text-sm font-bold text-slate-900 hover:bg-[#4ade80]/10 hover:text-[#16a34a] cursor-pointer uppercase tracking-tight transition-colors">Diesel</li>
                            </ul>
                        </div>

                        {{-- Location with typeahead (replaces dropdown) --}}
                        <div class="w-full relative group">
                            <div class="absolute inset-y-0 left-6 flex items-center pointer-events-none text-slate-300 group-focus-within:text-[#4ade80] transition-colors z-10">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <input type="text" name="location" id="location-input" value="{{ request('location') }}" placeholder="Any location..."
                                autocomplete="off"
                                class="w-full bg-slate-100/80 lg:bg-slate-100/50 border-none rounded-2xl lg:rounded-full py-4 lg:py-6 pl-14 pr-8 text-sm font-bold placeholder:text-slate-400 text-slate-900 focus:ring-2 focus:ring-[#4ade80]/20 transition-all">
                            <ul id="location-suggestions" class="hidden absolute left-0 right-0 top-full mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 z-[200] overflow-hidden max-h-48 overflow-y-auto"></ul>
                        </div>

                        <button type="submit" id="search-btn" class="w-full px-12 py-4 lg:py-6 bg-black text-white rounded-2xl lg:rounded-full font-black uppercase italic tracking-widest text-sm hover:bg-[#4ade80] hover:text-black transition-all duration-500 active:scale-95 shadow-xl shadow-black/20">
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

                    <div id="advanced-panel" class="{{ request()->hasAny(['brand','model_name','year_from','year_to','price_min','price_max']) ? '' : 'hidden' }} relative mt-6 p-8 lg:p-10 bg-[#0f172a]/90 border border-white/10 rounded-[2rem] lg:rounded-[3rem] shadow-2xl backdrop-blur-xl">
                        <div class="absolute inset-0 z-0 opacity-[0.05] pointer-events-none" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 24px 24px;"></div>
                        <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">

                            <!-- {{-- Brand with autocomplete --}}
                            <div class="space-y-3 relative">
                                <label class="text-[10px] font-black uppercase tracking-widest text-[#4ade80]">Vehicle Brand</label>
                                <input type="text" name="brand" id="brand-input" value="{{ request('brand') }}" placeholder="e.g. Tesla, BYD"
                                    autocomplete="off"
                                    class="w-full bg-white/5 border border-white/10 rounded-xl py-4 px-6 text-sm font-bold text-white placeholder:text-slate-600 focus:ring-2 focus:ring-[#4ade80]/20 outline-none">
                                <ul id="brand-suggestions" class="hidden absolute left-0 right-0 top-full mt-1 bg-[#1e293b] border border-white/10 rounded-xl shadow-2xl z-[200] overflow-hidden max-h-48 overflow-y-auto"></ul>
                            </div> -->

                            {{-- Model with autocomplete --}}
                            <div class="space-y-3 relative">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Model Name</label>
                                <input type="text" name="model_name" id="model-input" value="{{ request('model_name') }}" placeholder="e.g. Model 3"
                                    autocomplete="off"
                                    class="w-full bg-white/5 border border-white/10 rounded-xl py-4 px-6 text-sm font-bold text-white placeholder:text-slate-600 focus:ring-2 focus:ring-[#4ade80]/20 outline-none">
                                <ul id="model-suggestions" class="hidden absolute left-0 right-0 top-full mt-1 bg-[#1e293b] border border-white/10 rounded-xl shadow-2xl z-[200] overflow-hidden max-h-48 overflow-y-auto"></ul>
                            </div>

                            {{-- Year range — custom dropdowns --}}
                            <div class="space-y-3 lg:col-span-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                                    Year Range
                                    <span class="text-slate-600 normal-case font-medium ml-1">({{ $minYear }} – {{ $maxYear }})</span>
                                </label>
                                <div class="grid grid-cols-2 gap-3">

                                    {{-- Year From --}}
                                    <div class="relative" id="yr-from-wrapper">
                                        <input type="hidden" name="year_from" id="yr-from-value" value="{{ request('year_from') }}">
                                        <button type="button" id="yr-from-btn"
                                            class="w-full flex items-center justify-between bg-white/5 border border-white/10 rounded-xl py-4 px-5 text-sm font-bold text-white cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#4ade80]/20 transition-all">
                                            <span id="yr-from-label">{{ request('year_from') ?: 'From' }}</span>
                                            <svg id="yr-from-chevron" class="w-4 h-4 text-slate-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M19 9l-7 7-7-7"/></svg>
                                        </button>
                                        <ul id="yr-from-list" class="hidden bg-[#1e293b] border border-white/10 rounded-xl shadow-2xl overflow-y-auto" style="max-height:220px">
                                            <li data-value="" class="yr-from-opt px-5 py-3 text-sm font-bold text-slate-500 hover:bg-[#4ade80]/20 hover:text-[#4ade80] cursor-pointer transition-colors">From (any)</li>
                                            @for ($y = $minYear; $y <= $maxYear; $y++)
                                            <li data-value="{{ $y }}" class="yr-from-opt px-5 py-3 text-sm font-bold text-white hover:bg-[#4ade80]/20 hover:text-[#4ade80] cursor-pointer transition-colors">{{ $y }}</li>
                                            @endfor
                                        </ul>
                                    </div>

                                    {{-- Year To --}}
                                    <div class="relative" id="yr-to-wrapper">
                                        <input type="hidden" name="year_to" id="yr-to-value" value="{{ request('year_to') }}">
                                        <button type="button" id="yr-to-btn"
                                            class="w-full flex items-center justify-between bg-white/5 border border-white/10 rounded-xl py-4 px-5 text-sm font-bold text-white cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#4ade80]/20 transition-all">
                                            <span id="yr-to-label">{{ request('year_to') ?: 'To' }}</span>
                                            <svg id="yr-to-chevron" class="w-4 h-4 text-slate-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M19 9l-7 7-7-7"/></svg>
                                        </button>
                                        <ul id="yr-to-list" class="hidden bg-[#1e293b] border border-white/10 rounded-xl shadow-2xl overflow-y-auto" style="max-height:220px">
                                            <li data-value="" class="yr-to-opt px-5 py-3 text-sm font-bold text-slate-500 hover:bg-[#4ade80]/20 hover:text-[#4ade80] cursor-pointer transition-colors">To (any)</li>
                                            @for ($y = $maxYear; $y >= $minYear; $y--)
                                            <li data-value="{{ $y }}" class="yr-to-opt px-5 py-3 text-sm font-bold text-white hover:bg-[#4ade80]/20 hover:text-[#4ade80] cursor-pointer transition-colors">{{ $y }}</li>
                                            @endfor
                                        </ul>
                                    </div>

                                </div>
                            </div>

                            {{-- Price slider --}}
                            <div class="space-y-3 lg:col-span-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">
                                    Price Range (NRs)
                                    <span class="text-[#4ade80] ml-2" id="price-display">
                                        {{ request('price_min') ? number_format((int)request('price_min')) : number_format($minPrice) }}
                                        –
                                        {{ request('price_max') ? number_format((int)request('price_max')) : number_format($maxPrice) }}
                                    </span>
                                </label>
                                <input type="hidden" name="price_min" id="price_min" value="{{ request('price_min', $minPrice) }}">
                                <input type="hidden" name="price_max" id="price_max" value="{{ request('price_max', $maxPrice) }}">
                                <div id="price-slider" class="relative h-6 flex items-center px-2"></div>
                            </div>

                            {{-- Only available toggle --}}
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

                {{-- Stats (updated live by JS after each search) --}}
                <div class="mt-10 flex flex-wrap items-center gap-4 px-4">
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#4ade80] animate-pulse"></span>
                        <span class="text-[9px] lg:text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $totalActive }} Active Units</span>
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
    <section id="listings-section" class="py-20 bg-[#f1f5f9]">
        <div class="max-w-7xl mx-auto px-6">

            {{-- Results count & sort (updated live by JS) --}}
            <div id="results-meta" class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
                <p id="results-count" class="text-[11px] font-black text-slate-400 uppercase tracking-widest"></p>
                <div class="flex items-center gap-3">
                    <span class="text-[10px] font-bold text-slate-400 uppercase italic">Sort:</span>
                    <button data-sort="newest"    class="sort-btn text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-900 transition-colors">Newest</button>
                    <button data-sort="price_asc" class="sort-btn text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-900 transition-colors">Price ↑</button>
                    <button data-sort="price_desc"class="sort-btn text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-900 transition-colors">Price ↓</button>
                </div>
            </div>

            {{-- Loading spinner --}}
            <div id="listings-loading" class="hidden py-20 flex justify-center">
                <div class="w-10 h-10 rounded-full border-4 border-slate-200 border-t-[#4ade80] animate-spin"></div>
            </div>

            {{-- Dynamic grid --}}
            <div id="listings-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>

            {{-- Pagination --}}
            <div id="listings-pagination" class="mt-12 flex justify-center gap-2"></div>

            {{-- Empty state (hidden by default) --}}
            <div id="listings-empty" class="hidden text-center py-20">
                <p class="text-6xl mb-6">🔍</p>
                <h3 class="text-2xl font-black text-slate-900 uppercase italic tracking-tight mb-3">No vehicles found</h3>
                <p class="text-slate-500 font-medium mb-8">Try adjusting your search or filters.</p>
                <button onclick="clearFilters()" class="inline-flex items-center gap-2 bg-slate-900 text-white px-6 py-3 rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all shadow-lg">Clear Filters →</button>
            </div>

        </div>
    </section>

    <script>
    // ── Server data ───────────────────────────────────────────────────────
    const ALL_BRANDS    = @json($brands->values());
    const ALL_MODELS    = @json($models->values());
    const ALL_LOCATIONS = @json($locations->values());
    const PRICE_MIN     = {{ $minPrice }};
    const PRICE_MAX     = {{ $maxPrice }};
    const SEARCH_URL    = '{{ route("marketplace.search") }}';
    const LOGIN_URL     = '{{ route("login") }}';
    const IS_AUTH       = @json(auth()->check());
    const IS_BUYER      = @json(auth()->check() && auth()->user()->hasRole('buyer'));
    const IS_SELLER     = @json(auth()->check() && (auth()->user()->hasRole('seller') || auth()->user()->hasRole('business') || auth()->user()->hasRole('ev-station') || auth()->user()->hasRole('garage')));
    const MIN_YEAR      = {{ $minYear }};
    const MAX_YEAR      = {{ $maxYear }};
    // Sets of car IDs the buyer has already ordered / pre-ordered (server-rendered, updated on each AJAX call)
    let ORDERED_IDS     = new Set(@json($orderedCarIds->values()));
    let PRE_ORDERED_IDS = new Set(@json($preOrderedCarIds->values()));

    // ── State ─────────────────────────────────────────────────────────────
    let currentSort = 'newest';
    let currentPage = 1;

    // ── Toggle advanced panel ─────────────────────────────────────────────
    function toggleFilters() {
        document.getElementById('advanced-panel').classList.toggle('hidden');
    }

    // ── Build params from form ────────────────────────────────────────────
    function getParams(page = 1) {
        const f = document.getElementById('quick-form');
        const p = new URLSearchParams();
        ['search','drivetrain','location','brand','model_name','year_from','year_to','price_min','price_max'].forEach(k => {
            const el = f.elements[k];
            if (!el) return;
            const v = el.value?.trim();
            if (v && v !== 'all' && v !== '') p.set(k, v);
        });
        const oa = f.elements['only_available'];
        if (oa?.checked) p.set('only_available', '1');
        p.set('sort', currentSort);
        p.set('page', page);
        return p;
    }

    // ── Fetch and render results ──────────────────────────────────────────
    async function fetchResults(page = 1, scroll = true) {
        currentPage = page;
        const grid    = document.getElementById('listings-grid');
        const loading = document.getElementById('listings-loading');
        const empty   = document.getElementById('listings-empty');
        const pag     = document.getElementById('listings-pagination');
        const count   = document.getElementById('results-count');

        grid.innerHTML = '';
        empty.classList.add('hidden');
        pag.innerHTML  = '';
        loading.classList.remove('hidden');
        loading.classList.add('flex');

        const params = getParams(page);
        // Update browser URL without reload
        history.pushState(null, '', '?' + params.toString());

        try {
            const res  = await fetch(SEARCH_URL + '?' + params.toString());
            const data = await res.json();

            loading.classList.add('hidden');
            loading.classList.remove('flex');

            count.textContent = data.total + ' result' + (data.total !== 1 ? 's' : '');

            // Refresh buyer order state from fresh response
            if (IS_BUYER) {
                ORDERED_IDS     = new Set((data.cars || []).filter(c => c.already_ordered).map(c => c.id));
                PRE_ORDERED_IDS = new Set((data.cars || []).filter(c => c.already_pre_ordered).map(c => c.id));
            }

            // Scroll to listings section always — whether results found or not
            if (scroll) {
                document.getElementById('listings-section')
                    .scrollIntoView({ behavior: 'smooth', block: 'start' });
            }

            if (data.cars.length === 0) {
                empty.classList.remove('hidden');
                return;
            }

            // Render cards
            data.cars.forEach(car => { grid.appendChild(buildCard(car)); });

            // Render pagination
            if (data.last_page > 1) renderPagination(data.current_page, data.last_page);

            // Animate cards in
            grid.querySelectorAll('.car-card').forEach((c, i) => {
                c.style.opacity = '0';
                c.style.transform = 'translateY(16px)';
                setTimeout(() => {
                    c.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    c.style.opacity = '1';
                    c.style.transform = 'translateY(0)';
                }, i * 50);
            });

        } catch (e) {
            loading.classList.add('hidden');
            grid.innerHTML = '<p class="text-slate-500 col-span-3 text-center py-10">Something went wrong. Please try again.</p>';
        }
    }

    // ── Build a single car card ───────────────────────────────────────────
    function buildCard(car) {
        const dtBadge = {
            ev:     'bg-[#4ade80] text-black',
            hybrid: 'bg-blue-500 text-white',
            petrol: 'bg-slate-600 text-white',
            diesel: 'bg-slate-600 text-white',
        }[car.drivetrain] ?? 'bg-slate-600 text-white';
        const dtLabel = car.drivetrain === 'ev' ? '⚡ EV' : car.drivetrain.charAt(0).toUpperCase() + car.drivetrain.slice(1);

        const imgHtml = car.primary_image
            ? `<img src="${car.primary_image}" class="w-full h-full object-cover opacity-90 group-hover:scale-105 transition-transform duration-700" alt="${car.name}">`
            : `<div class="w-full h-full flex items-center justify-center"><span class="text-5xl opacity-10">⚡</span></div>`;

        const specBadges = [
            car.mileage     ? `<span class="text-[10px] font-black px-2 py-1 bg-slate-100 text-slate-600 rounded-lg uppercase tracking-wider">${car.mileage} km</span>` : '',
            car.range_km    ? `<span class="text-[10px] font-black px-2 py-1 bg-[#4ade80]/10 text-[#16a34a] rounded-lg uppercase tracking-wider border border-[#4ade80]/20">${car.range_km} km range</span>` : '',
            car.battery_kwh ? `<span class="text-[10px] font-black px-2 py-1 bg-slate-100 text-slate-600 rounded-lg uppercase tracking-wider">${car.battery_kwh} kWh</span>` : '',
            car.is_preorder ? `<span class="inline-flex items-center gap-1 text-[10px] font-black px-2 py-1 bg-violet-100 text-violet-700 rounded-lg uppercase tracking-wider"><span class="w-1 h-1 rounded-full bg-violet-500 animate-pulse"></span>Upcoming</span>` : '',
        ].join('');

        // ── Determine action footer based on role & order state ──────────
        const alreadyOrdered    = IS_BUYER && ORDERED_IDS.has(car.id);
        const alreadyPreOrdered = IS_BUYER && PRE_ORDERED_IDS.has(car.id);
        const detailsBtn = `<a href="${car.url}" class="flex items-center gap-1.5 bg-slate-100 text-slate-700 px-3 py-2.5 rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-slate-200 transition-all shrink-0">
            Details
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
        </a>`;

        let footerHtml = '';

        if (IS_SELLER) {
            // Sellers & businesses: Details only, no order/pre-order button
            footerHtml = `<div class="flex items-center gap-2 mt-auto pt-4 border-t border-slate-100">${detailsBtn}</div>`;

        } else if (IS_BUYER) {
            if (car.is_preorder) {
                if (alreadyPreOrdered) {
                    footerHtml = `<div class="mt-auto pt-3 border-t border-violet-100 -mx-6 -mb-6 px-6 py-3 bg-violet-50 rounded-b-xl">
                        <div class="flex items-center gap-2">
                            ${detailsBtn}
                            <div class="flex-1 flex items-center justify-center gap-2 text-[11px] font-black text-violet-600 uppercase tracking-widest">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Pre-Order Placed
                            </div>
                        </div>
                    </div>`;
                } else {
                    footerHtml = `<div class="flex items-center gap-2 mt-auto pt-4 border-t border-slate-100">
                        ${detailsBtn}
                        <a href="${car.order_url}" class="flex-1 flex items-center justify-center gap-1.5 bg-violet-600 text-white px-3 py-2.5 rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-violet-700 transition-all active:scale-95">
                            ⚡ Pre-Order
                        </a>
                    </div>`;
                }
            } else {
                if (alreadyOrdered) {
                    footerHtml = `<div class="mt-auto pt-3 border-t border-green-100 flex items-center justify-center gap-2 text-[11px] font-black text-green-600 uppercase tracking-widest bg-green-50 rounded-b-xl -mx-6 -mb-6 px-6 py-3">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Already Ordered
                    </div>`;
                } else {
                    footerHtml = `<div class="flex items-center gap-2 mt-auto pt-4 border-t border-slate-100">
                        ${detailsBtn}
                        <a href="${car.order_url}" class="flex-1 flex items-center justify-center gap-1.5 bg-slate-900 text-white px-3 py-2.5 rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all active:scale-95">
                            Order Now
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>`;
                }
            }

        } else {
            // Guest: single Details/login CTA
            if (car.is_preorder) {
                footerHtml = `<div class="flex items-center gap-2 mt-auto pt-4 border-t border-slate-100">
                    ${detailsBtn}
                    <a href="${LOGIN_URL}" class="flex-1 flex items-center justify-center gap-1.5 bg-violet-600 text-white px-3 py-2.5 rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-violet-700 transition-all active:scale-95">
                        ⚡ Pre-Order
                    </a>
                </div>`;
            } else {
                footerHtml = `<div class="flex items-center gap-2 mt-auto pt-4 border-t border-slate-100">
                    ${detailsBtn}
                    <a href="${LOGIN_URL}" class="flex-1 flex items-center justify-center gap-1.5 bg-slate-900 text-white px-3 py-2.5 rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all active:scale-95">
                        Order Now
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>`;
            }
        }

        const div = document.createElement('div');
        div.className = 'car-card group bg-white rounded-2xl overflow-hidden shadow-[0_2px_12px_-2px_rgba(0,0,0,0.07)] hover:shadow-[0_16px_40px_-8px_rgba(0,0,0,0.12)] transition-all duration-300 border border-slate-100 hover:border-slate-200 flex flex-col';
        div.innerHTML = `
            <div class="relative h-44 bg-slate-900 overflow-hidden">
                ${imgHtml}
                <div class="absolute top-3 left-3">
                    <span class="text-[9px] font-black px-2.5 py-1 rounded-full uppercase tracking-widest ${dtBadge}">${dtLabel}</span>
                </div>
                <div class="absolute top-3 right-3">
                    ${car.is_preorder
                        ? `<span class="text-[9px] font-black px-2.5 py-1 rounded-full uppercase tracking-widest bg-violet-600 text-white">Upcoming</span>`
                        : `<span class="text-[9px] font-black px-2.5 py-1 rounded-full uppercase tracking-widest bg-black/60 text-white backdrop-blur-sm">${car.condition}</span>`
                    }
                </div>
            </div>
            <div class="p-5 flex flex-col flex-1">
                <h3 class="text-[14px] font-black text-slate-900 uppercase italic tracking-tight leading-snug mb-1">${car.name}</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-1">
                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    ${car.location}
                </p>
                <div class="flex flex-wrap gap-1.5 mb-4">${specBadges}</div>
                <div class="mb-1">
                    <span class="block text-[9px] uppercase tracking-widest text-slate-400 font-bold mb-0.5">Price</span>
                    <span class="text-[18px] font-black italic tracking-tight whitespace-nowrap ${car.is_preorder ? 'text-slate-900' : 'text-slate-900'}">NRs ${car.price}</span>
                    ${car.is_preorder && car.preorder_deposit
                        ? `<span class="block text-[9px] font-black text-slate-900 uppercase tracking-widest mt-0.5">Deposit: NRs ${car.preorder_deposit}${car.expected_arrival_date ? ' · ' + car.expected_arrival_date : ''}</span>`
                        : (car.price_negotiable ? '<span class="block text-[9px] font-black text-[#16a34a] uppercase tracking-widest mt-0.5">Negotiable</span>' : '')
                    }
                </div>
                ${footerHtml}
            </div>`;

        return div;
    }

    // ── Pagination ────────────────────────────────────────────────────────
    function renderPagination(current, last) {
        const pag = document.getElementById('listings-pagination');
        const btn = (label, page, active = false, disabled = false) => {
            const b = document.createElement('button');
            b.textContent = label;
            b.className = [
                'px-4 py-2 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all',
                active   ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200',
                disabled ? 'opacity-30 pointer-events-none' : '',
            ].join(' ');
            if (!disabled) b.addEventListener('click', () => fetchResults(page));
            return b;
        };
        pag.appendChild(btn('←', current - 1, false, current === 1));
        for (let p = 1; p <= last; p++) {
            if (last > 7 && Math.abs(p - current) > 2 && p !== 1 && p !== last) {
                if (p === current - 3 || p === current + 3) pag.appendChild(Object.assign(document.createElement('span'), { textContent: '…', className: 'px-2 text-slate-400' }));
                continue;
            }
            pag.appendChild(btn(p, p, p === current));
        }
        pag.appendChild(btn('→', current + 1, false, current === last));
    }

    // ── Sort buttons ──────────────────────────────────────────────────────
    function updateSortUI() {
        document.querySelectorAll('.sort-btn').forEach(b => {
            const active = b.dataset.sort === currentSort;
            b.className = 'sort-btn text-[10px] font-black uppercase tracking-widest transition-colors ' +
                (active ? 'text-slate-900 underline decoration-[#4ade80] decoration-2 underline-offset-4' : 'text-slate-400 hover:text-slate-900');
        });
    }
    document.querySelectorAll('.sort-btn').forEach(b => {
        b.addEventListener('click', () => {
            currentSort = b.dataset.sort;
            updateSortUI();
            fetchResults(1);
        });
    });

    // ── Form submit ───────────────────────────────────────────────────────
    document.getElementById('quick-form').addEventListener('submit', e => {
        e.preventDefault();
        fetchResults(1);
    });

    // ── Clear ─────────────────────────────────────────────────────────────
    function clearFilters() {
        document.getElementById('quick-form').reset();
        document.getElementById('price_min').value = PRICE_MIN;
        document.getElementById('price_max').value = PRICE_MAX;
        currentSort = 'newest';
        updateSortUI();
        fetchResults(1, false);
    }

    // ── Typeahead factory ─────────────────────────────────────────────────
    function makeTypeahead(inputId, listId, dataArr, opts = {}) {
        const input = document.getElementById(inputId);
        const list  = document.getElementById(listId);
        if (!input || !list) return;

        // Move list to body so it escapes all overflow/z-index parent constraints
        document.body.appendChild(list);
        list.style.position = 'fixed';
        list.style.zIndex   = '9999';
        list.style.margin   = '0';

        const dark = opts.dark ?? false;
        const itemCls = dark
            ? 'px-5 py-3 cursor-pointer text-sm font-bold text-white hover:bg-[#4ade80]/20 hover:text-[#4ade80] transition-colors'
            : 'px-5 py-3 cursor-pointer text-sm font-bold text-slate-800 hover:bg-[#4ade80]/10 hover:text-[#16a34a] transition-colors';

        function position() {
            const r = input.getBoundingClientRect();
            list.style.top   = (r.bottom + 8) + 'px';
            list.style.left  = r.left + 'px';
            list.style.width = r.width + 'px';
        }

        function render(matches) {
            list.innerHTML = '';
            if (!matches.length) { list.classList.add('hidden'); return; }
            matches.forEach(val => {
                const li = document.createElement('li');
                li.className = itemCls;
                li.textContent = val;
                li.addEventListener('mousedown', e => { e.preventDefault(); input.value = val; list.classList.add('hidden'); });
                list.appendChild(li);
            });
            position();
            list.classList.remove('hidden');
        }

        input.addEventListener('input',  () => { const q = input.value.trim().toLowerCase(); render(q ? dataArr.filter(v => v.toLowerCase().includes(q)).slice(0, 8) : []); });
        input.addEventListener('focus',  () => { if (!input.value.trim()) render(dataArr.slice(0, 8)); else render(dataArr.filter(v => v.toLowerCase().includes(input.value.trim().toLowerCase())).slice(0, 8)); });
        window.addEventListener('scroll', () => { if (!list.classList.contains('hidden')) position(); }, true);
        window.addEventListener('resize', () => { if (!list.classList.contains('hidden')) position(); });
        document.addEventListener('click', e => { if (!input.contains(e.target) && !list.contains(e.target)) list.classList.add('hidden'); });
    }

    // ── Price dual-range slider ───────────────────────────────────────────
    function initPriceSlider() {
        const track   = document.getElementById('price-slider');
        const inpMin  = document.getElementById('price_min');
        const inpMax  = document.getElementById('price_max');
        const display = document.getElementById('price-display');
        if (!track) return;
        track.innerHTML = `
            <div class="relative w-full h-1.5 rounded-full" style="background:#1e293b;">
                <div id="ps-range" class="absolute h-1.5 rounded-full" style="background:#4ade80;"></div>
                <input id="ps-lo" type="range" min="${PRICE_MIN}" max="${PRICE_MAX}" step="50000" class="absolute w-full appearance-none pointer-events-none h-1.5 bg-transparent" style="top:0;left:0;">
                <input id="ps-hi" type="range" min="${PRICE_MIN}" max="${PRICE_MAX}" step="50000" class="absolute w-full appearance-none pointer-events-none h-1.5 bg-transparent" style="top:0;left:0;">
            </div>`;
        const style = document.createElement('style');
        style.textContent = `#ps-lo,#ps-hi{pointer-events:none}#ps-lo::-webkit-slider-thumb,#ps-hi::-webkit-slider-thumb{pointer-events:all;appearance:none;width:20px;height:20px;border-radius:50%;background:#4ade80;border:2px solid #fff;cursor:pointer;box-shadow:0 2px 8px rgba(0,0,0,.4)}#ps-lo::-moz-range-thumb,#ps-hi::-moz-range-thumb{pointer-events:all;width:20px;height:20px;border-radius:50%;background:#4ade80;border:2px solid #fff;cursor:pointer;box-shadow:0 2px 8px rgba(0,0,0,.4)}`;
        document.head.appendChild(style);
        const loEl = document.getElementById('ps-lo');
        const hiEl = document.getElementById('ps-hi');
        const rng  = document.getElementById('ps-range');
        loEl.value = inpMin.value || PRICE_MIN;
        hiEl.value = inpMax.value || PRICE_MAX;
        const fmt = n => 'NRs ' + Number(n).toLocaleString();
        function sync() {
            let lo = parseInt(loEl.value), hi = parseInt(hiEl.value);
            if (lo > hi) { [lo, hi] = [hi, lo]; loEl.value = lo; hiEl.value = hi; }
            const pct = v => ((v - PRICE_MIN) / (PRICE_MAX - PRICE_MIN)) * 100;
            rng.style.left = pct(lo) + '%'; rng.style.width = (pct(hi) - pct(lo)) + '%';
            inpMin.value = lo; inpMax.value = hi;
            if (display) display.textContent = fmt(lo) + ' – ' + fmt(hi);
        }
        loEl.addEventListener('input', sync);
        hiEl.addEventListener('input', sync);
        sync();
    }

    // ── Boot ──────────────────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
        // ── Drivetrain custom dropdown ────────────────────────────────────
        (function() {
            const btn     = document.getElementById('dt-btn');
            const list    = document.getElementById('dt-list');
            const valInp  = document.getElementById('dt-value');
            const label   = document.getElementById('dt-label');
            const chevron = document.getElementById('dt-chevron');
            if (!btn || !list) return;

            // Move to body to escape overflow clipping
            document.body.appendChild(list);
            list.style.position = 'fixed';
            list.style.zIndex   = '9999';
            list.style.margin   = '0';

            function positionList() {
                const r = btn.getBoundingClientRect();
                list.style.top   = (r.bottom + 8) + 'px';
                list.style.left  = r.left + 'px';
                list.style.width = r.width + 'px';
            }

            function openList() {
                positionList();
                list.classList.remove('hidden');
                chevron.style.transform = 'rotate(180deg)';
            }

            function closeList() {
                list.classList.add('hidden');
                chevron.style.transform = '';
            }

            btn.addEventListener('click', e => {
                e.stopPropagation();
                list.classList.contains('hidden') ? openList() : closeList();
            });

            list.querySelectorAll('.dt-opt').forEach(li => {
                li.addEventListener('mousedown', e => {
                    e.preventDefault();
                    valInp.value  = li.dataset.value;
                    label.textContent = li.textContent.trim();
                    closeList();
                });
            });

            document.addEventListener('click', e => {
                if (!btn.contains(e.target) && !list.contains(e.target)) closeList();
            });

            window.addEventListener('scroll', () => { if (!list.classList.contains('hidden')) positionList(); }, true);
            window.addEventListener('resize', () => { if (!list.classList.contains('hidden')) positionList(); });
        })();

        makeTypeahead('search-input',   'search-suggestions',   [...ALL_BRANDS, ...ALL_MODELS]);
        makeTypeahead('location-input', 'location-suggestions', ALL_LOCATIONS);

        // ── Year From / To custom dropdowns ──────────────────────────────
        function makeYearDropdown(btnId, listId, valueId, labelId, chevronId, optClass) {
            const btn     = document.getElementById(btnId);
            const list    = document.getElementById(listId);
            const valInp  = document.getElementById(valueId);
            const label   = document.getElementById(labelId);
            const chevron = document.getElementById(chevronId);
            if (!btn || !list) return;

            document.body.appendChild(list);
            list.style.position = 'fixed';
            list.style.zIndex   = '9999';
            list.style.margin   = '0';

            function pos() {
                const r = btn.getBoundingClientRect();
                list.style.top   = (r.bottom + 8) + 'px';
                list.style.left  = r.left + 'px';
                list.style.width = r.width + 'px';
            }
            function open()  { pos(); list.classList.remove('hidden'); chevron.style.transform = 'rotate(180deg)'; }
            function close() { list.classList.add('hidden'); chevron.style.transform = ''; }

            btn.addEventListener('click', e => { e.stopPropagation(); list.classList.contains('hidden') ? open() : close(); });

            list.querySelectorAll('.' + optClass).forEach(li => {
                li.addEventListener('mousedown', e => {
                    e.preventDefault();
                    valInp.value      = li.dataset.value;
                    label.textContent = li.textContent.trim();
                    close();
                });
            });

            document.addEventListener('click', e => { if (!btn.contains(e.target) && !list.contains(e.target)) close(); });
            window.addEventListener('scroll', () => { if (!list.classList.contains('hidden')) pos(); }, true);
            window.addEventListener('resize', () => { if (!list.classList.contains('hidden')) pos(); });
        }

        makeYearDropdown('yr-from-btn','yr-from-list','yr-from-value','yr-from-label','yr-from-chevron','yr-from-opt');
        makeYearDropdown('yr-to-btn',  'yr-to-list',  'yr-to-value',  'yr-to-label',  'yr-to-chevron',  'yr-to-opt');
        makeTypeahead('brand-input',    'brand-suggestions',    ALL_BRANDS, { dark: true });
        makeTypeahead('model-input',    'model-suggestions',    ALL_MODELS, { dark: true });
        initPriceSlider();
        updateSortUI();

        // Pre-fill form from URL params (browser back/forward support)
        const sp = new URLSearchParams(window.location.search);
        if (sp.get('sort')) { currentSort = sp.get('sort'); updateSortUI(); }
        ['search','drivetrain','location','brand','model_name','year_from','year_to','price_min','price_max'].forEach(k => {
            const el = document.getElementById('quick-form').elements[k];
            if (el && sp.get(k)) el.value = sp.get(k);
        });

        // Initial load — no scroll if page just opened fresh
        fetchResults(parseInt(sp.get('page') || 1), window.location.search.length > 1);
    });
    </script>

@endsection