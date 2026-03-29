@extends('admin.layout')
@section('title', isset($location) ? 'Edit Asset' : 'Register Asset')
@section('page-title', 'Geospatial Registry')

@section('content')
<div class="max-w-3xl">
    {{-- Navigation --}}
    <a href="{{ route('admin.locations.index') }}" class="inline-flex items-center text-[10px] font-black text-slate-400 hover:text-indigo-600 transition-colors mb-6 group uppercase tracking-[0.2em]">
        <svg class="w-3 h-3 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
        Back to Registry
    </a>

    <div class="bg-white border border-slate-200 rounded-[2.5rem] shadow-sm overflow-hidden">
        {{-- Header --}}
        <div class="px-10 py-8 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-xl font-black text-slate-800 tracking-tight">
                {{ isset($location) ? 'Update Asset Core' : 'Initialize New Asset' }}
            </h3>
            <p class="text-xs text-slate-500 font-medium mt-1">Define the physical parameters and geospatial positioning for this network node.</p>
        </div>

        <form method="POST" 
              action="{{ isset($location) ? route('admin.locations.update', $location->id) : route('admin.locations.store') }}" 
              class="p-10 space-y-8">
            
            @csrf
            @if (isset($location)) @method('PUT') @endif

            {{-- Asset Identity --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Friendly Name</label>
                    <input type="text" name="name" value="{{ old('name', $location->name ?? '') }}" required placeholder="e.g. Downtown Hub A"
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Asset Classification</label>
                    <select name="type" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all appearance-none cursor-pointer">
                        <option value="station" {{ (old('type', $location->type ?? '') == 'station') ? 'selected' : '' }}>⚡ Charging Hub</option>
                        <option value="vehicle" {{ (old('type', $location->type ?? '') == 'vehicle') ? 'selected' : '' }}>🚲 Active Vehicle</option>
                    </select>
                </div>
            </div>

            {{-- Geospatial Data --}}
            <div class="bg-slate-50 rounded-[2rem] p-8 space-y-6 border border-slate-100">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Geospatial Coordinates</h4>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Latitude</label>
                        <input type="text" name="latitude" value="{{ old('latitude', $location->latitude ?? '') }}" required placeholder="0.0000"
                            class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-mono font-bold text-slate-600 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Longitude</label>
                        <input type="text" name="longitude" value="{{ old('longitude', $location->longitude ?? '') }}" required placeholder="0.0000"
                            class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-mono font-bold text-slate-600 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all">
                    </div>
                </div>

                <div class="space-y-2 pt-2">
                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Verified Street Address</label>
                    <input type="text" name="address" value="{{ old('address', $location->address ?? '') }}" required placeholder="123 Digital Ave, Suite 400"
                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-600 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all">
                </div>
            </div>

            {{-- Submission --}}
            <div class="flex flex-col sm:flex-row items-center gap-4 pt-4">
                <button type="submit" class="w-full sm:flex-1 bg-slate-900 text-white py-4 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] hover:bg-indigo-600 transition-all shadow-xl active:scale-[0.98]">
                    {{ isset($location) ? 'Update Registry' : 'Provision Asset' }}
                </button>
                <a href="{{ route('admin.locations.index') }}" class="w-full sm:w-auto px-10 py-4 bg-white border border-slate-200 text-slate-400 rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] hover:bg-slate-50 transition-all text-center">
                    Discard
                </a>
            </div>
        </form>
    </div>
</div>
@endsection