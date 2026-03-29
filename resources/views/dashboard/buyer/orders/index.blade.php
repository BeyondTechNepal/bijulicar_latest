@extends('dashboard.buyer.layout')
@section('title', 'My Orders')
@section('page-title', 'My Orders')

@section('content')

    {{-- Header row --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Buyer Portal</p>
            <p class="text-sm font-bold text-slate-600 mt-0.5">All orders you have placed on BijuliCar.</p>
        </div>
        <a href="{{ route('marketplace') }}"
            class="inline-flex items-center gap-2 bg-slate-900 text-white px-4 py-2.5 rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all shadow-lg">
            ⚡ Place New Order
        </a>
    </div>

    {{-- Orders table --}}
    @if($orders->isNotEmpty())
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">

        {{-- Table header --}}
        <div class="grid grid-cols-12 gap-4 px-6 py-3 border-b border-slate-100 bg-slate-50">
            <div class="col-span-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Vehicle</div>
            <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Price</div>
            <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</div>
            <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Date</div>
            <div class="col-span-1"></div>
        </div>

        {{-- Table rows --}}
        @foreach($orders as $order)
        <div class="grid grid-cols-12 gap-4 px-6 py-4 border-b border-slate-100 last:border-0 items-center hover:bg-slate-50/50 transition-colors">

            {{-- Vehicle --}}
            <div class="col-span-5 flex items-center gap-3">
                <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-[10px] font-black text-slate-500 uppercase shrink-0">
                    EV
                </div>
                <div>
                    <p class="text-sm font-black text-slate-900">{{ $order->car->displayName() }}</p>
                    <p class="text-[11px] text-slate-400 font-medium mt-0.5">{{ ucfirst($order->car->condition) }} · {{ $order->car->location }}</p>
                </div>
            </div>

            {{-- Price --}}
            <div class="col-span-2">
                <p class="text-sm font-black text-slate-800">{{ $order->car->formattedPrice() }}</p>
            </div>

            {{-- Status badge --}}
            <div class="col-span-2">
                <span @class([
                    'text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider',
                    'bg-yellow-100 text-yellow-700'  => $order->status === 'pending',
                    'bg-blue-100 text-blue-700'      => $order->status === 'confirmed',
                    'bg-[#4ade80]/15 text-[#16a34a]' => $order->status === 'completed',
                    'bg-red-100 text-red-600'        => $order->status === 'cancelled',
                ])>{{ ucfirst($order->status) }}</span>
            </div>

            {{-- Date --}}
            <div class="col-span-2">
                <p class="text-[11px] text-slate-400 font-medium">{{ $order->ordered_at->format('d M Y') }}</p>
                <p class="text-[10px] text-slate-300 font-medium">{{ $order->ordered_at->diffForHumans() }}</p>
            </div>

            {{-- Action --}}
            <div class="col-span-1 flex justify-end">
                <a href="{{ route('buyer.orders.show', $order) }}"
                    class="w-8 h-8 bg-slate-100 hover:bg-slate-900 hover:text-white rounded-xl flex items-center justify-center transition-all group">
                    <svg class="w-4 h-4 text-slate-500 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

        </div>
        @endforeach

    </div>

    {{-- Pagination --}}
    @if($orders->hasPages())
    <div class="mt-5">
        {{ $orders->links() }}
    </div>
    @endif

    @else
    {{-- Empty state --}}
    <div class="bg-white border border-dashed border-slate-200 rounded-2xl p-14 text-center">
        <p class="text-5xl mb-4">📋</p>
        <p class="font-black text-slate-900 uppercase italic tracking-tight text-lg">No orders yet</p>
        <p class="text-sm text-slate-500 font-medium mt-2 mb-6">
            Browse the marketplace and place your first order.
        </p>
        <a href="{{ route('marketplace') }}"
            class="inline-flex items-center gap-2 bg-slate-900 text-white px-6 py-3 rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all shadow-lg">
            ⚡ Browse Marketplace
        </a>
    </div>
    @endif

@endsection