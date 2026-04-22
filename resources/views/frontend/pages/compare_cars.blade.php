@extends('frontend.app')

@section('content')

{{-- ══════════════════════════════════════════════════════════════
     HERO
══════════════════════════════════════════════════════════════════ --}}
<section class="relative w-full min-h-[44vh] flex items-center justify-center overflow-hidden pt-20">
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1619767886558-efdc259cde1a?q=80&w=2070&auto=format&fit=crop"
            class="w-full h-full object-cover" alt="Comparison Background">
        <div class="absolute inset-0 bg-slate-900/70 backdrop-blur-[2px]"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-slate-900/10 to-[#f1f5f9]"></div>
    </div>

    <div class="max-w-7xl mx-auto w-full px-6 relative z-10 py-14 text-center">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/10 backdrop-blur-xl border border-white/20 rounded-full mb-5">
            <span class="text-[10px] font-black text-[#4ade80] uppercase tracking-[0.3em]">Vehicle Analysis Mode</span>
            <span class="text-white/40 text-xs">|</span>
            <span class="text-[10px] font-black text-white uppercase tracking-[0.3em]">Comparison Engine</span>
        </div>
        <h1 class="text-5xl md:text-7xl font-black text-white uppercase italic tracking-tighter leading-none mb-4">
            Side-by-Side <span class="text-[#4ade80]">Comparison</span>
        </h1>
        <p class="text-slate-300 text-sm md:text-base font-medium max-w-xl mx-auto">
            Pick 2 or 3 vehicles and compare every spec, price, and detail at a glance.
        </p>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     SLOT SELECTOR PANEL
══════════════════════════════════════════════════════════════════ --}}
<section class="bg-[#f1f5f9] pb-6 pt-4">
    <div class="max-w-7xl mx-auto px-6">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
            @for ($slot = 0; $slot < 3; $slot++)
                @php
                    $car = $selected[$slot] ?? null;
                    $slotColors = [
                        0 => ['bar' => 'bg-green-500',  'label' => 'text-green-600',  'ring' => 'border-green-400',  'badge' => 'bg-green-100 text-green-700'],
                        1 => ['bar' => 'bg-blue-500',   'label' => 'text-blue-600',   'ring' => 'border-blue-400',   'badge' => 'bg-blue-100 text-blue-700'],
                        2 => ['bar' => 'bg-amber-500',  'label' => 'text-amber-600',  'ring' => 'border-amber-400',  'badge' => 'bg-amber-100 text-amber-700'],
                    ][$slot];
                @endphp

                <div class="slot-card relative bg-white rounded-2xl border-2 transition-all duration-200 overflow-hidden
                    {{ $car ? 'border-slate-200 shadow-md' : 'border-dashed border-slate-300' }}">

                    {{-- Color bar top --}}
                    @if ($car)
                        <div class="h-1 {{ $slotColors['bar'] }}"></div>
                    @endif

                    @if ($car)
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    {{-- Thumbnail --}}
                                    @if ($car->primary_image)
                                        <img src="{{ Storage::url($car->primary_image) }}"
                                            class="w-14 h-14 rounded-xl object-cover shrink-0 border border-slate-100"
                                            alt="{{ $car->brand }}">
                                    @else
                                        <div class="w-14 h-14 rounded-xl bg-slate-100 flex items-center justify-center shrink-0 text-2xl">🚗</div>
                                    @endif
                                    <div class="min-w-0">
                                        <span class="text-[10px] font-black {{ $slotColors['label'] }} uppercase tracking-widest">Vehicle {{ $slot + 1 }}</span>
                                        <h3 class="text-[14px] font-black text-slate-900 leading-tight truncate">
                                            {{ $car->year }} {{ $car->brand }} {{ $car->model }}
                                        </h3>
                                        @if ($car->variant)
                                            <p class="text-[11px] text-slate-400 font-medium truncate">{{ $car->variant }}</p>
                                        @endif
                                    </div>
                                </div>
                                {{-- Remove button --}}
                                @php
                                    $removeIds = $selected->filter(fn($c, $i) => $i !== $slot)->pluck('id')->values();
                                    $removeQuery = $removeIds->map(fn($id) => 'cars[]=' . $id)->implode('&');
                                @endphp
                                <a href="{{ route('compare_cars') }}{{ $removeQuery ? '?' . $removeQuery : '' }}"
                                    class="shrink-0 w-7 h-7 rounded-full bg-slate-100 hover:bg-red-100 hover:text-red-600 flex items-center justify-center text-slate-400 transition-all mt-0.5"
                                    title="Remove">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </a>
                            </div>

                            {{-- Badges --}}
                            <div class="flex flex-wrap gap-1.5 mt-3">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase
                                    {{ $car->drivetrain === 'ev' ? 'bg-green-100 text-green-700' : ($car->drivetrain === 'hybrid' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600') }}">
                                    {{ strtoupper($car->drivetrain) }}
                                </span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-slate-100 text-slate-600">
                                    {{ ucfirst($car->condition) }}
                                </span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-amber-100 text-amber-800">
                                    {{ ($car->price ? "NRs " . number_format($car->price) : ($car->rent_price_per_day ? "NRs " . number_format($car->rent_price_per_day) . "/day" : "—")) }}
                                </span>
                            </div>
                        </div>
                    @else
                        {{-- Empty slot --}}
                        <button onclick="openPicker({{ $slot }})"
                            class="w-full min-h-[118px] flex flex-col items-center justify-center gap-2 p-6 rounded-2xl hover:bg-green-50/40 transition-all group">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 group-hover:bg-green-100 flex items-center justify-center transition-all">
                                <svg class="w-5 h-5 text-slate-400 group-hover:text-green-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                            <span class="text-[11px] font-black text-slate-400 group-hover:text-green-700 uppercase tracking-widest transition-colors">
                                Add Vehicle {{ $slot + 1 }}
                            </span>
                            <span class="text-[10px] text-slate-300 font-medium">click to browse</span>
                        </button>
                    @endif
                </div>
            @endfor
        </div>

        {{-- Action bar --}}
        <div class="flex items-center justify-between flex-wrap gap-3">
            <p class="text-[12px] font-bold text-slate-400 uppercase tracking-widest">
                <span class="text-slate-700 font-black">{{ $selected->count() }}</span> of 3 vehicles selected
                @if ($selected->count() >= 2)
                    &nbsp;·&nbsp; <span class="text-green-600">Ready to compare</span>
                @endif
            </p>
            <div class="flex gap-3">
                @if ($selected->count() > 0)
                    <a href="{{ route('compare_cars') }}"
                        class="px-5 py-2 rounded-xl bg-white border border-slate-200 text-slate-600 text-[11px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all">
                        Reset All
                    </a>
                @endif
                @if ($selected->count() < 3)
                    <button onclick="openPicker({{ $selected->count() }})"
                        class="px-6 py-2 rounded-xl bg-slate-900 text-white text-[11px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all">
                        + Add Vehicle
                    </button>
                @endif
                <a href="{{ route('marketplace') }}"
                    class="px-5 py-2 rounded-xl bg-white border border-slate-200 text-slate-600 text-[11px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all">
                    Browse All Cars
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     COMPARISON TABLE  (≥2 cars)
══════════════════════════════════════════════════════════════════ --}}
@if ($selected->count() >= 2)
@php
    $cols     = $selected->count();
    $gridCols = $cols === 2 ? 'grid-cols-3' : 'grid-cols-4';
    $carCols  = $cols === 2 ? 'md:grid-cols-2' : 'md:grid-cols-3';
    $accentBg   = ['bg-green-500', 'bg-blue-500', 'bg-amber-500'];
    $accentText = ['text-green-700', 'text-blue-700', 'text-amber-700'];
    $accentBadge= ['bg-green-100 text-green-700', 'bg-blue-100 text-blue-700', 'bg-amber-100 text-amber-700'];
    $accentLight= ['bg-green-50', 'bg-blue-50', 'bg-amber-50'];
    $accentBorder = ['border-green-400', 'border-blue-400', 'border-amber-400'];
@endphp

<section class="bg-[#f1f5f9] pb-24 pt-2">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Section divider --}}
        <div class="flex items-center gap-3 mb-8">
            <span class="w-10 h-[2px] bg-[#4ade80]"></span>
            <span class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-500">Full Breakdown</span>
        </div>

        {{-- ── OVERVIEW CARDS ── --}}
        <div class="grid grid-cols-1 {{ $carCols }} gap-5 mb-8">
            @foreach ($selected as $i => $car)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="h-1.5 {{ $accentBg[$i] }}"></div>
                    <div class="p-6">
                        {{-- Car image --}}
                        @if ($car->primary_image)
                            <div class="w-full h-36 rounded-xl overflow-hidden mb-4 bg-slate-100">
                                <img src="{{ Storage::url($car->primary_image) }}"
                                    class="w-full h-full object-cover" alt="{{ $car->brand }} {{ $car->model }}">
                            </div>
                        @else
                            <div class="w-full h-36 rounded-xl bg-slate-100 flex items-center justify-center mb-4 text-4xl">🚗</div>
                        @endif

                        <div class="flex items-start justify-between mb-3">
                            <span class="text-[10px] font-black uppercase tracking-widest {{ $accentText[$i] }}">Vehicle {{ $i + 1 }}</span>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase {{ $accentBadge[$i] }}">
                                {{ strtoupper($car->drivetrain) }}
                            </span>
                        </div>

                        <h2 class="text-xl font-black text-slate-900 leading-tight">{{ $car->brand }} {{ $car->model }}</h2>
                        <p class="text-sm text-slate-400 font-medium mt-0.5">{{ $car->year }}{{ $car->variant ? ' · ' . $car->variant : '' }}</p>

                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <p class="text-2xl font-black text-slate-900">{{ ($car->price ? "NRs " . number_format($car->price) : ($car->rent_price_per_day ? "NRs " . number_format($car->rent_price_per_day) . "/day" : "—")) }}</p>
                            <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wide mt-0.5">
                                {{ $car->price_negotiable ? '✓ Price negotiable' : 'Fixed price' }}
                            </p>
                        </div>

                        <div class="mt-3 flex flex-wrap gap-2">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 uppercase">{{ ucfirst($car->condition) }}</span>
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 uppercase">{{ $car->location }}</span>
                            @if ($car->stock_quantity > 1)
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700 uppercase">{{ $car->stock_quantity }} in stock</span>
                            @endif
                        </div>

                        @auth
                            @if (auth()->user()->hasRole('buyer'))
                                @php
                                    $alreadyOrdered = auth()->user()->orders()
                                        ->where('car_id', $car->id)
                                        ->whereIn('status', ['pending', 'confirmed', 'completed'])
                                        ->exists();
                                @endphp
                                @if ($alreadyOrdered)
                                    <div class="mt-4 w-full py-2.5 rounded-xl bg-green-50 border border-green-200 text-green-700 text-[11px] font-black uppercase tracking-widest text-center">
                                        ✓ Already Ordered
                                    </div>
                                @elseif (!$car->inStock())
                                    <div class="mt-4 w-full py-2.5 rounded-xl bg-red-50 border border-red-200 text-red-500 text-[11px] font-black uppercase tracking-widest text-center">
                                        Out of Stock
                                    </div>
                                @else
                                    <a href="{{ route('cars.show', $car->id) }}#place-order"
                                        class="mt-4 block w-full py-2.5 rounded-xl {{ $accentBg[$i] }} text-white text-[11px] font-black uppercase tracking-widest hover:opacity-90 transition-all text-center">
                                        Order This Car
                                    </a>
                                @endif
                            @endif
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ── VERDICT BANNER ── --}}
        @php
            $verdictItems = [];

            // Best Value — only show if there's a unique cheapest
            $minPrice = $selected->min('price');
            $cheapestCars = $selected->filter(fn($c) => $c->price === $minPrice);
            if ($cheapestCars->count() === 1) {
                $cheapest = $cheapestCars->first();
                $cheapIdx = $selected->search(fn($c) => $c->id === $cheapest->id);
                $verdictItems[] = ['label' => 'Best Value', 'car' => $cheapest->brand . ' ' . $cheapest->model, 'detail' => 'NRs ' . number_format($cheapest->price), 'idx' => $cheapIdx];
            }

            // Longest Range — only show if there's a unique best
            $evCarsWithRange = $selected->filter(fn($c) => $c->range_km);
            if ($evCarsWithRange->count() >= 1) {
                $maxRange = $evCarsWithRange->max('range_km');
                $bestRangeCars = $evCarsWithRange->filter(fn($c) => $c->range_km == $maxRange);
                if ($bestRangeCars->count() === 1) {
                    $bestRange = $bestRangeCars->first();
                    $rangeIdx = $selected->search(fn($c) => $c->id === $bestRange->id);
                    $verdictItems[] = ['label' => 'Longest Range', 'car' => $bestRange->brand . ' ' . $bestRange->model, 'detail' => number_format($bestRange->range_km) . ' km', 'idx' => $rangeIdx];
                }
            }

            // Highest Mileage — only show if there's a unique highest
            $maxMileage = $selected->max('mileage');
            $highestMileageCars = $selected->filter(fn($c) => $c->mileage == $maxMileage);
            if ($highestMileageCars->count() === 1) {
                $highestMileage = $highestMileageCars->first();
                $mileageIdx = $selected->search(fn($c) => $c->id === $highestMileage->id);
                $verdictItems[] = ['label' => 'Highest Mileage', 'car' => $highestMileage->brand . ' ' . $highestMileage->model, 'detail' => number_format($highestMileage->mileage) . ' km', 'idx' => $mileageIdx];
            }
        @endphp

        @if (count($verdictItems) > 0)
        <div class="bg-slate-900 rounded-2xl p-6 mb-8">
            <p class="text-[10px] font-black text-[#4ade80] uppercase tracking-widest mb-4">Quick Verdict</p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach ($verdictItems as $v)
                    @php $vColors = [['#4ade80','text-green-400'],['#60a5fa','text-blue-400'],['#fbbf24','text-amber-400']][$v['idx']]; @endphp
                    <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                        <p class="text-[10px] font-black uppercase tracking-widest mb-1" style="color: {{ $vColors[0] }}">{{ $v['label'] }}</p>
                        <p class="text-white font-black text-[15px] leading-tight">{{ $v['car'] }}</p>
                        <p class="text-slate-400 text-[12px] font-bold mt-0.5">{{ $v['detail'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="bg-slate-900 rounded-2xl p-6 mb-8 text-center">
            <p class="text-[10px] font-black text-[#4ade80] uppercase tracking-widest mb-2">Quick Verdict</p>
            <p class="text-slate-400 text-sm font-medium">These vehicles are evenly matched — no clear winner across price, range, or mileage.</p>
        </div>
        @endif

        {{-- ── PRICE BAR CHART ── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
            <p class="text-[11px] font-black uppercase tracking-widest text-slate-500 mb-5">Price Comparison</p>
            @php $maxPrice = $selected->max('price'); @endphp
            @foreach ($selected as $i => $car)
                @php $pct = ($car->price && $maxPrice) ? round($car->price / $maxPrice * 100) : 0; @endphp
                <div class="mb-4 last:mb-0">
                    <div class="flex justify-between items-baseline mb-1.5">
                        <span class="text-[13px] font-bold text-slate-800">{{ $car->brand }} {{ $car->model }}</span>
                        <span class="text-[13px] font-black {{ $accentText[$i] }}">{{ ($car->price ? "NRs " . number_format($car->price) : ($car->rent_price_per_day ? "NRs " . number_format($car->rent_price_per_day) . "/day" : "—")) }}</span>
                    </div>
                    <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">
                        <div class="{{ $accentBg[$i] }} h-full rounded-full" style="width: {{ $pct }}%; transition: width 1s ease"></div>
                    </div>
                    @if ($car->price === $minPrice && $cheapestCars->count() === 1 && $cols > 1)
                        <p class="text-[10px] font-bold text-green-600 mt-1">↓ Lowest price</p>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- ── EV RANGE BARS ── --}}
        @php $evCars = $selected->filter(fn($c) => $c->drivetrain === 'ev' && $c->range_km); @endphp
        @if ($evCars->count() >= 1)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
            <p class="text-[11px] font-black uppercase tracking-widest text-slate-500 mb-5">EV Range Comparison</p>
            @php $maxRange = $evCars->max('range_km'); @endphp
            @foreach ($selected as $i => $car)
                @if ($car->range_km)
                    @php $pct = round($car->range_km / $maxRange * 100); @endphp
                    <div class="mb-4 last:mb-0">
                        <div class="flex justify-between items-baseline mb-1.5">
                            <span class="text-[13px] font-bold text-slate-800">{{ $car->brand }} {{ $car->model }}</span>
                            <span class="text-[13px] font-black {{ $accentText[$i] }}">{{ number_format($car->range_km) }} km</span>
                        </div>
                        <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">
                            <div class="{{ $accentBg[$i] }} h-full rounded-full" style="width: {{ $pct }}%; transition: width 1s ease"></div>
                        </div>
                        @if ($car->range_km == $maxRange && $evCars->filter(fn($c) => $c->range_km == $maxRange)->count() === 1 && $evCars->count() > 1)
                            <p class="text-[10px] font-bold text-green-600 mt-1">↑ Best range</p>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
        @endif

        {{-- ── FULL SPEC TABLE ── --}}
        @php
        $specGroups = [
            'Performance & Tech' => [
                ['label' => 'Drivetrain',    'key' => 'drivetrain',    'format' => 'upper',   'numeric' => false],
                ['label' => 'Range',         'key' => 'range_km',      'unit' => 'km',        'numeric' => true,  'higher' => true,  'ev_only' => true],
                ['label' => 'Battery',       'key' => 'battery_kwh',   'unit' => 'kWh',       'numeric' => true,  'higher' => true,  'ev_only' => true],
                ['label' => 'Mileage',       'key' => 'mileage',       'unit' => 'km',        'numeric' => true,  'higher' => true],
            ],
            'Vehicle Details' => [
                ['label' => 'Year',          'key' => 'year',          'numeric' => true,     'higher' => true],
                ['label' => 'Condition',     'key' => 'condition',     'format' => 'ucfirst', 'numeric' => false],
                ['label' => 'Color',         'key' => 'color',         'numeric' => false],
                ['label' => 'Location',      'key' => 'location',      'numeric' => false],
            ],
            'Pricing & Stock' => [
                ['label' => 'Asking Price',  'key' => 'price',         'format' => 'price',   'numeric' => true,  'higher' => false],
                ['label' => 'Negotiable',    'key' => 'price_negotiable', 'format' => 'bool', 'numeric' => false],
                ['label' => 'In Stock',      'key' => 'stock_quantity', 'unit' => 'units',    'numeric' => true,  'higher' => true],
            ],
            'Seller' => [
                ['label' => 'Listed By',     'key' => 'seller.name',   'numeric' => false],
                ['label' => 'Seller Role',   'key' => 'seller.roles',  'format' => 'role',    'numeric' => false],
            ],
        ];
        @endphp

        <div class="space-y-5">
            @foreach ($specGroups as $groupLabel => $rows)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

                {{-- Sticky-ish group header --}}
                <div class="grid {{ $gridCols }} px-5 py-3.5 bg-slate-50 border-b border-slate-100">
                    <div>
                        <span class="text-[11px] font-black uppercase tracking-widest text-slate-500">{{ $groupLabel }}</span>
                    </div>
                    @foreach ($selected as $i => $car)
                        <div class="text-center">
                            <span class="inline-block w-2 h-2 rounded-full {{ $accentBg[$i] }} mr-1.5 align-middle"></span>
                            <span class="text-[12px] font-black text-slate-700">{{ $car->brand }} {{ $car->model }}</span>
                        </div>
                    @endforeach
                </div>

                {{-- Spec rows --}}
                @foreach ($rows as $row)
                    @php
                        // Resolve values (supports dot notation for relationships)
                        $vals = $selected->map(function($car) use ($row) {
                            if (str_contains($row['key'], '.')) {
                                [$rel, $field] = explode('.', $row['key'], 2);
                                if ($field === 'roles') {
                                    return $car->$rel ? ucfirst($car->$rel->getRoleNames()->first() ?? '—') : '—';
                                }
                                return $car->$rel ? $car->$rel->$field : null;
                            }
                            return $car->{$row['key']};
                        });

                        // Skip EV-only rows if no EV selected
                        $anyEv = $selected->contains(fn($c) => $c->drivetrain === 'ev');
                        if (($row['ev_only'] ?? false) && !$anyEv) continue;

                        // Compute best/worst for numeric comparisons
                        $isNumeric = $row['numeric'] ?? false;
                        $best = null; $worst = null;
                        if ($isNumeric) {
                            $numVals = $vals->filter(fn($v) => is_numeric($v) && $v !== null);
                            if ($numVals->count() >= 2) {
                                $best  = ($row['higher'] ?? true) ? $numVals->max() : $numVals->min();
                                $worst = ($row['higher'] ?? true) ? $numVals->min() : $numVals->max();
                            }
                        }

                        // Format function
                        $fmt = function($v) use ($row) {
                            if (is_null($v) || $v === '' || $v === '—') return '—';
                            $f = $row['format'] ?? '';
                            if ($f === 'price')   return 'NRs ' . number_format($v);
                            if ($f === 'bool')    return $v ? 'Yes ✓' : 'No';
                            if ($f === 'ucfirst') return ucfirst($v);
                            if ($f === 'upper')   return strtoupper($v);
                            $unit = $row['unit'] ?? '';
                            return $v . ($unit ? ' ' . $unit : '');
                        };
                    @endphp

                    <div class="grid {{ $gridCols }} px-5 py-3.5 border-b border-slate-50 last:border-0 hover:bg-slate-50/60 transition-colors items-center">
                        <span class="text-[13px] font-bold text-slate-500">{{ $row['label'] }}</span>

                        @foreach ($vals as $i => $val)
                            @php
                                $display = $fmt($val);
                                $isBest  = $isNumeric && $best !== null && is_numeric($val) && (float)$val === (float)$best  && $best !== $worst;
                                $isWorst = $isNumeric && $worst !== null && is_numeric($val) && (float)$val === (float)$worst && $best !== $worst;
                            @endphp
                            <div class="flex justify-center">
                                <span class="text-[13px] font-bold px-2.5 py-1 rounded-lg text-center
                                    {{ $isBest  ? 'bg-green-100 text-green-700' :
                                       ($isWorst ? 'bg-red-50 text-red-500'    : 'text-slate-800') }}">
                                    {{ $display }}
                                    @if ($isBest) <span class="text-[10px] font-black ml-0.5">↑</span> @endif
                                    @if ($isWorst) <span class="text-[10px] font-black ml-0.5">↓</span> @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
            @endforeach
        </div>

        {{-- ── DESCRIPTION CARDS ── --}}
        @php $withDesc = $selected->filter(fn($c) => $c->description); @endphp
        @if ($withDesc->isNotEmpty())
        <div class="mt-6 grid grid-cols-1 {{ $carCols }} gap-5">
            @foreach ($selected as $i => $car)
                @if ($car->description)
                    @php $borderColors = ['border-l-green-400','border-l-blue-400','border-l-amber-400']; @endphp
                    <div class="bg-white rounded-2xl border border-slate-100 border-l-4 {{ $borderColors[$i] }} shadow-sm p-5">
                        <p class="text-[11px] font-black {{ $accentText[$i] }} uppercase tracking-widest mb-2">
                            {{ $car->brand }} {{ $car->model }} — Seller Notes
                        </p>
                        <p class="text-[13px] text-slate-600 leading-relaxed">{{ $car->description }}</p>
                    </div>
                @endif
            @endforeach
        </div>
        @endif

    </div>
</section>

@elseif ($selected->count() === 1)
{{-- Nudge: 1 car selected --}}
<section class="bg-[#f1f5f9] py-20">
    <div class="max-w-md mx-auto px-6 text-center">
        <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 0v10m0-10a2 2 0 012-2h2a2 2 0 012 2v10a2 2 0 01-2 2h-2a2 2 0 01-2-2"/>
            </svg>
        </div>
        <h2 class="text-2xl font-black text-slate-900 uppercase italic tracking-tighter mb-2">Add One More Vehicle</h2>
        <p class="text-slate-500 text-sm mb-6">You need at least 2 vehicles to compare. Add another above or browse the marketplace.</p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <button onclick="openPicker(1)"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-slate-900 text-white rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all">
                + Add Vehicle 2
            </button>
            <a href="{{ route('marketplace') }}"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-700 rounded-xl text-[12px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all">
                Browse Marketplace
            </a>
        </div>
    </div>
</section>

@else
{{-- Empty state --}}
<section class="bg-[#f1f5f9] py-24">
    <div class="max-w-lg mx-auto px-6 text-center">
        <div class="w-20 h-20 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-6 text-4xl">🚗</div>
        <h2 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter mb-3">Start Comparing</h2>
        <p class="text-slate-500 text-sm mb-8 max-w-sm mx-auto leading-relaxed">
            Select 2 or 3 vehicles from our marketplace and get a full side-by-side breakdown — specs, price, range, and more.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <button onclick="openPicker(0)"
                class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-slate-900 text-white rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all">
                + Add First Vehicle
            </button>
            <a href="{{ route('marketplace') }}"
                class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-white border border-slate-200 text-slate-700 rounded-xl text-[12px] font-black uppercase tracking-widest hover:bg-slate-50 transition-all">
                Browse Marketplace
            </a>
        </div>
    </div>
</section>
@endif

{{-- ══════════════════════════════════════════════════════════════
     CAR PICKER MODAL
══════════════════════════════════════════════════════════════════ --}}
<div id="pickerModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 invisible opacity-0 transition-all duration-200">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closePicker()"></div>

    <div class="relative w-full max-w-2xl bg-white rounded-[2rem] shadow-2xl overflow-hidden z-10 transform scale-95 transition-transform duration-200 flex flex-col max-h-[85vh]"
        id="pickerCard">

        {{-- Modal header --}}
        <div class="flex items-center justify-between px-7 py-5 border-b border-slate-100 shrink-0">
            <div>
                <h2 class="text-[17px] font-black text-slate-900 uppercase italic tracking-tight">Select a Vehicle</h2>
                <p class="text-[12px] text-slate-400 font-medium mt-0.5" id="pickerSubtitle">Adding to slot 1</p>
            </div>
            <button onclick="closePicker()" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Search + filters --}}
        <div class="px-7 pt-4 pb-3 shrink-0">
            <div class="relative mb-3">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="pickerSearch" placeholder="Search brand or model..."
                    oninput="filterCars(this.value)"
                    class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-[#16a34a] transition-all">
            </div>
            {{-- Drivetrain chips --}}
            <div class="flex gap-2 flex-wrap">
                @foreach (['all' => 'All', 'ev' => '⚡ EV', 'hybrid' => '🔋 Hybrid', 'petrol' => '⛽ Petrol', 'diesel' => '🛢 Diesel'] as $val => $label)
                    <button onclick="filterDrivetrain('{{ $val }}')"
                        class="drivetrain-chip px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border transition-all
                            {{ $val === 'all' ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-500 border-slate-200 hover:border-slate-400' }}"
                        data-dt="{{ $val }}">{{ $label }}</button>
                @endforeach
            </div>
        </div>

        {{-- Count indicator --}}
        <div class="px-7 pb-2 shrink-0">
            <p class="text-[11px] text-slate-400 font-medium" id="resultCount"></p>
        </div>

        {{-- Car list --}}
        <div id="carList" class="px-7 pb-7 overflow-y-auto space-y-2 flex-1">
            @forelse ($allCars as $car)
                @php $alreadySelected = $selected->pluck('id')->contains($car->id); @endphp
                <button
                    onclick="{{ $alreadySelected ? '' : 'selectCar(' . $car->id . ')' }}"
                    class="car-item w-full flex items-center gap-3 px-4 py-3 rounded-xl border transition-all text-left
                        {{ $alreadySelected
                            ? 'border-green-200 bg-green-50 cursor-default opacity-60'
                            : 'border-slate-100 hover:border-[#16a34a] hover:bg-green-50/20 cursor-pointer hover:shadow-sm' }}"
                    data-brand="{{ strtolower($car->brand) }}"
                    data-model="{{ strtolower($car->model) }}"
                    data-variant="{{ strtolower($car->variant ?? '') }}"
                    data-drivetrain="{{ $car->drivetrain }}">

                    {{-- Thumbnail --}}
                    @if ($car->primary_image)
                        <img src="{{ Storage::url($car->primary_image) }}"
                            class="w-12 h-12 rounded-lg object-cover shrink-0 border border-slate-100"
                            alt="{{ $car->brand }}">
                    @else
                        <div class="w-12 h-12 rounded-lg flex items-center justify-center text-lg shrink-0
                            {{ $car->drivetrain === 'ev' ? 'bg-green-100' : ($car->drivetrain === 'hybrid' ? 'bg-blue-100' : 'bg-slate-100') }}">
                            {{ $car->drivetrain === 'ev' ? '⚡' : ($car->drivetrain === 'hybrid' ? '🔋' : '⛽') }}
                        </div>
                    @endif

                    <div class="flex-1 min-w-0">
                        <p class="text-[13px] font-bold text-slate-900 leading-tight truncate">
                            {{ $car->year }} {{ $car->brand }} {{ $car->model }}{{ $car->variant ? ' ' . $car->variant : '' }}
                        </p>
                        <p class="text-[11px] text-slate-400 font-medium">
                            {{ strtoupper($car->drivetrain) }} · {{ ucfirst($car->condition) }} · {{ $car->location }}
                        </p>
                    </div>

                    <div class="text-right shrink-0">
                        <p class="text-[13px] font-black text-slate-800">{{ ($car->price ? "NRs " . number_format($car->price) : ($car->rent_price_per_day ? "NRs " . number_format($car->rent_price_per_day) . "/day" : "—")) }}</p>
                        @if ($alreadySelected)
                            <p class="text-[10px] font-bold text-green-600 uppercase">Added ✓</p>
                        @endif
                    </div>
                </button>
            @empty
                <div class="text-center py-12">
                    <p class="text-slate-400 text-sm">No available vehicles found.</p>
                    <a href="{{ route('marketplace') }}" class="text-green-600 text-sm font-bold mt-2 inline-block hover:underline">Browse Marketplace</a>
                </div>
            @endforelse

            {{-- No results message --}}
            <div id="noResults" class="hidden text-center py-10">
                <p class="text-slate-400 text-sm font-medium">No matching vehicles found.</p>
                <p class="text-slate-300 text-xs mt-1">Try a different search term or drivetrain filter.</p>
            </div>
        </div>
    </div>
</div>

<script>
    let currentSlot = 0;
    let activeDrivetrain = 'all';
    const currentIds = @json($selected->pluck('id')->values());

    function openPicker(slot) {
        currentSlot = slot;
        document.getElementById('pickerSubtitle').textContent = 'Adding to slot ' + (slot + 1);
        const modal = document.getElementById('pickerModal');
        const card  = document.getElementById('pickerCard');
        modal.classList.remove('invisible', 'opacity-0');
        card.classList.remove('scale-95');
        card.classList.add('scale-100');
        document.getElementById('pickerSearch').value = '';
        activeDrivetrain = 'all';
        // reset chips
        document.querySelectorAll('.drivetrain-chip').forEach(btn => {
            const isAll = btn.dataset.dt === 'all';
            btn.classList.toggle('bg-slate-900', isAll);
            btn.classList.toggle('text-white', isAll);
            btn.classList.toggle('border-slate-900', isAll);
            btn.classList.toggle('bg-white', !isAll);
            btn.classList.toggle('text-slate-500', !isAll);
            btn.classList.toggle('border-slate-200', !isAll);
        });
        filterCars('');
        document.body.style.overflow = 'hidden';
        setTimeout(() => document.getElementById('pickerSearch').focus(), 250);
    }

    function closePicker() {
        const modal = document.getElementById('pickerModal');
        const card  = document.getElementById('pickerCard');
        modal.classList.add('opacity-0');
        card.classList.remove('scale-100');
        card.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('invisible');
            document.body.style.overflow = '';
        }, 200);
    }

    function selectCar(id) {
        let ids = [...currentIds];
        while (ids.length <= currentSlot) ids.push(null);
        ids[currentSlot] = id;
        ids = ids.filter((v, i, a) => v !== null && a.indexOf(v) === i);
        const params = ids.map(i => 'cars[]=' + i).join('&');
        window.location.href = '{{ route("compare_cars") }}?' + params;
    }

    function filterCars(q) {
        const items = document.querySelectorAll('.car-item');
        let visible = 0;
        const query = q.toLowerCase().trim();
        items.forEach(item => {
            const brand   = item.dataset.brand   || '';
            const model   = item.dataset.model   || '';
            const variant = item.dataset.variant || '';
            const dt      = item.dataset.drivetrain || '';
            const matchText = query === '' || brand.includes(query) || model.includes(query) || variant.includes(query);
            const matchDt   = activeDrivetrain === 'all' || dt === activeDrivetrain;
            const show = matchText && matchDt;
            item.classList.toggle('hidden', !show);
            if (show) visible++;
        });
        const noRes = document.getElementById('noResults');
        noRes.classList.toggle('hidden', visible > 0);
        const countEl = document.getElementById('resultCount');
        if (countEl) countEl.textContent = visible + ' vehicle' + (visible !== 1 ? 's' : '') + ' available';
    }

    function filterDrivetrain(dt) {
        activeDrivetrain = dt;
        document.querySelectorAll('.drivetrain-chip').forEach(btn => {
            const active = btn.dataset.dt === dt;
            btn.classList.toggle('bg-slate-900',   active);
            btn.classList.toggle('text-white',      active);
            btn.classList.toggle('border-slate-900',active);
            btn.classList.toggle('bg-white',       !active);
            btn.classList.toggle('text-slate-500', !active);
            btn.classList.toggle('border-slate-200',!active);
        });
        filterCars(document.getElementById('pickerSearch').value);
    }

    document.addEventListener('keydown', e => { if (e.key === 'Escape') closePicker(); });
</script>

@endsection