@extends('admin.layout')
@section('title', 'Revenue')
@section('page-title', 'Revenue')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@section('content')

    {{-- ── Period Filter Bar ──────────────────────────────────────────── --}}
    <div class="flex flex-wrap items-center gap-2 mb-8">
        <span class="text-xs font-black text-slate-400 uppercase tracking-widest mr-1">Period:</span>
        @foreach ([
            '1month'  => 'Last Month',
            '3months' => 'Last 3 Months',
            '6months' => 'Last 6 Months',
            '1year'   => 'Last Year',
            '2years'  => 'Last 2 Years',
            'all'     => 'All Time',
        ] as $key => $label)
            <a href="{{ route('admin.revenue.index', ['period' => $key]) }}"
               class="px-4 py-2 rounded-full text-xs font-black uppercase tracking-wider transition-all
                      {{ $period === $key
                            ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/20'
                            : 'bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 hover:border-slate-300' }}">
                {{ $label }}
            </a>
        @endforeach
        <span class="ml-auto text-[10px] text-slate-400 font-bold uppercase tracking-widest">
            {{ $startDate->format('d M Y') }} – {{ $endDate->format('d M Y') }}
        </span>
    </div>

    {{-- ── KPI Cards ───────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        {{-- Total Revenue --}}
        <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-3xl p-6 text-white shadow-xl shadow-emerald-900/20">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2.5 bg-white/20 rounded-2xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest opacity-70">Platform Revenue</span>
            </div>
            <p class="text-xs font-black uppercase tracking-widest opacity-70">Total Earned</p>
            <h3 class="text-3xl font-black mt-1">NRs {{ number_format($totalRevenue) }}</h3>
            <p class="text-xs opacity-60 mt-2 font-semibold">From {{ $totalCount }} paid advertisement{{ $totalCount !== 1 ? 's' : '' }}</p>
        </div>

        {{-- Average per Ad --}}
        <div class="bg-white border border-slate-200 rounded-3xl p-6 hover:shadow-lg hover:-translate-y-1 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-2xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest opacity-60">Avg</span>
            </div>
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Avg. per Advertisement</p>
            <h3 class="text-3xl font-black text-slate-900 mt-1">
                NRs {{ $totalCount > 0 ? number_format($totalRevenue / $totalCount) : '0' }}
            </h3>
            <p class="text-xs text-slate-400 font-semibold mt-2">Across {{ $totalCount }} paid ads</p>
        </div>

        {{-- Top Placement --}}
        <div class="bg-white border border-slate-200 rounded-3xl p-6 hover:shadow-lg hover:-translate-y-1 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2.5 bg-amber-50 text-amber-600 rounded-2xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                </div>
                <span class="text-[10px] font-black text-amber-600 uppercase tracking-widest opacity-60">Best</span>
            </div>
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Top Placement</p>
            @if ($byPlacement->isNotEmpty())
                <h3 class="text-lg font-black text-slate-900 mt-1 leading-tight">
                    {{ \App\Models\Advertisement::PLACEMENTS[$byPlacement->first()->placement] ?? ucfirst($byPlacement->first()->placement) }}
                </h3>
                <p class="text-xs text-slate-400 font-semibold mt-2">NRs {{ number_format($byPlacement->first()->total) }}</p>
            @else
                <h3 class="text-lg font-black text-slate-400 mt-1">—</h3>
            @endif
        </div>
    </div>

    {{-- ── Chart + Breakdown ───────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">

        {{-- Monthly Revenue Chart --}}
        <div class="xl:col-span-2 bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
            <div class="mb-6">
                <h3 class="font-black text-slate-800 text-sm tracking-tight">Monthly Ad Revenue</h3>
                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest mt-1">Revenue collected from advertisements</p>
            </div>
            <canvas id="revenueChart" height="110"></canvas>
        </div>

        {{-- Revenue by Placement --}}
        <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
            <div class="mb-5">
                <h3 class="font-black text-slate-800 text-sm tracking-tight">Revenue by Placement</h3>
                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest mt-1">Which slots earn most</p>
            </div>

            @if ($byPlacement->isEmpty())
                <p class="text-slate-400 text-sm font-semibold text-center py-8">No data for this period.</p>
            @else
                @php $maxPlacement = $byPlacement->max('total'); @endphp
                <div class="space-y-4">
                    @foreach ($byPlacement as $row)
                        @php
                            $label = \App\Models\Advertisement::PLACEMENTS[$row->placement] ?? ucfirst($row->placement);
                            $pct   = $maxPlacement > 0 ? ($row->total / $maxPlacement) * 100 : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs font-bold text-slate-600 truncate pr-2" title="{{ $label }}">{{ $label }}</span>
                                <span class="text-xs font-black text-slate-800 shrink-0">NRs {{ number_format($row->total) }}</span>
                            </div>
                            <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-500 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                            </div>
                            <p class="text-[10px] text-slate-400 font-bold mt-0.5">{{ $row->count }} ad{{ $row->count != 1 ? 's' : '' }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ── Payment Method Breakdown + Transactions ─────────────────────── --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Payment Methods --}}
        <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
            <div class="mb-5">
                <h3 class="font-black text-slate-800 text-sm tracking-tight">By Payment Method</h3>
                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest mt-1">How advertisers paid</p>
            </div>

            @php
                $methodLabels = \App\Models\Advertisement::PAYMENT_METHODS;
                $methodColors = ['cash' => 'emerald', 'bank' => 'indigo', 'esewa' => 'violet', 'other' => 'slate'];
            @endphp

            @if ($byMethod->isEmpty())
                <p class="text-slate-400 text-sm font-semibold text-center py-8">No data for this period.</p>
            @else
                <div class="space-y-3">
                    @foreach ($byMethod as $method => $row)
                        @php
                            $color = $methodColors[$method] ?? 'slate';
                            $pct   = $totalRevenue > 0 ? ($row->total / $totalRevenue) * 100 : 0;
                        @endphp
                        <div class="flex items-center gap-3">
                            <span class="w-2.5 h-2.5 rounded-full bg-{{ $color }}-500 shrink-0"></span>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between mb-1">
                                    <span class="text-xs font-bold text-slate-600">{{ $methodLabels[$method] ?? ucfirst($method) }}</span>
                                    <span class="text-xs font-black text-slate-800">{{ number_format($pct, 1) }}%</span>
                                </div>
                                <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-{{ $color }}-500 rounded-full" style="width: {{ $pct }}%"></div>
                                </div>
                                <p class="text-[10px] text-slate-400 font-bold mt-0.5">NRs {{ number_format($row->total) }} · {{ $row->count }} payment{{ $row->count != 1 ? 's' : '' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Recent Transactions --}}
        <div class="xl:col-span-2 bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div>
                    <h3 class="font-black text-slate-800 text-sm tracking-tight">Recent Ad Payments</h3>
                    <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest mt-1">Latest confirmed payments in this period</p>
                </div>
                <a href="{{ route('admin.advertisements.index') }}"
                   class="text-[10px] font-black text-emerald-600 uppercase tracking-widest hover:text-slate-900 transition-colors flex items-center gap-1">
                    All Ads
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            @if ($transactions->isEmpty())
                <div class="px-6 py-14 text-center">
                    <svg class="w-10 h-10 text-slate-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    <p class="text-slate-400 font-bold text-sm">No payments found for this period.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                <th class="px-6 py-3 text-left">Ad Title</th>
                                <th class="px-6 py-3 text-left">Advertiser</th>
                                <th class="px-6 py-3 text-left">Method</th>
                                <th class="px-6 py-3 text-left">Paid On</th>
                                <th class="px-6 py-3 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($transactions as $ad)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-3.5 font-semibold text-slate-700 max-w-[160px] truncate">
                                        <a href="{{ route('admin.advertisements.show', $ad) }}"
                                           class="hover:text-indigo-600 transition-colors">
                                            {{ $ad->title }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-3.5 text-slate-500 text-xs font-semibold">
                                        {{ optional($ad->owner)->name ?? '—' }}
                                    </td>
                                    <td class="px-6 py-3.5">
                                        @php
                                            $mc = $methodColors[$ad->payment_method] ?? 'slate';
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black bg-{{ $mc }}-100 text-{{ $mc }}-700">
                                            {{ $methodLabels[$ad->payment_method] ?? ucfirst($ad->payment_method) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3.5 text-slate-400 text-xs font-bold">
                                        {{ \Carbon\Carbon::parse($ad->paid_at)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-3.5 text-right font-black text-slate-900">
                                        NRs {{ number_format($ad->amount_paid) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-slate-200 bg-slate-50/50">
                                <td colspan="4" class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest">
                                    Period Total
                                </td>
                                <td class="px-6 py-4 text-right font-black text-emerald-600 text-base">
                                    NRs {{ number_format($totalRevenue) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const data = @json($monthlyData);

    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.map(d => d.label),
            datasets: [{
                label: 'Ad Revenue',
                data: data.map(d => d.total),
                backgroundColor: 'rgba(16,185,129,0.8)',
                borderRadius: 8,
                borderSkipped: false,
                hoverBackgroundColor: 'rgba(16,185,129,1)',
            }],
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ' NRs ' + ctx.parsed.y.toLocaleString(),
                    },
                },
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10, weight: '700' }, color: '#94a3b8' },
                },
                y: {
                    grid: { color: '#f1f5f9' },
                    beginAtZero: true,
                    ticks: {
                        font: { size: 10, weight: '700' },
                        color: '#94a3b8',
                        callback: v => v >= 1000000
                            ? 'NRs ' + (v / 1000000).toFixed(1) + 'M'
                            : v >= 1000 ? 'NRs ' + (v / 1000).toFixed(0) + 'K'
                            : 'NRs ' + v,
                    },
                },
            },
        },
    });
});
</script>
@endpush