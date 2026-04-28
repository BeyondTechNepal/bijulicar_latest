@extends($layout)
@section('title', 'Pre-Orders')
@section('page-title', 'Pre-Orders')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ ucfirst($prefix) }} Portal</p>
            <p class="text-sm font-bold text-slate-600 mt-0.5">Buyers who have reserved your upcoming vehicles.</p>
        </div>
    </div>

    @if ($preOrders->isNotEmpty())
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">

            {{-- Table header --}}
            <div class="grid grid-cols-12 gap-4 px-6 py-3 border-b border-slate-100 bg-slate-50">
                <div class="col-span-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Vehicle</div>
                <div class="col-span-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Buyer</div>
                <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Deposit</div>
                <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</div>
                <div class="col-span-1"></div>
            </div>

            @foreach ($preOrders as $preOrder)
                <div
                    class="border-b border-slate-100 last:border-0
                px-4 py-4 md:px-6
                flex flex-col gap-3
                md:grid md:grid-cols-12 md:gap-4 md:items-center
                hover:bg-slate-50/50 transition-colors">

                    {{-- Vehicle --}}
                    <div class="flex items-center gap-3 md:col-span-4">
                        <div class="w-10 h-10 bg-slate-100 rounded-xl overflow-hidden shrink-0 flex items-center justify-center border border-slate-200">
                            @if ($preOrder->car && $preOrder->car->primaryImage)
                                <img src="{{ $preOrder->car->primaryImage->url() }}" class="w-full h-full object-cover"
                                    alt="{{ $preOrder->car->displayName() }}">
                            @else
                                <span class="text-base opacity-20">⚡</span>
                            @endif
                        </div>

                        <div>
                            <p class="text-sm font-black text-slate-900">
                                {{ $preOrder->car->displayName() }}
                            </p>
                            <p class="text-[11px] text-slate-400 font-medium mt-0.5">
                                {{ $preOrder->car->location }}
                            </p>
                        </div>
                    </div>

                    {{-- Buyer --}}
                    <div class="flex items-center justify-between md:block md:col-span-3">
                        <p class="text-sm font-bold text-slate-800">
                            {{ $preOrder->buyer_name }}
                        </p>
                        <p class="text-[11px] text-slate-400 font-medium">
                            {{ $preOrder->buyer_phone }}
                        </p>
                    </div>

                    {{-- Deposit + Status --}}
                    <div class="flex items-center justify-between md:block md:col-span-3">

                        <p class="text-sm font-black text-slate-800">
                            {{ $preOrder->formattedDeposit() }}
                        </p>

                        <span @class([
                            'text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider',
                            'bg-yellow-100 text-yellow-700' => $preOrder->status === 'pending_deposit',
                            'bg-blue-100 text-blue-700' => $preOrder->status === 'deposit_paid',
                            'bg-[#4ade80]/15 text-[#16a34a]' => $preOrder->status === 'converted',
                            'bg-red-100 text-red-600' => $preOrder->status === 'cancelled',
                            'bg-purple-100 text-purple-700' => $preOrder->status === 'refunded',
                        ])>
                            {{ str_replace('_', ' ', ucfirst($preOrder->status)) }}
                        </span>

                    </div>

                    {{-- Action --}}
                    <div class="flex justify-end md:col-span-2">
                        <a href="{{ route($prefix . '.preorders.show', $preOrder) }}"
                            class="w-9 h-9 bg-slate-100 hover:bg-slate-900 hover:text-white rounded-xl flex items-center justify-center transition-all group">

                            <svg class="w-4 h-4 text-slate-500 group-hover:text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>

                        </a>
                    </div>

                </div>
            @endforeach
        </div>

        @if ($preOrders->hasPages())
            <div class="mt-5">{{ $preOrders->links() }}</div>
        @endif
    @else
        <div class="bg-white border border-dashed border-slate-200 rounded-2xl p-14 text-center">
            <p class="text-5xl mb-4">🔋</p>
            <p class="font-black text-slate-900 uppercase italic tracking-tight text-lg">No pre-orders yet</p>
            <p class="text-sm text-slate-500 font-medium mt-2">
                Pre-orders will appear here when buyers reserve your upcoming vehicles.
            </p>
        </div>
    @endif

@endsection