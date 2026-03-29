@extends('admin.layout')
@section('title', isset($contact_banner) ? 'Edit Banner' : 'Create Banner')
@section('page-title', 'Banner Editor')

@section('content')
<div class="p-4 lg:p-6 max-w-4xl">
    {{-- Slim Header --}}
    <div class="bg-slate-900 border border-slate-800 rounded-2xl px-5 py-3 mb-6 flex items-center justify-between shadow-md">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-indigo-500/10 border border-indigo-500/20 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-black text-white uppercase tracking-widest">Asset Configuration</p>
                <p class="text-[9px] text-slate-500 font-medium leading-none">{{ isset($contact_banner) ? 'Modify existing asset' : 'Deploy new visual component' }}</p>
            </div>
        </div>
        <a href="{{ route('admin.contact_banner.index') }}" class="text-[9px] font-black text-slate-400 uppercase tracking-widest hover:text-white transition-colors">
            ← Back to List
        </a>
    </div>

    {{-- Form Card --}}
    <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
        <form method="POST" 
              action="{{ isset($contact_banner) ? route('admin.contact_banner.update', $contact_banner) : route('admin.contact_banner.store') }}" 
              enctype="multipart/form-data" 
              class="p-8">
            
            @csrf
            @if(isset($contact_banner)) @method('PUT') @endif

            <div class="space-y-6">
                {{-- Title Input --}}
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Banner Title</label>
                    <input type="text" name="title" 
                           value="{{ old('title', $contact_banner->title ?? '') }}" 
                           placeholder="Enter high-level title..."
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all placeholder:text-slate-300">
                </div>

                {{-- Image Upload --}}
                <div class="grid md:grid-cols-2 gap-6 items-center">
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Visual Asset</label>
                        <div class="relative group">
                            <input type="file" name="image" 
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center group-hover:border-indigo-400 transition-colors">
                                <svg class="w-6 h-6 text-slate-300 mx-auto mb-2 group-hover:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 00-2 2z"></path>
                                </svg>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Click to Upload New</span>
                            </div>
                        </div>
                    </div>

                    @if(isset($contact_banner))
                        <div class="relative">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Active Preview</label>
                            <div class="rounded-2xl overflow-hidden border border-slate-200 h-28 bg-slate-100">
                                <img src="{{ asset('storage/' . $contact_banner->image) }}" class="w-full h-full object-cover">
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Status Toggle --}}
                <div class="pt-4 border-t border-slate-100">
                    <label class="inline-flex items-center cursor-pointer group">
                        <div class="relative">
                            <input type="checkbox" name="is_active" class="sr-only peer" 
                                {{ old('is_active', $contact_banner->is_active ?? false) ? 'checked' : '' }}>
                            <div class="w-10 h-5 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                        </div>
                        <span class="ml-3 text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:text-slate-600 transition-colors">Mark as Active</span>
                    </label>
                </div>
            </div>

            {{-- Submit --}}
            <div class="mt-8">
                <button type="submit" 
                        class="w-full bg-slate-900 hover:bg-indigo-600 text-white text-[11px] font-black uppercase tracking-[0.2em] py-4 rounded-2xl transition-all shadow-lg shadow-slate-900/10 hover:shadow-indigo-500/20">
                    {{ isset($contact_banner) ? 'Synchronize Updates' : 'Initialize Asset' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection