@extends('admin.layout')
@section('title', 'Map Location Requests')
@section('page-title', 'Map Location Requests')

@section('content')

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <div class="text-2xl font-black text-amber-500">{{ $pending->count() }}</div>
            <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Awaiting Approval</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <div class="text-2xl font-black text-emerald-500">{{ $approved->count() }}</div>
            <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Live on Map</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <div class="text-2xl font-black text-indigo-500">{{ $pending->where('type', 'ev-station')->count() }} /
                {{ $pending->where('type', 'garage')->count() }}</div>
            <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">EV / Garage Pending</div>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-bold px-5 py-3 rounded-2xl">
            {{ session('success') }}
        </div>
    @endif

    {{-- ── PENDING MAP LOCATION REQUESTS ── --}}
    <div class="mb-10">
        <div class="flex items-center gap-3 mb-4">
            <h2 class="text-sm font-black text-gray-700 uppercase tracking-widest">Pending Map Requests</h2>
            @if ($pending->count() > 0)
                <span
                    class="text-[10px] font-black bg-amber-100 text-amber-700 border border-amber-200 px-2 py-0.5 rounded-full">
                    {{ $pending->count() }} pending
                </span>
            @endif
        </div>

        @if ($pending->isEmpty())
            <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center">
                <p class="text-sm font-bold text-gray-400">No pending map location requests</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($pending as $loc)
                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                        <div class="p-5">
                            <div class="flex flex-col lg:flex-row lg:items-start gap-4">

                                {{-- Info --}}
                                <div class="flex-1 grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Owner</p>
                                        <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $loc->user->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $loc->user->email }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Type</p>
                                        <span
                                            class="inline-block mt-0.5 text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded-lg
                                        {{ $loc->type === 'ev-station' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-violet-50 text-violet-600 border border-violet-200' }}">
                                            {{ $loc->type === 'ev-station' ? '⚡ EV Station' : '🔧 Garage' }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Address
                                        </p>
                                        <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $loc->address }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                            Coordinates</p>
                                        <p class="text-xs font-mono font-bold text-gray-600 mt-0.5">
                                            {{ number_format($loc->latitude, 5) }},
                                            {{ number_format($loc->longitude, 5) }}</p>
                                        <p class="text-xs text-gray-400">{{ $loc->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="flex items-center gap-2 shrink-0">
                                    <button
                                        onclick="toggleMap('map-{{ $loc->id }}', {{ $loc->latitude }}, {{ $loc->longitude }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-lg text-xs font-bold text-slate-700 transition-all">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Preview Map
                                    </button>

                                    <form method="POST" action="{{ route('admin.map_locations.approve', $loc->id) }}">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-black uppercase tracking-wider transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            Approve
                                        </button>
                                    </form>

                                    <button onclick="toggleRejectForm('reject-loc-{{ $loc->id }}')"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-50 hover:bg-red-100 border border-red-200 text-red-600 rounded-lg text-xs font-black uppercase tracking-wider transition-all">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Reject
                                    </button>
                                </div>
                            </div>

                            {{-- Inline map preview (hidden by default) --}}
                            <div id="map-{{ $loc->id }}" class="hidden mt-4 pt-4 border-t border-gray-100">
                                <div id="map-container-{{ $loc->id }}"
                                    class="w-full h-48 rounded-xl border border-gray-200 overflow-hidden"></div>
                            </div>

                            {{-- Reject form (hidden by default) --}}
                            <div id="reject-loc-{{ $loc->id }}" class="hidden mt-4 pt-4 border-t border-gray-100">
                                <form method="POST" action="{{ route('admin.map_locations.reject', $loc->id) }}"
                                    class="flex gap-3">
                                    @csrf
                                    <input type="text" name="reason" required placeholder="Reason for rejection..."
                                        class="flex-1 bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-red-400 transition-all">
                                    <button type="submit"
                                        class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-black uppercase tracking-wider shrink-0">
                                        Confirm Reject
                                    </button>
                                    <button type="button" onclick="toggleRejectForm('reject-loc-{{ $loc->id }}')"
                                        class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-xs font-bold shrink-0">
                                        Cancel
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ── APPROVED / LIVE LOCATIONS ── --}}
    @if ($approved->count() > 0)
        <div>
            <h2 class="text-sm font-black text-gray-700 uppercase tracking-widest mb-4">Live on Map</h2>
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/50">
                            <th class="text-left px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                Owner</th>
                            <th class="text-left px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                Type</th>
                            <th class="text-left px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                Address</th>
                            <th class="text-left px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                Coordinates</th>
                            <th class="px-5 py-3 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($approved as $loc)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-3.5">
                                    <div class="font-bold text-gray-800">{{ $loc->user->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $loc->user->email }}</div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span
                                        class="text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded-lg
                                {{ $loc->type === 'ev-station' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-violet-50 text-violet-600 border border-violet-200' }}">
                                        {{ $loc->type === 'ev-station' ? '⚡ EV Station' : '🔧 Garage' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-xs text-gray-700">{{ $loc->address }}</td>
                                <td class="px-5 py-3.5 text-xs font-mono text-gray-500">
                                    {{ number_format($loc->latitude, 4) }}, {{ number_format($loc->longitude, 4) }}</td>
                                <td class="px-5 py-3.5 text-right">
                                    <form method="POST" action="{{ route('admin.map_locations.reject', $loc->id) }}"
                                        onsubmit="return confirm('Remove this location from the public map?')">
                                        @csrf
                                        <input type="hidden" name="reason" value="Removed by admin">
                                        <button type="submit"
                                            class="text-[10px] font-black uppercase tracking-widest text-red-400 hover:text-red-600 transition-colors">
                                            Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <script>
        const mapInstances = {};

        function toggleMap(containerId, lat, lng) {
            const wrapper = document.getElementById(containerId);
            wrapper.classList.toggle('hidden');

            if (!wrapper.classList.contains('hidden')) {
                const mapDivId = 'map-container-' + containerId.replace('map-', '');
                if (!mapInstances[mapDivId]) {
                    const m = L.map(mapDivId).setView([lat, lng], 16);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap contributors',
                        maxZoom: 19
                    }).addTo(m);
                    L.marker([lat, lng]).addTo(m);
                    mapInstances[mapDivId] = m;
                }
            }
        }

        function toggleRejectForm(id) {
            const el = document.getElementById(id);
            el.classList.toggle('hidden');
            if (!el.classList.contains('hidden')) {
                el.querySelector('input[name="reason"]').focus();
            }
        }
    </script>

@endsection
