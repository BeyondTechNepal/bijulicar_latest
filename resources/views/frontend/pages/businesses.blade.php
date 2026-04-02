@extends('frontend.app')

<title>Businesses | BijuliCar</title>

@section('content')

{{-- ── Hero Section ──────────────────────────────────────────────────── --}}
<section class="relative pt-32 pb-20 lg:pt-40 lg:pb-28 overflow-hidden bg-[#0a0f1e] text-white">

    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,_rgba(74,222,128,0.08)_0%,_transparent_60%)]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_80%_50%,_rgba(15,23,42,0.4)_0%,_#0a0f1e_70%)]"></div>
        {{-- Dot grid --}}
        <div class="absolute inset-0 opacity-[0.04]"
            style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 28px 28px;"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="flex items-center gap-3 mb-5">
            <span class="w-8 h-[2px] bg-[#4ade80]"></span>
            <span class="text-[10px] uppercase tracking-[0.4em] text-[#4ade80] font-bold">Verified Network</span>
        </div>
        <h1 class="text-5xl md:text-7xl lg:text-8xl font-black tracking-tighter text-white uppercase italic leading-[0.85] mb-6">
            <span class="block">Business</span>
            <span class="text-slate-500">Directory</span>
        </h1>
        <p class="text-slate-400 text-sm lg:text-base font-medium max-w-md leading-relaxed">
            Discover Nepal's verified EV and hybrid dealerships. Browse their live
            inventory, read buyer reviews, and connect directly.
        </p>

        {{-- Stats Strip --}}
        <div class="mt-10 flex flex-wrap gap-4 lg:gap-6">
            <div class="flex items-center gap-3 bg-white/5 border border-white/10 backdrop-blur-md rounded-2xl px-5 py-3.5">
                <div class="w-8 h-8 bg-[#4ade80]/10 rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#4ade80]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-black text-white leading-none">{{ $totalBusinesses }}</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Businesses</p>
                </div>
            </div>
            <div class="flex items-center gap-3 bg-white/5 border border-white/10 backdrop-blur-md rounded-2xl px-5 py-3.5">
                <div class="w-8 h-8 bg-[#4ade80]/10 rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#4ade80]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-black text-white leading-none">{{ $totalListings }}</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Active Listings</p>
                </div>
            </div>
            <div class="flex items-center gap-3 bg-white/5 border border-white/10 backdrop-blur-md rounded-2xl px-5 py-3.5">
                <div class="w-8 h-8 bg-[#4ade80]/10 rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#4ade80]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-black text-white leading-none">{{ $totalCities }}</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Cities Covered</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── Filter Bar ─────────────────────────────────────────────────────── --}}
<section class="sticky top-[72px] z-40 bg-white/90 backdrop-blur-xl border-b border-slate-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 py-3">
        <form method="GET" action="{{ route('businesses.index') }}"
            class="flex flex-wrap items-center gap-3">

            {{-- Location with autocomplete --}}
            <div class="relative flex-1 min-w-[160px]" id="city-autocomplete-wrap">
                <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-300 z-10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    </svg>
                </div>
                <input type="text" name="location" id="city-input" value="{{ request('location') }}"
                    placeholder="Filter by city..."
                    autocomplete="off"
                    class="w-full bg-slate-100 border-none rounded-xl py-2.5 pl-10 pr-4 text-sm font-bold placeholder:text-slate-400 text-slate-900 focus:ring-2 focus:ring-[#4ade80]/30 outline-none transition-all">
                <ul id="city-suggestions"
                    class="hidden absolute left-0 right-0 top-full mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 z-50 overflow-hidden max-h-56 overflow-y-auto">
                </ul>
            </div>

            {{-- Specialization --}}
            <div class="relative">
                <select name="specialization"
                    class="bg-slate-100 border-none rounded-xl py-2.5 pl-4 pr-10 text-sm font-black text-slate-900 appearance-none cursor-pointer focus:ring-2 focus:ring-[#4ade80]/30 outline-none uppercase tracking-tight">
                    <option value="all">All Types</option>
                    <option value="ev" {{ request('specialization') === 'ev' ? 'selected' : '' }}>EV Only</option>
                    <option value="hybrid" {{ request('specialization') === 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                    <option value="petrol" {{ request('specialization') === 'petrol' ? 'selected' : '' }}>Petrol</option>
                </select>
                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                        <path d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            {{-- Sort --}}
            <div class="relative">
                <select name="sort"
                    class="bg-slate-100 border-none rounded-xl py-2.5 pl-4 pr-10 text-sm font-black text-slate-900 appearance-none cursor-pointer focus:ring-2 focus:ring-[#4ade80]/30 outline-none uppercase tracking-tight">
                    <option value="listings" {{ request('sort', 'listings') === 'listings' ? 'selected' : '' }}>Most Listings</option>
                    <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Top Rated</option>
                    <option value="reviews" {{ request('sort') === 'reviews' ? 'selected' : '' }}>Most Reviews</option>
                </select>
                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                        <path d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            <button type="submit"
                class="px-6 py-2.5 bg-slate-900 text-white rounded-xl text-sm font-black uppercase tracking-wider hover:bg-[#4ade80] hover:text-black transition-all duration-300 active:scale-95">
                Apply
            </button>

            @if(request()->hasAny(['location','specialization','sort']))
                <a href="{{ route('businesses.index') }}"
                    class="px-4 py-2.5 text-sm font-bold text-slate-400 hover:text-slate-700 transition-colors">
                    Clear
                </a>
            @endif
        </form>
    </div>
</section>

{{-- ── Main Content ────────────────────────────────────────────────────── --}}
<section class="bg-slate-50 py-14">
    <div class="max-w-7xl mx-auto px-6">

        @if($businesses->isEmpty())
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
                    class="inline-block mt-6 px-6 py-3 bg-slate-900 text-white rounded-xl text-sm font-black hover:bg-[#4ade80] hover:text-black transition-all">
                    View All Businesses
                </a>
            </div>
        @else
            <div class="mb-8 flex items-center justify-between">
                <p class="text-sm font-bold text-slate-400">
                    Showing <span class="text-slate-900">{{ $businesses->count() }}</span> verified
                    {{ Str::plural('business', $businesses->count()) }}
                </p>
            </div>

            {{-- Business Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($businesses as $biz)
                    <a href="{{ $biz['profile_url'] }}"
                        class="group bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col">

                        {{-- Card Header --}}
                        <div class="p-6 pb-4 flex items-start justify-between">
                            <div class="flex items-center gap-4">
                                {{-- Avatar --}}
                                <div class="w-14 h-14 rounded-2xl bg-slate-900 flex items-center justify-center text-white text-lg font-black uppercase shrink-0 group-hover:bg-[#16a34a] transition-colors duration-300">
                                    {{ $biz['initials'] }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-[15px] font-black text-slate-900 leading-tight">{{ $biz['name'] }}</h3>
                                        {{-- Verified badge --}}
                                        <span class="flex items-center gap-1 text-[10px] font-black text-[#16a34a] bg-green-50 px-2 py-0.5 rounded-full border border-green-100">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Verified
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2 mt-1.5">
                                        <span class="text-[11px] font-bold text-slate-400 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            </svg>
                                            {{ $biz['location'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Specialization Tag --}}
                        <div class="px-6 pb-4">
                            @php
                                $tagColors = [
                                    'EV Dealer'    => 'bg-green-50 text-green-700 border-green-100',
                                    'Hybrid'       => 'bg-blue-50 text-blue-700 border-blue-100',
                                    'Multi-Brand'  => 'bg-purple-50 text-purple-700 border-purple-100',
                                    'Traditional'  => 'bg-slate-100 text-slate-600 border-slate-200',
                                ];
                                $tagColor = $tagColors[$biz['specialization']] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                            @endphp
                            <span class="text-[11px] font-black px-3 py-1 rounded-full border {{ $tagColor }} uppercase tracking-wide">
                                {{ $biz['specialization'] }}
                            </span>
                        </div>

                        {{-- Divider --}}
                        <div class="mx-6 border-t border-slate-100"></div>

                        {{-- Stats --}}
                        <div class="px-6 py-4 grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xl font-black text-slate-900">{{ $biz['active_listings'] }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mt-0.5">Active Listings</p>
                            </div>
                            <div>
                                <div class="flex items-center gap-1.5">
                                    <p class="text-xl font-black text-slate-900">
                                        {{ $biz['avg_rating'] > 0 ? number_format($biz['avg_rating'], 1) : '—' }}
                                    </p>
                                    @if($biz['avg_rating'] > 0)
                                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endif
                                </div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mt-0.5">
                                    {{ $biz['review_count'] }} {{ Str::plural('Review', $biz['review_count']) }}
                                </p>
                            </div>
                        </div>

                        {{-- CTA --}}
                        <div class="px-6 pb-6 mt-auto">
                            <span class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-slate-900 text-white rounded-xl text-[13px] font-black uppercase tracking-wider group-hover:bg-[#4ade80] group-hover:text-black transition-all duration-300">
                                View Listings
                                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>


{{-- ── Business News Section ────────────────────────────────────────────── --}}
@if($latestNews->isNotEmpty())
<section class="bg-white py-16 border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Section header --}}
        <div class="flex items-center justify-between mb-10">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="w-6 h-[2px] bg-[#a855f7]"></span>
                    <span class="text-[10px] uppercase tracking-[0.3em] text-[#a855f7] font-bold">From the Network</span>
                </div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight uppercase italic">
                    Business <span class="text-slate-400">News</span>
                </h2>
                <p class="text-slate-400 text-sm font-medium mt-1">
                    Latest updates published directly by verified businesses.
                </p>
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
            @foreach($latestNews as $article)
                <a href="{{ route('business.news.show', $article->slug) }}"
                    class="group relative bg-slate-900 rounded-3xl overflow-hidden flex flex-col min-h-[360px] border border-slate-800 hover:border-[#a855f7]/40 hover:shadow-xl hover:shadow-purple-900/10 transition-all duration-300">

                    @if($article->hero_image)
                        <div class="absolute inset-0">
                            <img src="{{ asset('storage/' . $article->hero_image) }}"
                                alt="{{ $article->title }}"
                                class="w-full h-full object-cover opacity-40 group-hover:opacity-50 group-hover:scale-105 transition-all duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent"></div>
                        </div>
                    @else
                        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,_rgba(168,85,247,0.15)_0%,_transparent_60%)]"></div>
                    @endif

                    <div class="relative mt-auto p-7">

                        <span
                            onclick="event.preventDefault(); event.stopPropagation(); window.location='{{ route('businesses.show', $article->business->id) }}'"
                            class="inline-flex items-center gap-2 mb-4 text-[10px] font-black uppercase tracking-widest text-[#a855f7] bg-purple-500/10 border border-purple-500/20 px-3 py-1 rounded-full hover:bg-purple-500/20 transition-colors cursor-pointer">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#a855f7]"></span>
                            {{ $article->business_name }}
                        </span>

                        <h3 class="text-xl font-black text-white uppercase italic tracking-tight leading-tight mb-3 group-hover:text-purple-200 transition-colors">
                            {{ $article->title }}
                        </h3>

                        <p class="text-slate-400 text-sm leading-relaxed line-clamp-2 mb-4">
                            {{ strip_tags($article->lead_paragraph) }}
                        </p>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3 text-[11px] font-bold text-slate-500">
                                @if($article->newscategory)
                                    <span class="text-purple-400 uppercase tracking-wider">{{ $article->newscategory->name }}</span>
                                    <span>·</span>
                                @endif
                                <span>{{ $article->created_at->format('d M Y') }}</span>
                            </div>
                            <span class="text-[11px] font-black text-[#a855f7] uppercase tracking-wider flex items-center gap-1 group-hover:gap-2 transition-all">
                                Read
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8 sm:hidden text-center">
            <a href="{{ route('news') }}"
                class="inline-flex items-center gap-2 text-sm font-black text-slate-500 hover:text-slate-900 transition-colors uppercase tracking-wider">
                View All News
            </a>
        </div>

    </div>
</section>
@endif

{{-- ── CTA: Register your Business ────────────────────────────────────── --}}
@guest
<section class="bg-[#0a0f1e] py-16">
    <div class="max-w-3xl mx-auto px-6 text-center">
        <div class="w-16 h-16 mx-auto mb-6 bg-[#4ade80]/10 border border-[#4ade80]/20 rounded-2xl flex items-center justify-center">
            <svg class="w-8 h-8 text-[#4ade80]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
            </svg>
        </div>
        <h2 class="text-3xl font-black text-white tracking-tight mb-3">List Your Business</h2>
        <p class="text-slate-400 text-sm leading-relaxed mb-8 max-w-md mx-auto">
            Are you an EV or hybrid dealership? Join BijuliCar's verified business network and reach thousands of buyers.
        </p>
        <div class="flex items-center justify-center gap-4 flex-wrap">
            <a href="{{ route('register') }}"
                class="px-8 py-3.5 bg-[#4ade80] text-black rounded-xl font-black text-sm uppercase tracking-wider hover:bg-[#22c55e] transition-all active:scale-95 shadow-lg shadow-green-500/20">
                Register Now
            </a>
            <a href="{{ route('login') }}"
                class="px-8 py-3.5 bg-white/5 border border-white/10 text-white rounded-xl font-black text-sm uppercase tracking-wider hover:bg-white/10 transition-all">
                Sign In
            </a>
        </div>
    </div>
</section>
@endguest
<script>
    const ALL_CITIES = @json($cities->values());

    function makeTypeahead(inputId, listId, dataArr) {
        const input = document.getElementById(inputId);
        const list  = document.getElementById(listId);
        if (!input || !list) return;

        const itemCls = 'px-5 py-3 cursor-pointer text-sm font-bold text-slate-800 hover:bg-[#4ade80]/10 hover:text-[#16a34a] transition-colors flex items-center gap-2';

        function render(matches) {
            list.innerHTML = '';
            if (!matches.length) { list.classList.add('hidden'); return; }
            matches.forEach(val => {
                const li = document.createElement('li');
                li.className = itemCls;
                li.innerHTML = `
                    <svg class="w-3.5 h-3.5 text-slate-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    </svg>
                    <span>${val}</span>`;
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
            if (!input.closest('#city-autocomplete-wrap').contains(e.target)) {
                list.classList.add('hidden');
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        makeTypeahead('city-input', 'city-suggestions', ALL_CITIES);
    });
</script>

@endsection