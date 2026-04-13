@extends('admin.layout')
@section('title', 'Social Media Management')
@section('page-title', 'Social Links')

@section('content')
    <div class="p-4 lg:p-6">
        {{-- Action Header --}}
        <div class="bg-slate-900 border border-slate-800 rounded-[1.5rem] px-6 py-4 mb-8 flex items-center justify-between shadow-lg">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-indigo-500/10 border border-indigo-500/20 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-black text-white uppercase tracking-widest">Social Architecture</p>
                    <p class="text-[11px] text-slate-400 font-medium">Manage external platform redirects and social connectivity.</p>
                </div>
            </div>
            <a href="{{ route('admin.social-links.create') }}"
                class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-indigo-500 transition-all shadow-xl shadow-indigo-900/20 flex items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                </svg>
                Add New Link
            </a>
        </div>

        {{-- Main Table Card --}}
        <div class="bg-white border border-slate-200 rounded-[2rem] shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <div>
                    <h3 class="font-black text-slate-700 text-sm uppercase tracking-tighter">Active Connections</h3>
                    <p class="text-[10px] text-slate-400 font-mono mt-0.5">DB_TABLE: SOCIAL_LINKS // STATUS: LIVE</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $links->count() }} Total Links</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Platform</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Destination URL</th>
                            <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">System Management</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($links as $link)
                            <tr class="group hover:bg-slate-50/50 transition-all">
                                {{-- Platform --}}
                                <td class="px-8 py-5">
                                    <span class="text-sm font-bold text-slate-700 tracking-tight uppercase">{{ $link->platform }}</span>
                                </td>

                                {{-- URL --}}
                                <td class="px-8 py-5">
                                    <span class="text-[11px] font-mono text-indigo-500 font-medium break-all">{{ $link->url }}</span>
                                </td>

                                {{-- Actions --}}
                                <td class="px-8 py-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        {{-- Edit --}}
                                        <a href="{{ route('admin.social-links.edit', $link->id) }}" 
                                           class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all"
                                           title="Edit Link">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ route('admin.social-links.destroy', $link->id) }}" method="POST" 
                                              onsubmit="return confirm('Confirm permanent deletion?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-300 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection