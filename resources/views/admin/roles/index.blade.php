@extends('admin.layout')
@section('title', 'Roles & Permissions')
@section('page-title', 'Role Architecture')

@section('content')
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-xl font-black text-slate-800 tracking-tight">Access Level Management</h2>
            <p class="text-xs text-slate-500 font-medium mt-1">Define security groups and aggregate permission tokens for end-users.</p>
        </div>
        <a href="{{ route('admin.roles.create') }}"
            class="bg-slate-900 text-white px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-indigo-600 transition-all shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            Create New Role
        </a>
    </div>

    <div class="grid grid-cols-1 gap-4">
        @forelse($roles as $role)
            @php 
                $isCore = in_array($role->name, ['buyer', 'seller', 'business', 'ev-station', 'garage']); 
                $userCount = $role->users()->count();
            @endphp
            
            <div class="bg-white border border-slate-200 rounded-[2rem] p-8 transition-all hover:shadow-md group">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    
                    {{-- Role Info --}}
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-2xl {{ $isCore ? 'bg-amber-50 text-amber-600 border-amber-100' : 'bg-indigo-50 text-indigo-600 border-indigo-100' }} border flex items-center justify-center shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="font-black text-slate-800 text-lg uppercase tracking-tighter">{{ $role->name }}</h3>
                                    @if($isCore)
                                        <span class="text-[9px] font-black bg-slate-100 text-slate-400 px-2 py-0.5 rounded uppercase tracking-widest border border-slate-200">System Protected</span>
                                    @endif
                                </div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                    Total Engagement: {{ number_format($userCount) }} {{ Str::plural('Member', $userCount) }}
                                </p>
                            </div>
                        </div>

                        {{-- Permission Tags --}}
                        <div class="flex flex-wrap gap-1.5">
                            @forelse($role->permissions as $perm)
                                <span class="text-[10px] font-bold bg-slate-50 text-slate-500 border border-slate-200 px-2.5 py-1 rounded-lg font-mono group-hover:border-indigo-100 group-hover:bg-white transition-all">
                                    {{ $perm->name }}
                                </span>
                            @empty
                                <span class="text-xs text-slate-400 italic font-medium">No permissions attached to this security group.</span>
                            @endforelse
                        </div>
                    </div>

                    {{-- Action Suite --}}
                    <div class="flex items-center gap-3 lg:pl-8 lg:border-l border-slate-100">
                        <a href="{{ route('admin.roles.edit', $role) }}"
                            class="flex items-center gap-2 text-[11px] font-black uppercase tracking-widest bg-white border border-slate-200 text-slate-600 px-5 py-3 rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit Access
                        </a>

                        @if (!$isCore)
                            <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" onsubmit="return confirm('Archive this role? Current users will lose access.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-3 text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all" title="Delete Role">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white border border-dashed border-slate-300 rounded-[2rem] py-20 text-center">
                <div class="inline-flex p-4 bg-slate-50 rounded-full text-slate-300 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m11-3V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2zm-7-3a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <p class="text-sm font-black text-slate-400 uppercase tracking-widest">No security roles identified.</p>
            </div>
        @endforelse
    </div>
@endsection