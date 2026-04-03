@extends('dashboard.station.layout')
@section('title', 'Station Dashboard')

@section('content')
    @php
        $user = auth()->user();
        $station = $user->stationVerification;
    @endphp

    {{-- Station Header --}}
    <div class="bg-slate-900 rounded-3xl p-8 mb-6 relative overflow-hidden">
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-4">
                <span
                    class="px-3 py-1 bg-emerald-500/20 text-emerald-400 text-[10px] font-black uppercase tracking-widest rounded-full border border-emerald-500/30">
                    Verified Station
                </span>
            </div>
            <h2 class="text-3xl font-black text-white uppercase italic tracking-tighter">
                {{ $station->station_name }}
            </h2>
            <p class="text-slate-400 mt-2 max-w-md font-medium">
                Your station is now live in our network. Use this panel to monitor your infrastructure as we roll out new
                features.
            </p>
        </div>

        {{-- Decorative Lightning Bolt --}}
        <div class="absolute right-[-20px] top-[-20px] text-[120px] opacity-10 select-none">⚡</div>
    </div>

    {{-- Basic Info Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Station Details Card --}}
        <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Registration Info</p>
                <div class="w-8 h-8 bg-slate-50 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="text-[10px] text-slate-400 font-bold uppercase">Location</label>
                    <p class="text-sm font-bold text-slate-800">{{ $station->location_details }}</p>
                </div>
                <div>
                    <label class="text-[10px] text-slate-400 font-bold uppercase">Contact</label>
                    <p class="text-sm font-bold text-slate-800">{{ $station->contact_number }}</p>
                </div>
            </div>
        </div>

        {{-- Features Placeholder --}}
        <div
            class="bg-emerald-50/50 border border-dashed border-emerald-200 rounded-3xl p-6 flex flex-col items-center justify-center text-center">
            <div class="w-12 h-12 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-emerald-500 animate-pulse" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <p class="font-black text-slate-900 uppercase italic tracking-tight text-sm">Analytics Incoming</p>
            <p class="text-xs text-slate-500 font-medium mt-1">
                Real-time charging metrics and revenue tracking are being integrated.
            </p>
        </div>

    </div>
@endsection
