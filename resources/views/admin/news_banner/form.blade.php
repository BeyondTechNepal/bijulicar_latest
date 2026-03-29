@extends('admin.layout')
@section('title', isset($news_banner) ? 'Edit News Banner' : 'Create News Banner')
@section('page-title', 'Banner Configuration')

@section('content')
<div class="p-4 lg:p-6 max-w-4xl">
    {{-- High-Level Header --}}
    <div class="bg-slate-900 border border-slate-800 rounded-[1.5rem] px-6 py-4 mb-8 flex items-center justify-between shadow-lg">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-indigo-500/10 border border-indigo-500/20 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-black text-white uppercase tracking-widest">Media Deployment</p>
                <p class="text-[11px] text-slate-400 font-medium">Configuring visual broadcast layers for the system front-end.</p>
            </div>
        </div>
        <a href="{{ route('admin.news_banner.index') }}" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-white transition-colors">
            ← Back to Assets
        </a>
    </div>

    {{-- Main Form Card --}}
    <div class="bg-white border border-slate-200 rounded-[2rem] shadow-sm overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-black text-slate-700 text-sm uppercase tracking-tighter">Banner Metadata</h3>
            <p class="text-[10px] text-slate-400 font-mono mt-0.5">SOURCE: MULTIPART_FORM // ASSET_TYPE: IMAGE</p>
        </div>

        <form method="POST" 
              action="{{ isset($news_banner) ? route('admin.news_banner.update', $news_banner) : route('admin.news_banner.store') }}" 
              enctype="multipart/form-data"
              class="p-8">

            @csrf
            @if (isset($news_banner)) @method('PUT') @endif

            <div class="space-y-8">
                {{-- Title Input --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Asset Identity (Title)</label>
                    <input type="text" name="title" 
                           value="{{ old('title', $news_banner->title ?? '') }}" 
                           placeholder="Enter banner headline..."
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                </div>

                {{-- Image Upload Section --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Graphic Payload (Image)</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                        {{-- Upload Box --}}
                        <div class="relative group">
                            <input type="file" name="image" id="image-upload" class="hidden">
                            <label for="image-upload" class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-slate-200 rounded-[1.5rem] bg-slate-50 hover:bg-slate-100 hover:border-indigo-300 transition-all cursor-pointer group">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 text-slate-300 group-hover:text-indigo-500 transition-colors mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:text-slate-600 transition-colors">Select New Asset</p>
                                </div>
                            </label>
                        </div>

                        {{-- Current Preview --}}
                        @if (isset($news_banner) && $news_banner->image)
                            <div class="space-y-2">
                                <p class="text-[9px] font-black text-slate-300 uppercase italic">Current Active Asset Preview</p>
                                <div class="relative h-48 rounded-[1.5rem] overflow-hidden border border-slate-200 shadow-inner">
                                    <img src="{{ asset('storage/' . $news_banner->image) }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 to-transparent"></div>
                                </div>
                            </div>
                        @else
                            <div class="h-48 rounded-[1.5rem] border border-slate-100 bg-slate-50/30 flex items-center justify-center italic text-slate-300 text-[10px] uppercase">
                                No active preview
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="mt-12 pt-8 border-t border-slate-100 flex items-center justify-between">
                <p class="text-[10px] text-slate-400 font-mono italic max-w-xs">Note: Image dimensions should ideally be 1920x600 for optimal resolution.</p>
                <button type="submit" 
                        class="bg-indigo-600 text-white px-10 py-3.5 rounded-xl text-xs font-black uppercase tracking-[0.2em] hover:bg-indigo-500 transition-all shadow-xl shadow-indigo-900/20 active:scale-95">
                    {{ isset($news_banner) ? 'Synchronize Asset' : 'Deploy Banner' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection