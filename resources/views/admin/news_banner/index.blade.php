@extends('admin.layout')
@section('title', 'Banner Management')
@section('page-title', 'Media Assets')

@section('content')
    {{-- Action Header --}}
    <div class="bg-slate-900 border border-slate-800 rounded-[1.5rem] px-6 py-4 mb-8 flex items-center justify-between shadow-lg">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-indigo-500/10 border border-indigo-500/20 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-black text-white uppercase tracking-widest">Global Broadcast Assets</p>
                <p class="text-[11px] text-slate-400 font-medium">Manage top-level display banners and promotional visibility layers.</p>
            </div>
        </div>
        {{-- <a href="{{ route('admin.news_banner.create') }}"
            class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-indigo-500 transition-all shadow-xl shadow-indigo-900/20 flex items-center gap-2">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
            Add Banner
        </a> --}}
    </div>

    {{-- Main Inventory Card --}}
    <div class="bg-white border border-slate-200 rounded-[2rem] shadow-sm overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <div>
                <h3 class="font-black text-slate-700 text-sm uppercase tracking-tighter">Active Deployments</h3>
                <p class="text-[10px] text-slate-400 font-mono mt-0.5">CONTENT_TYPE: NEWS_BANNER // STORAGE: S3_ASSETS</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $banners->count() }} Live Banners</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Asset Preview</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Title Manifest</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($banners as $banner)
                        <tr class="group hover:bg-slate-50/50 transition-all">
                            {{-- Image Column --}}
                            <td class="px-8 py-5">
                                <div class="w-32 h-16 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden relative group-hover:border-indigo-200 transition-all shadow-sm">
                                    @if ($banner->image)
                                        <img src="{{ asset('storage/' . $banner->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-slate-50 text-[10px] font-black text-slate-300 uppercase italic">No Media</div>
                                    @endif
                                </div>
                            </td>

                            {{-- Title Column --}}
                            <td class="px-8 py-5">
                                <span class="text-sm font-bold text-slate-700 tracking-tight">{{ $banner->title }}</span>
                                <span class="block text-[10px] text-slate-400 font-mono mt-0.5">UUID: {{ substr(md5($banner->id), 0, 8) }}</span>
                            </td>

                            {{-- Status Column --}}
                            <td class="px-8 py-5">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-700">
                                    <span class="w-1 h-1 rounded-full bg-emerald-500"></span>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Active</span>
                                </div>
                            </td>

                            {{-- Actions Column --}}
                            <td class="px-8 py-5">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.news_banner.edit', $banner) }}" 
                                       class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all"
                                       title="Edit Asset">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>

                                    {{-- <form method="POST" action="{{ route('admin.news_banner.destroy', $banner) }}" 
                                          onsubmit="return confirm('Initiate permanent removal of this media asset?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-300 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form> --}}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection