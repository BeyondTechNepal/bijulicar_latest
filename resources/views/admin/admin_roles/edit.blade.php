@extends('admin.layout')
@section('title', 'Edit Admin Role: ' . $role->name)
@section('page-title', 'Admin Role Configuration')

@section('content')
    <div class="max-w-3xl space-y-8">

        {{-- Feedback Alert --}}
        @if (session('success'))
            <div
                class="flex items-center gap-3 bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-[1.5rem] shadow-sm animate-in fade-in slide-in-from-top-4 duration-300">
                <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"></path>
                </svg>
                <p class="text-xs font-black uppercase tracking-widest">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Form Section 1: Identity --}}
        <div class="bg-white border border-slate-200 rounded-[2.5rem] shadow-sm overflow-hidden">
            <div class="px-10 py-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Role Identity</h3>
                    <p class="text-[11px] text-slate-400 font-medium">Changing the name updates all assigned admin users.
                    </p>
                </div>
                <span
                    class="px-3 py-1 bg-white border border-slate-200 rounded-full text-[10px] font-mono font-bold text-slate-400">ID:
                    {{ $role->id }}</span>
            </div>

            <form method="POST" action="{{ route('admin.admin_roles.update', $role) }}" class="p-10">
                @csrf @method('PUT')
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1 relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                </path>
                            </svg>
                        </div>
                        <input type="text" name="name" value="{{ old('name', $role->name) }}" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-2xl pl-11 pr-4 py-3.5 text-sm font-mono font-bold text-slate-700 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                    </div>
                    <button type="submit"
                        class="bg-slate-900 text-white px-8 py-3.5 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-indigo-600 transition-all active:scale-95 shadow-sm">
                        Update Name
                    </button>
                </div>
                @error('name')
                    <p class="text-[10px] font-bold text-red-500 mt-3 ml-2 tracking-wide uppercase">{{ $message }}</p>
                @enderror
            </form>
        </div>

        {{-- Form Section 2: Access Control --}}
        <div class="bg-white border border-slate-200 rounded-[2.5rem] shadow-sm overflow-hidden">
            <div class="px-10 py-8 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Capabilities Matrix</h3>
                <p class="text-[11px] text-slate-500 font-medium mt-1">Select the permission tokens that define this admin
                    role's authority.</p>
            </div>

            <form method="POST" action="{{ route('admin.admin_roles.permissions.update', $role) }}" class="p-10">
                @csrf

                @if ($permissions->isEmpty())
                    <div class="py-12 text-center border-2 border-dashed border-slate-100 rounded-3xl">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">No tokens available</p>
                        <a href="{{ route('admin.permissions.create') }}"
                            class="text-[11px] font-black text-indigo-600 hover:text-indigo-800 underline underline-offset-4">GENERATE
                            PERMISSIONS FIRST</a>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-10">
                        @foreach ($permissions as $permission)
                            @php $isActive = in_array($permission->id, $rolePermissionIds); @endphp
                            <label
                                class="group relative flex items-center justify-between p-4 border rounded-2xl cursor-pointer transition-all duration-200
                              {{ $isActive ? 'border-indigo-500 bg-indigo-50/30' : 'border-slate-100 hover:border-slate-300 hover:bg-slate-50' }}">

                                <div class="flex items-center gap-3">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                            {{ $isActive ? 'checked' : '' }}
                                            class="peer w-5 h-5 rounded-lg border-slate-300 text-indigo-600 focus:ring-0 focus:ring-offset-0 transition-all">
                                    </div>
                                    <span
                                        class="text-xs font-mono font-bold tracking-tight {{ $isActive ? 'text-indigo-900' : 'text-slate-600' }}">
                                        {{ $permission->name }}
                                    </span>
                                </div>

                                @if ($isActive)
                                    <span
                                        class="flex h-5 w-5 items-center justify-center rounded-full bg-indigo-600 text-[10px] text-white font-black animate-in zoom-in-50">
                                        ✓
                                    </span>
                                @endif
                            </label>
                        @endforeach
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row items-center gap-4 border-t border-slate-100 pt-8">
                    <button type="submit"
                        class="w-full sm:flex-1 bg-indigo-600 text-white py-4 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-slate-900 transition-all shadow-lg shadow-indigo-100 active:scale-[0.98]">
                        Sync Admin Capabilities
                    </button>
                    <a href="{{ route('admin.admin_roles.index') }}"
                        class="w-full sm:w-auto px-10 py-4 bg-white border border-slate-200 text-slate-400 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-slate-50 hover:text-slate-600 transition-all">
                        Discard Changes
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
