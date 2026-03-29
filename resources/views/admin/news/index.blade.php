@extends('admin.layout')
@section('title', 'News Articles')

@section('content')

<div class="p-4 lg:p-6">
    {{-- Modern Header --}}
    <div class="bg-slate-900 border border-slate-800 rounded-2xl px-5 py-3 mb-6 flex items-center justify-between shadow-md">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-indigo-500/10 border border-indigo-500/20 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                    </path>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-black text-white uppercase tracking-widest">Editorial Hub</p>
                <p class="text-[9px] text-slate-500 font-medium leading-none">Managing published and draft articles.</p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div class="hidden md:flex items-center gap-2 border-r border-slate-800 pr-4">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">
                    {{ $articles->total() }} Articles
                </span>
            </div>
            <a href="{{ route('admin.news.create') }}" 
                class="bg-indigo-600 hover:bg-indigo-500 text-white text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-lg transition-all shadow-lg shadow-indigo-500/20">
                + Create New
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-600 text-[10px] font-bold uppercase tracking-widest p-3 rounded-xl mb-6 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Compact Table Card --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-5 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest">Article Details</th>
                        <th class="px-5 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest">Author</th>
                        <th class="px-5 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-5 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right">Published At</th>
                        <th class="px-5 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">Manage</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($articles as $article)
                        <tr class="group hover:bg-slate-50/50 transition-all">
                            {{-- ID & Title --}}
                            <td class="px-5 py-4">
                                <div class="flex flex-col">
                                    <span class="text-[10px] text-slate-400 font-mono mb-0.5">#{{ $loop->iteration }}</span>
                                    <span class="text-xs font-bold text-slate-700 leading-tight group-hover:text-indigo-600 transition-colors">
                                        {{ $article->title }}
                                    </span>
                                </div>
                            </td>

                            {{-- Author --}}
                            <td class="px-5 py-4">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-700">{{ $article->author_name }}</span>
                                    <span class="text-[9px] text-slate-400 uppercase tracking-tighter">{{ $article->author_role ?? 'Staff Writer' }}</span>
                                </div>
                            </td>

                            {{-- Status Badge --}}
                            <td class="px-5 py-4">
                                <span class="text-[9px] font-black uppercase tracking-tighter text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">
                                    {{ $article->badge_text ?? 'Standard' }}
                                </span>
                            </td>

                            {{-- Date --}}
                            <td class="px-5 py-4 text-right">
                                <div class="text-[10px] font-bold text-slate-500 uppercase">
                                    {{ $article->published_at?->format('M d, Y') ?? 'Draft' }}
                                </div>
                                <div class="text-[9px] text-slate-300">
                                    {{ $article->published_at?->diffForHumans() }}
                                </div>
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.news.edit', $article) }}" 
                                        class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    
                                    <form action="{{ route('admin.news.destroy', $article) }}" method="POST" 
                                        onsubmit="return confirm('Archive this record?')">
                                        @csrf @method('DELETE')
                                        <button class="p-1.5 text-slate-300 hover:text-rose-600 hover:bg-rose-50 rounded-md transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center">
                                <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.2em]">No Articles Stored in Buffer</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Slim Pagination --}}
    <div class="mt-6">
        {{ $articles->links() }}
    </div>
</div>

@endsection