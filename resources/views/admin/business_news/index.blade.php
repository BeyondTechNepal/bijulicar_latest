@extends('admin.layout')

@section('title', 'Business News')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Business News</h1>
            <p class="text-xs text-slate-400 font-medium mt-1">News articles published by business accounts</p>
        </div>
        <span class="bg-slate-100 text-slate-600 px-4 py-2 rounded-xl font-black text-[10px] uppercase tracking-widest">
            {{ $articles->total() }} Articles
        </span>
    </div>

    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-xl text-sm font-semibold">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100">
                    <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest">Article</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest">Business</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest">Date</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest">Status</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($articles as $article)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    {{-- Article title + lead --}}
                    <td class="px-6 py-4 max-w-sm">
                        <p class="font-bold text-slate-800 text-sm leading-snug">{{ $article->title }}</p>
                        <p class="text-[11px] text-slate-400 mt-1 line-clamp-2 leading-relaxed">
                            {{ Str::limit(strip_tags($article->lead_paragraph), 120) }}
                        </p>
                    </td>

                    {{-- Business name --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center font-black text-[10px] uppercase flex-shrink-0">
                                {{ strtoupper(substr($article->business_name, 0, 2)) }}
                            </span>
                            <div>
                                <p class="text-xs font-bold text-slate-700">{{ $article->business_name }}</p>
                                @if($article->business)
                                    <p class="text-[10px] text-slate-400">{{ $article->business->email }}</p>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- Date --}}
                    <td class="px-6 py-4">
                        <p class="text-xs font-semibold text-slate-600">{{ $article->created_at->format('M d, Y') }}</p>
                        <p class="text-[10px] text-slate-400 font-mono">{{ $article->created_at->format('h:i A') }}</p>
                    </td>

                    {{-- Status --}}
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest
                            {{ $article->is_published ? 'bg-green-100 text-green-600' : 'bg-amber-100 text-amber-600' }}">
                            {{ $article->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </td>

                    {{-- Delete action --}}
                    <td class="px-6 py-4 text-right">
                        <form action="{{ route('admin.business-news.destroy', $article->slug) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                onclick="return confirm('Delete this business article permanently? This cannot be undone.')"
                                class="text-red-400 hover:text-red-600 font-black text-[10px] uppercase tracking-widest transition-colors">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-3 text-slate-400">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 22h16a2 2 0 002-2V4a2 2 0 00-2-2H8a2 2 0 00-2 2v16a2 2 0 01-2 2zm0 0a2 2 0 01-2-2v-9c0-1.1.9-2 2-2h2"/>
                                <path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8z"/>
                            </svg>
                            <p class="text-sm font-semibold">No business news articles yet</p>
                            <p class="text-xs">Articles published by businesses will appear here.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($articles->hasPages())
        <div class="mt-6">
            {{ $articles->links() }}
        </div>
    @endif
</div>
@endsection