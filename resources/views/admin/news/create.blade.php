@extends('admin.layout')

@section('title', 'Create News Article')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Create News Entry</h1>
            <p class="text-[10px] text-slate-500 font-mono uppercase mt-1 tracking-widest">Drafting New Record</p>
        </div>
        <a href="{{ route('admin.news.index') }}" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-indigo-600 transition-colors">&larr; Back to Archive</a>
    </div>

    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        {{-- Core Content --}}
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase mb-2">Main Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full border-slate-200 rounded-xl font-bold text-slate-700">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase mb-2">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug') }}" placeholder="Auto-generated" class="w-full border-slate-200 rounded-xl bg-slate-50 font-mono text-xs text-slate-400">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-500 uppercase mb-2">Lead Paragraph *</label>
                <textarea name="lead_paragraph" rows="6" required class="w-full border-slate-200 rounded-[1.5rem] text-sm text-slate-600">{{ old('lead_paragraph') }}</textarea>
            </div>
        </div>

        {{-- Author Attribution (Auto-filled from Admin Guard) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 p-8">
                <h3 class="font-black text-slate-400 uppercase tracking-widest text-[10px] mb-6">Author Details</h3>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="w-24">
                            <label class="block text-[10px] font-black text-slate-500 uppercase mb-2">Initials</label>
                            @php
                                $nameParts = explode(' ', Auth::guard('admin')->user()->name);
                                $initials = collect($nameParts)->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
                            @endphp
                            <input type="text" name="author_initials" value="{{ old('author_initials', strtoupper($initials)) }}" maxlength="2" required class="w-full border-slate-200 rounded-xl text-center font-mono font-bold text-indigo-600">
                        </div>
                        <div class="flex-1">
                            <label class="block text-[10px] font-black text-slate-500 uppercase mb-2">Full Name</label>
                            <input type="text" name="author_name" value="{{ old('author_name', Auth::guard('admin')->user()->name) }}" required class="w-full border-slate-200 rounded-xl font-bold">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase mb-2">Role / Designation</label>
                        <input type="text" name="author_role" value="{{ old('author_role') }}" placeholder="e.g. Lead Editor" required class="w-full border-slate-200 rounded-xl font-bold">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 p-8">
                <h3 class="font-black text-slate-400 uppercase tracking-widest text-[10px] mb-6">Hero Media</h3>
                <input type="file" name="hero_image" required class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-indigo-50 file:text-indigo-700">
            </div>
        </div>

        <button type="submit" class="w-full bg-slate-900 text-white font-black py-4 rounded-2xl shadow-xl hover:bg-indigo-600 transition-all uppercase tracking-widest text-xs">Deploy Article</button>
    </form>
</div>
@endsection