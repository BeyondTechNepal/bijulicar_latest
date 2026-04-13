@extends('admin.layout')
@section('title', isset($link) ? 'Edit Social Link' : 'Add Social Link')
@section('page-title', 'Social Configuration')

@section('content')
    <div class="p-4 lg:p-6 max-w-4xl">
        {{-- High-Level Header --}}
        <div class="bg-slate-900 border border-slate-800 rounded-[1.5rem] px-6 py-4 mb-8 flex items-center justify-between shadow-lg">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-indigo-500/10 border border-indigo-500/20 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-black text-white uppercase tracking-widest">Social Architecture</p>
                    <p class="text-[11px] text-slate-400 font-medium">Configuring external redirect nodes and brand connectivity.</p>
                </div>
            </div>
            <a href="{{ route('admin.social-links.index') }}"
                class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-white transition-colors">
                ← Abort Changes
            </a>
        </div>

        {{-- Main Form Card --}}
        <div class="bg-white border border-slate-200 rounded-[2rem] shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-black text-slate-700 text-sm uppercase tracking-tighter">Configuration Manifest</h3>
                <p class="text-[10px] text-slate-400 font-mono mt-0.5">OBJECT_TYPE: SOCIAL_LINK // SYSTEM_STATUS: READY</p>
            </div>

            <form method="POST"
                action="{{ isset($link) ? route('admin.social-links.update', $link->id) : route('admin.social-links.store') }}"
                class="p-8">

                @csrf
                @if(isset($link))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Platform Input --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Platform Identity</label>
                        <input type="text" name="platform" value="{{ old('platform', $link->platform ?? '') }}"
                            placeholder="e.g. Facebook, Instagram"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                    </div>

                    {{-- Icon Class Input --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Visual Identifier (Icon Class)</label>
                        <input type="text" name="icon_class" value="{{ old('icon_class', $link->icon_class ?? '') }}"
                            placeholder="fa-brands fa-facebook-f"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                    </div>

                    {{-- URL Input (Full Width) --}}
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Target Destination (URL)</label>
                        <input type="text" name="url" value="{{ old('url', $link->url ?? '') }}"
                            placeholder="https://platform.com/profile"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                    </div>
                </div>

                {{-- Action Button --}}
                <div class="mt-10 flex justify-end">
                    <button type="submit"
                        class="bg-indigo-600 text-white px-8 py-3.5 rounded-xl text-xs font-black uppercase tracking-[0.2em] hover:bg-indigo-500 transition-all shadow-xl shadow-indigo-900/20 active:scale-95">
                        {{ isset($link) ? 'Commit Updates' : 'Initialize Record' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection