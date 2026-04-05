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
                        <span class="w-2 h-2 rounded-full bg-[#4ade80] animate-pulse"></span>
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
                            <p class="text-sm font-black text-white" id="total-count">{{ $locations->count() }}</p>
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
                    <p class="text-slate-500 text-sm font-medium mt-1">Locate verified EV stations and garages in real-time.
                    </p>
                </div>
                <div class="px-4 py-2 bg-white border border-slate-200 rounded-2xl flex items-center gap-3 shadow-sm">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-[#16a34a]"></span>
                    </span>
                    <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest">
                        <span id="station-count">{{ $locations->where('type', 'ev-station')->count() }}</span>
                        Stations &amp;
                        <span id="garage-count">{{ $locations->where('type', 'garage')->count() }}</span>
                        Garages Found
                    </span>
                </div>
            </div>

            <div
                class="bg-white border border-slate-200 rounded-[2rem] p-3 shadow-xl shadow-slate-200/50 flex flex-col lg:flex-row gap-3 h-[600px]">

                {{-- Sidebar --}}
                <div class="lg:w-80 flex flex-col gap-3 h-full">

                    {{-- Category Tabs --}}
                    <div class="bg-slate-100 p-1.5 rounded-2xl border border-slate-200 flex gap-1">
                        <button onclick="switchCategory('ev-station')" id="tab-station"
                            class="flex-1 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all bg-white text-emerald-600 shadow-sm border border-slate-200">
                            EV Stations
                        </button>
                        <button onclick="switchCategory('garage')" id="tab-garage"
                            class="flex-1 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all text-slate-500 hover:text-slate-700">
                            Garages
                        </button>
                    </div>

                    {{-- Visibility Toggle --}}
                    <div class="bg-slate-50 p-4 rounded-[1.5rem] border border-slate-100 space-y-3">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Visibility
                            Toggle</label>
                        <div class="flex flex-col gap-2">
                            <label
                                class="flex items-center justify-between p-3 bg-white border border-slate-200 rounded-xl cursor-pointer hover:border-emerald-500 transition-colors">
                                <span class="text-[10px] font-bold text-slate-600 uppercase">Show Active Nodes</span>
                                <input type="checkbox" id="show-markers" checked onchange="filterMarkers()"
                                    class="rounded border-slate-300 text-emerald-600 w-4 h-4">
                            </label>
                        </div>
                    </div>

                    {{-- Focus Navigation --}}
                    <div class="bg-slate-50 p-4 rounded-[1.5rem] border border-slate-100">
                        <label
                            class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 block italic">Focus
                            Navigation</label>
                        <div class="relative">
                            <select id="asset-selector" onchange="focusLocation(this.value)"
                                class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3.5 text-[11px] font-bold text-slate-700 outline-none appearance-none cursor-pointer shadow-sm focus:border-indigo-500 transition-all">
                                <option value="">Select Asset...</option>
                                {{-- Populated by JS --}}
                            </select>
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <button onclick="resetView()"
                        class="mt-auto w-full border border-slate-200 text-slate-400 py-4 rounded-2xl text-[9px] font-black uppercase tracking-[0.2em] hover:bg-slate-900 hover:text-[#4ade80] hover:border-slate-900 transition-all duration-300">
                        Reset Tactical View
                    </button>
                </div>

                {{-- Map --}}
                <div
                    class="flex-1 relative overflow-hidden rounded-[1.8rem] border border-slate-100 bg-slate-100 shadow-inner">
                    <div id="map" class="absolute inset-0 z-0"></div>
                </div>
            </div>

        </div>
    </section>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // All active locations from server (NewLocation model, is_active = true)
        const allLocations = @json($locations);

        // Track current state
        let currentCategory = 'ev-station';
        let currentMarkers = [];
        let markersVisible = true;

        // Initialize Leaflet Map centred on Kathmandu
        const map = L.map('map').setView([27.7172, 85.3240], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        // Custom icons
        const stationIcon = L.divIcon({
            className: '',
            html: `<div style="background:#16a34a;width:28px;height:28px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,0.3)"></div>`,
            iconSize: [28, 28],
            iconAnchor: [14, 28],
            popupAnchor: [0, -30]
        });

        const garageIcon = L.divIcon({
            className: '',
            html: `<div style="background:#6366f1;width:28px;height:28px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,0.3)"></div>`,
            iconSize: [28, 28],
            iconAnchor: [14, 28],
            popupAnchor: [0, -30]
        });

        /**
         * Switch between EV Station and Garage tabs.
         * FIX: was filtering by 'ev' / 'garage' — now matches 'ev-station' / 'garage'
         * which is what LocationController stores in NewLocation.type.
         */
        function switchCategory(category) {
            currentCategory = category;

            // Update tab styles
            document.getElementById('tab-station').className =
                'flex-1 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all ' +
                (category === 'ev-station' ?
                    'bg-white text-emerald-600 shadow-sm border border-slate-200' :
                    'text-slate-500 hover:text-slate-700');
            document.getElementById('tab-garage').className =
                'flex-1 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all ' +
                (category === 'garage' ?
                    'bg-white text-indigo-600 shadow-sm border border-slate-200' :
                    'text-slate-500 hover:text-slate-700');

            // Rebuild markers and dropdown
            renderMarkers();
        }

        /**
         * FIX: was undefined — now defined.
         * Show or hide all current markers based on checkbox state.
         */
        function filterMarkers() {
            markersVisible = document.getElementById('show-markers').checked;
            currentMarkers.forEach(({
                marker
            }) => {
                if (markersVisible) {
                    marker.addTo(map);
                } else {
                    map.removeLayer(marker);
                }
            });
        }

        /**
         * FIX: was undefined — now defined.
         * Pan and zoom to the selected location.
         */
        function focusLocation(locationId) {
            if (!locationId) return;
            const found = currentMarkers.find(m => String(m.id) === String(locationId));
            if (found) {
                map.setView(found.marker.getLatLng(), 17, {
                    animate: true
                });
                found.marker.openPopup();
            }
        }

        function resetView() {
            map.setView([27.7172, 85.3240], 13, {
                animate: true
            });
            // Reset dropdown
            document.getElementById('asset-selector').value = '';
        }

        function renderMarkers() {
            // Clear existing markers from map
            currentMarkers.forEach(({
                marker
            }) => map.removeLayer(marker));
            currentMarkers = [];

            // Filter by active category
            const filtered = allLocations.filter(loc => loc.type === currentCategory);

            // Rebuild dropdown
            const selector = document.getElementById('asset-selector');
            selector.innerHTML = '<option value="">Select Asset...</option>';

            filtered.forEach(loc => {
                const icon = currentCategory === 'ev-station' ? stationIcon : garageIcon;
                const marker = L.marker([loc.latitude, loc.longitude], {
                        icon
                    })
                    .bindPopup(`
                        <div style="min-width:180px;font-family:inherit;padding:4px">
                            <p style="font-weight:900;font-size:12px;color:#1e293b;margin:0 0 4px">${loc.address}</p>
                            <span style="font-size:10px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em">
                                ${loc.type === 'ev-station' ? '⚡ EV Station' : '🔧 Garage'}
                            </span>
                        </div>
                    `);

                if (markersVisible) {
                    marker.addTo(map);
                }

                currentMarkers.push({
                    id: loc.id,
                    marker
                });

                // Add to dropdown
                const option = document.createElement('option');
                option.value = loc.id;
                option.textContent = (loc.type === 'ev-station' ? '⚡ ' : '🔧 ') + loc.address;
                selector.appendChild(option);
            });
        }

        // Boot with EV Stations
        switchCategory('ev-station');
    </script>

    <style>
        .leaflet-container {
            background: #f1f5f9;
            font-family: inherit;
        }

        .leaflet-popup-content-wrapper {
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            padding: 0;
        }

        .leaflet-popup-content {
            margin: 12px 16px;
        }
    </style>
@endsection
