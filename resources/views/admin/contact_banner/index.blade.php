@extends('admin.layout')
@section('title', 'Banner Management')
@section('page-title', 'Contact Banners')

@section('content')
    <div class="p-4 lg:p-6">
        {{-- Slim Header --}}
        <div class="bg-slate-900 border border-slate-800 rounded-2xl px-5 py-3 mb-6 flex items-center justify-between shadow-md">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-indigo-500/10 border border-indigo-500/20 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 00-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-white uppercase tracking-widest">Visual Assets</p>
                    <p class="text-[9px] text-slate-500 font-medium leading-none">Managing contact page banners.</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="hidden sm:flex items-center gap-2 border-r border-slate-800 pr-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ count($banners) }} Active</span>
                </div>
                {{-- <a href="{{ route('admin.contact_banner.create') }}" 
                   class="bg-indigo-600 hover:bg-indigo-500 text-white text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-xl transition-all shadow-lg shadow-indigo-500/20">
                    Add Banner
                </a> --}}
            </div>
        </div>

        {{-- Compact Table Card --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-5 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest">Preview</th>
                            <th class="px-5 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest">Banner Info</th>
                            <th class="px-5 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest">Visibility</th>
                            <th class="px-5 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">Manage</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($banners as $banner)
                            <tr class="group hover:bg-slate-50/50 transition-all">
                                {{-- Image Preview --}}
                                <td class="px-5 py-3">
                                    <div class="w-24 h-12 rounded-lg border border-slate-100 overflow-hidden bg-slate-50 shadow-sm">
                                        <img src="{{ asset('storage/' . $banner->image) }}" class="w-full h-full object-cover">
                                    </div>
                                </td>

                                {{-- Title --}}
                                <td class="px-5 py-3">
                                    <span class="text-xs font-bold text-slate-700 leading-tight block">{{ $banner->title }}</span>
                                    <span class="text-[9px] text-slate-400 uppercase font-black tracking-tighter">System ID: #{{ $banner->id }}</span>
                                </td>

                                {{-- Status --}}
                                <td class="px-5 py-3">
                                    @if($banner->is_active)
                                        <span class="text-[9px] font-black uppercase tracking-tighter text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">
                                            Active
                                        </span>
                                    @else
                                        <span class="text-[9px] font-black uppercase tracking-tighter text-slate-400 bg-slate-50 px-2 py-0.5 rounded border border-slate-100">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-5 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.contact_banner.edit', $banner) }}" 
                                           class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-colors"
                                           title="Edit Asset">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>

                                        {{-- <form method="POST" action="{{ route('admin.contact_banner.destroy', $banner) }}" onsubmit="return confirm('Delete this asset?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="p-1.5 text-slate-300 hover:text-rose-600 hover:bg-rose-50 rounded-md transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form> --}}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-[10px] font-black text-slate-300 uppercase tracking-widest">
                                    No Banners Found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection