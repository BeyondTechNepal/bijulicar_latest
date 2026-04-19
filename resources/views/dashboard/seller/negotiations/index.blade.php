@extends($layout)
@section('title', 'Negotiations')
@section('page-title', 'Negotiations')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ ucfirst($prefix) }} Portal</p>
            <p class="text-sm font-bold text-slate-600 mt-0.5">Price offers from buyers on your listings.</p>
        </div>
    </div>

    @if($negotiations->isNotEmpty())
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">

        <div class="grid grid-cols-12 gap-4 px-6 py-3 border-b border-slate-100 bg-slate-50">
            <div class="col-span-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Vehicle</div>
            <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Buyer</div>
            <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Offer</div>
            <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</div>
            <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Date</div>
        </div>

        @foreach($negotiations as $neg)
        <a href="{{ route($prefix . '.negotiations.show', $neg) }}"
            class="grid grid-cols-12 gap-4 px-6 py-4 border-b border-slate-100 last:border-0 items-center hover:bg-slate-50/50 transition-colors block">

            <div class="col-span-4 flex items-center gap-3">
                <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-[10px] font-black text-slate-500 uppercase shrink-0">
                    {{ strtoupper(substr($neg->car?->drivetrain ?? 'EV', 0, 2)) }}
                </div>
                <div>
                    <p class="text-sm font-black text-slate-900">{{ $neg->car?->displayName() ?? 'Deleted Listing' }}</p>
                    <p class="text-[11px] text-slate-400 font-medium mt-0.5">Listed: NRs {{ number_format($neg->listed_price) }}</p>
                </div>
            </div>

            <div class="col-span-2">
                <p class="text-sm font-black text-slate-800">{{ $neg->buyer->name ?? '—' }}</p>
            </div>

            <div class="col-span-2">
                <p class="text-sm font-black text-slate-800">NRs {{ number_format($neg->offered_price) }}</p>
                <p class="text-[10px] text-red-500 font-bold">-{{ $neg->discountPercent() }}%</p>
            </div>

            <div class="col-span-2">
                @php
                    $colors = [
                        'pending_seller' => 'bg-yellow-100 text-yellow-700',
                        'pending_buyer'  => 'bg-blue-100 text-blue-700',
                        'accepted'       => 'bg-green-100 text-green-700',
                        'declined'       => 'bg-red-100 text-red-600',
                        'expired'        => 'bg-slate-100 text-slate-500',
                        'cancelled'      => 'bg-slate-100 text-slate-500',
                        'ordered'        => 'bg-green-100 text-green-700',
                    ];
                @endphp
                <span class="text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider {{ $colors[$neg->status] ?? 'bg-slate-100 text-slate-500' }}">
                    {{ $neg->statusLabel() }}
                </span>
                @if($neg->isPendingSeller())
                <p class="text-[10px] text-amber-500 font-bold mt-0.5">Action needed</p>
                @endif
            </div>

            <div class="col-span-2">
                <p class="text-[11px] text-slate-500 font-medium">{{ $neg->created_at->format('d M Y') }}</p>
                <p class="text-[10px] text-slate-400">{{ $neg->created_at->diffForHumans() }}</p>
            </div>
        </a>
        @endforeach
    </div>

    <div class="mt-4">{{ $negotiations->links() }}</div>

    @else
    <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center">
        <p class="text-2xl mb-2">🤝</p>
        <p class="text-sm font-black text-slate-900 mb-1">No negotiations yet</p>
        <p class="text-[12px] text-slate-400 font-medium">Buyers will send offers on your negotiable-price listings here.</p>
    </div>
    @endif

@endsection