@extends('frontend.app')

<title>EV Price List | BijuliCar</title>

@section('content')

    {{-- ── Hero + Filter section (styled to match Marketplace) ───────────── --}}
    <section
        class="relative pt-32 pb-10 lg:pt-38 lg:pb-8 min-h-60vh flex flex-col justify-end overflow-hidden bg-[#0a0f1e] text-white">

        <div class="absolute inset-0 z-0">
            @if ($listings->isNotEmpty() && $listings->first()->image_url)
                <img src="{{ $listings->first()->image_url }}"
                    class="w-full h-full object-cover scale-105 blur-[8px] opacity-20 lg:opacity-20" alt="Background">
            @endif
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,_rgba(15,23,42,0.1)_0%,_#0a0f1e_100%)]">
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 relative z-10 w-full">

            <div class="mb-6 lg:mb-10">
                <div class="flex items-center gap-3 mb-2 lg:mb-3">
                    <span class="w-8 lg:w-10 h-[2px] bg-[#4ade80]"></span>
                    <span class="text-[9px] lg:text-[10px] uppercase tracking-[0.4em] text-[#4ade80] font-bold">
                        Powered by our EV Nepal partnership
                    </span>
                </div>
                <h1
                    class="text-[27px] md:text-6xl lg:text-7xl font-black tracking-tighter text-white uppercase italic leading-none whitespace-nowrap">
                    EV <span class="text-slate-500">Price List</span>
                </h1>
                <p class="mt-3 lg:mt-4 text-slate-400 text-xs lg:text-base font-medium max-w-sm lg:max-w-md leading-relaxed">
                    Current electric vehicle prices in Nepal, synced regularly from our data partner. Not part of the
                    BijuliCar marketplace listings.
                </p>
            </div>

            {{-- Search / filter card --}}
            <form method="GET" action="{{ route('ev-prices.index') }}">
                <div
                    class="bg-white rounded-3xl p-4 lg:p-5 shadow-[0_40px_100px_-20px_rgba(0,0,0,0.6)] border border-white/10 backdrop-blur-md space-y-3">

                    <div class="grid grid-cols-1 md:grid-cols-[2fr_1fr_auto] gap-3 items-stretch">

                        <div class="w-full relative group">
                            <div
                                class="absolute inset-y-0 left-5 flex items-center pointer-events-none text-slate-300 group-focus-within:text-[#4ade80] transition-colors z-10">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search brand or model..."
                                class="w-full h-full bg-slate-100/70 border-none rounded-2xl py-4 pl-12 pr-4 text-sm font-bold placeholder:text-slate-400 text-slate-900 focus:ring-2 focus:ring-[#4ade80]/30 transition-all">
                        </div>

                        <select name="brand"
                            class="w-full h-full bg-slate-100/70 border-none rounded-2xl px-5 text-sm font-black text-slate-900 cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#4ade80]/30 uppercase tracking-tight transition-all">
                            <option value="">All Brands</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand }}" {{ request('brand') === $brand ? 'selected' : '' }}>
                                    {{ $brand }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit"
                            class="w-full md:w-auto px-8 py-4 rounded-2xl bg-[#4ade80] text-black text-sm font-black uppercase tracking-tight hover:bg-[#22c55e] transition-colors whitespace-nowrap">
                            Filter
                        </button>
                    </div>

                    {{-- Price range row --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-5 flex items-center text-sm font-bold text-slate-400">Rs.</span>
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min price"
                                class="w-full bg-slate-100/70 border-none rounded-2xl py-3.5 pl-12 pr-4 text-sm font-bold placeholder:text-slate-400 text-slate-900 focus:ring-2 focus:ring-[#4ade80]/30 transition-all">
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-5 flex items-center text-sm font-bold text-slate-400">Rs.</span>
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max price"
                                class="w-full bg-slate-100/70 border-none rounded-2xl py-3.5 pl-12 pr-4 text-sm font-bold placeholder:text-slate-400 text-slate-900 focus:ring-2 focus:ring-[#4ade80]/30 transition-all">
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </section>

    <section class="bg-slate-50 py-10">
        <div class="max-w-7xl mx-auto px-4 md:px-6">

            {{-- Results count & sort tabs --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">
                    {{ $listings->total() }} {{ Str::plural('EV', $listings->total()) }} found
                </p>
                <div class="flex items-center gap-3">
                    <span class="text-[10px] font-bold text-slate-400 uppercase italic">Sort:</span>
                    @php
                        $sortLinks = [
                            
                            'price_asc'  => 'Price ↑',
                            'price_desc' => 'Price ↓',
                        ];
                        $currentSort = request('sort', 'newest');
                    @endphp
                    @foreach ($sortLinks as $value => $label)
                        <a href="{{ request()->fullUrlWithQuery(['sort' => $value, 'page' => null]) }}"
                            class="text-[10px] font-black uppercase tracking-widest transition-colors pb-0.5 {{ $currentSort === $value ? 'text-slate-900 border-b-2 border-[#4ade80]' : 'text-slate-400 hover:text-slate-900' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            @if ($listings->isEmpty())
                <div class="text-center py-20 text-slate-400">
                    <p>No EV listings found yet. Run the sync command to pull the latest prices.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($listings as $car)
                        <a href="{{ route('ev-prices.show', $car) }}"
                            class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow overflow-hidden group">
                            <div class="aspect-[4/3] bg-slate-100 overflow-hidden">
                                @if ($car->image_url)
                                    <img src="{{ $car->image_url }}" alt="{{ $car->displayName() }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                        <i class="fa-solid fa-car-side text-4xl"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-[#22c55e] mb-1">{{ $car->brand }}</p>
                                <h3 class="font-bold text-slate-900 mb-1">{{ $car->model }} {{ $car->variant }}</h3>
                                <p class="text-lg font-black text-slate-900 mb-2">{{ $car->formattedPrice() }}</p>
                                <div class="flex items-center gap-3 text-[12px] text-slate-500">
                                    @if ($car->battery_kwh)
                                        <span><i class="fa-solid fa-battery-three-quarters mr-1"></i>{{ $car->battery_kwh }} kWh</span>
                                    @endif
                                    @if ($car->range_km)
                                        <span><i class="fa-solid fa-road mr-1"></i>{{ $car->range_km }} km</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $listings->links() }}
                </div>
            @endif

        </div>
    </section>

@endsection