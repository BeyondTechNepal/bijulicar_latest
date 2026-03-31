@extends('admin.layout')

@section('title', 'Edit News Article')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Edit News Entry</h1>
            <p class="text-[10px] text-slate-500 font-mono uppercase mt-1 tracking-widest">Modified by: {{ $article->admin->name }}</p>
        </div>
    </div>

    <form action="{{ route('admin.news.update', $article->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 p-8 space-y-6">
            <div>
                <label class="block text-[10px] font-black text-slate-500 uppercase mb-2">Main Title</label>
                <input type="text" name="title" value="{{ old('title', $article->title) }}" required class="w-full border-slate-200 rounded-xl font-bold text-slate-700">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-slate-50 rounded-2xl">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase mb-2">Current Hero Image</p>
                    <img src="{{ asset('storage/' . $article->hero_image) }}" class="h-20 rounded-lg shadow-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Replace Image</label>
                    <input type="file" name="hero_image" class="text-xs text-slate-500">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-500 uppercase mb-2">Author Role</label>
                <input type="text" name="author_role" value="{{ old('author_role', $article->author_role) }}" required class="w-full border-slate-200 rounded-xl font-bold">
            </div>
        </div>

        <button type="submit" class="w-full bg-indigo-600 text-white font-black py-4 rounded-2xl shadow-xl hover:bg-slate-900 transition-all uppercase tracking-widest text-xs">Update Article</button>
    </form>
</div>
@endsection