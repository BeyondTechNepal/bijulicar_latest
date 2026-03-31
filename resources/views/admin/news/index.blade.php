@extends('admin.layout')
@section('title', 'News Articles')

@section('content')
    <div class="space-y-6">
        {{-- Top Action Bar --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight">News Archives</h2>
                <p class="text-xs text-slate-500 font-medium">
                    Managing published content and articles for the <span class="text-indigo-600 font-bold">public</span> feed.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.news.create') }}"
                    class="bg-slate-900 text-white px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-indigo-600 transition-all shadow-sm flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Entry
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-widest p-4 rounded-2xl flex items-center gap-3 animate-fade-in shadow-sm">
                <div class="w-5 h-5 rounded-full bg-emerald-500 flex items-center justify-center text-white">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                {{ session('success') }}
            </div>
        @endif

        {{-- Main Inventory Card --}}
        <div class="bg-white border border-slate-200 rounded-[2rem] shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></div>
                    <h3 class="font-black text-slate-700 text-sm uppercase tracking-tighter">Active Article Records</h3>
                </div>
                <span class="px-3 py-1 bg-white border border-slate-200 rounded-full text-[10px] font-bold text-slate-400 font-mono shadow-sm">
                    {{ $articles->total() }} Total_Entries
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Article Info</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Author_Ref</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Publication Status</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Timestamp</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">System Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($articles as $article)
                            <tr class="group hover:bg-slate-50/80 transition-all">
                                {{-- Title & ID --}}
                                <td class="px-8 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] text-indigo-500 font-mono font-bold">#{{ str_pad($article->id, 4, '0', STR_PAD_LEFT) }}</span>
                                        <span class="text-sm font-bold text-slate-700 group-hover:text-indigo-600 transition-colors">
                                            {{ $article->title }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Author - Updated to use DB columns --}}
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        {{-- Note: DB has no author_image column, using Hero as placeholder or initials --}}
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-[10px] text-slate-400 font-bold border border-slate-200">
                                            {{ $article->author_initials ?? substr($article->author_name, 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-xs font-bold text-slate-700">{{ $article->author_name }}</span>
                                            <span class="text-[9px] text-slate-400 uppercase font-medium">{{ $article->author_role }}</span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Status Badge - Updated for is_published column --}}
                                <td class="px-8 py-5 text-center">
                                    @if($article->is_published)
                                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-50 border border-emerald-100 rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span class="text-[9px] font-black text-emerald-700 uppercase">Live</span>
                                        </div>
                                    @else
                                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-amber-50 border border-amber-100 rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            <span class="text-[9px] font-black text-amber-700 uppercase">Draft</span>
                                        </div>
                                    @endif
                                </td>

                                {{-- Date --}}
                                <td class="px-8 py-5 text-right">
                                    <div class="text-[10px] font-bold text-slate-600">
                                        {{ $article->created_at ? $article->created_at->format('M d, Y') : '---' }}
                                    </div>
                                    <div class="text-[9px] text-slate-400 uppercase font-medium mt-0.5">
                                        {{ $article->created_at ? $article->created_at->diffForHumans() : '' }}
                                    </div>
                                </td>

                                {{-- Actions --}}
                                <td class="px-8 py-5">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- Note: Standard route parameter is $article->slug because of your model setup --}}
                                        <a href="{{ route('admin.news.edit', $article) }}"
                                            class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>

                                        <form action="{{ route('admin.news.destroy', $article) }}" method="POST" onsubmit="return confirm('Archive this record?')">
                                            @csrf @method('DELETE')
                                            <button class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-16 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-12 h-12 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                        </svg>
                                        <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest mt-2">
                                            No Articles Found in Database
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer Context --}}
            <div class="px-8 py-4 bg-slate-50/80 border-t border-slate-100 flex justify-between items-center">
                <div class="flex items-center gap-2 text-[10px] text-slate-400 font-bold uppercase tracking-tight">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    System Note: Images are optimized for web delivery.
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $articles->links() }}
        </div>
    </div>
@endsection