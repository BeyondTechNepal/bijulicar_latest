@extends('dashboard.buyer.layout')
@section('title', 'My Pre-Orders')
@section('page-title', 'My Pre-Orders')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Buyer Portal</p>
            <p class="text-sm font-bold text-slate-600 mt-0.5">Track all your pre-orders on upcoming EVs.</p>
        </div>
        <a href="{{ route('marketplace') }}"
            class="inline-flex items-center gap-2 bg-slate-900 text-white px-4 py-2.5 rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all shadow-lg">
            ⚡ Browse Upcoming EVs
        </a>
    </div>

    @if($preOrders->isNotEmpty())
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">

        {{-- Table header --}}
        <div class="grid grid-cols-12 gap-4 px-6 py-3 border-b border-slate-100 bg-slate-50">
            <div class="col-span-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Vehicle</div>
            <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Deposit</div>
            <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</div>
            <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Date</div>
            <div class="col-span-1"></div>
        </div>

        @foreach($preOrders as $preOrder)
        <div class="grid grid-cols-12 gap-4 px-6 py-4 border-b border-slate-100 last:border-0 items-center hover:bg-slate-50/50 transition-colors">

            {{-- Vehicle --}}
            <div class="col-span-5 flex items-center gap-3">
                <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-[10px] font-black text-slate-500 uppercase shrink-0">
                    ⚡
                </div>
                <div>
                    <p class="text-sm font-black text-slate-900">
                    {{ $preOrder->car?->displayName() ?? 'Car no longer available' }}
                </p>
                <p class="text-[11px] text-slate-400 font-medium mt-0.5">
                    {{ $preOrder->car ? ucfirst($preOrder->car->condition) . ' · ' . $preOrder->car->location : '—' }}
                </p>
                </div>
            </div>

            {{-- Deposit --}}
            <div class="col-span-2">
                <p class="text-sm font-black text-slate-800">{{ $preOrder->formattedDeposit() }}</p>
            </div>

            {{-- Status --}}
            <div class="col-span-2">
                <span @class([
                    'text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider',
                    'bg-yellow-100 text-yellow-700'  => $preOrder->status === 'pending_deposit',
                    'bg-blue-100 text-blue-700'      => $preOrder->status === 'deposit_paid',
                    'bg-[#4ade80]/15 text-[#16a34a]' => $preOrder->status === 'converted',
                    'bg-red-100 text-red-600'        => $preOrder->status === 'cancelled',
                    'bg-purple-100 text-purple-700'  => $preOrder->status === 'refunded',
                ])>{{ str_replace('_', ' ', ucfirst($preOrder->status)) }}</span>
            </div>

            {{-- Date --}}
            <div class="col-span-2">
                <p class="text-[11px] text-slate-400 font-medium">{{ $preOrder->created_at->format('d M Y') }}</p>
                <p class="text-[10px] text-slate-300 font-medium">{{ $preOrder->created_at->diffForHumans() }}</p>
            </div>

            {{-- Action --}}
            <div class="col-span-1 flex justify-end">
                <a href="{{ route('buyer.preorders.show', $preOrder) }}"
                    class="w-8 h-8 bg-slate-100 hover:bg-slate-900 hover:text-white rounded-xl flex items-center justify-center transition-all group">
                    <svg class="w-4 h-4 text-slate-500 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

        </div>
        @endforeach
    </div>

    @if($preOrders->hasPages())
    <div class="mt-5">
        {{ $preOrders->links() }}
    </div>
    @endif

    @else
    <div class="bg-white border border-dashed border-slate-200 rounded-2xl p-14 text-center">
        <p class="text-5xl mb-4">🔋</p>
        <p class="font-black text-slate-900 uppercase italic tracking-tight text-lg">No pre-orders yet</p>
        <p class="text-sm text-slate-500 font-medium mt-2 mb-6">
            Reserve an upcoming EV before it hits the market.
        </p>
        <a href="{{ route('marketplace') }}"
            class="inline-flex items-center gap-2 bg-slate-900 text-white px-6 py-3 rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all shadow-lg">
            ⚡ Browse Upcoming EVs
        </a>
    </div>
    @endif

@endsection