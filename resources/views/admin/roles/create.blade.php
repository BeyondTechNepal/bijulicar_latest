@extends('admin.layout')
@section('title', 'Create New Role')
@section('page-title', 'Role Architecture')

@section('content')
<div class="max-w-3xl">
    {{-- Back Link --}}
    <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center text-xs font-black text-slate-400 hover:text-indigo-600 transition-colors mb-6 group uppercase tracking-widest">
        <svg class="w-3 h-3 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
        Back to Roles
    </a>

    <div class="bg-white border border-slate-200 rounded-[2.5rem] shadow-sm overflow-hidden">
        {{-- Header --}}
        <div class="px-10 py-8 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-xl font-black text-slate-800 tracking-tight">Initialize Security Group</h3>
            <p class="text-xs text-slate-500 font-medium mt-1">Define a new user role and assign its baseline permissions for the <span class="text-indigo-600 font-bold">web</span> guard.</p>
        </div>

        <form method="POST" action="{{ route('admin.roles.store') }}" class="p-10 space-y-10">
            @csrf
            
            {{-- Role Name Input --}}
            <div class="space-y-3">
                <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest ml-1">Unique Role Identifier</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. moderator"
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-11 pr-4 py-4 text-sm font-mono font-bold text-slate-700 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all @error('name') border-red-400 bg-red-50 @enderror">
                </div>
                @error('name')
                    <p class="text-[10px] font-bold text-red-500 mt-2 ml-1 uppercase tracking-wide">{{ $message }}</p>
                @enderror
            </div>

            {{-- Permissions Selection --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest ml-1">Authorization Matrix</label>
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Step 2 of 2</span>
                </div>

                @if ($permissions->isEmpty())
                    <div class="py-12 text-center border-2 border-dashed border-slate-100 rounded-3xl bg-slate-50/30">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">No permission tokens found</p>
                        <a href="{{ route('admin.permissions.create') }}" class="text-[10px] font-black text-indigo-600 hover:text-indigo-800 underline underline-offset-4 uppercase">Create Tokens First</a>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach ($permissions as $permission)
                            @php $isOld = in_array($permission->id, old('permissions', [])); @endphp
                            <label class="group relative flex items-center gap-3 p-4 border rounded-2xl cursor-pointer transition-all duration-200
                                  {{ $isOld ? 'border-indigo-500 bg-indigo-50/50' : 'border-slate-100 hover:border-slate-300 hover:bg-slate-50' }}">
                                
                                <div class="relative flex items-center">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                        {{ $isOld ? 'checked' : '' }}
                                        class="peer w-5 h-5 rounded-lg border-slate-300 text-indigo-600 focus:ring-0 focus:ring-offset-0 transition-all cursor-pointer">
                                </div>
                                <span class="text-xs font-mono font-bold tracking-tight {{ $isOld ? 'text-indigo-900' : 'text-slate-600' }}">
                                    {{ $permission->name }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Submission --}}
            <div class="flex flex-col sm:flex-row items-center gap-4 pt-6">
                <button type="submit"
                    class="w-full sm:flex-1 bg-slate-900 text-white py-4 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-indigo-600 transition-all shadow-lg shadow-slate-200 active:scale-[0.98]">
                    Provision Role
                </button>
                <a href="{{ route('admin.roles.index') }}"
                    class="w-full sm:w-auto px-10 py-4 bg-white border border-slate-200 text-slate-400 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-slate-50 hover:text-slate-600 transition-all">
                    Discard
                </a>
            </div>
        </form>
    </div>
</div>
@endsection