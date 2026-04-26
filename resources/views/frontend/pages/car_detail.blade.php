@extends('frontend.app')

@section('content')

    @php
        $images = $car->images;
        $hasImages = $images->isNotEmpty();
        $driveColors = [
            'ev' => [
                'bg' => 'bg-green-500',
                'light' => 'bg-green-50',
                'text' => 'text-green-700',
                'badge' => 'bg-green-100 text-green-700',
                'label' => '<i class="fa-solid fa-leaf mr-1" style="color: rgb(46, 204, 113);"></i> Electric',
            ],
            'hybrid' => [
                'bg' => 'bg-blue-500',
                'light' => 'bg-blue-50',
                'text' => 'text-blue-700',
                'badge' => 'bg-blue-100 text-blue-700',
                'label' => '<i class="fa-solid fa-leaf" style="color: rgb(46, 204, 113);"></i>/<i class="fa-solid fa-gas-pump mr-1" style="color: rgb(231, 76, 60);"></i> Hybrid',
            ],
            'petrol' => [
                'bg' => 'bg-orange-500',
                'light' => 'bg-orange-50',
                'text' => 'text-orange-700',
                'badge' => 'bg-orange-100 text-orange-700',
                'label' => '<i class="fa-solid fa-gas-pump mr-1" style="color: rgb(231, 76, 60);"></i> Petrol',
            ],
            'diesel' => [
                'bg' => 'bg-slate-600',
                'light' => 'bg-slate-50',
                'text' => 'text-slate-700',
                'badge' => 'bg-slate-200 text-slate-700',
                'label' => '<i class="fa-solid fa-oil-can mr-1" style="color: rgb(241, 196, 15);"></i> Diesel',
            ],
        ];
        $dc = $driveColors[$car->drivetrain] ?? $driveColors['petrol'];
    @endphp

    {{-- ── HERO GALLERY ────────────────────────────────────────────────── --}}
    <section class="bg-slate-900 pt-20">
        <div class="max-w-7xl mx-auto px-4 md:px-6 py-8">

            {{-- Back breadcrumb --}}
            <div class="flex items-center gap-2 mb-6 text-slate-400">
                <a href="{{ route('marketplace') }}"
                    class="hover:text-[#4ade80] transition-colors text-[12px] font-bold uppercase tracking-widest">Marketplace</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span
                    class="text-[12px] font-bold uppercase tracking-widest text-slate-300 truncate max-w-xs">{{ $car->displayName() }}</span>
            </div>

            @if ($hasImages)
                {{-- Gallery: large image + thumbnails --}}
                <div class="flex flex-col lg:flex-row gap-3">

                    {{-- Main image --}}
                    <div class="flex-1 relative rounded-2xl overflow-hidden bg-slate-800 aspect-[16/9] lg:aspect-auto lg:min-h-[440px] cursor-zoom-in"
                        onclick="openLightbox(0)" id="mainImageWrapper">
                        <img id="mainImage" src="{{ Storage::url($images->first()->path) }}"
                            class="w-full h-full object-cover transition-opacity duration-300"
                            alt="{{ $car->displayName() }}">

                        {{-- Drivetrain badge --}}
                        <div class="absolute top-4 left-4">
                            <span
                                class="text-[11px] font-black px-3 py-1.5 rounded-full uppercase tracking-widest {{ $dc['badge'] }}">
                                {!!  $dc['label'] !!}
                            </span>
                        </div>

                        {{-- Image count --}}
                        @if ($images->count() > 1)
                            <div
                                class="absolute bottom-4 right-4 bg-black/60 text-white text-[11px] font-black px-3 py-1.5 rounded-full backdrop-blur-sm">
                                <span id="imageCounter">1</span> / {{ $images->count() }}
                            </div>
                        @endif

                        {{-- Zoom icon --}}
                        <div class="absolute bottom-4 left-4 bg-black/40 text-white/70 p-2 rounded-xl backdrop-blur-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                            </svg>
                        </div>
                    </div>

                    {{-- Thumbnail strip --}}
                    @if ($images->count() > 1)
                        <div
                            class="lg:w-24 flex lg:flex-col gap-2 overflow-x-auto lg:overflow-y-auto lg:max-h-[440px] pb-1 lg:pb-0">
                            @foreach ($images as $idx => $img)
                                <button onclick="switchImage({{ $idx }}, '{{ Storage::url($img->path) }}')"
                                    class="thumb-btn shrink-0 w-20 h-16 lg:w-24 lg:h-20 rounded-xl overflow-hidden border-2 transition-all
                                    {{ $idx === 0 ? 'border-[#4ade80]' : 'border-transparent opacity-60 hover:opacity-100 hover:border-slate-400' }}"
                                    data-idx="{{ $idx }}">
                                    <img src="{{ Storage::url($img->path) }}" class="w-full h-full object-cover"
                                        alt="Image {{ $idx + 1 }}">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
            @else
                {{-- No images placeholder --}}
                <div class="w-full h-80 rounded-2xl bg-slate-800 flex flex-col items-center justify-center gap-3">
                    <span class="text-6xl opacity-20">🚗</span>
                    <p class="text-slate-500 text-sm font-bold uppercase tracking-widest">No images uploaded</p>
                </div>
            @endif
        </div>
    </section>

    {{-- ── LIGHTBOX ────────────────────────────────────────────────────── --}}
    <div id="lightbox"
        class="fixed inset-0 z-[200] bg-black/95 flex items-center justify-center invisible opacity-0 transition-all duration-200"
        onclick="closeLightbox()">
        <button onclick="prevImage(event)"
            class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-all z-10">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <img id="lightboxImg" src="" class="max-w-[90vw] max-h-[88vh] object-contain rounded-xl"
            onclick="event.stopPropagation()">
        <button onclick="nextImage(event)"
            class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-all z-10">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
            </svg>
        </button>
        <button onclick="closeLightbox()"
            class="absolute top-4 right-4 w-10 h-10 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <div class="absolute bottom-4 text-white/50 text-sm font-bold" id="lightboxCounter"></div>
    </div>

    {{-- ── MAIN CONTENT ─────────────────────────────────────────────────── --}}
    <section class="bg-[#f1f5f9] py-10">
        <div class="max-w-7xl mx-auto px-4 md:px-6">
            <div class="flex flex-col xl:flex-row gap-8">

                {{-- ── LEFT: specs + reviews ────────────────────────────── --}}
                <div class="flex-1 min-w-0 space-y-6">

                    {{-- Title card --}}
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <span
                                        class="text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-widest {{ $dc['badge'] }}">{!! $dc['label'] !!}</span>
                                    <span
                                        class="text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-widest bg-slate-100 text-slate-600">{{ ucfirst($car->condition) }}</span>
                                    @if ($car->stock_quantity > 1)
                                        <span
                                            class="text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-widest bg-green-100 text-green-700">{{ $car->stock_quantity }}
                                            in stock</span>
                                    @endif
                                    @if ($car->isUpcoming())
                                        <span
                                            class="inline-flex items-center gap-1.5 text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-widest bg-violet-100 text-violet-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-violet-500 animate-pulse"></span>
                                            Upcoming · Pre-Order Open
                                        </span>
                                    @endif
                                </div>
                                <h1
                                    class="text-3xl md:text-4xl font-black text-slate-900 uppercase italic tracking-tight leading-tight">
                                    {{ $car->displayName() }}
                                </h1>
                                @if ($car->variant)
                                    <p class="text-slate-400 font-bold text-sm mt-1">{{ $car->variant }}</p>
                                @endif
                                <div class="flex items-center gap-4 mt-3 flex-wrap">
                                    <span class="flex items-center gap-1.5 text-[12px] font-bold text-slate-500">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ $car->location }}
                                    </span>
                                    @if ($reviewCount > 0)
                                        <span class="flex items-center gap-1.5 text-[12px] font-bold text-amber-600">
                                            <svg class="w-4 h-4 fill-amber-400" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                            </svg>
                                            {{ number_format($avgRating, 1) }} ({{ $reviewCount }}
                                            review{{ $reviewCount !== 1 ? 's' : '' }})
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Add to compare --}}
                            @php
                                $compareIds = request()->session()->get('compare_ids', []);
                            @endphp
                            <a href="{{ route('compare_cars', ['cars[]' => $car->id]) }}"
                                class="shrink-0 flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-[11px] font-black uppercase tracking-widest hover:border-green-400 hover:text-green-700 hover:bg-green-50 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 0v10m0-10a2 2 0 012-2h2a2 2 0 012 2v10a2 2 0 01-2 2h-2a2 2 0 01-2-2" />
                                </svg>
                                Compare
                            </a>
                        </div>
                    </div>

                    {{-- ── VEHICLE DETAILS ──────────────────────────────── --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                            <span
                                class="w-8 h-8 rounded-lg {{ $dc['light'] }} flex items-center justify-center text-base"><i class="fa-solid fa-truck-monster" style="color: rgb(255, 212, 59);"></i></span>
                            <span class="text-[12px] font-black uppercase tracking-widest text-slate-700">Vehicle
                                Details</span>
                        </div>
                        {{-- 2-column grid — icon prominent left, label + value stacked right --}}
                        <div class="grid grid-cols-2 divide-x divide-slate-50">
                            @php
                                $specs = [
                                    [
                                        'icon' => '<i class="fa-solid fa-calendar-days" style="color: #64748b;"></i>', 
                                        'label' => 'Year', 
                                        'value' => $car->year
                                    ],
                                    [
                                        'icon' => '<i class="fa-solid fa-gears" style="color: #475569;"></i>', 
                                        'label' => 'Drivetrain', 
                                        'value' => strtoupper($car->drivetrain)
                                    ],
                                    [
                                        'icon' => '<i class="fa-solid fa-road" style="color: #94a3b8;"></i>',
                                        'label' => 'Mileage',
                                        'value' => number_format($car->mileage) . ' km',
                                    ],
                                    [
                                        'icon' => '<i class="fa-solid fa-certificate" style="color: #f59e0b;"></i>', 
                                        'label' => 'Condition', 
                                        'value' => ucfirst($car->condition)
                                    ],
                                    [
                                        'icon' => '<i class="fa-solid fa-palette" style="color: #6366f1;"></i>', 
                                        'label' => 'Color', 
                                        'value' => $car->color ?? '—'
                                    ],
                                    [
                                        'icon' => '<i class="fa-solid fa-cubes" style="color: #0ea5e9;"></i>',
                                        'label' => 'Stock',
                                        'value' => $car->stock_quantity . ' unit' . ($car->stock_quantity !== 1 ? 's' : ''),
                                    ],
                                    [
                                        'icon' => '<i class="fa-solid fa-location-dot" style="color: #ef4444;"></i>', 
                                        'label' => 'Location', 
                                        'value' => $car->location
                                    ],
                                ];

                                if ($car->range_km) {
                                    $specs[] = [
                                        'icon' => '<i class="fa-solid fa-car-battery" style="color: #10b981;"></i>',
                                        'label' => 'EV Range',
                                        'value' => number_format($car->range_km) . ' km',
                                    ];
                                }

                                if ($car->battery_kwh) {
                                    $specs[] = [
                                        'icon' => '<i class="fa-solid fa-bolt-lightning" style="color: #fbbf24;"></i>',
                                        'label' => 'Battery',
                                        'value' => $car->battery_kwh . ' kWh',
                                    ];
                                }

                                // Grid balancing
                                if (count($specs) % 2 !== 0) {
                                    $specs[] = null;
                                }
                            @endphp

                            @foreach ($specs as $spec)
                                @if ($spec)
                                    <div
                                        class="flex items-center gap-3 px-5 py-3.5 border-b border-slate-50 hover:bg-slate-50/60 transition-colors">
                                        <span class="text-xl shrink-0 px-4">{!! $spec['icon'] !!}</span>
                                        <div class="min-w-0">
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                                {{ $spec['label'] }}</p>
                                            <p class="text-[13px] font-black text-slate-800 truncate">{{ $spec['value'] }}
                                            </p>
                                        </div>
                                    </div>
                                @else
                                    <div class="border-b border-slate-50"></div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    {{-- Description --}}
                    @if ($car->description)
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <span
                                    class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-base"><i class="fa-solid fa-pen-to-square" style="color: #6366f1;"></i></span>
                                <span
                                    class="text-[12px] font-black uppercase tracking-widest text-slate-700">Description</span>
                            </div>
                            <p class="text-slate-600 text-[15px] leading-relaxed">{{ $car->description }}</p>
                        </div>
                    @endif

                    {{-- ── REVIEWS ──────────────────────────────────────── --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span
                                    class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-base">⭐</span>
                                <span class="text-[12px] font-black uppercase tracking-widest text-slate-700">Reviews</span>
                            </div>
                            @if ($reviewCount > 0)
                                <div class="flex items-center gap-2">
                                    <div class="flex">
                                        @for ($s = 1; $s <= 5; $s++)
                                            <svg class="w-4 h-4 {{ $s <= round($avgRating) ? 'fill-amber-400' : 'fill-slate-200' }}"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                            </svg>
                                        @endfor
                                    </div>
                                    <span
                                        class="text-[13px] font-black text-slate-700">{{ number_format($avgRating, 1) }}</span>
                                    <span class="text-[12px] text-slate-400 font-medium">({{ $reviewCount }})</span>
                                </div>
                            @endif
                        </div>

                        @if ($car->reviews->isNotEmpty())
                            <div class="divide-y divide-slate-50">
                                @foreach ($car->reviews as $review)
                                    <div class="px-6 py-5 hover:bg-slate-50/50 transition-colors">
                                        <div class="flex items-start justify-between gap-3 mb-2">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-9 h-9 rounded-xl bg-slate-900 flex items-center justify-center text-white text-[11px] font-black uppercase">
                                                    {{ strtoupper(substr($review->buyer->name ?? 'U', 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div class="flex items-center gap-2">
                                                        <p class="text-[13px] font-bold text-slate-800">
                                                            {{ $review->buyer->name ?? 'Reviewer' }}</p>
                                                        {{-- Source badge: Rental vs Purchase --}}
                                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider {{ $review->sourceBadgeClasses() }}">
                                                            {{ $review->sourceLabel() }}
                                                        </span>
                                                    </div>
                                                    <p class="text-[11px] text-slate-400 font-medium">
                                                        {{ $review->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                            <div class="flex shrink-0">
                                                @for ($s = 1; $s <= 5; $s++)
                                                    <svg class="w-3.5 h-3.5 {{ $s <= $review->rating ? 'fill-amber-400' : 'fill-slate-200' }}"
                                                        viewBox="0 0 24 24">
                                                        <path
                                                            d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                    </svg>
                                                @endfor
                                            </div>
                                        </div>
                                        @if ($review->body)
                                            <p class="text-[13px] text-slate-600 leading-relaxed ml-12">
                                                {{ $review->body }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="px-6 py-10 text-center">
                                <p class="text-slate-400 text-sm font-medium">No reviews yet for this vehicle.</p>
                                @if ($hasPurchased && !$alreadyReviewed)
                                    <a href="{{ route('buyer.reviews.create', $car) }}"
                                        class="inline-flex items-center gap-2 mt-4 px-5 py-2.5 bg-slate-900 text-white rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all">
                                        Be the first to review
                                    </a>
                                @elseif ($hasRented && !$alreadyReviewedRental)
                                    <a href="{{ route('buyer.reviews.create', ['rental_id' => $completedRental->id]) }}"
                                        class="inline-flex items-center gap-2 mt-4 px-5 py-2.5 bg-slate-900 text-white rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-blue-600 transition-all">
                                        Be the first to review
                                    </a>
                                @endif
                            </div>
                        @endif

                        {{-- Write review CTAs --}}
                        @if ($car->reviews->isNotEmpty())
                            <div class="px-6 pb-5 flex flex-wrap gap-3">
                                @if ($hasPurchased && !$alreadyReviewed)
                                    <a href="{{ route('buyer.reviews.create', $car) }}"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-xl text-[11px] font-black uppercase tracking-widest hover:bg-amber-100 transition-all">
                                        ⭐ Review your purchase
                                    </a>
                                @endif
                                @if ($hasRented && !$alreadyReviewedRental)
                                    <a href="{{ route('buyer.reviews.create', ['rental_id' => $completedRental->id]) }}"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-50 text-blue-700 border border-blue-200 rounded-xl text-[11px] font-black uppercase tracking-widest hover:bg-blue-100 transition-all">
                                        ⭐ Review your rental
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- ── Business Profile Ads (between listings and reviews) ───────────── --}}
                    @if (isset($carDetailAds) && $carDetailAds->isNotEmpty())
                        <section class="bg-slate-50 py-6 border-t border-slate-100">
                            <div class="max-w-7xl mx-auto px-6">
                                <x-ads.horizontal-banner :ads="$carDetailAds" />
                            </div>
                        </section>
                    @endif
                </div>


                {{-- ── RIGHT: order sidebar ─────────────────────────────── --}}
                <div class="xl:w-80 space-y-5 xl:sticky xl:top-24 xl:self-start">

                    {{-- Buy / Rent tab selector (only shown when both options available) --}}
                    @if($car->isSaleable() && $car->isRentable())
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-1.5 flex gap-1" id="action-tabs">
                        <button type="button" id="tab-buy"
                            onclick="switchTab('buy')"
                            class="flex-1 py-2.5 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all bg-slate-900 text-white">
                            🏷️ Buy
                        </button>
                        <button type="button" id="tab-rent"
                            onclick="switchTab('rent')"
                            class="flex-1 py-2.5 rounded-xl text-[11px] font-black uppercase tracking-widest transition-all text-slate-500 hover:bg-slate-50">
                            📅 Rent
                        </button>
                    </div>
                    @endif

                    {{-- ── BUY PANEL ─────────────────────────────────────── --}}
                    @if($car->isSaleable())
                    <div id="panel-buy">

                    {{-- Price + Order card --}}
                    <div id="place-order" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 scroll-mt-28">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Asking Price</p>
                        <p class="text-4xl font-black text-slate-900 italic tracking-tight">NRs
                            {{ number_format($car->price) }}</p>
                        @if ($car->price_negotiable && !$isSoldOut)
                            @auth
                                @if (auth()->user()->hasRole('buyer') && !$alreadyOrdered)
                                    @if ($activeNegotiation?->isAccepted())
                                        {{-- Accepted — show agreed price banner --}}
                                        <div class="mt-2 bg-green-50 border border-green-200 rounded-xl p-3">
                                            <p class="text-[10px] font-black text-green-600 uppercase tracking-widest">🤝 Offer Accepted</p>
                                            <p class="text-xl font-black text-green-700 mt-0.5">NRs {{ number_format($activeNegotiation->offered_price) }}</p>
                                            <p class="text-[10px] text-green-500 font-medium">Place your order below at this agreed price.</p>
                                        </div>
                                    @elseif ($activeNegotiation?->isActive())
                                        {{-- Active negotiation in progress --}}
                                        <div class="mt-2 bg-amber-50 border border-amber-200 rounded-xl p-3">
                                            <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest">⏳ Negotiation in Progress</p>
                                            <p class="text-[11px] text-amber-700 font-medium mt-1">Your offer of NRs {{ number_format($activeNegotiation->offered_price) }} is {{ $activeNegotiation->statusLabel() }}.</p>
                                            <a href="{{ route('buyer.negotiations.show', $activeNegotiation) }}" class="inline-block mt-1.5 text-[10px] font-black text-amber-700 uppercase tracking-widest underline">View Negotiation →</a>
                                        </div>
                                    @else
                                        {{-- No active negotiation — show offer button --}}
                                        <p class="text-[11px] font-black text-green-600 uppercase tracking-widest mt-1">✓ Price is negotiable</p>
                                        <button onclick="document.getElementById('negotiationPanel').classList.toggle('hidden')"
                                            class="mt-2 w-full py-2.5 rounded-xl bg-green-50 border border-green-200 text-green-700 text-[11px] font-black uppercase tracking-widest hover:bg-green-100 transition-all">
                                            Make an Offer
                                        </button>
                                        <div id="negotiationPanel" class="hidden mt-3 bg-slate-50 border border-slate-200 rounded-xl p-4">
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Your Offer Price (NRs)</p>
                                            <form method="POST" action="{{ route('buyer.negotiations.store') }}">
                                                @csrf
                                                <input type="hidden" name="car_id" value="{{ $car->id }}">
                                                <input type="number" name="offered_price" required min="1" max="{{ $car->price ? $car->price - 1 : '' }}"
                                                    placeholder="e.g. {{ $car->price ? number_format($car->price * 0.9, 0, '.', '') : '' }}"
                                                    class="w-full bg-white border border-slate-200 rounded-xl py-2.5 px-3 text-sm text-slate-800 font-medium focus:outline-none focus:border-green-400 mb-2">
                                                <textarea name="message" rows="2" placeholder="Optional message to seller..."
                                                    class="w-full bg-white border border-slate-200 rounded-xl p-3 text-sm text-slate-800 font-medium focus:outline-none focus:border-green-400 resize-none mb-3"></textarea>
                                                <button type="submit"
                                                    class="w-full py-3 rounded-xl bg-green-600 text-white text-[12px] font-black uppercase italic tracking-widest hover:bg-green-700 transition-all">
                                                    Send Offer
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @else
                                    <p class="text-[11px] font-black text-green-600 uppercase tracking-widest mt-1">✓ Price is negotiable</p>
                                @endif
                            @else
                                <p class="text-[11px] font-black text-green-600 uppercase tracking-widest mt-1">✓ Price is negotiable</p>
                            @endauth
                        @endif

                        <div class="mt-5 pt-5 border-t border-slate-100 space-y-3">
                            @if ($isSoldOut)
                                {{-- ── SOLD OUT — car confirmed to another buyer ── --}}
                                <div class="w-full py-3.5 rounded-xl bg-red-50 border border-red-200 text-red-600 text-[12px] font-black uppercase tracking-widest text-center">
                                    ✕ Sold Out
                                </div>
                                <p class="text-center text-[11px] text-slate-400 font-medium">
                                    This car has been sold to another buyer.
                                </p>
                                <a href="{{ route('marketplace.index') }}"
                                    class="block w-full py-3.5 rounded-xl bg-slate-900 text-white text-[12px] font-black uppercase italic tracking-widest text-center hover:bg-[#16a34a] transition-all">
                                    Browse Other Listings
                                </a>
                            @else
                            @auth
                                @if (auth()->user()->hasRole('buyer'))
                                    {{-- ── PRE-ORDER FLOW ── --}}
                                    @if ($car->isPreorderable())
                                        @if ($alreadyPreOrdered)
                                            <div
                                                class="w-full py-3.5 rounded-xl bg-violet-50 border border-violet-200 text-violet-700 text-[12px] font-black uppercase tracking-widest text-center">
                                                ✔ Pre-Order Placed
                                            </div>
                                            <a href="{{ route('buyer.preorders.index') }}"
                                                class="block w-full py-3.5 rounded-xl bg-slate-100 text-slate-700 text-[12px] font-black uppercase italic tracking-widest text-center hover:bg-slate-200 transition-all">
                                                View My Pre-Orders
                                            </a>
                                        @else
                                            <a href="{{ route('buyer.preorders.create', $car) }}"
                                                class="flex w-full items-center justify-center gap-2 py-4 rounded-xl bg-violet-600 text-white text-[13px] font-black uppercase italic tracking-widest hover:bg-violet-700 transition-all shadow-lg shadow-violet-900/20">
                                                ⚡ Pre-Order Now
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                                </svg>
                                            </a>
                                            <div class="bg-violet-50 border border-violet-100 rounded-xl p-3 space-y-1.5">
                                                <div class="flex items-center justify-between">
                                                    <span
                                                        class="text-[10px] font-black text-violet-500 uppercase tracking-widest">Deposit
                                                        Required</span>
                                                    <span class="text-[13px] font-black text-violet-700">NRs
                                                        {{ number_format($car->preorder_deposit) }}</span>
                                                </div>
                                                @if ($car->expected_arrival_date)
                                                    <div class="flex items-center justify-between">
                                                        <span
                                                            class="text-[10px] font-black text-violet-500 uppercase tracking-widest">Expected
                                                            Arrival</span>
                                                        <span
                                                            class="text-[12px] font-black text-violet-700">{{ $car->expected_arrival_date->format('M Y') }}</span>
                                                    </div>
                                                @endif
                                                <p
                                                    class="text-[10px] text-violet-400 font-medium pt-1 border-t border-violet-100">
                                                    Deposit secures your slot. Remaining balance due on delivery.
                                                </p>
                                            </div>
                                        @endif

                                        {{-- ── REGULAR ORDER FLOW ── --}}
                                        @if ($alreadyOrdered)
                                        <div
                                            class="w-full py-3.5 rounded-xl bg-green-50 border border-green-200 text-green-700 text-[12px] font-black uppercase tracking-widest text-center">
                                            ✓ Already Ordered
                                        </div>
                                        <a href="{{ route('buyer.orders.index') }}"
                                            class="block w-full py-3.5 rounded-xl bg-slate-100 text-slate-700 text-[12px] font-black uppercase italic tracking-widest text-center hover:bg-slate-200 transition-all">
                                            View My Orders
                                        </a>
                                        @endif
                                    @elseif ($alreadyOrdered)
                                        <div class="w-full py-3.5 rounded-xl bg-green-50 border border-green-200 text-green-700 text-[12px] font-black uppercase tracking-widest text-center">
                                            ✓ Already Ordered
                                        </div>
                                        <a href="{{ route('buyer.orders.index') }}"
                                            class="block w-full py-3.5 rounded-xl bg-slate-100 text-slate-700 text-[12px] font-black uppercase italic tracking-widest text-center hover:bg-slate-200 transition-all">
                                            View My Orders
                                        </a>
                                    @elseif ($blockedBySaleRental)
                                        {{-- All units are out on confirmed/active rental — buying not possible right now --}}
                                        <div class="w-full py-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-700 text-[12px] font-black uppercase tracking-widest text-center">
                                            🚗 Currently On Rental
                                        </div>
                                        <p class="text-center text-[11px] text-slate-400 font-medium mt-1">
                                            All units of this car are out on rental. Orders will be available once a rental ends.
                                        </p>
                                    @elseif ($car->inStock())
                                        <form method="POST" action="{{ route('buyer.orders.store') }}" id="orderForm">
                                            @csrf
                                            <input type="hidden" name="car_id" value="{{ $car->id }}">

                                            {{-- Show negotiated price if applicable --}}
                                            @if ($activeNegotiation?->isAccepted())
                                            <div class="mb-3 pb-3 border-b border-slate-100 flex items-center justify-between">
                                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Order Price</span>
                                                <div class="text-right">
                                                    <span class="text-base font-black text-green-700">NRs {{ number_format($activeNegotiation->offered_price) }}</span>
                                                    <p class="text-[10px] text-slate-400 font-medium">Negotiated ✓</p>
                                                </div>
                                            </div>
                                            @endif

                                            {{-- Contact details section --}}
                                            <div class="mb-3 pb-3 border-b border-slate-100">
                                                <p
                                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">
                                                    Your Contact Details</p>

                                                {{-- Full Name --}}
                                                <div class="mb-2.5">
                                                    <label
                                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Full
                                                        Name <span class="text-red-400">*</span></label>
                                                    <input type="text" name="buyer_name" required
                                                        value="{{ old('buyer_name', auth()->user()->name) }}"
                                                        placeholder="Your full name"
                                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3 text-sm text-slate-800 placeholder:text-slate-300 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all font-medium @error('buyer_name') border-red-400 @enderror">
                                                    @error('buyer_name')
                                                        <p class="text-[10px] text-red-500 font-bold mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                {{-- Phone --}}
                                                <div class="mb-2.5">
                                                    <label
                                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Phone
                                                        Number <span class="text-red-400">*</span></label>
                                                    <input type="tel" name="buyer_phone"
                                                        value="{{ old('buyer_phone', auth()->user()->phone) }}" required
                                                        placeholder="e.g. 98XXXXXXXX"
                                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3 text-sm text-slate-800 placeholder:text-slate-300 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all font-medium @error('buyer_phone') border-red-400 @enderror">
                                                    @error('buyer_phone')
                                                        <p class="text-[10px] text-red-500 font-bold mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                {{-- Email --}}
                                                <div class="mb-0">
                                                    <label
                                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Email
                                                        Address <span class="text-red-400">*</span></label>
                                                    <input type="email" name="buyer_email" required
                                                        value="{{ old('buyer_email', auth()->user()->email) }}"
                                                        placeholder="you@gmail.com"
                                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3 text-sm text-slate-800 placeholder:text-slate-300 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all font-medium @error('buyer_email') border-red-400 @enderror">
                                                    @error('buyer_email')
                                                        <p class="text-[10px] text-red-500 font-bold mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- Note to seller --}}
                                            <div class="mb-3">
                                                <label
                                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1.5">Note
                                                    to Seller <span class="text-slate-300">(optional)</span></label>
                                                <textarea name="notes" rows="2" placeholder="Any questions or requirements..."
                                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-sm text-slate-800 placeholder:text-slate-300 focus:outline-none focus:border-[#16a34a] resize-none font-medium">{{ old('notes') }}</textarea>
                                            </div>

                                            <button type="submit"
                                                class="w-full py-4 rounded-xl bg-slate-900 text-white text-[13px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all shadow-lg shadow-slate-900/10 flex items-center justify-center gap-2">
                                                Place Order
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                                </svg>
                                            </button>

                                            <p class="text-[10px] text-slate-400 text-center mt-2 font-medium">
                                                Your contact info will be shared with the seller.
                                            </p>
                                        </form>
                                    @else
                                        <div
                                            class="w-full py-3.5 rounded-xl bg-red-50 border border-red-200 text-red-600 text-[12px] font-black uppercase tracking-widest text-center">
                                            Out of Stock
                                        </div>
                                    @endif
                                @elseif (auth()->user()->id === $car->seller_id)
                                    <a href="{{ auth()->user()->hasRole('business') ? route('business.cars.edit', $car) : route('seller.cars.edit', $car) }}"
                                    class="block w-full py-3.5 rounded-xl bg-slate-900 text-white text-[12px] font-black uppercase italic tracking-widest text-center hover:bg-[#16a34a] transition-all">
                                    Edit Your Listing
                                    </a>
                                @else
                                    <p class="text-center text-[12px] text-slate-400 font-bold">You must be buyer to order this
                                        car! Log in via buyer's account.</p>
                                @endif
                            @else
                                @if ($car->isPreorderable())
                                    <a href="{{ route('login') }}"
                                        class="flex w-full items-center justify-center gap-2 py-4 rounded-xl bg-violet-600 text-white text-[13px] font-black uppercase italic tracking-widest hover:bg-violet-700 transition-all shadow-lg">
                                        ⚡ Login to Pre-Order
                                    </a>
                                    <p class="text-center text-[10px] text-violet-500 font-bold uppercase tracking-widest">
                                        NRs {{ number_format($car->preorder_deposit) }} deposit ·
                                        {{ $car->expected_arrival_date?->format('M Y') }}
                                    </p>
                                @else
                                    <a href="{{ route('login') }}"
                                        class="block w-full py-4 rounded-xl bg-slate-900 text-white text-[13px] font-black uppercase italic tracking-widest text-center hover:bg-[#16a34a] transition-all shadow-lg">
                                        Login to Order
                                    </a>
                                @endif
                                <a href="{{ route('register') }}"
                                    class="block w-full py-3.5 rounded-xl bg-white border border-slate-200 text-slate-700 text-[12px] font-black uppercase tracking-widest text-center hover:bg-slate-50 transition-all">
                                    Create Free Account
                                </a>
                            @endauth
                            @endif {{-- end $isSoldOut --}}
                        </div>

                    </div> {{-- end #place-order --}}

                    </div> {{-- end #panel-buy --}}

                    @endif {{-- end isSaleable --}}

                    {{-- ── RENT PANEL ─────────────────────────────────── --}}
                    @if($car->isRentable() && !$isSoldOut && $car->rent_price_per_day)
                    <div id="panel-rent" class="{{ ($car->isSaleable()) ? 'hidden' : '' }}">
                        <div id="rent-car" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 scroll-mt-28">

                            {{-- Daily rate display --}}
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Daily Rental Rate</p>
                            <p class="text-4xl font-black text-slate-900 italic tracking-tight">
                                NRs {{ number_format($car->rent_price_per_day) }}
                                <span class="text-lg font-bold text-slate-400 not-italic">/day</span>
                            </p>

                            <div class="mt-3 flex flex-wrap gap-2">
                                <span class="text-[10px] font-black px-2.5 py-1 bg-blue-50 text-blue-700 rounded-lg uppercase tracking-wider border border-blue-100">
                                    {{ $car->rentDurationLabel() }}
                                </span>
                                @if($car->rent_deposit)
                                <span class="text-[10px] font-black px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg uppercase tracking-wider">
                                    NRs {{ number_format($car->rent_deposit) }} deposit
                                </span>
                                @endif
                            </div>

                            <div class="mt-5 pt-5 border-t border-slate-100 space-y-3">
                                @auth
                                    @if(auth()->user()->hasRole('buyer'))
                                        @if($alreadyRented)
                                            {{-- Already has active/pending rental --}}
                                            <div class="w-full py-3.5 rounded-xl bg-blue-50 border border-blue-200 text-blue-700 text-[12px] font-black uppercase tracking-widest text-center">
                                                ✓ Already Booked
                                            </div>
                                            <a href="{{ route('buyer.rentals.index') }}"
                                                class="block w-full py-3.5 rounded-xl bg-slate-100 text-slate-700 text-[12px] font-black uppercase italic tracking-widest text-center hover:bg-slate-200 transition-all">
                                                View My Rentals
                                            </a>
                                        @elseif($availableForRent === 0)
                                            {{-- All stock units are out on confirmed/active rental --}}
                                            <div class="w-full py-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-700 text-[12px] font-black uppercase tracking-widest text-center">
                                                🚗 Currently On Rental
                                            </div>
                                            <p class="text-center text-[11px] text-slate-400 font-medium mt-1">
                                                All units of this car are currently out on rental. Check back once a rental ends.
                                            </p>
                                        @else
                                            {{-- Rental booking form --}}
                                            <form method="POST" action="{{ route('buyer.rentals.store') }}" id="rentalForm">
                                                @csrf
                                                <input type="hidden" name="car_id" value="{{ $car->id }}">

                                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Your Contact Details</p>

                                                <div class="mb-2.5">
                                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Full Name <span class="text-red-400">*</span></label>
                                                    <input type="text" name="renter_name" required
                                                        value="{{ old('renter_name', auth()->user()->name) }}"
                                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3 text-sm text-slate-800 focus:outline-none focus:border-blue-400 transition-all font-medium @error('renter_name') border-red-400 @enderror">
                                                    @error('renter_name')<p class="text-[10px] text-red-500 font-bold mt-1">{{ $message }}</p>@enderror
                                                </div>

                                                <div class="mb-2.5">
                                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Phone <span class="text-red-400">*</span></label>
                                                    <input type="text" name="renter_phone" required
                                                        value="{{ old('renter_phone', auth()->user()->phone) }}"
                                                        placeholder="98XXXXXXXX"
                                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3 text-sm text-slate-800 placeholder:text-slate-300 focus:outline-none focus:border-blue-400 transition-all font-medium @error('renter_phone') border-red-400 @enderror">
                                                    @error('renter_phone')<p class="text-[10px] text-red-500 font-bold mt-1">{{ $message }}</p>@enderror
                                                </div>

                                                <div class="mb-2.5">
                                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Email <span class="text-red-400">*</span></label>
                                                    <input type="email" name="renter_email" required
                                                        value="{{ old('renter_email', auth()->user()->email) }}"
                                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3 text-sm text-slate-800 focus:outline-none focus:border-blue-400 transition-all font-medium @error('renter_email') border-red-400 @enderror">
                                                    @error('renter_email')<p class="text-[10px] text-red-500 font-bold mt-1">{{ $message }}</p>@enderror
                                                </div>

                                                <div class="border-t border-slate-100 pt-3 mt-3 mb-2.5">
                                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Rental Dates</p>
                                                    <div class="grid grid-cols-2 gap-2">
                                                        <div>
                                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Pickup <span class="text-red-400">*</span></label>
                                                            <input type="date" name="pickup_date" required
                                                                value="{{ old('pickup_date') }}"
                                                                min="{{ today()->format('Y-m-d') }}"
                                                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3 text-sm text-slate-800 focus:outline-none focus:border-blue-400 transition-all font-medium @error('pickup_date') border-red-400 @enderror"
                                                                id="pickup-date-input">
                                                            @error('pickup_date')<p class="text-[10px] text-red-500 font-bold mt-1">{{ $message }}</p>@enderror
                                                        </div>
                                                        <div>
                                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Return <span class="text-red-400">*</span></label>
                                                            <input type="date" name="return_date" required
                                                                value="{{ old('return_date') }}"
                                                                min="{{ today()->addDay()->format('Y-m-d') }}"
                                                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3 text-sm text-slate-800 focus:outline-none focus:border-blue-400 transition-all font-medium @error('return_date') border-red-400 @enderror"
                                                                id="return-date-input">
                                                            @error('return_date')<p class="text-[10px] text-red-500 font-bold mt-1">{{ $message }}</p>@enderror
                                                        </div>
                                                    </div>
                                                    {{-- Live cost preview --}}
                                                    <div id="rental-cost-preview" class="hidden mt-3 p-3 bg-blue-50 border border-blue-100 rounded-xl">
                                                        <div class="flex items-center justify-between">
                                                            <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest">Estimated Total</span>
                                                            <span class="text-base font-black text-blue-700" id="rental-cost-amount">—</span>
                                                        </div>
                                                        <p class="text-[10px] text-blue-400 font-medium mt-0.5" id="rental-cost-days"></p>
                                                    </div>
                                                </div>

                                                <div class="mb-4">
                                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Notes <span class="normal-case font-medium text-slate-300">(optional)</span></label>
                                                    <textarea name="notes" rows="2" placeholder="Any special requests or questions..."
                                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-3 text-sm text-slate-800 placeholder:text-slate-300 focus:outline-none focus:border-blue-400 transition-all font-medium resize-none">{{ old('notes') }}</textarea>
                                                </div>

                                                <button type="submit"
                                                    class="w-full flex items-center justify-center gap-2 py-4 rounded-xl bg-slate-900 text-white text-[13px] font-black uppercase italic tracking-widest hover:bg-blue-600 transition-all shadow-lg">
                                                    📅 Request Rental
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                                </button>
                                            </form>

                                            {{-- Live cost calculator script --}}
                                            <script>
                                            (function(){
                                                const pricePerDay = {{ $car->rent_price_per_day }};
                                                const minDays     = {{ $car->rent_min_days ?? 1 }};
                                                const maxDays     = {{ $car->rent_max_days ?? 'null' }};
                                                const pickupEl    = document.getElementById('pickup-date-input');
                                                const returnEl    = document.getElementById('return-date-input');
                                                const preview     = document.getElementById('rental-cost-preview');
                                                const amountEl    = document.getElementById('rental-cost-amount');
                                                const daysEl      = document.getElementById('rental-cost-days');

                                                function update() {
                                                    if (!pickupEl.value || !returnEl.value) { preview.classList.add('hidden'); return; }
                                                    const pickup = new Date(pickupEl.value);
                                                    const ret    = new Date(returnEl.value);
                                                    const days   = Math.round((ret - pickup) / 86400000);
                                                    if (days <= 0) { preview.classList.add('hidden'); return; }
                                                    const total  = days * pricePerDay;
                                                    amountEl.textContent = 'NRs ' + total.toLocaleString();
                                                    let note = days + ' day' + (days !== 1 ? 's' : '') + ' × NRs ' + pricePerDay.toLocaleString();
                                                    if (maxDays && days > maxDays) note += ' ⚠ exceeds max ' + maxDays + ' days';
                                                    if (days < minDays) note += ' ⚠ min ' + minDays + ' days required';
                                                    daysEl.textContent = note;
                                                    preview.classList.remove('hidden');
                                                    // Update return date min to be after pickup
                                                    returnEl.min = new Date(pickup.getTime() + 86400000).toISOString().split('T')[0];
                                                }
                                                pickupEl.addEventListener('change', update);
                                                returnEl.addEventListener('change', update);
                                            })();
                                            </script>
                                        @endif
                                    @else
                                        <p class="text-center text-[12px] text-slate-400 font-bold">You must be a buyer to rent this car. Log in via a buyer account.</p>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}"
                                        class="block w-full py-4 rounded-xl bg-slate-900 text-white text-[13px] font-black uppercase italic tracking-widest text-center hover:bg-blue-600 transition-all shadow-lg">
                                        Login to Rent
                                    </a>
                                    <a href="{{ route('register') }}"
                                        class="block w-full py-3.5 rounded-xl bg-white border border-slate-200 text-slate-700 text-[12px] font-black uppercase tracking-widest text-center hover:bg-slate-50 transition-all">
                                        Create Free Account
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                    @endif {{-- end isRentable --}}

                    {{-- Tab switching script --}}
                    @if($car->isSaleable() && $car->isRentable())
                    <script>
                    function switchTab(tab) {
                        const buyPanel  = document.getElementById('panel-buy');
                        const rentPanel = document.getElementById('panel-rent');
                        const buyTab    = document.getElementById('tab-buy');
                        const rentTab   = document.getElementById('tab-rent');
                        if (tab === 'buy') {
                            buyPanel.classList.remove('hidden');
                            rentPanel.classList.add('hidden');
                            buyTab.className  = buyTab.className.replace('text-slate-500 hover:bg-slate-50', 'bg-slate-900 text-white');
                            rentTab.className = rentTab.className.replace('bg-slate-900 text-white', 'text-slate-500 hover:bg-slate-50');
                        } else {
                            rentPanel.classList.remove('hidden');
                            buyPanel.classList.add('hidden');
                            rentTab.className  = rentTab.className.replace('text-slate-500 hover:bg-slate-50', 'bg-slate-900 text-white');
                            buyTab.className   = buyTab.className.replace('bg-slate-900 text-white', 'text-slate-500 hover:bg-slate-50');
                        }
                    }
                    // Auto-switch to rent tab if URL hash is #rent-car
                    document.addEventListener('DOMContentLoaded', function() {
                        if (window.location.hash === '#rent-car') switchTab('rent');
                    });
                    </script>
                    @endif

                    {{-- Seller card --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">Listed By</p>
                        <div class="flex items-center gap-3">
                            <div
                                class="w-11 h-11 rounded-xl bg-slate-900 flex items-center justify-center text-white text-[13px] font-black uppercase shrink-0 overflow-hidden">
                                @if($car->seller->profile_photo)
                                    <img src="{{ Storage::url($car->seller->profile_photo) }}" alt="{{ $car->seller->name }}" class="w-full h-full object-cover">
                                @else
                                    {{ strtoupper(substr($car->seller->name, 0, 2)) }}
                                @endif
                            </div>
                            <div>
                                @if ($car->seller->hasRole('business') && $car->seller->businessVerification?->isApproved())
                                    @php $bizName = $car->seller->businessVerification->business_name ?? $car->seller->name; @endphp
                                    <p class="text-[14px] font-black text-slate-900">{{ $bizName }}</p>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <svg class="w-3 h-3 text-[#16a34a]" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <p class="text-[11px] font-bold text-green-600 uppercase tracking-wide">Verified
                                            Business</p>
                                    </div>
                                @else
                                    <p class="text-[14px] font-black text-slate-900">{{ $car->seller->name }}</p>
                                    <p class="text-[11px] font-bold text-green-600 uppercase tracking-wide">
                                        {{ ucfirst($car->seller->getRoleNames()->first() ?? 'seller') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-slate-100 space-y-2 text-[12px] font-bold text-slate-500">
                            <div class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Listed {{ $car->created_at->diffForHumans() }}
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                {{ $car->seller->listedCars()->where('status', 'available')->count() }} active
                                listing{{ $car->seller->listedCars()->where('status', 'available')->count() !== 1 ? 's' : '' }}
                            </div>
                        </div>
                        @if ($car->seller->hasRole('business') && $car->seller->businessVerification?->isApproved())
                            <a href="{{ route('businesses.show', $car->seller->id) }}"
                                class="mt-4 w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-900 text-white rounded-xl text-[12px] font-black uppercase tracking-wider hover:bg-[#4ade80] hover:text-black transition-all duration-300">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
                                </svg>
                                View Business Profile
                            </a>
                        @endif
                    </div>

                    {{-- Share / Compare buttons --}}
                    <div class="flex gap-3">
                        <a href="{{ route('compare_cars', ['cars[]' => $car->id]) }}"
                            class="flex-1 flex items-center justify-center gap-2 py-3 rounded-xl border border-slate-200 text-slate-600 text-[11px] font-black uppercase tracking-widest hover:border-green-400 hover:text-green-700 hover:bg-green-50 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 0v10m0-10a2 2 0 012-2h2a2 2 0 012 2v10a2 2 0 01-2 2h-2a2 2 0 01-2-2" />
                            </svg>
                            Compare
                        </a>
                        <button onclick="copyLink()"
                            class="flex-1 flex items-center justify-center gap-2 py-3 rounded-xl border border-slate-200 text-slate-600 text-[11px] font-black uppercase tracking-widest hover:border-slate-400 hover:bg-slate-50 transition-all"
                            id="shareBtn">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                            </svg>
                            Share
                        </button>
                    </div>

                    <!-- {{-- ── Sidebar ads (priority: Premium → Featured → Standard) ── --}}
                    @if (isset($carDetailAds) && $carDetailAds->isNotEmpty())
                        <x-ads.vertical-sidebar :ads="$carDetailAds" />
                    @endif -->

                </div>
            </div>

            {{-- ── OTHER LISTINGS BY SAME SELLER ──────────────────────── --}}
           @if ($otherListings->isNotEmpty())
    <div class="mt-12">
        <div class="flex items-center gap-3 mb-6">
            <span class="w-8 h-[2px] bg-[#4ade80]"></span>
            <span class="text-[11px] font-black uppercase tracking-widest text-slate-500">
                More from {{ $car->seller->name }}
            </span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach ($otherListings as $other)
                @php
                    // Consistency with your Recently Added drivetrain logic
                    $dtColors = [
                        'ev' => ['badge' => 'bg-green-100 text-green-700', 'label' => '<i class="fa-solid fa-leaf mr-1"></i>EV'],
                        'hybrid' => ['badge' => 'bg-blue-100 text-blue-700', 'label' => '<i class="fa-solid fa-leaf text-[9px]"></i>/<i class="fa-solid fa-gas-pump mr-1"></i>HYBRID'],
                        'petrol' => ['badge' => 'bg-red-100 text-red-700', 'label' => '<i class="fa-solid fa-gas-pump mr-1"></i>PETROL'],
                        'diesel' => ['badge' => 'bg-amber-100 text-amber-700', 'label' => '<i class="fa-solid fa-oil-can mr-1"></i>DIESEL'],
                    ];
                    $dtc = $dtColors[$other->drivetrain] ?? $dtColors['petrol'];
                @endphp

                <a href="{{ route('cars.show', $other) }}"
                    class="group bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md overflow-hidden transition-all flex flex-col">   
                    
                    {{-- Image Area --}}
                    <div class="h-36 bg-slate-100 overflow-hidden relative">
                        @if ($other->primary_image)
                            <img src="{{ Storage::url($other->primary_image) }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                alt="{{ $other->displayName() }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-3xl opacity-20">
                                <i class="fa-solid fa-truck-monster" style="color: #64748b;"></i>
                            </div>
                        @endif

                        {{-- Drivetrain Badge --}}
                        <span class="absolute top-3 left-3 text-[9px] font-black px-2.5 py-1 rounded-full uppercase tracking-tighter {{ $dtc['badge'] }}">
                            {!! $dtc['label'] !!}
                        </span>

                        {{-- Time Ago Badge --}}
                        <div class="absolute bottom-2 right-2 bg-white/90 backdrop-blur-sm px-2 py-0.5 rounded text-[9px] font-bold text-slate-500 border border-slate-100">
                            <i class="fa-regular fa-clock mr-1"></i>{{ $other->created_at->diffForHumans(null, true) }}
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="p-4 flex-1 flex flex-col">
                        <h3 class="text-[14px] font-black text-slate-900 uppercase italic leading-tight group-hover:text-[#16a34a] transition-colors">
                            {{ $other->displayName() }}
                        </h3>
                        
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wide mt-1 flex items-center gap-1">
                            <i class="fa-solid fa-location-dot text-[9px]"></i>
                            {{ $other->location }}
                        </p>

                        <p class="mt-auto pt-3 text-[15px] font-black text-slate-900 italic">
                            @if($other->price)
                                NRs {{ number_format($other->price) }}
                            @elseif($other->rent_price_per_day)
                                NRs {{ number_format($other->rent_price_per_day) }}<span class="text-[10px] font-bold text-slate-400 not-italic uppercase ml-0.5">/ day</span>
                            @endif
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endif

        </div>
    </section>

    {{-- ── JS: gallery + lightbox ─────────────────────────────────────── --}}
    <script>
        if (window.location.hash === '#place-order') {
            document.addEventListener('DOMContentLoaded', () => {
                const el = document.getElementById('place-order');
                if (el) {
                    setTimeout(() => {
                        el.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                        el.classList.add('ring-2', 'ring-violet-400', 'ring-offset-2');
                        setTimeout(() => el.classList.remove('ring-2', 'ring-violet-400', 'ring-offset-2'),
                            2000);
                    }, 300);
                }
            });
        }

        const imagePaths = @json($images->pluck('path')->map(fn($p) => Storage::url($p)));
        let currentIdx = 0;

        function switchImage(idx, url) {
            currentIdx = idx;
            const img = document.getElementById('mainImage');
            img.style.opacity = '0';
            setTimeout(() => {
                img.src = url;
                img.style.opacity = '1';
            }, 150);
            const counter = document.getElementById('imageCounter');
            if (counter) counter.textContent = idx + 1;
            document.querySelectorAll('.thumb-btn').forEach(btn => {
                const active = parseInt(btn.dataset.idx) === idx;
                btn.classList.toggle('border-[#4ade80]', active);
                btn.classList.toggle('opacity-60', !active);
                btn.classList.toggle('border-transparent', !active);
            });
        }

        function openLightbox(idx) {
            currentIdx = idx;
            const lb = document.getElementById('lightbox');
            document.getElementById('lightboxImg').src = imagePaths[idx];
            updateLightboxCounter();
            lb.classList.remove('invisible', 'opacity-0');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            const lb = document.getElementById('lightbox');
            lb.classList.add('opacity-0');
            setTimeout(() => {
                lb.classList.add('invisible');
                document.body.style.overflow = '';
            }, 200);
        }

        function prevImage(e) {
            e.stopPropagation();
            currentIdx = (currentIdx - 1 + imagePaths.length) % imagePaths.length;
            document.getElementById('lightboxImg').src = imagePaths[currentIdx];
            switchImage(currentIdx, imagePaths[currentIdx]);
            updateLightboxCounter();
        }

        function nextImage(e) {
            e.stopPropagation();
            currentIdx = (currentIdx + 1) % imagePaths.length;
            document.getElementById('lightboxImg').src = imagePaths[currentIdx];
            switchImage(currentIdx, imagePaths[currentIdx]);
            updateLightboxCounter();
        }

        function updateLightboxCounter() {
            const el = document.getElementById('lightboxCounter');
            if (el) el.textContent = (currentIdx + 1) + ' / ' + imagePaths.length;
        }

        function copyLink() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                const btn = document.getElementById('shareBtn');
                const orig = btn.innerHTML;
                btn.innerHTML =
                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg> Copied!';
                btn.classList.add('text-green-600', 'border-green-300', 'bg-green-50');
                setTimeout(() => {
                    btn.innerHTML = orig;
                    btn.classList.remove('text-green-600', 'border-green-300', 'bg-green-50');
                }, 2000);
            });
        }

        document.addEventListener('keydown', e => {
            const lb = document.getElementById('lightbox');
            if (!lb.classList.contains('invisible')) {
                if (e.key === 'Escape') closeLightbox();
                if (e.key === 'ArrowLeft') prevImage(e);
                if (e.key === 'ArrowRight') nextImage(e);
            }
        });
    </script>

@endsection