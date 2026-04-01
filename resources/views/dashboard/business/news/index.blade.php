@extends('dashboard.business.layout')
@section('title', 'My News')
@section('page-title', 'My News')

@section('content')

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Business Portal</p>
            <p class="text-sm font-bold text-slate-600 mt-0.5">Write and publish news articles for your business.</p>
        </div>
        <a href="{{ route('business.news.create') }}"
            class="inline-flex items-center gap-2 bg-slate-900 text-white px-4 py-2.5 rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-purple-700 transition-all">
            + New Article
        </a>
    </div>

    @if($articles->isNotEmpty())
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">

        {{-- Table header --}}
        <div class="grid grid-cols-12 gap-4 px-6 py-3 border-b border-slate-100 bg-slate-50">
            <div class="col-span-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Article</div>
            <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Category</div>
            <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Date</div>
            <div class="col-span-1 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</div>
            <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Actions</div>
        </div>

        @foreach($articles as $article)
        <div class="grid grid-cols-12 gap-4 px-6 py-4 border-b border-slate-100 last:border-0 items-center hover:bg-slate-50/50 transition-colors">

            {{-- Article title + image --}}
            <div class="col-span-5 flex items-center gap-3">
                @if($article->hero_image)
                    <img src="{{ Storage::url($article->hero_image) }}" alt="{{ $article->title }}"
                        class="w-12 h-10 rounded-xl object-cover shrink-0 border border-slate-200">
                @else
                    <div class="w-12 h-10 bg-purple-50 border border-purple-100 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 12h6m-6-4h2" />
                        </svg>
                    </div>
                @endif
                <div>
                    <p class="text-sm font-black text-slate-900 uppercase italic tracking-tight leading-tight line-clamp-1">
                        {{ $article->title }}
                    </p>
                    <p class="text-[11px] text-slate-400 font-medium mt-0.5 line-clamp-1">
                        {{ Str::limit(strip_tags($article->lead_paragraph), 60) }}
                    </p>
                </div>
            </div>

            {{-- Category --}}
            <div class="col-span-2">
                @if($article->newscategory)
                    <span class="text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider bg-purple-50 text-purple-700">
                        {{ $article->newscategory->name }}
                    </span>
                @else
                    <span class="text-[10px] text-slate-400 font-medium">—</span>
                @endif
            </div>

            {{-- Date --}}
            <div class="col-span-2">
                <p class="text-[11px] text-slate-500 font-medium">
                    {{ $article->created_at->format('d M Y') }}
                </p>
            </div>

            {{-- Status --}}
            <div class="col-span-1">
                @if($article->is_published)
                    <span class="text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider bg-green-100 text-green-700">Live</span>
                @else
                    <span class="text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider bg-slate-100 text-slate-500">Draft</span>
                @endif
            </div>

            {{-- Actions --}}
            <div class="col-span-2 flex items-center gap-2">
                @if($article->is_published)
                    <a href="{{ route('business.news.show', $article->slug) }}"
                        target="_blank"
                        class="text-[10px] font-black px-2.5 py-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors uppercase tracking-wider">
                        View
                    </a>
                @endif
                <a href="{{ route('business.news.edit', $article->slug) }}"
                    class="text-[10px] font-black px-2.5 py-1.5 rounded-lg bg-purple-50 text-purple-700 hover:bg-purple-100 transition-colors uppercase tracking-wider">
                    Edit
                </a>
                <form method="POST" action="{{ route('business.news.destroy', $article->slug) }}"
                    onsubmit="return confirm('Delete this article permanently?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="text-[10px] font-black px-2.5 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors uppercase tracking-wider">
                        Del
                    </button>
                </form>
            </div>

        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($articles->hasPages())
        <div class="mt-6">{{ $articles->links() }}</div>
    @endif

    @else

    {{-- Empty state --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-16 text-center">
        <div class="w-16 h-16 bg-purple-50 border border-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 12h6m-6-4h2" />
            </svg>
        </div>
        <p class="text-slate-900 font-black uppercase italic tracking-tight text-lg mb-1">No articles yet</p>
        <p class="text-slate-400 text-sm font-medium mb-6">Publish your first news article to showcase it on your business profile and the public news feed.</p>
        <a href="{{ route('business.news.create') }}"
            class="inline-flex items-center gap-2 bg-slate-900 text-white px-5 py-2.5 rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-purple-700 transition-all">
            + Write First Article
        </a>
    </div>

    @endif

@endsection