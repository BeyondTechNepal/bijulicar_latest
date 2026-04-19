@extends($layout)
@section('title', 'Incoming Orders')
@section('page-title', 'Incoming Orders')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ ucfirst($prefix) }} Portal</p>
            <p class="text-sm font-bold text-slate-600 mt-0.5">Orders placed by buyers on your listings.</p>
        </div>
    </div>

    @if($orders->isNotEmpty())
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">

        {{-- Table header --}}
        <div class="grid grid-cols-12 gap-4 px-6 py-3 border-b border-slate-100 bg-slate-50">
            <div class="col-span-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Vehicle</div>
            <div class="col-span-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Buyer</div>
            <div class="col-span-1 text-[10px] font-black text-slate-400 uppercase tracking-widest">Price</div>
            <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</div>
            <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Date</div>
        </div>

        @foreach($orders as $order)
        <a href="{{ route($prefix . '.orders.show', $order) }}"
            class="grid grid-cols-12 gap-4 px-6 py-4 border-b border-slate-100 last:border-0 items-center hover:bg-slate-50/50 transition-colors block">

            {{-- Vehicle --}}
            <div class="col-span-4 flex items-center gap-3">
                <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-[10px] font-black text-slate-500 uppercase shrink-0">
                    {{ strtoupper($order->car->drivetrain) }}
                </div>
                <div>
                    <p class="text-sm font-black text-slate-900 uppercase italic tracking-tight leading-tight">
                        {{ $order->car->displayName() }}
                    </p>
                    <p class="text-[11px] text-slate-400 font-medium mt-0.5">
                        Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                    </p>
                </div>
            </div>

            {{-- Buyer name + phone --}}
            <div class="col-span-3">
                <p class="text-sm font-bold text-slate-700">{{ $order->buyer_name ?? $order->buyer->name }}</p>
                @if($order->buyer_phone)
                    <p class="text-[11px] text-green-600 font-bold mt-0.5">📞 {{ $order->buyer_phone }}</p>
                @endif
            </div>

            {{-- Price --}}
            <div class="col-span-1">
                <p class="text-sm font-black text-slate-800">NRs {{ number_format($order->total_price) }}</p>
                @if($order->total_price < $order->car->price)
                    <p class="text-[10px] text-green-600 font-bold">Negotiated</p>
                @endif
            </div>

            {{-- Status --}}
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

        </a>
        @endforeach

    </div>

    @if($orders->hasPages())
    <div class="mt-5">{{ $orders->links() }}</div>
    @endif

    @else
    <div class="bg-white border border-dashed border-slate-200 rounded-2xl p-14 text-center">
        <p class="text-5xl mb-4">📋</p>
        <p class="font-black text-slate-900 uppercase italic tracking-tight text-lg">No orders yet</p>
        <p class="text-sm text-slate-500 font-medium mt-2 mb-6">
            Once buyers place orders on your listings they will appear here.
        </p>
        <a href="{{ route($prefix . '.cars.index') }}"
            class="inline-flex items-center gap-2 bg-slate-900 text-white px-6 py-3 rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all shadow-lg">
            View My Listings →
        </a>
    </div>
    @endif

@endsection