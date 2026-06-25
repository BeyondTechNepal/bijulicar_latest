@extends('frontend.app')

@section('content')

    <section class="bg-slate-900 pt-20 pb-10">
        <div class="max-w-7xl mx-auto px-4 md:px-6">
            <p class="text-[#4ade80] text-[12px] font-bold uppercase tracking-widest mb-2">Powered by our EV Nepal
                partnership</p>
            <h1 class="text-3xl md:text-4xl font-black text-white mb-2">EV Price List</h1>
            <p class="text-slate-400 text-sm max-w-2xl">Current electric vehicle prices in Nepal, synced regularly from
                our data partner. Not part of the BijuliCar marketplace listings.</p>
        </div>
    </section>

    <section class="bg-slate-50 py-10">
        <div class="max-w-7xl mx-auto px-4 md:px-6">

            {{-- Filters --}}
            <form method="GET" class="flex flex-wrap gap-3 mb-8">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search brand or model..."
                    class="flex-1 min-w-[200px] px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-green-400" />

                <select name="brand"
                    class="px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-300">
                    <option value="">All Brands</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand }}" {{ request('brand') === $brand ? 'selected' : '' }}>{{ $brand }}</option>
                    @endforeach
                </select>

                <button type="submit"
                    class="px-5 py-2.5 text-sm font-bold rounded-xl bg-[#4ade80] text-black hover:bg-[#22c55e] transition-colors">
                    Filter
                </button>
            </form>

            {{-- Grid --}}
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