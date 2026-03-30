@extends('frontend.app')

<title>News | BijuliCar</title>

@section('content')
    {{-- News Header --}}
    <section class="relative pt-20 pb-10 lg:pt-32 lg:pb-16 overflow-hidden bg-[#0a0f1e] text-white">
        <div class="absolute inset-0 z-0">
            {{-- <img src="{{ asset('images/news_header.jpg') }}"
                class="w-full h-full object-cover opacity-100 scale-105 blur-[3px]" alt="Automotive News Background"> --}}
            @if ($banner->image)
                <img src="{{ asset('storage/' . $banner->image) }}"
                    class="w-full h-full object-cover opacity-100 scale-105 blur-[3px]" alt="Automotive News Background">
            @endif

            <div class="absolute inset-0 bg-gradient-to-b from-[#0a0f1e]/80 via-[#0a0f1e]/25 to-[#202638]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-10">
                <div class="max-w-3xl">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="w-12 h-[3px] bg-[#4ade80]"></span>
                        <span class="text-[10px] lg:text-[12px] uppercase tracking-[0.5em] text-[#4ade80] font-bold">The
                            Intelligence Hub</span>
                    </div>

                    <h1 class="text-5xl md:text-7xl font-black tracking-tighter uppercase italic leading-[0.8] mb-6">
                        Auto<span class="text-slate-400 block lg:inline lg:ml-4">Intel</span>
                    </h1>

                    <p
                        class="text-slate-400 text-sm lg:text-base font-medium max-w-xl leading-relaxed border-l-2 border-white/10 pl-6">
                        Stay ahead of the curve with expert analysis on <span class="text-white">EV breakthroughs</span>,
                        hybrid efficiency, and the evolving landscape of traditional precision engineering.
                    </p>
                </div>

                <div class="hidden lg:flex flex-col items-end text-right space-y-4">
                    <div class="bg-white/5 border border-white/10 backdrop-blur-md rounded-2xl p-6">
                        <div class="flex items-center justify-end gap-3 mb-1">
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#4ade80] opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-[#4ade80]"></span>
                            </span>
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Live
                                Updates</span>
                        </div>
                        <p class="text-xs font-bold text-white uppercase italic">March 2026 Edition</p>
                    </div>
                </div>
            </div>

            <div class="mt-10 flex flex-wrap gap-3 lg:gap-4 border-t border-white/5 pt-6">
                <button
                    class="px-8 py-3 bg-[#4ade80] text-black rounded-full text-[10px] font-black uppercase tracking-widest italic shadow-lg shadow-[#4ade80]/20 hover:scale-105 transition-transform">
                    Discover
                </button>
                <button
                    class="px-8 py-3 bg-white/5 border border-white/10 hover:border-[#4ade80]/50 rounded-full text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-white transition-all">
                    Electric
                </button>
                <button
                    class="px-8 py-3 bg-white/5 border border-white/10 hover:border-[#4ade80]/50 rounded-full text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-white transition-all">
                    Hybrid
                </button>
                <button
                    class="px-8 py-3 bg-white/5 border border-white/10 hover:border-[#4ade80]/50 rounded-full text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-white transition-all">
                    Markets
                </button>
            </div>
        </div>
        <div
            class="absolute bottom-6 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 animate-bounce opacity-50 hover:opacity-100 transition-opacity">
            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-white/50">Scroll</span>
            <div class="w-5 h-8 border-2 border-white/30 rounded-full flex justify-center p-1">
                <div class="w-1 h-2 bg-[#4ade80] rounded-full"></div>
            </div>
        </div>
    </section>

    <div class="space-y-32 pb-24">
        @foreach ($articles as $article)
            <section class="news-article">
                {{-- Header --}}
                <header class="pt-16 pb-12 max-w-4xl mx-auto px-6 text-center">
                    <div class="flex justify-center gap-2 mb-6">
                        @if ($article->badge_text)
                            <span
                                class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">
                                {{ $article->badge_text }}
                            </span>
                        @endif
                        @if ($article->read_time)
                            <span class="text-slate-400 text-[10px] font-black uppercase tracking-widest self-center">
                                {{ $article->read_time }}
                            </span>
                        @endif
                    </div>

                    <h1 class="text-4xl md:text-6xl font-black text-slate-900 leading-[1.1] tracking-tight mb-8">
                        {{ $article->title }}
                    </h1>

                    <div class="flex items-center justify-center gap-4">
                        @if ($article->author_image)
                            <img src="{{ asset('storage/' . $article->author_image) }}"
                                class="w-10 h-10 rounded-full object-cover">
                        @endif
                        <div class="text-left">
                            <p class="text-sm font-bold text-slate-900 m-0 leading-tight">{{ $article->author_name }}</p>
                            <p class="text-xs text-slate-400 m-0">{{ $article->author_role }}</p>
                        </div>
                    </div>
                </header>

                {{-- Hero Image --}}
                @if ($article->hero_image)
                    <div class="max-w-7xl mx-auto px-6 mb-16">
                        <div class="rounded-[2.5rem] overflow-hidden shadow-2xl h-[500px] md:h-[700px]">
                            <img src="{{ asset('storage/' . $article->hero_image) }}" class="w-full h-full object-cover">
                        </div>
                        @if ($article->hero_caption)
                            <p class="text-center text-slate-400 text-xs mt-4 italic">{{ $article->hero_caption }}</p>
                        @endif
                    </div>
                @endif

                {{-- Main Layout --}}
                <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-12 gap-12 relative">

                    {{-- Left Sidebar (Social) --}}
                    <aside class="hidden lg:block lg:col-span-1">
                        <div class="sticky top-32 space-y-6">
                            <button
                                class="w-12 h-12 rounded-full border border-slate-100 flex items-center justify-center hover:bg-slate-50 transition-all">FB</button>
                            <button
                                class="w-12 h-12 rounded-full border border-slate-100 flex items-center justify-center hover:bg-slate-50 transition-all">TW</button>
                            <button
                                class="w-12 h-12 rounded-full border border-slate-100 flex items-center justify-center hover:bg-slate-50 transition-all">🔗</button>
                        </div>
                    </aside>

                    {{-- Content Column --}}
                    <article class="lg:col-span-7 prose prose-slate prose-lg max-w-none pb-14">
                        @if ($article->content_html)
                            <p class="drop-cap">
                                {{ $article->content_html }}
                            </p>
                        @endif

                        @if ($article->quote)
                            <blockquote class="my-12 py-8 border-y-2 border-slate-900">
                                <p class="text-3xl italic text-slate-900 leading-tight">
                                    "{{ $article->quote }}"
                                </p>
                            </blockquote>
                        @endif

                        @if ($article->title2)
                            <h2 class="text-3xl font-black text-slate-900 py-8">{{ $article->title2 }}</h2>
                        @endif

                        @if ($article->content_html2)
                            <p>
                                {{ $article->content_html2 }}
                            </p>
                        @endif

                        <div class="grid grid-cols-2 gap-4 my-5">
                            @foreach ($article->content_images2 ?? [] as $image)
                                <img src="{{ $image }}" class="rounded-3xl h-64 w-full object-cover">
                            @endforeach
                        </div>

                        @if ($article->title3)
                            <h2 class="text-3xl font-black text-slate-900 py-5">{{ $article->title3 }}</h2>
                        @endif

                        @if ($article->content_html3)
                            <p>
                                {{ $article->content_html3 }}
                            </p>
                        @endif
                    </article>

                    {{-- Right Sidebar (Specs & Related) --}}
                    <aside class="lg:col-span-4 space-y-12">
                        @if ($article->vehicle_specs)
                            <div class="bg-slate-50 rounded-[2rem] p-8 border border-slate-100">
                                <h4 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-6">Vehicle Specs
                                </h4>
                                <div class="space-y-4">
                                    @foreach ($article->vehicle_specs as $spec)
                                        <div class="flex justify-between border-b border-slate-200 pb-2 last:border-0">
                                            <span class="text-sm font-medium text-slate-500">{{ $spec['key'] }}</span>
                                            <span class="text-sm font-bold text-slate-900">{{ $spec['value'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="space-y-6">
                            <h4 class="text-xs font-black uppercase tracking-widest text-slate-400">Keep Reading</h4>
                            {{-- This section can be populated with other articles --}}
                            @foreach ($article->related_articles as $related)
                                <div class="flex gap-4 group cursor-pointer">
                                    <img src="{{ $related['image'] }}" class="w-20 h-20 rounded-2xl object-cover">
                                    <div>
                                        <h5 class="text-sm font-bold group-hover:text-green-600 transition-colors">
                                            {{ $related['title'] }}</h5>
                                        <p class="text-[10px] text-slate-400 uppercase font-black mt-1">
                                            {{ $related['category'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </aside>
                </div>
            </section>
            @if (!$loop->last)
                <hr class="border-slate-100 max-w-7xl mx-auto px-6">
            @endif
        @endforeach

        {{-- Pagination --}}
        <div class="mt-12 flex justify-center">
            {{ $articles->links() }}
        </div>
    </div>

    <style>
        h1,
        h2,
        h3 {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        article p {
            font-family: 'Source Serif 4', serif;
            font-size: 1.2rem;
            line-height: 1.8;
            color: #334155;
        }

        .progress-bar {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            background: #16a34a;
            width: 0%;
            z-index: 100;
            transition: width 0.1s;
        }

        .drop-cap::first-letter {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 800;
            font-size: 4rem;
            float: left;
            margin-right: 0.5rem;
            line-height: 1;
            color: #0f172a;
        }
    </style>
@endsection
