@extends('dashboard.garage.layout')
@section('title', 'Garage Dashboard')

@section('content')
    @php
        $user = auth()->user();
        $garage = $user->garageVerification;
    @endphp

    {{-- Garage Header --}}
    <div class="bg-slate-900 rounded-3xl p-8 mb-6 relative overflow-hidden">
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-4">
                <span
                    class="px-3 py-1 bg-amber-500/20 text-amber-400 text-[10px] font-black uppercase tracking-widest rounded-full border border-amber-500/30">
                    Verified Repair Expert
                </span>
            </div>
            <h2 class="text-3xl font-black text-white uppercase italic tracking-tighter">
                {{ $garage->garage_name }}
            </h2>
            <p class="text-slate-400 mt-2 max-w-md font-medium">
                Your workshop is now a certified partner in the Bijulicar network. Manage your service profile and 
                prepare for upcoming booking features.
            </p>
        </div>

        {{-- Decorative Wrench/Gear --}}
        <div class="absolute right-[-20px] top-[-20px] text-[120px] opacity-10 select-none">🔧</div>
    </div>

    {{-- Basic Info Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Garage Details Card --}}
        <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Workshop Credentials</p>
                <div class="w-8 h-8 bg-slate-50 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5" />
                    </svg>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="text-[10px] text-slate-400 font-bold uppercase">Location</label>
                    <p class="text-sm font-bold text-slate-800">{{ $garage->garage_location }}</p>
                </div>
                <div>
                    <label class="text-[10px] text-slate-400 font-bold uppercase">Specialization</label>
                    <p class="text-sm font-bold text-slate-800">{{ $garage->specialization }}</p>
                </div>
                <div>
                    <label class="text-[10px] text-slate-400 font-bold uppercase">Contact</label>
                    <p class="text-sm font-bold text-slate-800">{{ $garage->contact }}</p>
                </div>
            </div>
        </div>

        {{-- Appointments & Bay Management Card --}}
        @php
            $pendingCount  = \App\Models\GarageAppointment::where('garage_user_id', auth()->id())->where('status','pending')->count();
            $bayCount      = \App\Models\GarageBay::where('user_id', auth()->id())->count();
            $occupiedCount = \App\Models\GarageBay::where('user_id', auth()->id())->where('status','occupied')->count();
        @endphp
        <a href="{{ route('garage.appointments.index') }}"
            class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm hover:shadow-md hover:border-purple-200 transition-all group block">
            <div class="flex items-center justify-between mb-5">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Service Bays & Bookings</p>
                <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center group-hover:bg-purple-100 transition-colors">
                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-3 mb-5">
                <div class="bg-slate-50 rounded-2xl p-3 text-center">
                    <p class="text-2xl font-black text-slate-800">{{ $bayCount }}</p>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Bays</p>
                </div>
                <div class="bg-red-50 rounded-2xl p-3 text-center">
                    <p class="text-2xl font-black text-red-600">{{ $occupiedCount }}</p>
                    <p class="text-[9px] font-black text-red-400 uppercase tracking-widest mt-0.5">In Use</p>
                </div>
                <div class="bg-amber-50 rounded-2xl p-3 text-center relative">
                    <p class="text-2xl font-black text-amber-700">{{ $pendingCount }}</p>
                    <p class="text-[9px] font-black text-amber-500 uppercase tracking-widest mt-0.5">Pending</p>
                    @if ($pendingCount)
                        <span class="absolute top-2 right-2 w-2 h-2 bg-amber-400 rounded-full animate-pulse"></span>
                    @endif
                </div>
            </div>
            <p class="text-xs font-black text-purple-600 uppercase tracking-widest group-hover:underline">
                Manage Bays & Appointments →
            </p>
        </a>

    </div>
@endsection