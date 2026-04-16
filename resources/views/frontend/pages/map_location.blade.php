@extends('frontend.app')

<title>Map | BijuliCar</title>

@section('content')

    {{-- ── Hero ──────────────────────────────────────────────────────────── --}}
    <section class="relative bg-[#0a0f1e] pt-[110px] pb-8 lg:pt-32 lg:pb-15 overflow-hidden border-b border-white/5">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1542362567-b07e54358753?auto=format&fit=crop&q=80&w=2071"
                class="w-full h-full object-cover opacity-20 lg:opacity-30 blur-[4px] scale-105" alt="Map Background">
            <div class="absolute inset-0 bg-gradient-to-b from-[#0a0f1e] via-transparent to-[#0a0f1e]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(74,222,128,0.08)_0%,_transparent_50%)]"></div>
            <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-[#4ade80]/30 to-transparent"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-[#4ade80] animate-pulse"></span>
                        <span class="text-[10px] uppercase tracking-[0.4em] text-[#4ade80] font-black">Live Network</span>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-black text-white uppercase italic tracking-tighter leading-none">
                        Unit <span class="text-slate-400">Locator</span>
                    </h1>
                    <p class="text-slate-300 text-sm max-w-md font-medium leading-relaxed">
                        Find verified EV charging stations, garages, car sellers, and businesses across <span class="text-[#4ade80]">Nepal</span>.
                        Live slot availability updated in real-time.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-4">
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-4 flex items-center gap-4">
                        <div class="p-2 bg-[#4ade80]/10 rounded-lg">
                            <svg class="w-5 h-5 text-[#4ade80]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
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

    {{-- ── Map section ──────────────────────────────────────────────────── --}}
    <section class="py-12 bg-[#f8fafc]">
        <div class="max-w-7xl mx-auto px-6">

            {{-- Header row --}}
            <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter">
                        Map <span class="text-[#16a34a]">Intelligence</span>
                    </h2>
                    <p class="text-slate-500 text-sm font-medium mt-1">
                        Click any pin to see live availability and book directly.
                    </p>
                </div>
                <div class="px-4 py-2 bg-white border border-slate-200 rounded-2xl flex items-center gap-3 shadow-sm">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-[#16a34a]"></span>
                    </span>
                    <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest">
                        <span id="station-count">{{ $locations->where('type', 'ev-station')->count() }}</span> Stations &amp;
                        <span id="garage-count">{{ $locations->where('type', 'garage')->count() }}</span> Garages &amp;
                        <span id="seller-count">{{ $locations->where('type', 'seller')->count() }}</span> Sellers &amp;
                        <span id="business-count">{{ $locations->where('type', 'business')->count() }}</span> Businesses
                    </span>
                </div>
            </div>

            {{-- Map container --}}
            <div class="bg-white border border-slate-200 rounded-[2rem] p-3 shadow-xl shadow-slate-200/50 flex flex-col lg:flex-row gap-3"
                 style="height: 760px;">

                {{-- ── Sidebar ──────────────────────────────────────────── --}}
                <div class="lg:w-[420px] flex flex-col gap-3 h-full overflow-hidden">

                    {{-- Category tabs --}}
                    <div class="bg-slate-100 p-1.5 rounded-2xl border border-slate-200 grid grid-cols-3 gap-1.5 shrink-0">
                        <button onclick="switchCategory('ev-fuel')" id="tab-ev-fuel"
                            class="py-3.5 rounded-xl transition-all bg-white text-emerald-600 shadow-sm border border-slate-200 flex flex-col items-center gap-1.5">
                            <span class="text-lg leading-none">⚡⛽</span>
                            <span class="text-[9px] font-black uppercase tracking-wider leading-tight text-center">EV & Petrol</span>
                        </button>
                        <button onclick="switchCategory('garage')" id="tab-garage"
                            class="py-3.5 rounded-xl transition-all text-slate-400 hover:text-slate-600 flex flex-col items-center gap-1.5">
                            <span class="text-lg leading-none">🔧</span>
                            <span class="text-[9px] font-black uppercase tracking-wider leading-tight text-center">Garages</span>
                        </button>
                        <button onclick="switchCategory('seller-business')" id="tab-seller-business"
                            class="py-3.5 rounded-xl transition-all text-slate-400 hover:text-slate-600 flex flex-col items-center gap-1.5">
                            <span class="text-lg leading-none">🚗🏢</span>
                            <span class="text-[9px] font-black uppercase tracking-wider leading-tight text-center">Sellers & Biz</span>
                        </button>
                    </div>

                    {{-- Legend pills — shown per tab --}}
                    <div id="tab-legend" class="hidden bg-slate-50 rounded-xl border border-slate-100 px-4 py-2.5 shrink-0">
                        {{-- filled by switchCategory() --}}
                    </div>

                    {{-- Filters --}}
                    <div class="bg-slate-50 p-4 rounded-[1.5rem] border border-slate-100 space-y-3 shrink-0">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Filters</label>

                        <label class="flex items-center justify-between p-3 bg-white border border-slate-200 rounded-xl cursor-pointer hover:border-emerald-400 transition-colors">
                            <span class="text-[10px] font-bold text-slate-600 uppercase">Available only</span>
                            <input type="checkbox" id="filter-available" onchange="renderMarkers()"
                                class="rounded border-slate-300 text-emerald-600 w-4 h-4">
                        </label>

                        <label class="flex items-center justify-between p-3 bg-white border border-slate-200 rounded-xl cursor-pointer hover:border-emerald-400 transition-colors">
                            <span class="text-[10px] font-bold text-slate-600 uppercase">Show all pins</span>
                            <input type="checkbox" id="show-markers" checked onchange="filterMarkers()"
                                class="rounded border-slate-300 text-emerald-600 w-4 h-4">
                        </label>

                        {{-- Petrol pump radius — only on EV & Fuel tab --}}
                        <div id="fuel-radius-wrap" class="hidden p-3 bg-white border border-slate-200 rounded-xl space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold text-slate-600 uppercase">⛽ Pump search radius</span>
                                <span id="fuel-radius-label" class="text-[10px] font-black text-emerald-600">7 km</span>
                            </div>
                            <input type="range" id="fuel-radius" min="1" max="20" value="7" step="1"
                                oninput="document.getElementById('fuel-radius-label').textContent=this.value+' km'"
                                onchange="loadPetrolPumps()"
                                class="w-full accent-emerald-500 cursor-pointer">
                            <p class="text-[9px] text-slate-400 font-semibold">Requires your location · Powered by OpenStreetMap</p>
                        </div>
                    </div>

                    {{-- Focus navigation --}}
                    <div class="bg-slate-50 p-4 rounded-[1.5rem] border border-slate-100 shrink-0">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 block italic">
                            Jump to location
                        </label>
                        <div class="relative">
                            <select id="asset-selector" onchange="focusLocation(this.value)"
                                class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3.5 text-[11px] font-bold text-slate-700 outline-none appearance-none cursor-pointer shadow-sm focus:border-emerald-400 transition-all">
                                <option value="">Select location...</option>
                            </select>
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Fuel status toast --}}
                    <div id="fuel-status" class="hidden shrink-0 bg-orange-50 border border-orange-200 text-orange-700 text-[10px] font-bold px-4 py-2.5 rounded-xl"></div>

                    {{-- Location list --}}
                    <div class="flex-1 overflow-y-auto space-y-2 pr-0.5" id="location-list">
                        {{-- Filled by JS --}}
                    </div>

                    <button onclick="resetView()"
                        class="shrink-0 w-full border border-slate-200 text-slate-400 py-4 rounded-2xl text-[9px] font-black uppercase tracking-[0.2em] hover:bg-slate-900 hover:text-[#4ade80] hover:border-slate-900 transition-all duration-300">
                        Reset View
                    </button>
                </div>

                {{-- ── Map canvas ───────────────────────────────────────── --}}
                <div class="flex-1 relative overflow-hidden rounded-[1.8rem] border border-slate-100 bg-slate-100 shadow-inner">
                    <div id="map" class="absolute inset-0 z-0"></div>

                    {{-- My Location GPS button --}}
                    <button id="locate-btn" onclick="locateMe()" title="Show my location"
                        class="absolute bottom-4 right-4 z-[999] w-11 h-11 bg-white rounded-2xl shadow-lg border border-slate-200 flex items-center justify-center hover:bg-emerald-50 hover:border-emerald-400 transition-all duration-200 group">
                        <svg id="locate-icon" class="w-5 h-5 text-slate-500 group-hover:text-emerald-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm8.94 3A8.994 8.994 0 0013 3.06V1h-2v2.06A8.994 8.994 0 003.06 11H1v2h2.06A8.994 8.994 0 0011 20.94V23h2v-2.06A8.994 8.994 0 0020.94 13H23v-2h-2.06z"/>
                        </svg>
                    </button>

                    {{-- Loading overlay --}}
                    <div id="map-loading" class="absolute inset-0 z-10 bg-slate-100 flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-8 h-8 border-4 border-emerald-500 border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Loading live data...</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- ── Booking modal (shared for both types) ─────────────────────── --}}
    <div id="booking-modal" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">

            {{-- Modal header --}}
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
                <div>
                    <p id="modal-type-label" class="text-[10px] font-black uppercase tracking-widest text-emerald-600 mb-0.5"></p>
                    <h3 id="modal-title" class="text-base font-black text-slate-900 uppercase italic tracking-tight"></h3>
                </div>
                <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-xl bg-slate-100 hover:bg-slate-200 transition-colors text-slate-500 font-black text-sm">✕</button>
            </div>

            {{-- Modal body — filled by JS --}}
            <div id="modal-body" class="px-6 py-5"></div>

        </div>
    </div>

    {{-- ── Assets ───────────────────────────────────────────────────────── --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        .leaflet-container { background: #f1f5f9; font-family: inherit; }
        .leaflet-popup-content-wrapper { border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,.18); padding: 0; border: 1px solid #e2e8f0; }
        .leaflet-popup-content { margin: 0; }
        .leaflet-popup-tip-container { display: none; }
        .loc-card { cursor: pointer; background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 12px 14px; transition: border-color .15s, box-shadow .15s; }
        .loc-card:hover { border-color: #4ade80; box-shadow: 0 2px 12px rgba(22,163,74,.1); }
        .slot-pill { display: inline-flex; align-items: center; gap: 4px; font-size: 10px; font-weight: 800; padding: 3px 8px; border-radius: 999px; border: 1px solid; }
        .pill-green { background: #f0fdf4; color: #16a34a; border-color: #bbf7d0; }
        .pill-red { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
        .pill-amber { background: #fffbeb; color: #d97706; border-color: #fde68a; }
        .pill-gray { background: #f8fafc; color: #64748b; border-color: #e2e8f0; }
        @keyframes locatePulse {
            0%   { transform: scale(1); opacity: .6; }
            70%  { transform: scale(2.5); opacity: 0; }
            100% { transform: scale(1); opacity: 0; }
        }
    </style>

    <script>
        // ── Auth state passed from server ──────────────────────────────────
        const IS_AUTH   = {{ auth()->check() ? 'true' : 'false' }};
        const AUTH_ID   = {{ auth()->id() ?? 'null' }};
        const LOGIN_URL = "{{ route('login') }}";
        const BOOK_GARAGE_URL = "{{ route('booking.garage') }}";
        const BOOK_SLOT_URL   = "{{ route('booking.slot') }}";
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

        // ── State ──────────────────────────────────────────────────────────
        let allLocations   = [];
        let currentCategory = 'ev-fuel';
        let currentMarkers  = [];
        let markersVisible  = true;
        let activeLocation  = null;  // currently open in modal

        // ── Map init ───────────────────────────────────────────────────────
        const map = L.map('map').setView([27.7172, 85.3240], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors', maxZoom: 19
        }).addTo(map);

        // ── Custom marker icons ────────────────────────────────────────────
        function makeIcon(color, available) {
            const pulse = available
                ? `<span style="position:absolute;top:-3px;right:-3px;width:10px;height:10px;background:#4ade80;border-radius:50%;border:2px solid #fff;box-shadow:0 0 0 2px #4ade80"></span>`
                : '';
            return L.divIcon({
                className: '',
                html: `<div style="position:relative;width:32px;height:32px">
                           <div style="background:${color};width:32px;height:32px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 3px 10px rgba(0,0,0,.25)"></div>
                           ${pulse}
                       </div>`,
                iconSize: [32, 32],
                iconAnchor: [16, 32],
                popupAnchor: [0, -36]
            });
        }

        // ── Fetch enriched locations from API ──────────────────────────────
        async function loadLocations() {
            try {
                const res = await fetch('/api/map-locations');
                allLocations = await res.json();
                document.getElementById('map-loading').classList.add('hidden');
                renderMarkers();
            } catch (e) {
                console.error('Failed to load locations:', e);
                document.getElementById('map-loading').innerHTML =
                    '<p class="text-xs font-black text-red-400 uppercase tracking-widest">Failed to load. Refresh page.</p>';
            }
        }

        // ── Render markers + sidebar list ──────────────────────────────────
        function renderMarkers() {
            // Clear old markers
            currentMarkers.forEach(({ marker }) => map.removeLayer(marker));
            currentMarkers = [];
            document.getElementById('location-list').innerHTML = '';
            document.getElementById('asset-selector').innerHTML = '<option value="">Select location...</option>';

            const filterAvailable = document.getElementById('filter-available').checked;

            const filtered = allLocations.filter(loc => {
                if (currentCategory === 'ev-fuel') {
                    if (loc.type !== 'ev-station') return false;
                } else if (currentCategory === 'seller-business') {
                    if (loc.type !== 'seller' && loc.type !== 'business') return false;
                } else {
                    if (loc.type !== currentCategory) return false;
                }
                if (filterAvailable) {
                    if (loc.type === 'ev-station' && (loc.available_slots ?? 0) === 0) return false;
                    if (loc.type === 'garage'     && (loc.free_bays ?? 0) === 0) return false;
                    if (loc.type === 'seller'   && (loc.listing_count ?? 0) === 0) return false;
                    if (loc.type === 'business' && (loc.listing_count ?? 0) === 0) return false;
                }
                return true;
            });

            filtered.forEach(loc => {
                const isEV       = loc.type === 'ev-station';
                const isGarage   = loc.type === 'garage';
                const isSeller   = loc.type === 'seller';
                const isBusiness = loc.type === 'business';

                const hasSpace   = isEV ? (loc.available_slots > 0) : isGarage ? (loc.free_bays > 0) : ((loc.listing_count ?? 0) > 0);
                const hasAvailable = loc.available_slots > 0;
                const hasBooked    = (loc.booked_slots ?? 0) > 0;
                const iconColor    = isEV
                    ? (hasAvailable ? '#16a34a' : hasBooked ? '#f59e0b' : '#ef4444')
                    : isGarage
                        ? (hasSpace ? '#6366f1' : '#ef4444')
                        : isSeller
                            ? '#10b981'   // green for sellers
                            : '#8b5cf6';  // purple for businesses

                const marker = L.marker([loc.latitude, loc.longitude], { icon: makeIcon(iconColor, hasSpace) })
                    .addTo(map)
                    .bindPopup(buildPopup(loc), { maxWidth: 320, minWidth: 280 });

                marker.on('click', () => { activeLocation = loc; });

                currentMarkers.push({ id: loc.id, marker });

                // Sidebar card
                document.getElementById('location-list').insertAdjacentHTML('beforeend', buildSidebarCard(loc));

                // Dropdown option
                const opt = document.createElement('option');
                opt.value = loc.id;
                opt.textContent = (isEV ? '⚡ ' : isGarage ? '🔧 ' : isSeller ? '🚗 ' : '🏢 ') + (loc.name || loc.address);
                document.getElementById('asset-selector').appendChild(opt);
            });

            if (!markersVisible) {
                currentMarkers.forEach(({ marker }) => map.removeLayer(marker));
            }
        }

        // ── Build Leaflet popup HTML ───────────────────────────────────────
        function buildPopup(loc) {
            const isEV       = loc.type === 'ev-station';
            const isGarage   = loc.type === 'garage';
            const isSeller   = loc.type === 'seller';
            const isBusiness = loc.type === 'business';

            let availBadge = '';
            let slotGrid   = '';
            let bookBtn    = '';

            if (isEV) {
                const avail = loc.available_slots ?? 0;
                const total = loc.total_slots ?? 0;
                const occ   = loc.occupied_slots ?? 0;

                availBadge = avail > 0
                    ? `<span class="slot-pill pill-green">⚡ ${avail}/${total} available</span>`
                    : `<span class="slot-pill pill-red">⚡ All occupied</span>`;

                if (loc.next_free_at) {
                    availBadge += ` <span class="slot-pill pill-amber">Free ~${timeAgo(loc.next_free_at)}</span>`;
                }

                // Slot mini-grid
                if (loc.slots && loc.slots.length) {
                    slotGrid = `<div style="margin-top:10px">
                        <p style="font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:#94a3b8;margin:0 0 6px">Port status</p>
                        <div style="display:flex;flex-wrap:wrap;gap:4px">`;
                    loc.slots.forEach(s => {
                        // green=available, amber=pending, blue=booked, red=occupied
                        const bg    = s.status === 'available' ? '#f0fdf4'
                                    : s.status === 'pending'   ? '#fffbeb'
                                    : s.status === 'booked'    ? '#eff6ff'
                                    : '#fef2f2';
                        const color = s.status === 'available' ? '#16a34a'
                                    : s.status === 'pending'   ? '#d97706'
                                    : s.status === 'booked'    ? '#2563eb'
                                    : '#dc2626';
                        const bdr   = s.status === 'available' ? '#bbf7d0'
                                    : s.status === 'pending'   ? '#fde68a'
                                    : s.status === 'booked'    ? '#bfdbfe'
                                    : '#fecaca';
                        const label = s.status === 'pending' ? '~' : s.slot_number;
                        const tip   = s.status === 'pending' ? `Port #${s.slot_number} — pending approval`
                                    : s.status === 'booked'  ? `Port #${s.slot_number} — booked (arriving soon)`
                                    : `Port #${s.slot_number} — ${s.status}`;
                        slotGrid += `<div title="${tip}"
                                          style="width:28px;height:28px;border-radius:6px;background:${bg};border:1px solid ${bdr};
                                                 display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:900;color:${color}">
                                         ${label}
                                     </div>`;
                    });
                    slotGrid += `</div></div>`;
                }

                if (avail > 0) {
                    bookBtn = IS_AUTH
                        ? `<button onclick="openSlotModal(${loc.user_id}, '${escHtml(loc.name)}', '${escHtml(loc.address)}')"
                               style="margin-top:12px;width:100%;background:#16a34a;color:#fff;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:.08em;padding:9px 0;border-radius:10px;border:none;cursor:pointer">
                               Request a Slot →
                           </button>`
                        : `<a href="${LOGIN_URL}" style="display:block;margin-top:12px;text-align:center;background:#0f172a;color:#fff;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:.08em;padding:9px 0;border-radius:10px;text-decoration:none">
                               Log in to book →
                           </a>`;
                }

            } else if (isGarage) {
                // Garage
                const free = loc.free_bays ?? 0;
                const total = loc.total_slots ?? 0;
                const walkin = loc.accepts_walkins ? '<span class="slot-pill pill-green">Walk-ins welcome</span>' : '<span class="slot-pill pill-gray">By appointment</span>';

                availBadge = free > 0
                    ? `<span class="slot-pill pill-green">🔧 ${free}/${total} bays free</span>`
                    : `<span class="slot-pill pill-red">🔧 All bays busy</span>`;
                availBadge += ' ' + walkin;

                if (loc.next_finish_at) {
                    availBadge += ` <span class="slot-pill pill-amber">Next free ~${timeAgo(loc.next_finish_at)}</span>`;
                }

                bookBtn = IS_AUTH
                    ? `<button onclick="openGarageModal(${loc.user_id}, '${escHtml(loc.name)}', '${escHtml(loc.address)}')"
                           style="margin-top:12px;width:100%;background:#6366f1;color:#fff;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:.08em;padding:9px 0;border-radius:10px;border:none;cursor:pointer">
                           Book Appointment →
                       </button>`
                    : `<a href="${LOGIN_URL}" style="display:block;margin-top:12px;text-align:center;background:#0f172a;color:#fff;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:.08em;padding:9px 0;border-radius:10px;text-decoration:none">
                           Log in to book →
                       </a>`;

            } else if (isSeller) {
                const count = loc.listing_count ?? 0;
                availBadge = count > 0
                    ? `<span class="slot-pill pill-green">🚗 ${count} listing${count !== 1 ? 's' : ''} available</span>`
                    : `<span class="slot-pill pill-gray">🚗 No active listings</span>`;

                bookBtn = loc.profile_url
                    ? `<a href="${loc.profile_url}" style="display:block;margin-top:12px;text-align:center;background:#10b981;color:#fff;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:.08em;padding:9px 0;border-radius:10px;text-decoration:none">
                           View Listings →
                       </a>`
                    : '';

            } else if (isBusiness) {
                const count = loc.listing_count ?? 0;
                availBadge = count > 0
                    ? `<span class="slot-pill pill-green">🏢 ${count} listing${count !== 1 ? 's' : ''} available</span>`
                    : `<span class="slot-pill pill-gray">🏢 No active listings</span>`;

                bookBtn = loc.profile_url
                    ? `<a href="${loc.profile_url}" style="display:block;margin-top:12px;text-align:center;background:#8b5cf6;color:#fff;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:.08em;padding:9px 0;border-radius:10px;text-decoration:none">
                           View Business →
                       </a>`
                    : '';
            }

            return `<div style="padding:14px 16px;font-family:inherit">
                        <p style="font-size:13px;font-weight:900;color:#0f172a;margin:0 0 2px">${escHtml(loc.name)}</p>
                        <p style="font-size:11px;color:#64748b;font-weight:600;margin:0 0 10px">${escHtml(loc.address)}</p>
                        <div style="display:flex;flex-wrap:wrap;gap:5px;margin-bottom:4px">${availBadge}</div>
                        ${slotGrid}
                        ${bookBtn}
                        <div style="margin-top:10px;padding-top:10px;border-top:1px solid #f1f5f9">
                            <a href="${buildGmapsUrl(loc.latitude, loc.longitude)}"
                               target="_blank" rel="noopener noreferrer"
                               style="font-size:10px;font-weight:800;color:#16a34a;text-decoration:none;text-transform:uppercase;letter-spacing:.06em">
                               Open in Google Maps ↗
                            </a>
                        </div>
                    </div>`;
        }

        // ── Build sidebar card HTML ────────────────────────────────────────
        function buildSidebarCard(loc) {
            const isEV       = loc.type === 'ev-station';
            const isGarage   = loc.type === 'garage';
            const isSeller   = loc.type === 'seller';
            const isBusiness = loc.type === 'business';

            let avail, total, pillClass, pillLabel;

            if (isEV) {
                avail = loc.available_slots ?? 0;
                total = loc.total_slots ?? 0;
                pillClass = avail > 0 ? 'pill-green' : 'pill-red';
                pillLabel = `⚡ ${avail}/${total} slots`;
            } else if (isGarage) {
                avail = loc.free_bays ?? 0;
                total = loc.total_slots ?? 0;
                pillClass = avail > 0 ? 'pill-green' : 'pill-red';
                pillLabel = `🔧 ${avail}/${total} bays`;
            } else if (isSeller) {
                avail = loc.listing_count ?? 0;
                pillClass = avail > 0 ? 'pill-green' : 'pill-gray';
                pillLabel = `🚗 ${avail} listing${avail !== 1 ? 's' : ''}`;
            } else {
                avail = loc.listing_count ?? 0;
                pillClass = avail > 0 ? 'pill-green' : 'pill-gray';
                pillLabel = `🏢 ${avail} listing${avail !== 1 ? 's' : ''}`;
            }

            const dot = avail > 0 ? 'background:#4ade80' : 'background:#ef4444';

            return `<div class="loc-card" onclick="focusLocation(${loc.id})">
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px">
                            <span style="width:7px;height:7px;border-radius:50%;${dot};flex-shrink:0"></span>
                            <span style="font-size:11px;font-weight:900;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${escHtml(loc.name)}</span>
                        </div>
                        <p style="font-size:10px;color:#94a3b8;font-weight:600;margin:0 0 6px;padding-left:15px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${escHtml(loc.address)}</p>
                        <div style="padding-left:15px">
                            <span class="slot-pill ${pillClass}">${pillLabel}</span>
                        </div>
                    </div>`;
        }

        // ── Booking modals ─────────────────────────────────────────────────

        function openSlotModal(userId, name, address) {
            document.getElementById('modal-type-label').textContent = '⚡ EV Charging Station';
            document.getElementById('modal-title').textContent       = name;

            // Find available slots for this station
            const loc   = allLocations.find(l => l.user_id == userId && l.type === 'ev-station');
            const slots = (loc?.slots ?? []).filter(s => s.status === 'available');

            let slotOptions = slots.map(s => `<option value="${s.id}">Port #${s.slot_number}</option>`).join('');

            document.getElementById('modal-body').innerHTML = `
                <p style="font-size:12px;color:#64748b;margin:0 0 16px">${escHtml(address)}</p>

                <div style="margin-bottom:14px">
                    <label style="display:block;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:.1em;color:#94a3b8;margin-bottom:6px">
                        Select Port
                    </label>
                    <select id="slot-select" style="width:100%;border:1px solid #e2e8f0;border-radius:10px;padding:10px 14px;font-size:13px;font-weight:700;color:#0f172a;outline:none">
                        ${slotOptions.length ? slotOptions : '<option value="">No slots available</option>'}
                    </select>
                </div>

                ${slotOptions.length ? `
                <button onclick="submitSlotRequest(${userId})"
                    style="width:100%;background:#16a34a;color:#fff;font-size:11px;font-weight:900;text-transform:uppercase;letter-spacing:.1em;padding:12px 0;border-radius:12px;border:none;cursor:pointer;transition:background .2s"
                    onmouseover="this.style.background='#15803d'" onmouseout="this.style.background='#16a34a'">
                    Request Slot →
                </button>` : `
                <p style="text-align:center;font-size:12px;color:#ef4444;font-weight:700;padding:8px 0">
                    No available slots at this station right now.
                </p>`}

                <div id="slot-msg" style="margin-top:10px;display:none"></div>
            `;

            document.getElementById('booking-modal').classList.remove('hidden');
        }

        function openGarageModal(userId, name, address) {
            document.getElementById('modal-type-label').textContent = '🔧 Garage';
            document.getElementById('modal-title').textContent       = name;

            const minDate = new Date(Date.now() + 5 * 60 * 1000)
                .toISOString().slice(0, 16);

            document.getElementById('modal-body').innerHTML = `
                <p style="font-size:12px;color:#64748b;margin:0 0 16px">${escHtml(address)}</p>

                <div style="margin-bottom:14px">
                    <label style="display:block;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:.1em;color:#94a3b8;margin-bottom:6px">
                        What do you need done? <span style="color:#ef4444">*</span>
                    </label>
                    <textarea id="service-desc" rows="3" maxlength="300" placeholder="e.g. Oil change, brake inspection, EV battery check..."
                        style="width:100%;border:1px solid #e2e8f0;border-radius:10px;padding:10px 14px;font-size:13px;font-weight:600;color:#0f172a;outline:none;resize:none;box-sizing:border-box"></textarea>
                </div>

                <div style="margin-bottom:20px">
                    <label style="display:block;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:.1em;color:#94a3b8;margin-bottom:6px">
                        Preferred appointment time <span style="color:#ef4444">*</span>
                    </label>
                    <input type="datetime-local" id="appt-time" min="${minDate}"
                        style="width:100%;border:1px solid #e2e8f0;border-radius:10px;padding:10px 14px;font-size:13px;font-weight:700;color:#0f172a;outline:none;box-sizing:border-box">
                </div>

                <button onclick="submitGarageBooking(${userId})"
                    style="width:100%;background:#6366f1;color:#fff;font-size:11px;font-weight:900;text-transform:uppercase;letter-spacing:.1em;padding:12px 0;border-radius:12px;border:none;cursor:pointer;transition:background .2s"
                    onmouseover="this.style.background='#4f46e5'" onmouseout="this.style.background='#6366f1'">
                    Send Booking Request →
                </button>

                <div id="garage-msg" style="margin-top:10px;display:none"></div>
            `;

            document.getElementById('booking-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('booking-modal').classList.add('hidden');
        }

        // Close modal when clicking backdrop
        document.getElementById('booking-modal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        // ── Submit booking via fetch ───────────────────────────────────────

        async function submitSlotRequest(stationUserId) {
            const slotId = document.getElementById('slot-select')?.value;
            const msgEl  = document.getElementById('slot-msg');

            if (!slotId) return showMsg(msgEl, 'Please select a port.', 'error');

            const res = await postBooking(BOOK_SLOT_URL, { slot_id: slotId });
            if (res.ok) {
                showMsg(msgEl, '✓ Request sent! The station will confirm shortly.', 'success');
                setTimeout(() => { closeModal(); loadLocations(); }, 2000);
            } else {
                const data = await res.json().catch(() => ({}));
                showMsg(msgEl, data.message || 'Something went wrong.', 'error');
            }
        }

        async function submitGarageBooking(garageUserId) {
            const desc  = document.getElementById('service-desc')?.value?.trim();
            const appt  = document.getElementById('appt-time')?.value;
            const msgEl = document.getElementById('garage-msg');

            if (!desc) return showMsg(msgEl, 'Please describe the service needed.', 'error');
            if (!appt) return showMsg(msgEl, 'Please select an appointment time.', 'error');

            const res = await postBooking(BOOK_GARAGE_URL, {
                garage_user_id:      garageUserId,
                service_description: desc,
                requested_at:        appt.replace('T', ' ') + ':00',
            });

            if (res.ok) {
                showMsg(msgEl, '✓ Appointment request sent! The garage will confirm and email you.', 'success');
                setTimeout(() => { closeModal(); loadLocations(); }, 2500);
            } else {
                const data = await res.json().catch(() => ({}));
                // Laravel validation errors
                const errors = data.errors ? Object.values(data.errors).flat().join(' ') : (data.message || 'Something went wrong.');
                showMsg(msgEl, errors, 'error');
            }
        }

        async function postBooking(url, body) {
            return fetch(url, {
                method:  'POST',
                headers: {
                    'Content-Type':    'application/json',
                    'X-CSRF-TOKEN':    CSRF_TOKEN,
                    'Accept':          'application/json',
                },
                body: JSON.stringify(body),
            });
        }

        function showMsg(el, text, type) {
            el.style.display    = 'block';
            el.style.padding    = '10px 14px';
            el.style.borderRadius = '10px';
            el.style.fontSize   = '12px';
            el.style.fontWeight = '700';
            el.style.background = type === 'success' ? '#f0fdf4' : '#fef2f2';
            el.style.color      = type === 'success' ? '#16a34a' : '#dc2626';
            el.style.border     = `1px solid ${type === 'success' ? '#bbf7d0' : '#fecaca'}`;
            el.textContent      = text;
        }

        // ── Helpers ────────────────────────────────────────────────────────

        function switchCategory(category) {
            currentCategory = category;

            const tabs = {
                'tab-ev-fuel':        'ev-fuel',
                'tab-garage':         'garage',
                'tab-seller-business':'seller-business',
            };
            const activeColors = {
                'ev-fuel':         'text-emerald-600',
                'garage':          'text-indigo-600',
                'seller-business': 'text-violet-600',
            };
            const activeBase   = 'py-3.5 rounded-xl transition-all bg-white shadow-sm border border-slate-200 flex flex-col items-center gap-1.5';
            const inactiveBase = 'py-3.5 rounded-xl transition-all text-slate-400 hover:text-slate-600 flex flex-col items-center gap-1.5';

            Object.entries(tabs).forEach(([id, cat]) => {
                const el = document.getElementById(id);
                if (!el) return;
                el.className = cat === category
                    ? `${activeBase} ${activeColors[cat]}`
                    : inactiveBase;
            });

            // Legend
            const legend = document.getElementById('tab-legend');
            const fuelWrap = document.getElementById('fuel-radius-wrap');

            if (category === 'ev-fuel') {
                legend.innerHTML = `
                    <div class="flex items-center flex-wrap gap-3">
                        <span class="flex items-center gap-1.5 text-[9px] font-black uppercase text-slate-500">
                            <span class="w-3 h-3 rounded-full inline-block" style="background:#16a34a"></span>EV (available)
                        </span>
                        <span class="flex items-center gap-1.5 text-[9px] font-black uppercase text-slate-500">
                            <span class="w-3 h-3 rounded-full inline-block" style="background:#f59e0b"></span>EV (partial)
                        </span>
                        <span class="flex items-center gap-1.5 text-[9px] font-black uppercase text-slate-500">
                            <span class="w-3 h-3 rounded-full inline-block" style="background:#f97316"></span>Petrol pump
                        </span>
                    </div>`;
                legend.classList.remove('hidden');
                if (fuelWrap) fuelWrap.classList.remove('hidden');
            } else if (category === 'seller-business') {
                legend.innerHTML = `
                    <div class="flex items-center flex-wrap gap-3">
                        <span class="flex items-center gap-1.5 text-[9px] font-black uppercase text-slate-500">
                            <span class="w-3 h-3 rounded-full inline-block" style="background:#10b981"></span>Seller
                        </span>
                        <span class="flex items-center gap-1.5 text-[9px] font-black uppercase text-slate-500">
                            <span class="w-3 h-3 rounded-full inline-block" style="background:#8b5cf6"></span>Business
                        </span>
                    </div>`;
                legend.classList.remove('hidden');
                if (fuelWrap) fuelWrap.classList.add('hidden');
            } else {
                legend.classList.add('hidden');
                if (fuelWrap) fuelWrap.classList.add('hidden');
            }

            // Clear petrol pump markers when switching away
            if (category !== 'ev-fuel') clearPetrolMarkers();

            renderMarkers();

            // Auto-load petrol pumps if we have location
            if (category === 'ev-fuel' && userLat !== null) loadPetrolPumps();
        }

        function filterMarkers() {
            markersVisible = document.getElementById('show-markers').checked;
            currentMarkers.forEach(({ marker }) => {
                markersVisible ? marker.addTo(map) : map.removeLayer(marker);
            });
        }

        function focusLocation(id) {
            if (!id) return;
            const found = currentMarkers.find(m => String(m.id) === String(id));
            if (found) {
                map.setView(found.marker.getLatLng(), 17, { animate: true });
                found.marker.openPopup();
            }
        }

        function resetView() {
            map.setView([27.7172, 85.3240], 13, { animate: true });
            document.getElementById('asset-selector').value = '';
        }

        function escHtml(str) {
            if (!str) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function timeAgo(dateStr) {
            const diff = Math.round((new Date(dateStr) - Date.now()) / 60000);
            if (diff <= 0) return 'soon';
            if (diff < 60) return `${diff} min`;
            return `${Math.round(diff / 60)}h`;
        }

        // ── User location state ────────────────────────────────────────────
        let userLat             = null;
        let userLng             = null;
        let userLocationMarker  = null;
        let userLocationCircle  = null;

        // Build Google Maps directions URL — includes origin if we have location
        function buildGmapsUrl(destLat, destLng) {
            const dest = `${destLat},${destLng}`;
            if (userLat !== null && userLng !== null) {
                return `https://www.google.com/maps/dir/?api=1&origin=${userLat},${userLng}&destination=${dest}`;
            }
            return `https://www.google.com/maps/dir/?api=1&destination=${dest}`;
        }

        function placeUserDot(lat, lng, acc) {
            if (userLocationMarker) map.removeLayer(userLocationMarker);
            if (userLocationCircle) map.removeLayer(userLocationCircle);

            userLat = lat; userLng = lng;

            userLocationCircle = L.circle([lat, lng], {
                radius: acc,
                color: '#3b82f6', fillColor: '#3b82f6',
                fillOpacity: 0.08, weight: 1,
            }).addTo(map);

            const dotIcon = L.divIcon({
                className: '',
                html: `<div style="position:relative;width:20px;height:20px">
                           <div style="position:absolute;inset:0;background:#3b82f6;border-radius:50%;border:3px solid #fff;box-shadow:0 2px 8px rgba(59,130,246,.5)"></div>
                           <div style="position:absolute;inset:-5px;background:rgba(59,130,246,0.25);border-radius:50%;animation:locatePulse 2s ease-out infinite"></div>
                       </div>`,
                iconSize: [20, 20], iconAnchor: [10, 10], popupAnchor: [0, -14],
            });

            userLocationMarker = L.marker([lat, lng], { icon: dotIcon })
                .addTo(map)
                .bindPopup('<div style="font-size:11px;font-weight:800;color:#0f172a;padding:4px 8px">📍 You are here</div>');
        }

        function locateMe() {
            if (!navigator.geolocation) {
                alert('Geolocation is not supported by your browser.');
                return;
            }
            const btn  = document.getElementById('locate-btn');
            const icon = document.getElementById('locate-icon');
            btn.classList.add('animate-pulse');
            icon.classList.remove('text-slate-500');
            icon.classList.add('text-emerald-500');

            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    btn.classList.remove('animate-pulse');
                    const { latitude: lat, longitude: lng, accuracy: acc } = pos.coords;
                    placeUserDot(lat, lng, acc);
                    map.setView([lat, lng], 15, { animate: true });
                    userLocationMarker.openPopup();
                    btn.classList.add('bg-emerald-50', 'border-emerald-400');
                    // Auto-load petrol pumps if on the right tab
                    if (currentCategory === 'ev-fuel') loadPetrolPumps();
                },
                (err) => {
                    btn.classList.remove('animate-pulse');
                    icon.classList.remove('text-emerald-500');
                    icon.classList.add('text-slate-500');
                    const msgs = { 1: 'Location access denied.', 2: 'Could not determine your location.', 3: 'Location request timed out.' };
                    alert(msgs[err.code] || 'Location unavailable.');
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 30000 }
            );
        }

        // ── Overpass API — Petrol Pumps ────────────────────────────────────
        let petrolMarkers = [];
        let petrolLoading = false;

        function clearPetrolMarkers() {
            petrolMarkers.forEach(m => map.removeLayer(m));
            petrolMarkers = [];
        }

        async function loadPetrolPumps() {
            if (userLat === null || userLng === null) {
                // Prompt user to share location first
                const statusEl = document.getElementById('fuel-status');
                if (statusEl) {
                    statusEl.textContent = '📍 Enable your location first to find nearby petrol pumps.';
                    statusEl.classList.remove('hidden');
                }
                return;
            }
            if (petrolLoading) return;
            petrolLoading = true;

            const radiusKm = parseInt(document.getElementById('fuel-radius')?.value ?? 7);
            const radiusM  = radiusKm * 1000;

            // Show loading state in sidebar
            const statusEl = document.getElementById('fuel-status');
            if (statusEl) {
                statusEl.textContent = `⛽ Loading petrol pumps within ${radiusKm} km…`;
                statusEl.classList.remove('hidden');
            }

            clearPetrolMarkers();

            const query = `[out:json][timeout:25];(node["amenity"="fuel"](around:${radiusM},${userLat},${userLng});way["amenity"="fuel"](around:${radiusM},${userLat},${userLng}););out center;`;

            try {
                const res  = await fetch('https://overpass-api.de/api/interpreter', {
                    method: 'POST', body: query,
                });
                const data = await res.json();
                const pumps = data.elements ?? [];

                pumps.forEach(el => {
                    const lat = el.lat ?? el.center?.lat;
                    const lng = el.lon ?? el.center?.lon;
                    if (!lat || !lng) return;

                    const name    = el.tags?.name || el.tags?.brand || el.tags?.operator || 'Petrol Pump';
                    const brand   = el.tags?.brand ?? '';
                    const hours   = el.tags?.opening_hours ?? '';
                    const address = [el.tags?.['addr:street'], el.tags?.['addr:city']].filter(Boolean).join(', ') || '';
                    const destUrl = buildGmapsUrl(lat, lng);

                    const pumpIcon = L.divIcon({
                        className: '',
                        html: `<div style="position:relative;width:32px;height:32px">
                                   <div style="background:#f97316;width:32px;height:32px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 3px 10px rgba(0,0,0,.25)"></div>
                                   <span style="position:absolute;top:50%;left:50%;transform:translate(-50%,-55%);font-size:13px;line-height:1">⛽</span>
                               </div>`,
                        iconSize: [32, 32], iconAnchor: [16, 32], popupAnchor: [0, -36],
                    });

                    const popup = `<div style="padding:14px 16px;font-family:inherit">
                        <p style="font-size:13px;font-weight:900;color:#0f172a;margin:0 0 2px">${name}</p>
                        ${address ? `<p style="font-size:11px;color:#64748b;font-weight:600;margin:0 0 8px">${address}</p>` : ''}
                        <div style="display:flex;flex-wrap:wrap;gap:5px;margin-bottom:8px">
                            ${brand ? `<span class="slot-pill pill-amber">⛽ ${brand}</span>` : '<span class="slot-pill pill-amber">⛽ Petrol Pump</span>'}
                            ${hours ? `<span class="slot-pill pill-gray">🕐 ${hours}</span>` : ''}
                        </div>
                        <div style="padding-top:10px;border-top:1px solid #f1f5f9">
                            <a href="${destUrl}" target="_blank" rel="noopener noreferrer"
                               style="font-size:10px;font-weight:800;color:#f97316;text-decoration:none;text-transform:uppercase;letter-spacing:.06em">
                               Get Directions ↗
                            </a>
                        </div>
                    </div>`;

                    const m = L.marker([lat, lng], { icon: pumpIcon }).addTo(map).bindPopup(popup, { maxWidth: 300, minWidth: 260 });
                    petrolMarkers.push(m);
                });

                if (statusEl) {
                    statusEl.textContent = pumps.length > 0
                        ? `⛽ ${pumps.length} petrol pump${pumps.length !== 1 ? 's' : ''} found within ${radiusKm} km`
                        : `⛽ No petrol pumps found within ${radiusKm} km`;
                    setTimeout(() => statusEl.classList.add('hidden'), 5000);
                }
            } catch (e) {
                console.error('Overpass error:', e);
                if (statusEl) {
                    statusEl.textContent = '⚠️ Could not load petrol pumps. Check your connection.';
                }
            } finally {
                petrolLoading = false;
            }
        }

        // ── Boot — auto-detect location on page load ───────────────────────
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    const { latitude: lat, longitude: lng, accuracy: acc } = pos.coords;
                    placeUserDot(lat, lng, acc);
                    map.setView([lat, lng], 14, { animate: false });
                    const btn = document.getElementById('locate-btn');
                    if (btn) btn.classList.add('bg-emerald-50', 'border-emerald-400');
                    // Load petrol pumps since we start on ev-fuel tab
                    loadPetrolPumps();
                },
                () => { /* silent — user can still click the locate button */ },
                { enableHighAccuracy: true, timeout: 8000, maximumAge: 60000 }
            );
        }

        loadLocations();
    </script>

@endsection