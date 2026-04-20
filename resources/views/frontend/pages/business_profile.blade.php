@extends('frontend.app')

<title>{{ $businessName }} | BijuliCar</title>

@section('content')

{{-- ── Business Profile Hero ───────────────────────────────────────────── --}}
<section class="relative pt-32 pb-16 lg:pt-40 lg:pb-20 bg-[#0a0f1e] text-white overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,_rgba(74,222,128,0.06)_0%,_transparent_60%)]"></div>
    <div class="absolute inset-0 opacity-[0.03]"
        style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 28px 28px;"></div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-xs font-bold text-slate-500 mb-8">
            <a href="{{ route('home') }}" class="hover:text-slate-300 transition-colors">Home</a>
            <span>/</span>
            <a href="{{ route('businesses.index') }}" class="hover:text-slate-300 transition-colors">Businesses</a>
            <span>/</span>
            <span class="text-slate-400">{{ $businessName }}</span>
        </div>

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
            <div class="flex items-center gap-6">
                {{-- Big Avatar --}}
                @if($user->profile_photo)
                    <img src="{{ Storage::url($user->profile_photo) }}"
                         alt="{{ $businessName }}"
                         class="w-20 h-20 lg:w-24 lg:h-24 rounded-3xl object-cover border border-white/10 shrink-0">
                @else
                    <div class="w-20 h-20 lg:w-24 lg:h-24 rounded-3xl bg-white/10 border border-white/10 flex items-center justify-center text-3xl font-black uppercase text-white shrink-0">
                        {{ strtoupper(substr($businessName, 0, 2)) }}
                    </div>
                @endif
                <div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-3xl lg:text-4xl font-black text-white tracking-tight">{{ $businessName }}</h1>
                        <span class="flex items-center gap-1.5 text-[11px] font-black text-[#4ade80] bg-[#4ade80]/10 border border-[#4ade80]/20 px-3 py-1 rounded-full">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Verified Business
                        </span>
                    </div>
                    <div class="flex flex-wrap items-center gap-4 mt-2">
                        @if($mapLocation)
                        <a href="{{ route('map_location') }}?user_id={{ $user->id }}"
                           class="flex items-center gap-1.5 text-xs font-bold text-slate-400 hover:text-[#4ade80] transition-colors group"
                           title="View on map">
                            <svg class="w-3.5 h-3.5 group-hover:text-[#4ade80]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            </svg>
                            {{ $location }}
                            <svg class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                        @else
                        <span class="flex items-center gap-1.5 text-xs font-bold text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            </svg>
                            {{ $location }}
                        </span>
                        @endif
                        @php
                            $tagColors = [
                                'EV Dealer'   => 'text-green-400 bg-green-400/10 border-green-400/20',
                                'Hybrid'      => 'text-blue-400 bg-blue-400/10 border-blue-400/20',
                                'Multi-Brand' => 'text-purple-400 bg-purple-400/10 border-purple-400/20',
                                'Traditional' => 'text-slate-400 bg-slate-400/10 border-slate-400/20',
                            ];
                            $tagColor = $tagColors[$spec] ?? 'text-slate-400 bg-slate-400/10';
                        @endphp
                        <span class="text-[11px] font-black px-3 py-1 rounded-full border {{ $tagColor }} uppercase tracking-wide">
                            {{ $spec }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Quick stats --}}
            <div class="flex items-center gap-4 lg:gap-6">
                <div class="text-center">
                    <p class="text-2xl font-black text-white">{{ $activeCars->count() }}</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Listings</p>
                </div>
                <div class="w-px h-10 bg-white/10"></div>
                <div class="text-center">
                    <p class="text-2xl font-black text-white">
                        {{ $avgRating > 0 ? number_format($avgRating, 1) : '—' }}
                    </p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Avg Rating</p>
                </div>
                <div class="w-px h-10 bg-white/10"></div>
                <div class="text-center">
                    <p class="text-2xl font-black text-white">{{ $reviewCount }}</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Reviews</p>
                </div>
            </div>
        </div>

        {{-- Contact info strip --}}
        @if($contact || $email)
        <div class="mt-8 flex flex-wrap items-center gap-3">
            @if($contact)
            <div class="flex items-center gap-3 bg-white/5 border border-white/10 rounded-2xl px-5 py-3.5">
                <div class="w-8 h-8 bg-[#4ade80]/10 rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#4ade80]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Phone</p>
                    <p class="text-sm font-black text-white">{{ $contact }}</p>
                </div>
            </div>
            @endif
            @if($email)
            <div class="flex items-center gap-3 bg-white/5 border border-white/10 rounded-2xl px-5 py-3.5">
                <div class="w-8 h-8 bg-blue-400/10 rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Email</p>
                    <a href="mailto:{{ $email }}" class="text-sm font-black text-white hover:text-blue-400 transition-colors">{{ $email }}</a>
                </div>
            </div>
            @endif
        </div>
        @endif
    </div>
</section>

{{-- ── Tabs: Listings / Reviews ────────────────────────────────────────── --}}
<div class="sticky top-[72px] z-40 bg-white border-b border-slate-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex gap-1">
            <button onclick="switchTab('listings')" id="tab-listings"
                class="tab-btn px-6 py-4 text-sm font-black uppercase tracking-wider border-b-2 transition-all text-[#16a34a] border-[#16a34a]">
                Active Listings
                <span class="ml-2 text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full font-black">
                    {{ $activeCars->count() }}
                </span>
            </button>
            <button onclick="switchTab('reviews')" id="tab-reviews"
                class="tab-btn px-6 py-4 text-sm font-black uppercase tracking-wider border-b-2 border-transparent text-slate-400 hover:text-slate-700 transition-all">
                Reviews
                <span class="ml-2 text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full font-black">
                    {{ $reviewCount }}
                </span>
            </button>
            <button onclick="switchTab('news')" id="tab-news"
            class="tab-btn px-6 py-4 text-sm font-black uppercase tracking-wider border-b-2 border-transparent text-slate-400 hover:text-slate-700 transition-all">
            News
            <span class="ml-2 text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full font-black">
                {{ $businessNews->count() }}
            </span>
        </button>
        </div>
    </div>
</div>

{{-- ── Listings Tab ─────────────────────────────────────────────────────── --}}
<section id="pane-listings" class="tab-pane bg-slate-50 py-12">
    <div class="max-w-7xl mx-auto px-6">
        @if($activeCars->isEmpty())
            <div class="text-center py-20">
                <div class="w-16 h-16 mx-auto mb-5 bg-slate-100 rounded-2xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <p class="text-slate-400 font-bold text-sm">No active listings at the moment.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($activeCars as $car)
                    @php
                        $img = $car->primary_image
                            ? asset('storage/' . $car->primary_image)
                            : null;
                    @endphp
                    <a href="{{ route('cars.show', $car->id) }}"
                        class="group bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">

                        {{-- Image --}}
                        <div class="relative h-48 bg-slate-100 overflow-hidden">
                            @if($img)
                                <img src="{{ $img }}" alt="{{ $car->displayName() }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200">
                                    <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                            @endif

                            {{-- Status badge --}}
                            <div class="absolute top-3 left-3">
                                @if($car->is_preorder || $car->status === 'upcoming')
                                    <span class="text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full bg-amber-400 text-amber-900">Pre-order</span>
                                @else
                                    <span class="text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full bg-[#4ade80] text-green-900">Available</span>
                                @endif
                            </div>

                            {{-- Drivetrain badge --}}
                            <div class="absolute top-3 right-3">
                                <span class="text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full bg-black/60 text-white backdrop-blur-sm">
                                    {{ strtoupper($car->drivetrain) }}
                                </span>
                            </div>
                        </div>

                        <div class="p-5">
                            <h3 class="text-[15px] font-black text-slate-900 leading-tight group-hover:text-[#16a34a] transition-colors">
                                {{ $car->displayName() }}
                            </h3>
                            <p class="text-xs font-bold text-slate-400 mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                {{ $car->location }}
                            </p>

                            <div class="flex items-center justify-between mt-4">
                                <div>
                                    <p class="text-xs font-bold text-slate-400">Price</p>
                                    <p class="text-base font-black text-slate-900">
                                        NRs {{ number_format($car->price) }}
                                        @if($car->price_negotiable)
                                            <span class="text-[10px] font-bold text-green-600 ml-1">Negotiable</span>
                                        @endif
                                    </p>
                                </div>
                                @if($car->range_km)
                                <div class="text-right">
                                    <p class="text-xs font-bold text-slate-400">Range</p>
                                    <p class="text-base font-black text-slate-900">{{ $car->range_km }} km</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>

{{-- ── Business Profile Ads (between listings and reviews) ───────────── --}}
@if(isset($businessProfileAds) && $businessProfileAds->isNotEmpty())
<section class="bg-slate-50 py-6 border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-6">
        <x-ads.horizontal-banner :ads="$businessProfileAds" />
    </div>
</section>
@endif

{{-- ── Reviews Tab ─────────────────────────────────────────────────────── --}}
<section id="pane-reviews" class="tab-pane hidden bg-slate-50 py-12">
    <div class="max-w-7xl mx-auto px-6">

        @if($reviewCount > 0)
        {{-- Rating Summary --}}
        <div class="bg-white rounded-3xl border border-slate-100 p-8 mb-8 flex flex-col sm:flex-row items-center gap-8">
            <div class="text-center sm:border-r sm:border-slate-100 sm:pr-8">
                <p class="text-6xl font-black text-slate-900">{{ number_format($avgRating, 1) }}</p>
                <div class="flex items-center justify-center gap-1 mt-2">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= round($avgRating) ? 'text-amber-400' : 'text-slate-200' }}"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    @endfor
                </div>
                <p class="text-xs font-bold text-slate-400 mt-1">{{ $reviewCount }} {{ Str::plural('review', $reviewCount) }}</p>
            </div>
            {{-- Star breakdown --}}
            <div class="flex-1 w-full space-y-2">
                @for($star = 5; $star >= 1; $star--)
                    @php
                        $count = $allReviews->where('rating', $star)->count();
                        $pct   = $reviewCount > 0 ? ($count / $reviewCount) * 100 : 0;
                    @endphp
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-black text-slate-500 w-4 shrink-0">{{ $star }}</span>
                        <svg class="w-3.5 h-3.5 text-amber-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <div class="flex-1 bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="h-2 bg-amber-400 rounded-full transition-all duration-700"
                                style="width: {{ $pct }}%"></div>
                        </div>
                        <span class="text-xs font-bold text-slate-400 w-6 text-right">{{ $count }}</span>
                    </div>
                @endfor
            </div>
        </div>
        @endif

        @if($allReviews->isEmpty())
            <div class="text-center py-20">
                <div class="w-16 h-16 mx-auto mb-5 bg-slate-100 rounded-2xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <p class="text-slate-400 font-bold text-sm">No reviews yet for this business.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($allReviews as $review)
                    <div class="bg-white rounded-2xl border border-slate-100 p-6">
                        <div class="flex items-start justify-between gap-4">
                                <div class="flex items-center gap-3">
                                @if($review->buyer && $review->buyer->profile_photo)
                                    <img src="{{ Storage::url($review->buyer->profile_photo) }}"
                                         alt="{{ $review->buyer->name }}"
                                         class="w-10 h-10 rounded-xl object-cover shrink-0">
                                @else
                                    <div class="w-10 h-10 rounded-xl bg-slate-900 flex items-center justify-center text-white text-xs font-black uppercase shrink-0">
                                        {{ strtoupper(substr($review->buyer->name ?? 'U', 0, 2)) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="text-[13px] font-black text-slate-900">{{ $review->buyer->name ?? 'Anonymous' }}</p>
                                    @if($review->car)
                                        <a href="{{ route('cars.show', $review->car_id) }}"
                                            class="text-[11px] font-bold text-[#16a34a] hover:underline">
                                            {{ $review->car->displayName() }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <div class="flex items-center gap-0.5 justify-end">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-200' }}"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                                <p class="text-[10px] font-bold text-slate-400 mt-1">{{ $review->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @if($review->body)
                            <p class="mt-4 text-sm text-slate-600 leading-relaxed">{{ $review->body }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
<section id="pane-news" class="tab-pane hidden bg-slate-50 py-12">
    <div class="max-w-7xl mx-auto px-6">
 
        @if($businessNews->isNotEmpty())
 
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($businessNews as $article)
                    <a href="{{ route('business.news.show', $article->slug) }}"
                        class="group bg-white border border-slate-200 rounded-2xl overflow-hidden hover:shadow-md hover:border-slate-300 transition-all block">
 
                        {{-- Thumbnail --}}
                        <div class="aspect-video overflow-hidden bg-slate-100">
                            @if($article->hero_image)
                                <img src="{{ asset('storage/' . $article->hero_image) }}"
                                    alt="{{ $article->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-50 to-slate-100">
                                    <svg class="w-10 h-10 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 12h6"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
 
                        {{-- Content --}}
                        <div class="p-5">
                            <div class="flex items-center gap-2 mb-3 flex-wrap">
                                @if($article->newscategory)
                                    <span class="text-[9px] font-black uppercase tracking-widest text-purple-600 bg-purple-50 border border-purple-200 px-2 py-0.5 rounded-full">
                                        {{ $article->newscategory->name }}
                                    </span>
                                @endif
                                <span class="text-[10px] text-slate-400 font-medium">
                                    {{ $article->created_at->format('d M Y') }}
                                </span>
                            </div>
 
                            <h3 class="text-base font-black text-slate-900 uppercase italic tracking-tight leading-tight mb-2 group-hover:text-slate-600 transition-colors line-clamp-2">
                                {{ $article->title }}
                            </h3>
 
                            <p class="text-sm text-slate-500 font-medium leading-relaxed line-clamp-2">
                                {{ strip_tags($article->lead_paragraph) }}
                            </p>
 
                            <div class="flex items-center gap-1.5 mt-4 text-[11px] font-black text-slate-400 uppercase tracking-wider">
                                <span>Read more</span>
                                <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
 
                    </a>
                @endforeach
            </div>
 
        @else
 
            {{-- Empty state --}}
            <div class="text-center py-16">
                <div class="w-14 h-14 bg-purple-50 border border-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 12h6"/>
                    </svg>
                </div>
                <p class="text-slate-400 font-medium text-sm">This business hasn't published any news yet.</p>
            </div>
 
        @endif
 
    </div>
</section>

{{-- ── Back link ────────────────────────────────────────────────────────── --}}
<div class="bg-white border-t border-slate-100 py-6">
    <div class="max-w-7xl mx-auto px-6">
        <a href="{{ route('businesses.index') }}"
            class="inline-flex items-center gap-2 text-sm font-black text-slate-500 hover:text-slate-900 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
            </svg>
            Back to All Businesses
        </a>
    </div>
</div>

<script>
    function switchTab(tab) {
        // Panes
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.add('hidden'));
        document.getElementById('pane-' + tab).classList.remove('hidden');

        // Tabs
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('text-[#16a34a]', 'border-[#16a34a]');
            btn.classList.add('text-slate-400', 'border-transparent');
        });
        const active = document.getElementById('tab-' + tab);
        active.classList.remove('text-slate-400', 'border-transparent');
        active.classList.add('text-[#16a34a]', 'border-[#16a34a]');
    }
</script>

@endsection