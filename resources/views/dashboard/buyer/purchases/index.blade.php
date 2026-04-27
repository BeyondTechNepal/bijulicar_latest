@extends('dashboard.buyer.layout')
@section('title', 'My Purchases')
@section('page-title', 'My Purchases')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Buyer Portal</p>
            <p class="text-sm font-bold text-slate-600 mt-0.5">Vehicles you have successfully purchased.</p>
        </div>
    </div>

    @if ($purchases->isNotEmpty())
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">

            {{-- Table header --}}
            <div class="grid grid-cols-12 gap-4 px-6 py-3 border-b border-slate-100 bg-slate-50">
                <div class="col-span-2 text-[8px] font-black text-slate-400 uppercase tracking-widest">Vehicle</div>
                <div class="col-span-2 text-[8px] font-black text-slate-400 uppercase tracking-widest">Amount Paid</div>
                <div class="col-span-2 text-[8px] font-black text-slate-400 uppercase tracking-widest">Method</div>
                <div class="col-span-2 text-[8px] font-black text-slate-400 uppercase tracking-widest">Status</div>
                <div class="col-span-2 text-[8px] font-black text-slate-400 uppercase tracking-widest">Date</div>
                <div class="col-span-1 text-[8px] font-black text-slate-400 uppercase tracking-widest">Review</div>
            </div>

            {{-- Rows --}}
            @foreach ($purchases as $purchase)
                <div
                    class="border border-slate-100 rounded-xl p-4 
                flex flex-col gap-3
                md:grid md:grid-cols-12 md:gap-4 md:px-6 md:py-4 md:items-center 
                md:border-0 md:rounded-none md:border-b md:last:border-0
                hover:bg-slate-50/50 transition-colors">

                    {{-- Vehicle --}}
                    <div class="flex items-center gap-3 md:col-span-3">
                        <div
                            class="w-10 h-10 bg-[#4ade80]/10 border border-[#4ade80]/20 rounded-xl flex items-center justify-center text-[10px] font-black text-[#16a34a] uppercase shrink-0">
                            {{ $purchase->order->car ? strtoupper($purchase->order->car->drivetrain) : '—' }}
                        </div>

                        <div>
                            <p class="text-sm font-black text-slate-900">
                                {{ $purchase->order->car ? $purchase->order->car->displayName() : 'Listing removed' }}
                            </p>
                            <p class="text-[11px] text-slate-400 font-medium mt-0.5">
                                Order #{{ str_pad($purchase->order_id, 5, '0', STR_PAD_LEFT) }}
                            </p>
                        </div>
                    </div>

                    {{-- Payment (clean separation) --}}
                    <div class="md:col-span-5 flex items-center justify-between md:justify-start md:gap-6">

                        <div>
                            <p class="text-sm font-black text-slate-800">
                                {{ $purchase->formattedAmount() }}
                            </p>
                            <p class="text-[11px] text-slate-500 font-medium">
                                {{ $purchase->paymentMethodLabel() }}
                            </p>
                        </div>

                        <span @class([
                            'text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider',
                            'bg-[#4ade80]/15 text-[#16a34a]' => $purchase->payment_status === 'paid',
                            'bg-yellow-100 text-yellow-700' => $purchase->payment_status === 'pending',
                            'bg-red-100 text-red-600' => $purchase->payment_status === 'failed',
                            'bg-slate-100 text-slate-600' => $purchase->payment_status === 'refunded',
                        ])>
                            {{ ucfirst($purchase->payment_status) }}
                        </span>

                    </div>

                    {{-- Date --}}
                    <div class="flex items-center justify-between md:block md:col-span-2">
                        <p class="text-[11px] text-slate-400 font-medium">
                            {{ $purchase->purchased_at->format('d M Y') }}
                        </p>
                        <p class="text-[10px] text-slate-300 font-medium">
                            {{ $purchase->purchased_at->diffForHumans() }}
                        </p>
                    </div>

                    {{-- Review --}}
                    <div class="flex justify-end md:col-span-2">
                        @if ($purchase->order->car)
                            @php
                                $alreadyReviewed = auth()
                                    ->user()
                                    ->reviews()
                                    ->where('car_id', $purchase->order->car->id)
                                    ->exists();
                            @endphp

                            @if ($alreadyReviewed)
                                <span class="text-[10px] font-black text-[#16a34a] uppercase tracking-widest">
                                    ✓ Done
                                </span>
                            @else
                                <a href="{{ route('buyer.reviews.create', ['car_id' => $purchase->order->car->id]) }}"
                                    class="text-[10px] font-black px-2.5 py-1.5 bg-slate-900 text-white rounded-lg uppercase tracking-widest hover:bg-[#16a34a] transition-all">
                                    Review
                                </a>
                            @endif
                        @else
                            <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">—</span>
                        @endif
                    </div>

                </div>
            @endforeach

        </div>

        @if ($purchases->hasPages())
            <div class="mt-5">{{ $purchases->links() }}</div>
        @endif
    @else
        <div class="bg-white border border-dashed border-slate-200 rounded-2xl p-14 text-center">
            <p class="text-5xl mb-4">🚗</p>
            <p class="font-black text-slate-900 uppercase italic tracking-tight text-lg">No purchases yet</p>
            <p class="text-sm text-slate-500 font-medium mt-2 mb-6">
                Once you complete payment for an order it will appear here.
            </p>
            <a href="{{ route('buyer.orders.index') }}"
                class="inline-flex items-center gap-2 bg-slate-900 text-white px-6 py-3 rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all shadow-lg">
                View My Orders →
            </a>
        </div>
    @endif

@endsection
