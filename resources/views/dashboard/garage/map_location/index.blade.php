@extends('dashboard.garage.layout')
@section('title', 'Map Location')
@section('page-title', 'Map Location')

@section('content')

<div class="flex items-center justify-between mb-6">
    <p class="text-slate-500 text-sm font-medium">Manage your garage's pinned location on the public map.</p>
    @if (!$location)
        <a href="{{ route('garage.location.create') }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-black uppercase tracking-wider rounded-xl transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Location
        </a>
    @endif
</div>

@if (!$location)
    <div class="bg-white border border-dashed border-slate-300 rounded-3xl p-16 flex flex-col items-center justify-center text-center">
        <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center mb-4 border border-amber-100">
            <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
        <p class="font-black text-slate-900 uppercase italic tracking-tight">No Location Set</p>
        <p class="text-sm text-slate-500 font-medium mt-1 max-w-xs">
            Pin your garage on the public map so customers can find you. It will be visible once your account is verified.
        </p>
        <a href="{{ route('garage.location.create') }}"
            class="mt-6 inline-flex items-center gap-2 px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-black uppercase tracking-wider rounded-xl transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Pin My Garage
        </a>
    </div>
@else
    <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">

        <div class="w-full h-64 bg-slate-100">
            <iframe
                src="https://www.openstreetmap.org/export/embed.html?bbox={{ $location->longitude - 0.01 }},{{ $location->latitude - 0.01 }},{{ $location->longitude + 0.01 }},{{ $location->latitude + 0.01 }}&layer=mapnik&marker={{ $location->latitude }},{{ $location->longitude }}"
                class="w-full h-full border-0"
                loading="lazy">
            </iframe>
        </div>

        <div class="p-6">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                <div class="space-y-3">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Address</p>
                        <p class="text-slate-900 font-bold text-sm">{{ $location->address }}</p>
                    </div>
                    <div class="flex gap-6">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Latitude</p>
                            <p class="text-slate-700 font-bold text-sm font-mono">{{ $location->latitude }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Longitude</p>
                            <p class="text-slate-700 font-bold text-sm font-mono">{{ $location->longitude }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Status</p>
                        @if ($location->is_active)
                            <span class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Live on map
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                Pending verification
                            </span>
                        @endif
                    </div>
                </div>

                <div class="flex gap-3 shrink-0">
                    {{-- <a href="{{ route('garage.location.edit') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 hover:bg-slate-700 text-white text-sm font-black uppercase tracking-wider rounded-xl transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a> --}}
                    <form method="POST" action="{{ route('garage.location.destroy') }}"
                        onsubmit="return confirm('Remove this location from the map?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 text-sm font-black uppercase tracking-wider rounded-xl border border-red-200 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Remove
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection
