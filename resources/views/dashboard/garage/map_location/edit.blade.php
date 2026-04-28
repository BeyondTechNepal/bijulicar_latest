@extends('dashboard.garage.layout')
@section('title', 'Edit Map Location')
@section('page-title', 'Edit Map Location')

@section('content')

<div class="max-w-2xl">
    <p class="text-slate-500 text-sm font-medium mb-8">
        Update your garage's pin on the public map. Click, search, or type coordinates.
    </p>

    <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">

        <div class="relative">
            <div id="map" class="w-full h-72 z-[1]"></div>
            <div class="absolute top-3 left-3 right-3 z-[1]">
                <div class="flex gap-2">
                    <input type="text" id="search-input" placeholder="Search new address…"
                        class="flex-1 px-4 py-2.5 rounded-xl bg-white shadow-md border border-slate-200 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-amber-400" />
                    <button onclick="searchAddress()"
                        class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-sm font-black shadow-md transition-all">
                        Search
                    </button>
                </div>
            </div>
        </div>

        <div class="p-6 border-t border-slate-100">
            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-5">
                📍 Click map, search, or type coordinates
            </p>

            <form method="POST" action="{{ route('garage.location.update') }}">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-1.5">Address</label>
                        <input type="text" name="address" id="address"
                            value="{{ old('address', $location->address) }}"
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-amber-400 @error('address') border-red-400 @enderror" />
                        @error('address')
                            <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-1.5">Latitude</label>
                            <input type="text" name="latitude" id="latitude"
                                oninput="manualLocationUpdate()"
                                value="{{ old('latitude', $location->latitude) }}"
                                class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-mono font-medium focus:outline-none focus:ring-2 focus:ring-amber-400 @error('latitude') border-red-400 @enderror" />
                            @error('latitude')
                                <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-1.5">Longitude</label>
                            <input type="text" name="longitude" id="longitude"
                                oninput="manualLocationUpdate()"
                                value="{{ old('longitude', $location->longitude) }}"
                                class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm font-mono font-medium focus:outline-none focus:ring-2 focus:ring-amber-400 @error('longitude') border-red-400 @enderror" />
                            @error('longitude')
                                <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    @if (!$location->is_active)
                        <p class="text-[11px] text-amber-600 font-medium bg-amber-50 border border-amber-200 rounded-xl px-4 py-2.5">
                            ⏳ Your location is pending verification. Updating it will not reset your verification status.
                        </p>
                    @endif
                </div>

                <div class="flex items-center gap-3 mt-6 pt-5 border-t border-slate-100">
                    <button type="submit"
                        class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-black uppercase tracking-wider rounded-xl transition-all">
                        Update Location
                    </button>
                    <a href="{{ route('garage.location.index') }}"
                        class="px-5 py-2.5 text-slate-500 hover:text-slate-800 text-sm font-bold transition-all">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    const initLat = parseFloat("{{ old('latitude', $location->latitude) }}") || 27.7172;
    const initLng = parseFloat("{{ old('longitude', $location->longitude) }}") || 85.3240;

    const map = L.map('map').setView([initLat, initLng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    let marker = null;

    function placeMarker(lat, lng, label, shouldPan = true) {
        if (isNaN(lat) || isNaN(lng)) return;

        if (marker) map.removeLayer(marker);
        marker = L.marker([lat, lng]).addTo(map);

        if (label) {
            marker.bindPopup(label).openPopup();
            document.getElementById('address').value = label;
        }

        document.getElementById('latitude').value = lat.toFixed(6);
        document.getElementById('longitude').value = lng.toFixed(6);

        if (shouldPan) {
            map.panTo([lat, lng]);
        }
    }

    function manualLocationUpdate() {
        const lat = parseFloat(document.getElementById('latitude').value);
        const lng = parseFloat(document.getElementById('longitude').value);

        if (
            !isNaN(lat) && !isNaN(lng) &&
            lat >= -90 && lat <= 90 &&
            lng >= -180 && lng <= 180
        ) {
            placeMarker(lat, lng, null, true);
            map.setView([lat, lng], 15);
        }
    }

    map.on('click', function (e) {
        placeMarker(e.latlng.lat, e.latlng.lng, null, false);
    });

    async function searchAddress() {
        const q = document.getElementById('search-input').value.trim();
        if (!q) return;

        const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(q)}&limit=1`);
        const data = await res.json();

        if (data.length) {
            const { lat, lon, display_name } = data[0];
            map.setView([lat, lon], 16);
            placeMarker(parseFloat(lat), parseFloat(lon), display_name);
        } else {
            alert('Location not found.');
        }
    }

    document.getElementById('search-input').addEventListener('keydown', e => {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchAddress();
        }
    });

    // Initialize existing location
    window.onload = () => {
        placeMarker(initLat, initLng, "{{ old('address', $location->address) }}");
    };
</script>

@endsection