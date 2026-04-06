@extends('admin.layout')

@section('title', isset($location) ? 'Edit Map Location' : 'Create Map Location')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-12">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
            <div>
                <nav class="mb-2">
                    <a href="{{ route('admin.locations.index') }}" class="group inline-flex items-center text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 hover:text-indigo-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
                        </svg>
                        Back to Registry
                    </a>
                </nav>
                <h1 class="text-3xl font-black text-slate-900 tracking-tighter uppercase leading-none">
                    {{ isset($location) ? 'Edit Location' : 'New Location Node' }}
                </h1>
                @if (isset($location))
                    <div class="mt-2 inline-flex items-center bg-slate-100 px-2 py-1 rounded">
                        <span class="text-[9px] text-slate-500 font-mono uppercase tracking-widest">
                            UID: {{ $location->id }}
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <form action="{{ isset($location) ? route('admin.locations.update', $location->id) : route('admin.locations.store') }}" 
              method="POST" 
              class="space-y-6">
            @csrf
            @if (isset($location)) @method('PUT') @endif

            {{-- Card 1: Identity --}}
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50/50 border-b border-slate-100 px-8 py-4">
                    <h3 class="font-black text-slate-400 uppercase tracking-widest text-[10px]">Node Classification</h3>
                </div>
                
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="block text-[11px] font-black text-slate-700 uppercase tracking-wider">Location Type <span class="text-indigo-500">*</span></label>
                        <input type="text" name="type" value="{{ old('type', $location->type ?? '') }}" required
                            placeholder="e.g. BRANCH"
                            class="w-full bg-slate-50 border-slate-200 rounded-xl font-bold text-slate-500 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all uppercase text-sm cursor-not-allowed p-2" readonly>
                        @error('type')
                            <p class="text-[10px] font-bold text-red-500 uppercase mt-1 tracking-tight">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[11px] font-black text-slate-700 uppercase tracking-wider">Visibility Status</label>
                        <div class="relative">
                            <select name="is_active"
                                class="w-full border-slate-200 rounded-xl font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 appearance-none bg-transparent py-3 px-4 transition-all">
                                <option value="1" {{ old('is_active', $location->is_active ?? '') == 1 ? 'selected' : '' }}>ACTIVE / VISIBLE</option>
                                <option value="0" {{ old('is_active', $location->is_active ?? '') == 0 ? 'selected' : '' }}>INACTIVE / HIDDEN</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 2: Coordinates --}}
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50/50 border-b border-slate-100 px-8 py-4">
                    <h3 class="font-black text-slate-400 uppercase tracking-widest text-[10px]">Geographic Intelligence</h3>
                </div>

                <div class="p-8 space-y-8">
                    <div class="space-y-2">
                        <label class="block text-[11px] font-black text-slate-700 uppercase tracking-wider">Full Physical Address *</label>
                        <input type="text" name="address" value="{{ old('address', $location->address ?? '') }}" required
                            placeholder="Street, City, Province, Postal Code"
                            class="w-full border-slate-200 rounded-xl font-bold text-slate-800 placeholder:text-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 py-3 px-4 transition-all p-2">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-indigo-50/30 p-5 rounded-2xl border border-indigo-100/50 space-y-2">
                            <label class="block text-[10px] font-bold text-indigo-400 uppercase tracking-[0.15em] font-mono">Latitude</label>
                            <input type="text" name="latitude" value="{{ old('latitude', $location->latitude ?? '') }}"
                                required placeholder="27.7172"
                                class="w-full bg-white border-slate-200 rounded-lg font-mono text-sm text-indigo-600 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 p-2">
                        </div>

                        <div class="bg-indigo-50/30 p-5 rounded-2xl border border-indigo-100/50 space-y-2">
                            <label class="block text-[10px] font-bold text-indigo-400 uppercase tracking-[0.15em] font-mono">Longitude</label>
                            <input type="text" name="longitude" value="{{ old('longitude', $location->longitude ?? '') }}" 
                                required placeholder="85.3240"
                                class="w-full bg-white border-slate-200 rounded-lg font-mono text-sm text-indigo-600 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                        </div>
                    </div>

                    <div class="flex items-center gap-4 px-4 py-3 bg-slate-900 rounded-xl">
                        <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-indigo-500 rounded-full text-white text-xs font-bold italic">i</div>
                        <p class="text-[10px] text-slate-300 leading-normal font-medium uppercase tracking-wide">
                            Geodata accuracy affects global map rendering. <br class="hidden md:block"> Ensure coordinates are in <span class="text-indigo-400 font-mono">decimal format</span>.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex flex-col md:flex-row items-center gap-4 pt-4">
                <button type="submit"
                    class="w-full md:flex-1 bg-indigo-600 text-white font-black py-5 rounded-2xl shadow-xl shadow-indigo-200 hover:bg-slate-900 hover:shadow-none transition-all duration-300 uppercase tracking-[0.2em] text-xs">
                    {{ isset($location) ? 'Update Registry' : 'Initialize Location' }}
                </button>

                <a href="{{ route('admin.locations.index') }}"
                    class="w-full md:w-auto md:px-12 bg-white border border-slate-200 text-slate-400 font-black py-5 rounded-2xl text-center hover:bg-slate-50 hover:text-slate-600 transition-all uppercase tracking-[0.2em] text-xs">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection