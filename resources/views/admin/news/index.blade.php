@extends('admin.layout')

@section('title', 'News Archive')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase">News Archive</h1>
        <a href="{{ route('admin.news.create') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest hover:shadow-lg transition-all">+ New Entry</a>
    </div>

    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100">
                    <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest">Article</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest">Author</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest">Status</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($articles as $article)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <p class="font-bold text-slate-700 text-sm">{{ $article->title }}</p>
                        <p class="text-[10px] text-slate-400 font-mono">{{ $article->created_at->format('M d, Y') }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-md bg-slate-200 text-[10px] flex items-center justify-center font-black text-slate-600">{{ $article->author_initials }}</span>
                            <span class="text-xs font-bold text-slate-600">{{ $article->author_name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $article->is_published ? 'bg-green-100 text-green-600' : 'bg-amber-100 text-amber-600' }}">
                            {{ $article->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-3">
                        <a href="{{ route('admin.news.edit', $article->slug) }}" class="text-indigo-600 hover:text-indigo-900 font-black text-[10px] uppercase tracking-widest">Edit</a>
                        <form action="{{ route('admin.news.destroy', $article->slug) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Archive permanently?')" class="text-red-400 hover:text-red-600 font-black text-[10px] uppercase tracking-widest">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $articles->links() }}
    </div>
</div>
@endsection