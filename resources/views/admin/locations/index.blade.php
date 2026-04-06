@extends('admin.layout')

@section('title', 'Map Location Requests')

@section('content')
<div class="p-6">
    {{-- Header Section --}}
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Map Location Requests</h1>
        {{-- <a href="{{ route('admin.locations.create') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest hover:shadow-lg transition-all border-b-4 border-indigo-800 active:border-b-0 active:translate-y-1">
            + Add New Location
        </a> --}}
    </div>

    {{-- Main Table Card --}}
    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
        @if(session('success'))
            <div class="bg-green-50 border-b border-green-100 px-6 py-4 text-green-600 text-xs font-bold">
                {{ session('success') }}
            </div>
        @endif

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100">
                    <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest">Location Info</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest">Type</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest">Submitted By</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Status</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($locations as $location)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    {{-- Location Info --}}
                    <td class="px-6 py-4">
                        <p class="font-bold text-slate-700 text-sm">{{ Str::limit($location->address, 50) }}</p>
                        <p class="text-[10px] text-slate-400 font-mono tracking-tighter">
                            LAT: {{ $location->latitude }} / LNG: {{ $location->longitude }}
                        </p>
                    </td>

                    {{-- Type --}}
                    <td class="px-6 py-4">
                        <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-[10px] font-black uppercase tracking-tighter">
                            {{ $location->type }}
                        </span>
                    </td>

                    {{-- User --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-md bg-indigo-100 text-[10px] flex items-center justify-center font-black text-indigo-600 uppercase">
                                {{ substr($location->user->name ?? 'S', 0, 1) }}
                            </span>
                            <span class="text-xs font-bold text-slate-600">{{ $location->user->name ?? 'System' }}</span>
                        </div>
                    </td>

                    {{-- Status Toggle --}}
                    <td class="px-6 py-4 text-center">
                        <form action="{{ route('admin.locations.toggle', $location->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest transition-all {{ $location->is_active ? 'bg-green-100 text-green-600 hover:bg-green-200' : 'bg-amber-100 text-amber-600 hover:bg-amber-200' }}">
                                {{ $location->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </form>
                    </td>

                    {{-- Actions --}}
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end items-center gap-4">
                            <a href="{{ route('admin.locations.edit', $location->id) }}" class="text-indigo-600 hover:text-indigo-900 font-black text-[10px] uppercase tracking-widest">Edit</a>
                            
                            <form action="{{ route('admin.locations.destroy', $location->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Delete permanently?')" class="text-red-400 hover:text-red-600 font-black text-[10px] uppercase tracking-widest">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    {{-- Pagination --}}
    <div class="mt-6">
        {{ $locations->links() }}
    </div>
</div>
@endsection