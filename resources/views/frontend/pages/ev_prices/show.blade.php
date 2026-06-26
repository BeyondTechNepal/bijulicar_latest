@extends('frontend.app')

@section('content')

    <section class="bg-slate-900 pt-20">
        <div class="max-w-7xl mx-auto px-4 md:px-6 py-8">

            <div class="flex items-center gap-2 mb-6 text-slate-400">
                <a href="{{ route('ev-prices.index') }}"
                    class="hover:text-[#4ade80] transition-colors text-[12px] font-bold uppercase tracking-widest">EV
                    Price List</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-[12px] font-bold uppercase tracking-widest text-slate-300">{{ $listing->displayName() }}</span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                <div class="rounded-2xl overflow-hidden bg-slate-800 aspect-[4/3] p-6">
                    @if ($listing->image_url)
                        <img src="{{ $listing->image_url }}" alt="{{ $listing->displayName() }}"
                            class="w-full h-full object-contain" />
                    @endif
                </div>

                <div>
                    <p class="text-[#4ade80] text-[12px] font-bold uppercase tracking-widest mb-2">{{ $listing->brand }}</p>
                    <h1 class="text-3xl md:text-4xl font-black text-white mb-4">{{ $listing->model }} {{ $listing->variant }}</h1>
                    <p class="text-3xl font-black text-[#4ade80] mb-6">{{ $listing->formattedPrice() }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-slate-50 py-10">
        <div class="max-w-7xl mx-auto px-4 md:px-6">

            <div class="bg-white rounded-2xl border border-slate-100 p-6 mb-8">
                <h2 class="text-xl font-bold text-slate-900 mb-5">Specifications</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-5">
                    @php
                        $specs = [
                            'Battery'          => $listing->battery_kwh ? "{$listing->battery_kwh} kWh" : null,
                            'Motor Power'      => $listing->motor_kw ? "{$listing->motor_kw} kW" : null,
                            'Range'            => $listing->range_km ? "{$listing->range_km} km ({$listing->range_test_standard})" : null,
                            'Drive Type'       => $listing->drivetrain,
                            'Seating Capacity' => $listing->seating_capacity ? "{$listing->seating_capacity} People" : null,
                            'Total Airbags'    => $listing->total_airbags ?: null,
                            'Ground Clearance' => $listing->ground_clearance_mm ? "{$listing->ground_clearance_mm} mm" : null,
                            'Boot Space'       => $listing->boot_space_litres ? "{$listing->boot_space_litres} L" : null,
                            'Charging Time'    => $listing->charging_time,
                            'Safety Rating'    => $listing->safety_rating,
                            'Dimensions'       => $listing->dimensions,
                        ];
                    @endphp
                    @foreach ($specs as $label => $value)
                        @if ($value)
                            <div>
                                <p class="text-[11px] uppercase tracking-wider text-slate-400 font-bold mb-1">{{ $label }}</p>
                                <p class="text-sm font-semibold text-slate-900">{{ $value }}</p>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            @if ($listing->about_text)
                <div class="bg-white rounded-2xl border border-slate-100 p-6 mb-8">
                    <h2 class="text-xl font-bold text-slate-900 mb-4">About {{ $listing->displayName() }}</h2>
                    <div class="text-sm text-slate-600 leading-relaxed space-y-3">
                        @foreach (explode("\n\n", $listing->about_text) as $paragraph)
                            <p>{{ $paragraph }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (!empty($listing->key_features))
                <div class="bg-white rounded-2xl border border-slate-100 p-6 mb-8">
                    <h2 class="text-xl font-bold text-slate-900 mb-4">Key Features</h2>
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach ($listing->key_features as $feature)
                            <li class="flex items-start gap-2 text-sm text-slate-700">
                                <i class="fa-solid fa-check text-[#22c55e] text-xs mt-1"></i>
                                <span>{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($similar->isNotEmpty())
                <h2 class="text-xl font-bold text-slate-900 mb-5">More from {{ $listing->brand }}</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach ($similar as $car)
                        <a href="{{ route('ev-prices.show', $car) }}"
                            class="bg-white rounded-xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                            <div class="aspect-[4/3] bg-slate-100 p-3">
                                @if ($car->image_url)
                                    <img src="{{ $car->image_url }}" class="w-full h-full object-contain" />
                                @endif
                            </div>
                            <div class="p-3">
                                <p class="text-sm font-bold text-slate-900">{{ $car->model }}</p>
                                <p class="text-xs text-slate-500">{{ $car->formattedPrice() }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

        </div>
    </section>

@endsection