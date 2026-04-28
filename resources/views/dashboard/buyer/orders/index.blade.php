@extends('dashboard.buyer.layout')
@section('title', 'My Orders')
@section('page-title', 'My Orders')

@section('content')

    {{-- Header row --}}
    <div class="flex flex-col gap-4 mb-6 
            md:flex-row md:items-center md:justify-between">

        {{-- Left side --}}
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                Buyer Portal
            </p>
            <p class="text-sm font-bold text-slate-600 mt-0.5">
                All orders you have placed on BijuliCar.
            </p>
        </div>

        {{-- CTA Button --}}
        <a href="{{ route('marketplace') }}"
            class="inline-flex items-center justify-center gap-2 
               w-full md:w-auto
               bg-slate-900 text-white px-4 py-2.5 rounded-xl 
               text-[11px] font-black uppercase italic tracking-widest 
               hover:bg-[#16a34a] transition-all shadow-lg">

            <i class="fa-solid fa-bolt text-xs"></i>
            Place New Order
        </a>

    </div>

    {{-- Orders table --}}
    @if ($orders->isNotEmpty())
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
            {{-- Table header: Visible only on medium screens and up --}}
            <div class="hidden md:grid grid-cols-12 gap-4 px-6 py-3 border-b border-slate-100 bg-slate-50">
                <div class="col-span-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Vehicle</div>
                <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Price</div>
                <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</div>
                <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Date</div>
                <div class="col-span-1"></div>
            </div>

            {{-- Table rows --}}
            <div class="divide-y divide-slate-100">
                @foreach ($orders as $order)
                    <div
                        class="p-4 flex flex-col gap-3 md:grid md:grid-cols-12 md:gap-4 md:px-6 md:py-4 md:items-center hover:bg-slate-50/50 transition-colors">

                        {{-- Vehicle Section --}}
                        <div class="flex items-center gap-4 md:col-span-5">
                            <div
                                class="w-14 h-14 bg-slate-100 rounded-xl overflow-hidden shrink-0 flex items-center justify-center border border-slate-200">
                                @if ($order->car && $order->car->primaryImage)
                                    <img src="{{ $order->car->primaryImage->url() }}" class="w-full h-full object-cover"
                                        alt="{{ $order->car->displayName() }}">
                                @else
                                    <span class="text-xl opacity-20">⚡</span>
                                @endif
                            </div>

                            <div class="min-w-0">
                                @if ($order->car)
                                    <h4 class="text-sm font-bold text-slate-800 truncate">
                                        {{ $order->car->displayName() }}
                                    </h4>
                                    <p class="text-[11px] text-slate-400 font-medium mt-0.5">
                                        {{ ucfirst($order->car->condition) }} · {{ $order->car->location }}
                                    </p>
                                @else
                                    <p class="text-sm font-black text-slate-400 italic">Listing removed</p>
                                    <p class="text-[11px] text-slate-300 font-medium mt-0.5">This vehicle is no longer
                                        available</p>
                                @endif
                            </div>
                        </div>

                        {{-- Price (Mobile: Label + Value) --}}
                        <div class="flex items-center justify-between md:col-span-2 md:block">
                            <span class="md:hidden text-[10px] font-bold text-slate-400 uppercase">Price</span>
                            <p class="text-sm font-black text-slate-800">
                                NRs {{ number_format($order->total_price) }}
                            </p>
                        </div>

                        {{-- Status --}}
                        <div class="flex items-center justify-between md:col-span-2 md:block">
                            <span class="md:hidden text-[10px] font-bold text-slate-400 uppercase">Status</span>
                            <span @class([
                                'text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider inline-block',
                                'bg-yellow-100 text-yellow-700' => $order->status === 'pending',
                                'bg-blue-100 text-blue-700' => $order->status === 'confirmed',
                                'bg-emerald-100 text-emerald-700' => $order->status === 'completed',
                                'bg-red-100 text-red-600' => $order->status === 'cancelled',
                            ])>
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        {{-- Date --}}
                        <div class="flex items-center justify-between md:block md:col-span-2">
                            <span class="md:hidden text-[10px] font-bold text-slate-400 uppercase">Ordered</span>
                            <div>
                                <p class="text-[11px] text-slate-500 font-bold md:text-slate-400 md:font-medium">
                                    {{ $order->ordered_at->format('d M Y') }}
                                </p>
                                <p class="text-[10px] text-slate-300 font-medium hidden md:block">
                                    {{ $order->ordered_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                        {{-- Action --}}
                        <div class="flex justify-end md:col-span-1">
                            <a href="{{ route('buyer.orders.show', $order) }}"
                                class="w-full md:w-8 md:h-8 bg-slate-100 hover:bg-slate-900 hover:text-white rounded-xl flex items-center justify-center transition-all group py-2 md:py-0">
                                <span class="md:hidden text-xs font-bold mr-2">View Details</span>
                                <svg class="w-4 h-4 text-slate-500 group-hover:text-white" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Pagination --}}
        @if ($orders->hasPages())
            <div class="mt-6">
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
