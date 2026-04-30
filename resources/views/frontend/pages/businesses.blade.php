@extends('frontend.app')

<title>Businesses | BijuliCar</title>

@section('content')

    {{-- ── Hero Section ──────────────────────────────────────────────────── --}}
    <section
        class="relative bg-[#050a15] pt-[110px] pb-5 md:pt-[120px] md:pb-20 lg:pt-[135px] overflow-hidden border-b border-white/5 text-white">
        {{-- Layered background --}}
        <div class="absolute inset-0 z-0">
            {{-- Background Image with Effects --}}
            <img src="https://images.unsplash.com/photo-1563720223185-11003d516935?auto=format&fit=crop&q=80&w=2071"
                class="w-full h-full object-cover opacity-40 mix-blend-luminosity scale-110 blur-[2px]"
                alt="Business Directory Background">

            {{-- Gradient Overlays --}}
            <div class="absolute inset-0 bg-gradient-to-r from-[#050a15] via-[#050a15]/80 to-transparent"></div>
            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_left,_rgba(74,222,128,0.1)_0%,_transparent_40%)]">
            </div>

            {{-- Subtle Grid Overlay --}}
            <div class="absolute inset-0 opacity-[0.035]"
                style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 28px 28px;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                {{-- Left Side: Content --}}
                <div class="space-y-6">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <span class="w-6 h-[2px] bg-[#4ade80]"></span>
                            <span class="text-[10px] uppercase tracking-[0.3em] text-[#4ade80] font-bold">Verified
                                Network</span>
                        </div>

                        <h1
                            class="text-4xl md:text-6xl lg:text-7xl font-black tracking-tighter text-white uppercase italic leading-[0.85] mb-6">
                            <span class="block">Business</span>
                            <span class="text-slate-500">Directory</span>
                        </h1>

                        <p class="text-slate-400 text-base md:text-lg max-w-sm font-medium leading-relaxed">
                            Discover Nepal's verified <span class="text-white">EV and hybrid</span> dealerships.
                            Browse live inventory and connect directly.
                        </p>
                    </div>

                    {{-- Stats strip --}}
                    <div class="flex flex-wrap gap-3 pt-4">
                        <div
                            class="flex items-center gap-3 bg-white/5 border border-white/10 backdrop-blur-md rounded-2xl px-5 py-3 hover:bg-white/10 transition-colors">
                            <div
                                class="w-8 h-8 bg-[#4ade80]/10 border border-[#4ade80]/20 rounded-xl flex items-center justify-center">
                                <svg class="w-4 h-4 text-[#4ade80]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xl font-black text-white leading-none">{{ $totalBusinesses }}</p>
                                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-wider mt-0.5">Businesses
                                </p>
                            </div>
                        </div>

                        <div
                            class="flex items-center gap-3 bg-white/5 border border-white/10 backdrop-blur-md rounded-2xl px-5 py-3 hover:bg-white/10 transition-colors">
                            <div
                                class="w-8 h-8 bg-[#4ade80]/10 border border-[#4ade80]/20 rounded-xl flex items-center justify-center">
                                <svg class="w-4 h-4 text-[#4ade80]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xl font-black text-white leading-none">{{ $totalListings }}</p>
                                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-wider mt-0.5">Listings</p>
                            </div>
                        </div>

                        <div
                            class="flex items-center gap-3 bg-blue-500/10 border border-blue-500/20 backdrop-blur-md rounded-2xl px-5 py-3">
                            <div
                                class="w-8 h-8 bg-blue-400/10 border border-blue-400/20 rounded-xl flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xl font-black text-blue-300 leading-none">{{ $totalRentable }}</p>
                                <p class="text-[9px] font-bold text-blue-500/70 uppercase tracking-wider mt-0.5">Rentable
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Side: Decorative or Featured Card (Optional) --}}
                <div class="hidden lg:block">
                    <div class="relative group max-w-sm ml-auto">
                        <div
                            class="absolute -inset-1 bg-gradient-to-r from-[#4ade80]/20 to-blue-500/20 rounded-3xl blur opacity-75">
                        </div>
                        <div class="relative bg-white/5 border border-white/10 backdrop-blur-2xl rounded-3xl p-8">
                            <h3 class="text-white font-black uppercase italic text-sm tracking-wider mb-2">Nepal Automotive
                                Hub</h3>
                            <p class="text-slate-400 text-xs leading-relaxed">Access the most comprehensive database of
                                green energy mobility providers in the region.</p>
                            <div
                                class="mt-6 flex items-center gap-2 text-[#4ade80] text-[10px] font-bold uppercase tracking-widest">
                                <span>Explore Maps</span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Scroll indicator --}}
        <div
            class="absolute bottom-1 left-1/2 -translate-x-1/2 hidden md:flex flex-col items-center gap-2 animate-bounce opacity-40">
            <span class="text-[9px] font-black uppercase tracking-[0.4em] text-white/60">Scroll</span>
            <div class="w-5 h-8 border-2 border-white/20 rounded-full flex justify-center p-1">
                <div class="w-1 h-2 bg-[#4ade80] rounded-full"></div>
            </div>
        </div>
    </section>

    {{-- ── Filter Bar ────────────────────────────────────────────────────── --}}
    <section class="sticky top-[72px] z-40 bg-white/90 backdrop-blur-xl border-b border-slate-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-3">
            <form method="GET" action="{{ route('businesses.index') }}" class="flex flex-wrap items-center gap-3">

                <div class="relative flex-1 min-w-[160px]" id="city-autocomplete-wrap">
                    <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-300 z-10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        </svg>
                    </div>
                    <input type="text" name="location" id="city-input" value="{{ request('location') }}"
                        placeholder="Filter by city..." autocomplete="off"
                        class="w-full bg-slate-100 border-none rounded-xl py-2.5 pl-10 pr-4 text-sm font-bold placeholder:text-slate-400 text-slate-900 focus:ring-2 focus:ring-[#4ade80]/30 outline-none transition-all">
                    <ul id="city-suggestions"
                        class="hidden absolute left-0 right-0 top-full mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 z-50 overflow-hidden max-h-56 overflow-y-auto">
                    </ul>
                </div>

                <div class="relative inline-block" x-data="{
                    open: false,
                    selected: '{{ request('specialization') ?? 'all' }}',
                    label: '{{ request('specialization') ? (request('specialization') === 'ev' ? 'EV Only' : (request('specialization') === 'hybrid' ? 'Hybrid' : 'Petrol')) : 'All Types' }}'
                }">
                    <input type="hidden" name="specialization" :value="selected">
                    <button type="button" @click="open = !open" @click.away="open = false"
                        class="flex items-center justify-between bg-slate-100 border-none rounded-xl py-2.5 pl-4 pr-10 text-sm font-black text-slate-900 cursor-pointer uppercase tracking-tight focus:outline-none"
                        :class="open ? 'ring-2 ring-[#4ade80]/30 bg-white' : 'hover:bg-slate-200/50'">
                        <span x-text="label">All Types</span>
                        <div class="absolute right-3 flex items-center pointer-events-none text-slate-400 transition-transform duration-200"
                            :class="open ? 'rotate-180 text-[#4ade80]' : ''">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="3">
                                <path d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        class="absolute z-50 min-w-[160px] mt-2 bg-white border border-slate-100 rounded-xl shadow-xl overflow-hidden left-0"
                        style="display:none;">
                        <div class="py-1">
                            <button type="button" @click="selected='all'; label='All Types'; open=false"
                                class="w-full text-left px-5 py-2.5 text-xs font-black uppercase tracking-tight transition-colors"
                                :class="selected === 'all' ? 'text-[#4ade80] bg-slate-50' :
                                    'text-slate-600 hover:bg-slate-50'">All
                                Types</button>
                            <button type="button" @click="selected='ev'; label='EV Only'; open=false"
                                class="w-full text-left px-5 py-2.5 text-xs font-black uppercase tracking-tight transition-colors"
                                :class="selected === 'ev' ? 'text-[#4ade80] bg-slate-50' :
                                    'text-slate-600 hover:bg-slate-50'">EV
                                Only</button>
                            <button type="button" @click="selected='hybrid'; label='Hybrid'; open=false"
                                class="w-full text-left px-5 py-2.5 text-xs font-black uppercase tracking-tight transition-colors"
                                :class="selected === 'hybrid' ? 'text-[#4ade80] bg-slate-50' :
                                    'text-slate-600 hover:bg-slate-50'">Hybrid</button>
                            <button type="button" @click="selected='petrol'; label='Petrol'; open=false"
                                class="w-full text-left px-5 py-2.5 text-xs font-black uppercase tracking-tight transition-colors"
                                :class="selected === 'petrol' ? 'text-[#4ade80] bg-slate-50' :
                                    'text-slate-600 hover:bg-slate-50'">Petrol</button>
                        </div>
                    </div>
                </div>

                <div class="relative inline-block" x-data="{
                    open: false,
                    selected: '{{ request('sort', 'listings') }}',
                    label: '{{ request('sort') === 'rating' ? 'Top Rated' : (request('sort') === 'reviews' ? 'Most Reviews' : 'Most Listings') }}'
                }">
                    <input type="hidden" name="sort" :value="selected">
                    <button type="button" @click="open = !open" @click.away="open = false"
                        class="flex items-center justify-between min-w-[160px] bg-slate-100 border-none rounded-xl py-2.5 pl-4 pr-10 text-sm font-black text-slate-900 cursor-pointer uppercase tracking-tight focus:outline-none"
                        :class="open ? 'ring-2 ring-[#4ade80]/30 bg-white' : 'hover:bg-slate-200/50'">
                        <span x-text="label">Most Listings</span>
                        <div class="absolute right-3 flex items-center pointer-events-none text-slate-400 transition-transform duration-200"
                            :class="open ? 'rotate-180 text-[#4ade80]' : ''">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="3">
                                <path d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        class="absolute z-50 w-full mt-2 bg-white border border-slate-100 rounded-xl shadow-xl overflow-hidden left-0"
                        style="display:none;">
                        <div class="py-1">
                            <button type="button" @click="selected='listings'; label='Most Listings'; open=false"
                                class="w-full text-left px-5 py-2.5 text-xs font-black uppercase tracking-tight transition-colors"
                                :class="selected === 'listings' ? 'text-[#4ade80] bg-slate-50' :
                                    'text-slate-600 hover:bg-slate-50'">Most
                                Listings</button>
                            <button type="button" @click="selected='rating'; label='Top Rated'; open=false"
                                class="w-full text-left px-5 py-2.5 text-xs font-black uppercase tracking-tight transition-colors"
                                :class="selected === 'rating' ? 'text-[#4ade80] bg-slate-50' :
                                    'text-slate-600 hover:bg-slate-50'">Top
                                Rated</button>
                            <button type="button" @click="selected='reviews'; label='Most Reviews'; open=false"
                                class="w-full text-left px-5 py-2.5 text-xs font-black uppercase tracking-tight transition-colors"
                                :class="selected === 'reviews' ? 'text-[#4ade80] bg-slate-50' :
                                    'text-slate-600 hover:bg-slate-50'">Most
                                Reviews</button>
                        </div>
                    </div>
                </div>

                <button type="submit"
                    class="px-6 py-2.5 bg-slate-900 text-white rounded-xl text-sm font-black uppercase tracking-wider hover:bg-[#4ade80] hover:text-black transition-all duration-300 active:scale-95">Apply</button>

                @if (request()->hasAny(['location', 'specialization', 'sort']))
                    <a href="{{ route('businesses.index') }}"
                        class="px-4 py-2.5 text-sm font-bold text-slate-400 hover:text-slate-700 transition-colors">Clear</a>
                @endif
            </form>
        </div>
    </section>

    {{-- ── Banner Ads ────────────────────────────────────────────────────── --}}
    @if (isset($businessBannerAds) && $businessBannerAds->isNotEmpty())
        <section class="bg-slate-50 pt-8 pb-0">
            <div class="max-w-7xl mx-auto px-6">
                <x-ads.horizontal-banner :ads="$businessBannerAds" />
            </div>
        </section>
    @endif

    {{-- ── Business Grid ─────────────────────────────────────────────────── --}}
    <section class="bg-slate-50 py-14">
        <div class="max-w-7xl mx-auto px-6">

            @if ($businesses->isEmpty())
                <div class="text-center py-24">
                    <div class="w-20 h-20 mx-auto mb-6 bg-slate-100 rounded-3xl flex items-center justify-center">
                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 mb-2">No businesses found</h3>
                    <p class="text-slate-400 text-sm">Try adjusting your filters or check back later.</p>
                    <a href="{{ route('businesses.index') }}"
                        class="inline-block mt-6 px-6 py-3 bg-slate-900 text-white rounded-xl text-sm font-black hover:bg-[#4ade80] hover:text-black transition-all">View
                        All Businesses</a>
                </div>
            @else
                <div class="mb-8 flex items-center justify-between">
                    <p class="text-sm font-bold text-slate-400">
                        Showing <span class="text-slate-900">{{ $businesses->count() }}</span> verified
                        {{ Str::plural('business', $businesses->count()) }}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($businesses as $biz)
                        <a href="{{ $biz['profile_url'] }}"
                            class="group bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col">

                            {{-- Card Header --}}
                            <div class="p-6 pb-4 flex items-start justify-between">
                                <div class="flex items-center gap-4">
                                    @if ($biz['profile_photo'])
                                        <img src="{{ $biz['profile_photo'] }}" alt="{{ $biz['name'] }}"
                                            class="w-14 h-14 rounded-2xl object-cover shrink-0 group-hover:ring-2 group-hover:ring-[#16a34a] transition-all duration-300">
                                    @else
                                        <div
                                            class="w-14 h-14 rounded-2xl bg-slate-900 flex items-center justify-center text-white text-lg font-black uppercase shrink-0 group-hover:bg-[#16a34a] transition-colors duration-300">
                                            {{ $biz['initials'] }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h3 class="text-[15px] font-black text-slate-900 leading-tight">
                                                {{ $biz['name'] }}</h3>
                                            <span
                                                class="flex items-center gap-1 text-[10px] font-black text-[#16a34a] bg-green-50 px-2 py-0.5 rounded-full border border-green-100">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Verified
                                            </span>
                                        </div>
                                        <span class="text-[11px] font-bold text-slate-400 flex items-center gap-1 mt-1.5">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            </svg>
                                            {{ $biz['location'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Tags row --}}
                            <div class="px-6 pb-4 flex items-center gap-2 flex-wrap">
                                @php
                                    $tagColors = [
                                        'EV Dealer' => 'bg-green-50 text-green-700 border-green-100',
                                        'Hybrid' => 'bg-blue-50 text-blue-700 border-blue-100',
                                        'Multi-Brand' => 'bg-purple-50 text-purple-700 border-purple-100',
                                        'Traditional' => 'bg-slate-100 text-slate-600 border-slate-200',
                                    ];
                                    $tagColor =
                                        $tagColors[$biz['specialization']] ??
                                        'bg-slate-100 text-slate-600 border-slate-200';
                                @endphp
                                <span
                                    class="text-[11px] font-black px-3 py-1 rounded-full border {{ $tagColor }} uppercase tracking-wide">{{ $biz['specialization'] }}</span>

                                @if ($biz['rentable_listings'] > 0)
                                    <span
                                        class="text-[11px] font-black px-3 py-1 rounded-full border bg-blue-50 text-blue-600 border-blue-100 uppercase tracking-wide flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Rentals Available
                                    </span>
                                @endif
                            </div>

                            <div class="mx-6 border-t border-slate-100"></div>

                            {{-- Stats --}}
                            <div class="px-6 py-4 grid grid-cols-3 gap-3">
                                <div>
                                    <p class="text-xl font-black text-slate-900">{{ $biz['active_listings'] }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mt-0.5">Listings
                                    </p>
                                </div>
                                @if ($biz['rentable_listings'] > 0)
                                    <div>
                                        <p class="text-xl font-black text-blue-600">{{ $biz['rentable_listings'] }}</p>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mt-0.5">
                                            Rentable</p>
                                    </div>
                                @else
                                    <div></div>
                                @endif
                                <div>
                                    <div class="flex items-center gap-1">
                                        <p class="text-xl font-black text-slate-900">
                                            {{ $biz['avg_rating'] > 0 ? number_format($biz['avg_rating'], 1) : '—' }}</p>
                                        @if ($biz['avg_rating'] > 0)
                                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mt-0.5">
                                        {{ $biz['review_count'] }} {{ Str::plural('Review', $biz['review_count']) }}</p>
                                </div>
                            </div>

                            {{-- CTA --}}
                            <div class="px-6 pb-6 mt-auto">
                                <span
                                    class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-slate-900 text-white rounded-xl text-[13px] font-black uppercase tracking-wider group-hover:bg-[#4ade80] group-hover:text-black transition-all duration-300">
                                    View Profile
                                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ── Business News ─────────────────────────────────────────────────── --}}
    @if ($latestNews->isNotEmpty())
        <section class="bg-white py-16 border-t border-slate-100">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="w-6 h-[2px] bg-[#a855f7]"></span>
                            <span class="text-[10px] uppercase tracking-[0.3em] text-[#a855f7] font-bold">From the
                                Network</span>
                        </div>
                        <h2 class="text-3xl font-black text-slate-900 tracking-tight uppercase italic">Business <span
                                class="text-slate-400">News</span></h2>
                        <p class="text-slate-400 text-sm font-medium mt-1">Latest updates published directly by verified
                            businesses.</p>
                    </div>
                    <a href="{{ route('news') }}"
                        class="hidden sm:flex items-center gap-2 text-sm font-black text-slate-500 hover:text-slate-900 transition-colors uppercase tracking-wider">
                        All News
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($latestNews as $article)
                        <a href="{{ route('business.news.show', $article->slug) }}"
                            class="group relative bg-slate-900 rounded-3xl overflow-hidden flex flex-col min-h-[360px] border border-slate-800 hover:border-[#a855f7]/40 hover:shadow-xl transition-all duration-300">
                            @if ($article->hero_image)
                                <div class="absolute inset-0">
                                    <img src="{{ asset('storage/' . $article->hero_image) }}"
                                        alt="{{ $article->title }}"
                                        class="w-full h-full object-cover opacity-40 group-hover:opacity-50 group-hover:scale-105 transition-all duration-500">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent">
                                    </div>
                                </div>
                            @else
                                <div
                                    class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,_rgba(168,85,247,0.15)_0%,_transparent_60%)]">
                                </div>
                            @endif
                            <div class="relative mt-auto p-7">
                                <span
                                    onclick="event.preventDefault();event.stopPropagation();window.location='{{ route('businesses.show', $article->business->id) }}'"
                                    class="inline-flex items-center gap-2 mb-4 text-[10px] font-black uppercase tracking-widest text-[#a855f7] bg-purple-500/10 border border-purple-500/20 px-3 py-1 rounded-full hover:bg-purple-500/20 transition-colors cursor-pointer">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full bg-[#a855f7]"></span>{{ $article->business_name }}
                                </span>
                                <h3
                                    class="text-xl font-black text-white uppercase italic tracking-tight leading-tight mb-3 group-hover:text-purple-200 transition-colors">
                                    {{ $article->title }}</h3>
                                <p class="text-slate-400 text-sm leading-relaxed line-clamp-2 mb-4">
                                    {{ strip_tags($article->lead_paragraph) }}</p>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3 text-[11px] font-bold text-slate-500">
                                        @if ($article->newscategory)
                                            <span
                                                class="text-purple-400 uppercase tracking-wider">{{ $article->newscategory->name }}</span><span>·</span>
                                        @endif
                                        <span>{{ $article->created_at->format('d M Y') }}</span>
                                    </div>
                                    <span
                                        class="text-[11px] font-black text-[#a855f7] uppercase tracking-wider flex items-center gap-1 group-hover:gap-2 transition-all">Read
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M9 5l7 7-7 7" />
                                        </svg></span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ── Register CTA ──────────────────────────────────────────────────── --}}
    @guest
        <section class="bg-[#0a0f1e] py-16">
            <div class="max-w-3xl mx-auto px-6 text-center">
                <div
                    class="w-16 h-16 mx-auto mb-6 bg-[#4ade80]/10 border border-[#4ade80]/20 rounded-2xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-[#4ade80]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
                    </svg>
                </div>
                <h2 class="text-3xl font-black text-white tracking-tight mb-3">List Your Business</h2>
                <p class="text-slate-400 text-sm leading-relaxed mb-8 max-w-md mx-auto">Are you an EV or hybrid dealership?
                    Join BijuliCar's verified network and reach thousands of buyers and renters.</p>
                <div class="flex items-center justify-center gap-4 flex-wrap">
                    <a href="{{ route('register') }}"
                        class="px-8 py-3.5 bg-[#4ade80] text-black rounded-xl font-black text-sm uppercase tracking-wider hover:bg-[#22c55e] transition-all active:scale-95 shadow-lg shadow-green-500/20">Register
                        Now</a>
                    <a href="{{ route('login') }}"
                        class="px-8 py-3.5 bg-white/5 border border-white/10 text-white rounded-xl font-black text-sm uppercase tracking-wider hover:bg-white/10 transition-all">Sign
                        In</a>
                </div>
            </div>
        </section>
    @endguest

    <script>
        const ALL_CITIES = @json($cities->values());

        function makeTypeahead(inputId, listId, dataArr) {
            const input = document.getElementById(inputId);
            const list = document.getElementById(listId);
            if (!input || !list) return;
            const itemCls =
                'px-5 py-3 cursor-pointer text-sm font-bold text-slate-800 hover:bg-[#4ade80]/10 hover:text-[#16a34a] transition-colors flex items-center gap-2';

            function render(matches) {
                list.innerHTML = '';
                if (!matches.length) {
                    list.classList.add('hidden');
                    return;
                }
                matches.forEach(val => {
                    const li = document.createElement('li');
                    li.className = itemCls;
                    li.innerHTML =
                        `<svg class="w-3.5 h-3.5 text-slate-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg><span>${val}</span>`;
                    li.addEventListener('mousedown', e => {
                        e.preventDefault();
                        input.value = val;
                        list.classList.add('hidden');
                        input.closest('form').submit();
                    });
                    list.appendChild(li);
                });
                list.classList.remove('hidden');
            }
            input.addEventListener('input', () => {
                const q = input.value.trim().toLowerCase();
                render(q ? dataArr.filter(v => v.toLowerCase().startsWith(q)).slice(0, 8) : []);
            });
            input.addEventListener('focus', () => {
                if (!input.value.trim()) render(dataArr.slice(0, 8));
            });
            document.addEventListener('click', e => {
                if (!input.closest('#city-autocomplete-wrap').contains(e.target)) list.classList.add('hidden');
            });
        }
        document.addEventListener('DOMContentLoaded', () => {
            makeTypeahead('city-input', 'city-suggestions', ALL_CITIES);
        });
    </script>
@endsection
