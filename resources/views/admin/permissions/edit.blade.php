@extends('admin.layout')
@section('title', 'Edit Permission')
@section('page-title', 'System Settings')

@section('content')
<div class="max-w-2xl">
    {{-- Breadcrumb Navigation --}}
    <nav class="flex items-center gap-2 mb-6">
        <a href="{{ route('admin.permissions.index') }}" class="text-xs font-bold text-slate-400 hover:text-indigo-600 transition-colors uppercase tracking-widest">Permissions</a>
        <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-xs font-bold text-slate-900 uppercase tracking-widest">Modify Token</span>
    </nav>

    <div class="bg-white border border-slate-200 rounded-[2rem] shadow-sm overflow-hidden">
        {{-- Form Header --}}
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <div>
                <h3 class="text-base font-black text-slate-900 tracking-tight">Edit Authorization Token</h3>
                <p class="text-[11px] text-slate-500 font-medium mt-0.5">Updating this key affects the <span class="text-indigo-600 font-bold">web</span> guard globally.</p>
            </div>
            <div class="text-right">
                <span class="block text-[9px] font-black text-slate-300 uppercase tracking-widest leading-none">Record ID</span>
                <span class="text-sm font-mono font-bold text-slate-400">#{{ $permission->id }}</span>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.permissions.update', $permission) }}" class="p-8 space-y-6">
            @csrf 
            @method('PUT')

            {{-- Input Field --}}
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <label for="name" class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">Permission Key Name</label>
                    <span class="text-[9px] font-bold text-indigo-500 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">Immutable Guard: Web</span>
                </div>
                
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    </div>
                    <input type="text" name="name" id="name" value="{{ old('name', $permission->name) }}" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-11 pr-4 py-3 text-sm font-mono font-bold text-slate-700 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all @error('name') border-red-400 bg-red-50 @enderror"
                        placeholder="e.g. view.analytics">
                </div>

                @error('name')
                    <div class="flex items-center gap-2 text-red-500 mt-2">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                        <p class="text-xs font-bold">{{ $message }}</p>
                    </div>
                @enderror
            </div>

            {{-- Helper Note --}}
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 flex gap-3">
                <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-[11px] text-slate-500 leading-relaxed">
                    Changing the name of a permission can affect the user experience. Ensure your application logic is updated to reflect this new key.
                </p>
            </div>

            {{-- Buttons --}}
            <div class="flex items-center gap-3 pt-4 border-t border-slate-50">
                <button type="submit"
                    class="flex-1 bg-slate-900 text-white py-3.5 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-indigo-600 transition-all shadow-sm active:scale-[0.98]">
                    Update Record
                </button>
                <a href="{{ route('admin.permissions.index') }}"
                    class="px-8 py-3.5 bg-white border border-slate-200 text-slate-400 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-50 hover:text-slate-600 transition-all">
                    Discard
                </a>
            </div>
        </form>
    </div>
</div>
@endsection