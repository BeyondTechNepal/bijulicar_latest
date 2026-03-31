@extends('admin.layout')
@section('title', 'Admin Permissions')
@section('page-title', 'Access Control - Admin Guard')

@section('content')
    <div class="space-y-6">
        {{-- Top Action Bar --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight">Admin Permission Registry</h2>
                <p class="text-xs text-slate-500 font-medium">
                    Managing authorization tokens for the <span class="text-indigo-600 font-bold">admin</span> guard.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.admin_permissions.create') }}"
                    class="bg-slate-900 text-white px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-indigo-600 transition-all shadow-sm flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Token
                </a>
            </div>
        </div>

        {{-- Main Inventory Card --}}
        <div class="bg-white border border-slate-200 rounded-[2rem] shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></div>
                    <h3 class="font-black text-slate-700 text-sm uppercase tracking-tighter">Admin Guard Inventory</h3>
                </div>
                <span
                    class="px-3 py-1 bg-white border border-slate-200 rounded-full text-[10px] font-bold text-slate-400 font-mono shadow-sm">
                    spatie/laravel-permission
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Permission
                                Name</th>
                            <th
                                class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">
                                Implementation</th>
                            <th
                                class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">
                                System Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($permissions as $permission)
                            <tr class="group hover:bg-slate-50/80 transition-all">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                                                </path>
                                            </svg>
                                        </div>
                                        <span
                                            class="font-mono text-sm font-bold text-slate-700 tracking-tight group-hover:text-indigo-600">
                                            {{ $permission->name }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    @if ($permission->roles_count > 0)
                                        <div
                                            class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-50 border border-emerald-100 rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span class="text-[10px] font-black text-emerald-700 uppercase">
                                                Linked to {{ $permission->roles_count }}
                                                {{ Str::plural('Role', $permission->roles_count) }}
                                            </span>
                                        </div>
                                    @else
                                        <div
                                            class="inline-flex items-center gap-2 px-3 py-1 bg-slate-100 border border-slate-200 rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                            <span
                                                class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">Standalone
                                                Token</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.admin_permissions.edit', $permission) }}"
                                            class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all"
                                            title="Modify Context">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>

                                        <form method="POST"
                                            action="{{ route('admin.admin_permissions.destroy', $permission) }}"
                                            onsubmit="return confirm('Archive this permission token? This may break frontend functionality.')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-8 py-16 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-12 h-12 text-slate-200" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                            </path>
                                        </svg>
                                        <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest mt-2">
                                            Admin Access Control List is Empty
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer Context --}}
            <div class="px-8 py-4 bg-slate-50/80 border-t border-slate-100">
                <div class="flex items-center gap-2 text-[10px] text-slate-400 font-bold uppercase tracking-tight">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Best Practice: Keep admin permission names lowercase and action-based (e.g., 'user.manage')
                </div>
            </div>
        </div>
    </div>
@endsection
