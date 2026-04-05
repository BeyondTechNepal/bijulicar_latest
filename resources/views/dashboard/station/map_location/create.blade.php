@extends('dashboard.station.layout')
@section('title', 'Add Map Location')
@section('page-title', 'Add Map Location')

@section('content')

    <div class="max-w-2xl">
        <p class="text-slate-500 text-sm font-medium mb-8">
            Pin your EV station on the public map. Use the map below to find your exact coordinates, or search by address.
        </p>

        <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">

            {{-- Interactive map picker --}}
            <div class="relative">
                <div id="map" class="w-full h-72"></div>
                <div class="absolute top-3 left-3 right-3 z-[1000]">
                    <div class="flex gap-2">
                        <input type="text" id="search-input" placeholder="Search address…"
                            class="flex-1 px-4 py-2.5 rounded-xl bg-white shadow-md border border-slate-200 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-emerald-400" />
                        <button onclick="searchAddress()"
                            class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-sm font-black shadow-md transition-all">
                            Search
                        </button>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-slate-100">
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-5">
                    📍 Click the map to place your pin, or search above
                </p>

                <form method="POST" action="{{ route('station.location.store') }}" id="location-form">
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-1.5">Address</label>
                            <input type="text" name="address" id="address" value="{{ old('address') }}"
                                placeholder="e.g. Thamel, Kathmandu, Nepal"
                                class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-emerald-400 @error('address') border-red-400 @enderror" />
                            @error('address')
                                <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-1.5">Latitude</label>
                                <input type="text" name="latitude" id="latitude" oninput="manualLocationUpdate()"
                                    value="{{ old('latitude') }}" placeholder="27.7172"
                                    class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-mono font-medium focus:outline-none focus:ring-2 focus:ring-emerald-400 @error('latitude') border-red-400 @enderror" />
                                @error('latitude')
                                    <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-1.5">Longitude</label>
                                <input type="text" name="longitude" id="longitude" oninput="manualLocationUpdate()"
                                    value="{{ old('longitude') }}" placeholder="85.3240"
                                    class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-mono font-medium focus:outline-none focus:ring-2 focus:ring-emerald-400 @error('longitude') border-red-400 @enderror" />
                                @error('longitude')
                                    <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <p class="text-[11px] text-slate-400 font-medium">
                            ℹ️ Your location will appear on the public map after your account verification is approved.
                        </p>
                    </div>

                    <div class="flex items-center gap-3 mt-6 pt-5 border-t border-slate-100">
                        <button type="submit" id="submit-btn" disabled
                            class="px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 disabled:opacity-40 disabled:cursor-not-allowed text-white text-sm font-black uppercase tracking-wider rounded-xl transition-all">
                            Save Location
                        </button>
                        <a href="{{ route('station.location.index') }}"
                            class="px-5 py-2.5 text-slate-500 hover:text-slate-800 text-sm font-bold transition-all">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        const defaultLat = 27.7172,
            defaultLng = 85.3240;
        const map = L.map('map').setView([defaultLat, defaultLng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        let marker = null;

        // Core function to handle marker placement
        function placeMarker(lat, lng, label, shouldPan = true) {
            if (isNaN(lat) || isNaN(lng)) return;

            if (marker) map.removeLayer(marker);
            marker = L.marker([lat, lng]).addTo(map);

            if (label) {
                marker.bindPopup(label).openPopup();
                document.getElementById('address').value = label;
            }

            // Update input values (only if not already typing)
            document.getElementById('latitude').value = lat.toFixed(6);
            document.getElementById('longitude').value = lng.toFixed(6);

            document.getElementById('submit-btn').disabled = false;

            if (shouldPan) {
                map.panTo([lat, lng]);
            }
        }

        // Function for when the user TYPES manually
        function manualLocationUpdate() {
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');

            const lat = parseFloat(latInput.value);
            const lng = parseFloat(lngInput.value);

            // ✅ Only run when BOTH values are valid
            if (
                !isNaN(lat) && !isNaN(lng) &&
                lat >= -90 && lat <= 90 &&
                lng >= -180 && lng <= 180
            ) {
                // Place marker AND move map
                placeMarker(lat, lng, null, true);

                // Zoom in nicely
                map.setView([lat, lng], 15);
            }
        }

        // Map click listener
        map.on('click', function(e) {
            placeMarker(e.latlng.lat, e.latlng.lng, null, false);
        });

        // Address Search logic
        async function searchAddress() {
            const q = document.getElementById('search-input').value.trim();
            if (!q) return;
            try {
                const res = await fetch(
                    `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(q)}&limit=1`);
                const data = await res.json();
                if (data.length) {
                    const {
                        lat,
                        lon,
                        display_name
                    } = data[0];
                    const latitude = parseFloat(lat);
                    const longitude = parseFloat(lon);
                    map.setView([latitude, longitude], 16);
                    placeMarker(latitude, longitude, display_name);
                } else {
                    alert('Location not found.');
                }
            } catch (error) {
                console.error("Search failed", error);
            }
        }

        // Prevent enter key from submitting form on search
        document.getElementById('search-input').addEventListener('keydown', e => {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchAddress();
            }
        });

        // Initialization for old values
        window.onload = () => {
            const oldLat = "{{ old('latitude') }}";
            const oldLng = "{{ old('longitude') }}";
            if (oldLat && oldLng) {
                placeMarker(parseFloat(oldLat), parseFloat(oldLng), "{{ old('address') }}");
                map.setView([parseFloat(oldLat), parseFloat(oldLng)], 15);
            }
        };
    </script>

@endsection
