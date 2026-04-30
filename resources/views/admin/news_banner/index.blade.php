@extends('admin.layout')
@section('title', 'Banner Management')
@section('page-title', 'Media Assets')

@section('content')
    {{-- Action Header --}}
    <div
        class="bg-slate-900 border border-slate-800 rounded-[1.5rem] px-6 py-4 mb-8 flex items-center justify-between shadow-lg">
        <div class="flex items-center gap-4">
            <div
                class="w-10 h-10 bg-indigo-500/10 border border-indigo-500/20 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-black text-white uppercase tracking-widest">Global Broadcast Assets</p>
                <p class="text-[11px] text-slate-400 font-medium">Manage top-level display banners and promotional
                    visibility layers.</p>
            </div>
        </div>
        @if ($banners->count() === 0)
            <a href="{{ route('admin.news_banner.create') }}"
                class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-indigo-500 transition-all shadow-xl flex items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Banner
            </a>
        @endif
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
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $banners->count() }} Live
                    Banners</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Asset Preview
                        </th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Title Manifest
                        </th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($banners as $banner)
                        <tr class="group hover:bg-slate-50/50 transition-all">
                            {{-- Image Column --}}
                            <td class="px-8 py-5">
                                <div
                                    class="w-32 h-16 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden relative group-hover:border-indigo-200 transition-all shadow-sm">
                                    @if ($banner->image)
                                        <img src="{{ asset('storage/' . $banner->image) }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div
                                            class="w-full h-full flex items-center justify-center text-[10px] font-black text-slate-300 uppercase italic">
                                            No Media
                                        </div>
                                    @endif
                                </div>
                            </td>

                            {{-- Title --}}
                            <td class="px-8 py-5">
                                <span class="text-sm font-bold text-slate-700">{{ $banner->title }}</span>
                                <span class="block text-[10px] text-slate-400 font-mono mt-0.5">
                                    UUID: {{ substr(md5($banner->id), 0, 8) }}
                                </span>
                            </td>

                            {{-- Status --}}
                            <td class="px-8 py-5">
                                <div
                                    class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-700">
                                    <span class="w-1 h-1 rounded-full bg-emerald-500"></span>
                                    <span class="text-[10px] font-black uppercase">Active</span>
                                </div>
                            </td>

                            {{-- Actions --}}
                            <td class="px-8 py-5 text-right">
                                <a href="{{ route('admin.news_banner.edit', $banner) }}"
                                    class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all">
                                    ✏️
                                </a>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-16">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="text-slate-300 text-sm font-bold">No banners found</div>
                                    <a href="{{ route('admin.news_banner.create') }}"
                                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-indigo-500 transition">
                                        + Add First Banner
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
