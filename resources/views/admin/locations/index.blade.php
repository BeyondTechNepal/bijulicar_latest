@extends('admin.layout')
@section('title', 'Location Registry')
@section('page-title', 'Geographical Assets')

@section('content')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-xl font-black text-slate-800 tracking-tight">Location Matrix</h2>
            <p class="text-xs text-slate-500 font-medium mt-1">Manage physical touchpoints and geospatial coordinates for the
                network.</p>
        </div>
        <a href="{{ route('admin.locations.create') }}"
            class="bg-indigo-600 text-white px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-slate-900 transition-all shadow-lg shadow-indigo-100 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Register Location
        </a>
    </div>

    <div class="bg-white border border-slate-200 rounded-[2rem] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Site Name</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Classification
                        </th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Geospatial
                            Data</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($locations as $loc)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            {{-- Name Column --}}
                            <td class="px-8 py-5">
                                <span
                                    class="text-sm font-bold text-slate-700 tracking-tight group-hover:text-indigo-600 transition-colors">
                                    {{ $loc->name }}
                                </span>
                            </td>

                            {{-- Type Column --}}
                            <td class="px-8 py-5">
                                <span
                                    class="text-[10px] font-black uppercase tracking-widest px-3 py-1 bg-slate-100 text-slate-500 rounded-lg border border-slate-200">
                                    {{ $loc->type }}
                                </span>
                            </td>

                            {{-- Coordinates Column --}}
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4 text-xs font-mono font-bold text-slate-400">
                                    <div class="flex items-center gap-1">
                                        <span class="text-[9px] text-slate-300 uppercase">Lat</span>
                                        <span class="text-slate-600">{{ number_format($loc->latitude, 4) }}°</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span class="text-[9px] text-slate-300 uppercase">Lng</span>
                                        <span class="text-slate-600">{{ number_format($loc->longitude, 4) }}°<ontan>
                                    </div>
                                </div>
                            </td>

                            {{-- Actions Column --}}
                            <td class="px-8 py-5">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.locations.edit', $loc->id) }}"
                                        class="text-[11px] font-black uppercase tracking-widest text-indigo-600 hover:text-slate-900 transition-colors">
                                        Modify
                                    </a>

                                    <span class="text-slate-200 text-xs">|</span>

                                    <form method="POST" action="{{ route('admin.locations.destroy', $loc->id) }}"
                                        onsubmit="return confirm('Archive this location record?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="text-[11px] font-black uppercase tracking-widest text-slate-300 hover:text-red-500 transition-colors">
                                            Archive
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Empty State --}}
        @if ($locations->isEmpty())
            <div class="py-20 text-center">
                <p class="text-xs font-black text-slate-300 uppercase tracking-widest">No active coordinates registered.</p>
            </div>
        @endif
    </div>
@endsection
