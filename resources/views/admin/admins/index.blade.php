@extends('admin.layout')
@section('title', 'Admin Management')
@section('page-title', 'System Administrators')

@section('content')
    {{-- High-Level Security Alert --}}
    <div class="bg-slate-900 border border-slate-800 rounded-[1.5rem] px-6 py-4 mb-8 flex items-center justify-between shadow-lg">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-amber-500/10 border border-amber-500/20 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.876c1.27 0 2.09-1.383 1.45-2.43l-6.938-12c-.63-1.09-2.21-1.09-2.84 0l-6.938 12c-.64 1.047.18 2.43 1.45 2.43z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-black text-white uppercase tracking-widest">Restricted Access Zone</p>
                <p class="text-[11px] text-slate-400 font-medium">Elevated privileges required. Unauthorized changes to admin accounts may result in system lockout.</p>
            </div>
        </div>
        <a href="{{ route('admin.admins.create') }}"
            class="hidden md:flex bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-indigo-500 transition-all items-center gap-2 shadow-xl shadow-indigo-900/20">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
            Provision Admin
        </a>
    </div>

    {{-- Main Inventory Card --}}
    <div class="bg-white border border-slate-200 rounded-[2rem] shadow-sm overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <div>
                <h3 class="font-black text-slate-700 text-sm uppercase tracking-tighter">Authorized Personnel</h3>
                <p class="text-[10px] text-slate-400 font-mono mt-0.5">AUTH_GUARD: ADMIN // TABLE: ADMINS</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $admins->count() }} Total Accounts</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Identity</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Security Role</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Onboarding</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($admins as $adm)
                        @php 
                            $isSelf = $adm->id === $currentAdmin->id; 
                            $role = $adm->getRoleNames()->first();
                        @endphp
                        <tr class="group transition-all {{ $isSelf ? 'bg-indigo-50/30' : 'hover:bg-slate-50/50' }}">
                            {{-- Identity Column --}}
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-black text-xs border-2 border-white shadow-sm overflow-hidden">
                                        @if($adm->avatar_url)
                                            <img src="{{ $adm->avatar_url }}" class="w-full h-full object-cover">
                                        @else
                                            {{ strtoupper(substr($adm->name, 0, 2)) }}
                                        @endif
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-bold text-slate-700 tracking-tight">{{ $adm->name }}</span>
                                            @if ($isSelf)
                                                <span class="text-[9px] font-black bg-indigo-600 text-white px-2 py-0.5 rounded-full uppercase tracking-tighter">current session</span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] font-medium text-slate-400">{{ $adm->email }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Role Column --}}
                            <td class="px-8 py-5">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg border {{ $role === 'superadmin' ? 'bg-rose-50 border-rose-100 text-rose-700' : 'bg-slate-100 border-slate-200 text-slate-600' }}">
                                    <span class="w-1 h-1 rounded-full {{ $role === 'superadmin' ? 'bg-rose-500' : 'bg-slate-400' }}"></span>
                                    <span class="text-[10px] font-black uppercase tracking-widest">{{ $role }}</span>
                                </div>
                            </td>

                            {{-- Date Column --}}
                            <td class="px-8 py-5 text-[11px] font-bold text-slate-500 tracking-tight uppercase">
                                {{ $adm->created_at->format('M d, Y') }}
                                <span class="block text-[9px] font-medium text-slate-300 normal-case">{{ $adm->created_at->diffForHumans() }}</span>
                            </td>

                            {{-- Actions Column --}}
                            <td class="px-8 py-5">
                                <div class="flex items-center justify-end gap-2">
                                    @if (!$isSelf)
                                        <a href="{{ route('admin.admins.edit', $adm) }}" 
                                           class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all"
                                           title="Edit Administrator">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        
                                        <form method="POST" action="{{ route('admin.admins.destroy', $adm) }}" 
                                              onsubmit="return confirm('Permanently revoke access for {{ $adm->name }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-300 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest border border-slate-100 px-3 py-1 rounded-lg">Read Only</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection