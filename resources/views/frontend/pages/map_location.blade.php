@extends('frontend.app')

<title>Map Location | BijuliCar</title>

@section('content')
    <section class="relative bg-[#0a0f1e] pt-[110px] pb-8 lg:pt-32 lg:pb-15 overflow-hidden border-b border-white/5">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1542362567-b07e54358753?auto=format&fit=crop&q=80&w=2071"
                class="w-full h-full object-cover opacity-20 lg:opacity-30 blur-[4px] scale-105" alt="Map Background">
            <div class="absolute inset-0 bg-gradient-to-b from-[#0a0f1e] via-transparent to-[#0a0f1e]"></div>
            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(74,222,128,0.08)_0%,_transparent_50%)]">
            </div>
            <div
                class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-[#4ade80]/30 to-transparent">
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="flex -space-x-2">
                            <span class="w-2 h-2 rounded-full bg-[#4ade80] animate-pulse"></span>
                        </div>
                        <span class="text-[10px] uppercase tracking-[0.4em] text-[#4ade80] font-black">Interactive
                            Network</span>
                    </div>

                    <h1 class="text-4xl md:text-5xl font-black text-white uppercase italic tracking-tighter leading-none">
                        Unit <span class="text-slate-400">Locator</span>
                    </h1>

                    <p class="text-slate-300 text-sm max-w-md font-medium leading-relaxed">
                        Pinpoint verified inventory across <span class="text-[#4ade80]">Nepal</span>. Real-time availability
                        synced with local dealer hubs.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-4 lg:gap-6">
                    <div
                        class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-2xl p-4 flex items-center gap-4 hover:border-[#4ade80]/30 transition-all cursor-pointer group">
                        <div class="p-2 bg-[#4ade80]/10 rounded-lg group-hover:bg-[#4ade80] transition-all duration-500">
                            <svg class="w-5 h-5 text-[#4ade80] group-hover:text-black" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Active Locations</p>
                            <p class="text-sm font-black text-white" id="total-count">{{ \App\Models\Location::count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-12 bg-[#f8fafc]">
        <div class="max-w-7xl mx-auto px-6">
            <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter">
                        Map <span class="text-[#16a34a]">Intelligence</span>
                    </h2>
                    <p class="text-slate-500 text-sm font-medium mt-1">Locate verified inventory and charging hubs in
                        real-time.</p>
                </div>
                <div class="px-4 py-2 bg-white border border-slate-200 rounded-2xl flex items-center gap-3 shadow-sm">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-[#16a34a]"></span>
                    </span>
                    <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest">
                        <span id="station-count">{{ \App\Models\Location::where('type', 'station')->count() }}</span>
                        Stations &
                        <span id="vehicle-count">{{ \App\Models\Location::where('type', 'vehicle')->count() }}</span>
                        Vehicles Found
                    </span>
                </div>
            </div>
            {{-- @php
                $locations = [
                    (object) [
                        'id' => 1,
                        'name' => 'Central Station Alpha',
                        'type' => 'station',
                        'latitude' => 27.7172,
                        'longitude' => 85.324,
                        'address' => 'Durbar Marg, Kathmandu',
                    ],
                    (object) [
                        'id' => 2,
                        'name' => 'Electric Hub Beta',
                        'type' => 'station',
                        'latitude' => 27.671,
                        'longitude' => 85.321,
                        'address' => 'Pulchowk, Lalitpur',
                    ],
                    (object) [
                        'id' => 3,
                        'name' => 'Scooter #402',
                        'type' => 'vehicle',
                        'latitude' => 27.7,
                        'longitude' => 85.3,
                        'address' => 'Basantapur Square',
                    ],
                    (object) [
                        'id' => 4,
                        'name' => 'Delivery Bike #12',
                        'type' => 'vehicle',
                        'latitude' => 27.685,
                        'longitude' => 85.345,
                        'address' => 'Koteshwor Junction',
                    ],
                ];
            @endphp --}}

            <div
                class="bg-white border border-slate-200 rounded-[2rem] p-3 shadow-xl shadow-slate-200/50 flex flex-col lg:flex-row gap-3 h-[550px]">
                <div class="lg:w-72 flex flex-col gap-3 h-full">
                    <div class="bg-slate-50 p-4 rounded-[1.5rem] border border-slate-100">
                        <label
                            class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 block italic">Focus
                            Navigation</label>
                        <div class="relative group">
                            <select id="asset-selector" onchange="focusLocation(this.value)"
                                class="w-full bg-white border border-slate-200 rounded-xl px-3 py-3 text-[11px] font-bold text-slate-700 outline-none transition-all appearance-none cursor-pointer shadow-sm">
                                <option value="">Select Asset...</option>
                                @foreach ($locations as $loc)
                                    <option value="{{ $loc->latitude }},{{ $loc->longitude }}"
                                        data-address="{{ $loc->address }}">
                                        {{ $loc->type == 'station' ? '⚡' : '🚲' }} {{ $loc->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-[1.5rem] border border-slate-100 space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Filters</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label
                                class="flex items-center gap-2 p-2 bg-white border border-slate-200 rounded-lg cursor-pointer">
                                <input type="checkbox" id="show-stations" checked onchange="filterMarkers()"
                                    class="rounded border-slate-300 text-emerald-600 w-3 h-3">
                                <span class="text-[10px] font-bold text-slate-600 uppercase">Stations</span>
                            </label>
                            <label
                                class="flex items-center gap-2 p-2 bg-white border border-slate-200 rounded-lg cursor-pointer">
                                <input type="checkbox" id="show-vehicles" checked onchange="filterMarkers()"
                                    class="rounded border-slate-300 text-blue-500 w-3 h-3">
                                <span class="text-[10px] font-bold text-slate-600 uppercase">Vehicles</span>
                            </label>
                        </div>
                    </div>

                    <button onclick="resetView()"
                        class="mt-auto w-full border border-slate-200 text-slate-500 py-3 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-white hover:text-indigo-600 transition-all">
                        Reset View
                    </button>
                </div>

                <div class="flex-1 relative overflow-hidden rounded-[1.5rem] border border-slate-100 bg-slate-100">
                    <div id="map" class="absolute inset-0 z-0"></div>

                    <div
                        class="absolute bottom-4 right-4 z-[1000] bg-slate-900/90 backdrop-blur-md px-3 py-2 rounded-xl border border-slate-800 shadow-xl">
                        <p class="text-[10px] font-mono font-bold text-white tracking-tighter" id="coords-display">READY</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // 1. Single Data Source
        const locations = @json(\App\Models\Location::all());
        const defaultView = [27.7172, 85.3240];

        // 2. Map Setup
        const map = L.map('map', {
            zoomControl: false
        }).setView(defaultView, 13);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png').addTo(map);
        L.control.zoom({
            position: 'bottomright'
        }).addTo(map);

        let markers = [];

        // 3. Optimized Marker & UI Sync
        const icons = {
            station: L.divIcon({
                className: 'custom-icon',
                html: '<div class="bg-emerald-500 w-3 h-3 rounded-full border-2 border-white shadow-lg"></div>'
            }),
            vehicle: L.divIcon({
                className: 'custom-icon',
                html: '<div class="bg-blue-500 w-3 h-3 rounded-full border-2 border-white shadow-lg"></div>'
            })
        };

        locations.forEach(loc => {
            const marker = L.marker([loc.latitude, loc.longitude], {
                    icon: icons[loc.type]
                })
                .addTo(map)
                .bindPopup(
                    `<b class="text-sm">${loc.name}</b><br><span class="text-xs text-slate-500">${loc.address ?? ''}</span>`
                );

            markers.push({
                marker,
                type: loc.type
            });
        });

        // 4. Client-side Filtering (No DB hits)
        function filterMarkers() {
            const states = {
                station: document.getElementById('show-stations').checked,
                vehicle: document.getElementById('show-vehicles').checked
            };

            markers.forEach(({
                marker,
                type
            }) => {
                states[type] ? marker.addTo(map) : map.removeLayer(marker);
            });
        }

        function focusLocation(val) {
            if (!val) return resetView();
            const [lat, lng] = val.split(',');
            map.flyTo([lat, lng], 17);
            // Toggle detail card visibility
            document.getElementById('asset-detail-card').classList.remove('hidden');
        }

        function resetView() {
            map.flyTo(defaultView, 13);
            document.getElementById('asset-detail-card').classList.add('hidden');
        }
    </script>

    <style>
        .leaflet-container {
            background: #f1f5f9;
            font-family: inherit;
        }

        .leaflet-popup-content-wrapper {
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .leaflet-popup-content {
            margin: 0;
        }

        .custom-icon {
            background: transparent;
            border: none;
        }
    </style>
@endsection
