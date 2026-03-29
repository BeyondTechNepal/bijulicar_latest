@extends('admin.layout')
@section('title', 'Dashboard')
@section('page-title', 'System Overview')

@section('content')
    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @php
            $cards = [
                ['label' => 'Total Users', 'value' => $stats['total_users'], 'color' => 'blue', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                ['label' => 'Active Buyers', 'value' => $stats['buyers'], 'color' => 'emerald', 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
                ['label' => 'Verified Sellers', 'value' => $stats['sellers'], 'color' => 'amber', 'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z'],
                ['label' => 'Businesses', 'value' => $stats['businesses'], 'color' => 'indigo', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4']
            ];
            
            // Map colors to classes because Tailwind needs full class names
            $bgColors = ['blue' => 'bg-blue-500', 'emerald' => 'bg-emerald-500', 'amber' => 'bg-amber-500', 'indigo' => 'bg-indigo-500'];
            $textColors = ['blue' => 'text-blue-600', 'emerald' => 'text-emerald-600', 'amber' => 'text-amber-600', 'indigo' => 'text-indigo-600'];
            $softBgs = ['blue' => 'bg-blue-50', 'emerald' => 'bg-emerald-50', 'amber' => 'bg-amber-50', 'indigo' => 'bg-indigo-50'];
        @endphp

        @foreach ($cards as $stat)
            <div class="bg-white border border-slate-200 rounded-3xl p-6 transition-all hover:shadow-lg hover:-translate-y-1 group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-2.5 {{ $softBgs[$stat['color']] }} {{ $textColors[$stat['color']] }} rounded-2xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"></path>
                        </svg>
                    </div>
                    <span class="text-[10px] font-black {{ $textColors[$stat['color']] }} opacity-50 uppercase tracking-widest">Live</span>
                </div>
                
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $stat['label'] }}</p>
                <h3 class="text-3xl font-black text-slate-900 mt-1">{{ number_format($stat['value']) }}</h3>
                
                <div class="mt-5 h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full {{ $bgColors[$stat['color']] }} rounded-full" style="width: 70%"></div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Security / Permissions Audit --}}
    <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-gray-50/50">
            <div>
                <h3 class="font-black text-slate-800 text-sm tracking-tight">Access Control Audit</h3>
                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest mt-1">Current Session Permissions</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-[10px] bg-slate-100 text-slate-600 px-3 py-1 rounded-full uppercase font-black tracking-tighter border border-slate-200">
                    Guard: admin
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left bg-white text-xs">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-4 font-bold text-slate-400 uppercase tracking-widest text-[9px]">ID</th>
                        <th class="px-8 py-4 font-bold text-slate-400 uppercase tracking-widest text-[9px]">Permission Token</th>
                        <th class="px-8 py-4 font-bold text-slate-400 uppercase tracking-widest text-[9px]">Scope</th>
                        <th class="px-8 py-4 font-bold text-slate-400 uppercase tracking-widest text-[9px] text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse (auth('admin')->user()->getAllPermissions() as $perm)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-8 py-4 text-slate-400 font-mono text-[10px]">#{{ str_pad($perm->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-8 py-4">
                                <span class="font-bold text-slate-700 bg-slate-100 px-2 py-1 rounded border border-slate-200 uppercase tracking-tighter text-[10px]">
                                    {{ $perm->name }}
                                </span>
                            </td>
                            <td class="px-8 py-4">
                                <span class="text-slate-500 italic text-[11px]">{{ $perm->guard_name }}</span>
                            </td>
                            <td class="px-8 py-4 text-right">
                                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">Active</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-12 text-center text-slate-400 italic">No permissions assigned to this session.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection