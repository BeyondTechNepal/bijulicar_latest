@extends('admin.layout')
@section('title', 'Modify Admin: ' . $admin->name)
@section('page-title', 'Account Security')

@section('content')
<div class="max-w-2xl">
    {{-- Breadcrumb Navigation --}}
    <nav class="flex items-center gap-2 mb-6">
        <a href="{{ route('admin.admins.index') }}" class="text-[10px] font-black text-slate-400 hover:text-indigo-600 transition-colors uppercase tracking-[0.2em]">Personnel Registry</a>
        <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em]">Update Credentials</span>
    </nav>

    <div class="bg-white border border-slate-200 rounded-[2.5rem] shadow-sm overflow-hidden">
        {{-- Header Card --}}
        <div class="px-10 py-8 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white font-black text-sm shadow-lg shadow-indigo-200">
                    {{ strtoupper(substr($admin->name, 0, 2)) }}
                </div>
                <div>
                    <h3 class="text-base font-black text-slate-800 tracking-tight tracking-widest">{{ $admin->name }}</h3>
                    <p class="text-[11px] text-slate-400 font-mono font-bold">{{ $admin->email }}</p>
                </div>
            </div>
            <div class="text-right hidden sm:block">
                <span class="block text-[9px] font-black text-slate-300 uppercase tracking-widest leading-none">Record Reference</span>
                <span class="text-sm font-mono font-bold text-slate-400">#{{ str_pad($admin->id, 4, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.admins.update', $admin) }}" class="p-10 space-y-8">
            @csrf @method('PATCH')

            {{-- Identity Section --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $admin->name) }}" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                    @error('name') <p class="text-[10px] font-bold text-red-500 mt-1 uppercase">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $admin->email) }}" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                    @error('email') <p class="text-[10px] font-bold text-red-500 mt-1 uppercase">{{ $message }}</p> @enderror
                </div>
            </div>

            <hr class="border-slate-100">

            {{-- Security Section --}}
            <div class="space-y-6">
                <div>
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Security Credentials</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">New Password</label>
                            <input type="password" name="password" placeholder="••••••••"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Confirm Update</label>
                            <input type="password" name="password_confirmation" placeholder="••••••••"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                        </div>
                    </div>
                    @error('password') <p class="text-[10px] font-bold text-red-500 mt-2 uppercase tracking-wide">{{ $message }}</p> @enderror
                </div>

                {{-- Role Selection --}}
                @php $currentRole = $admin->getRoleNames()->first() @endphp
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Access Tier</label>
                    
                    @if ($admin->id === $currentAdmin->id)
                        <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl flex items-center gap-3">
                            <svg class="w-5 h-5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span class="text-xs font-bold text-slate-600 uppercase tracking-tight">Active Session Role: <span class="text-indigo-600">{{ $currentRole }}</span></span>
                            <input type="hidden" name="role" value="{{ $currentRole }}">
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="cursor-pointer group">
                                <input type="radio" name="role" value="admin" class="sr-only peer" {{ old('role', $currentRole) === 'admin' ? 'checked' : '' }}>
                                <div class="border-2 border-slate-100 rounded-2xl p-5 transition-all group-hover:bg-slate-50 peer-checked:border-indigo-600 peer-checked:bg-indigo-50/50">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-black uppercase tracking-widest text-slate-700">Standard</span>
                                        <div class="w-3 h-3 rounded-full border-2 border-slate-300 peer-checked:border-indigo-600"></div>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-1 font-medium">Standard administrative access to dashboard assets.</p>
                                </div>
                            </label>
                            <label class="cursor-pointer group">
                                <input type="radio" name="role" value="superadmin" class="sr-only peer" {{ old('role', $currentRole) === 'superadmin' ? 'checked' : '' }}>
                                <div class="border-2 border-slate-100 rounded-2xl p-5 transition-all group-hover:bg-slate-50 peer-checked:border-rose-500 peer-checked:bg-rose-50/50">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-black uppercase tracking-widest text-rose-600">Superadmin</span>
                                        <div class="w-3 h-3 rounded-full border-2 border-slate-300 peer-checked:border-rose-500"></div>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-1 font-medium">Full system ownership. Destructive permission rights.</p>
                                </div>
                            </label>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Footer Actions --}}
            <div class="flex items-center gap-4 pt-6 border-t border-slate-50">
                <button type="submit" class="flex-1 bg-slate-900 text-white py-4 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] hover:bg-indigo-600 transition-all shadow-xl active:scale-[0.98]">
                    Commit Update
                </button>
                <a href="{{ route('admin.admins.index') }}" class="px-8 py-4 bg-white border border-slate-200 text-slate-400 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] hover:bg-slate-50 transition-all">
                    Discard
                </a>
            </div>
        </form>
    </div>
</div>
@endsection