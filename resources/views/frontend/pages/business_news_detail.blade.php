@extends('frontend.app')

<title>{{ $news->title }} | BijuliCar</title>

@section('content')

{{-- ── Hero Banner ───────────────────────────────────────────────────────── --}}
<section class="relative pt-20 pb-10 lg:pt-32 lg:pb-16 overflow-hidden bg-[#0a0f1e] text-white">
    <div class="absolute inset-0 z-0">
        @if($news->hero_image)
            <img src="{{ asset('storage/' . $news->hero_image) }}"
                class="w-full h-full object-cover opacity-30 scale-105 blur-[2px]" alt="{{ $news->title }}">
        @elseif($banner?->image)
            <img src="{{ asset('storage/' . $banner->image) }}"
                class="w-full h-full object-cover opacity-30 scale-105 blur-[2px]" alt="Banner">
        @endif
        <div class="absolute inset-0 bg-gradient-to-b from-[#0a0f1e]/70 via-[#0a0f1e]/40 to-[#0a0f1e]"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 relative z-10">
        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-xs font-bold text-slate-500 mb-8">
            <a href="{{ route('home') }}" class="hover:text-slate-300 transition-colors">Home</a>
            <span>/</span>
            <a href="{{ route('news') }}" class="hover:text-slate-300 transition-colors">News</a>
            <span>/</span>
            <span class="text-slate-400">{{ Str::limit($news->title, 40) }}</span>
        </div>

        {{-- Business badge --}}
        <div class="flex items-center gap-3 mb-5">
            <span class="w-10 h-[2px] bg-[#a855f7]"></span>
            <a href="{{ route('businesses.show', $news->business->id) }}"
                class="text-[11px] font-black uppercase tracking-widest text-[#a855f7] hover:text-purple-300 transition-colors">
                {{ $news->business_name }}
            </a>
            <span class="text-[10px] font-black uppercase tracking-widest text-purple-500 bg-purple-500/10 border border-purple-500/20 px-2.5 py-0.5 rounded-full">
                Business News
            </span>
        </div>

        {{-- Title --}}
        <h1 class="text-4xl md:text-6xl font-black tracking-tighter uppercase italic leading-[0.9] mb-6 max-w-4xl">
            {{ $news->title }}
        </h1>

        {{-- Meta row --}}
        <div class="flex flex-wrap items-center gap-4 text-xs font-bold text-slate-400">
            <span class="flex items-center gap-2">
                <span class="w-7 h-7 rounded-full bg-purple-500/20 border border-purple-500/30 flex items-center justify-center text-[10px] font-black text-purple-400 uppercase">
                    {{ strtoupper(substr($news->author_name, 0, 2)) }}
                </span>
                {{ $news->author_name }}
                @if($news->author_role)
                    <span class="text-slate-600">·</span>
                    <span class="text-slate-500">{{ $news->author_role }}</span>
                @endif
            </span>
            <span class="text-slate-600">·</span>
            <span>{{ $news->created_at->format('d M Y') }}</span>
            @if($news->newscategory)
                <span class="text-slate-600">·</span>
                <span class="text-purple-400 uppercase tracking-wider">{{ $news->newscategory->name }}</span>
            @endif
        </div>
    </div>
</section>

{{-- ── Article Body ──────────────────────────────────────────────────────── --}}
<section class="bg-white py-16 lg:py-24">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid lg:grid-cols-12 gap-12 lg:gap-16">

            {{-- ── Main column ──────────────────────────────────────────── --}}
            <article class="lg:col-span-8">

                {{-- Hero image --}}
                @if($news->hero_image)
                    <figure class="mb-10 -mx-6 lg:mx-0">
                        <img src="{{ asset('storage/' . $news->hero_image) }}"
                            alt="{{ $news->title }}"
                            class="w-full max-h-[480px] object-cover rounded-none lg:rounded-2xl">
                        @if($news->figure_caption)
                            <figcaption class="text-xs text-slate-400 font-medium text-center mt-3 px-6 lg:px-0">
                                {{ $news->figure_caption }}
                            </figcaption>
                        @endif
                    </figure>
                @endif

                {{-- Lead paragraph --}}
                <p class="text-lg lg:text-xl font-medium text-slate-700 leading-relaxed border-l-4 border-[#a855f7] pl-6 mb-10">
                    {{ $news->lead_paragraph }}
                </p>

                {{-- Section 1 --}}
                @if($news->section_1_title || $news->section_1_content)
                    <div class="mb-10">
                        @if($news->section_1_title)
                            <h2 class="text-2xl font-black text-slate-900 uppercase italic tracking-tight mb-4">
                                {{ $news->section_1_title }}
                            </h2>
                        @endif
                        @if($news->section_1_content)
                            <div class="text-base text-slate-600 leading-relaxed space-y-4">
                                @foreach(explode("\n", $news->section_1_content) as $para)
                                    @if(trim($para))
                                        <p>{{ $para }}</p>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Pull quote --}}
                @if($news->quote_text)
                    <blockquote class="my-12 border-l-4 border-[#a855f7] pl-8 py-2">
                        <p class="text-2xl font-black italic text-slate-800 leading-snug tracking-tight">
                            "{{ $news->quote_text }}"
                        </p>
                        @if($news->quote_author)
                            <cite class="block mt-4 text-sm font-black text-purple-500 uppercase tracking-widest not-italic">
                                — {{ $news->quote_author }}
                            </cite>
                        @endif
                    </blockquote>
                @endif

                {{-- Section 2 --}}
                @if($news->section_2_title || $news->section_2_content)
                    <div class="mb-10">
                        @if($news->section_2_title)
                            <h2 class="text-2xl font-black text-slate-900 uppercase italic tracking-tight mb-4">
                                {{ $news->section_2_title }}
                            </h2>
                        @endif
                        @if($news->section_2_content)
                            <div class="text-base text-slate-600 leading-relaxed space-y-4">
                                @foreach(explode("\n", $news->section_2_content) as $para)
                                    @if(trim($para))
                                        <p>{{ $para }}</p>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Back to business profile CTA --}}
                <div class="mt-12 p-6 bg-slate-50 border border-slate-200 rounded-2xl flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-0.5">Published by</p>
                        <p class="text-base font-black text-slate-900 uppercase italic tracking-tight">
                            {{ $news->business_name }}
                        </p>
                    </div>
                    <a href="{{ route('businesses.show', $news->business->id) }}"
                        class="shrink-0 inline-flex items-center gap-2 bg-slate-900 text-white px-4 py-2.5 rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-purple-700 transition-all">
                        View Business
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

            </article>

            {{-- ── Sidebar ───────────────────────────────────────────────── --}}
            <aside class="lg:col-span-4">
                <div class="sticky top-24 space-y-8">

                    {{-- ── Sidebar ads ── --}}
                    @if(isset($newsDetailAds) && $newsDetailAds->isNotEmpty())
                        <x-ads.vertical-sidebar :ads="$newsDetailAds" />
                    @endif

                    {{-- Recent articles --}}
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 border-b border-slate-100 pb-3">
                            Recent Articles
                        </p>
                        <div class="space-y-4">
                            @foreach($recentArticles as $item)
                                <a href="{{ route($item['route'], $item['slug']) }}"
                                    class="flex items-start gap-3 group">
                                    @if($item['hero_image'])
                                        <img src="{{ asset('storage/' . $item['hero_image']) }}"
                                            class="w-14 h-12 rounded-xl object-cover shrink-0 border border-slate-100 group-hover:opacity-80 transition-opacity"
                                            alt="">
                                    @else
                                        <div class="w-14 h-12 rounded-xl bg-slate-100 shrink-0 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 12h6"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        @if($item['type'] === 'business')
                                            <span class="text-[9px] font-black uppercase tracking-widest text-purple-500 block mb-0.5">Business</span>
                                        @endif
                                        <p class="text-sm font-black text-slate-800 leading-tight line-clamp-2 group-hover:text-slate-600 transition-colors uppercase italic tracking-tight">
                                            {{ $item['title'] }}
                                        </p>
                                        <p class="text-[10px] text-slate-400 font-medium mt-1">
                                            {{ \Carbon\Carbon::parse($item['created_at'])->format('d M Y') }}
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Back to news --}}
                    <a href="{{ route('news') }}"
                        class="flex items-center justify-center gap-2 w-full border-2 border-slate-200 text-slate-500 hover:border-slate-900 hover:text-slate-900 rounded-xl py-3 text-[11px] font-black uppercase tracking-widest transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        All News
                    </a>
                </div>
            </aside>

        </div>
    </div>
</section>

@endsection