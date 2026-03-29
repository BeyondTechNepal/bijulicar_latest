@extends('dashboard.buyer.layout')
@section('title', 'Complete Payment')
@section('page-title', 'Complete Payment')

@section('content')

    {{-- Back link --}}
    <a href="{{ route('buyer.orders.show', $order) }}"
        class="inline-flex items-center gap-2 text-[11px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-700 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Order
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Payment form --}}
        <div class="lg:col-span-2">
            <div class="bg-white border border-slate-200 rounded-2xl p-6">

                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Payment Details</p>

                <form method="POST" action="{{ route('buyer.purchases.store', $order) }}">
                    @csrf

                    {{-- Payment method --}}
                    <div class="mb-6">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-3">
                            Payment Method
                        </label>
                        <div class="grid grid-cols-2 gap-3">

                            @foreach(['cash' => '💵 Cash', 'bank_transfer' => '🏦 Bank Transfer', 'emi' => '📅 EMI', 'other' => '💳 Other'] as $value => $label)
                            <label class="relative cursor-pointer">
                                <input type="radio" name="payment_method" value="{{ $value }}"
                                    class="peer sr-only"
                                    {{ old('payment_method', 'cash') === $value ? 'checked' : '' }}>
                                <div class="w-full flex items-center gap-3 px-4 py-3 border-2 border-slate-100 rounded-xl font-black text-sm text-slate-600
                                    peer-checked:border-[#4ade80] peer-checked:bg-[#4ade80]/5 peer-checked:text-[#16a34a]
                                    hover:border-slate-200 transition-all">
                                    {{ $label }}
                                </div>
                            </label>
                            @endforeach

                        </div>
                        @error('payment_method')
                            <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Transaction reference --}}
                    <div class="mb-8">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">
                            Transaction Reference
                            <span class="text-slate-300 normal-case font-medium ml-1">(optional — for bank transfers)</span>
                        </label>
                        <input type="text"
                            name="transaction_ref"
                            value="{{ old('transaction_ref') }}"
                            placeholder="e.g. TXN-2024-001234"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 placeholder:text-slate-300 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                        @error('transaction_ref')
                            <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm button --}}
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-3 bg-slate-900 text-white py-4 rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all shadow-xl shadow-slate-200">
                        💳 Confirm Payment of {{ $order->car->formattedPrice() }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </button>

                    <p class="text-center text-[10px] font-bold text-slate-300 uppercase tracking-widest mt-4">
                        This action cannot be undone. The car will be marked as sold.
                    </p>

                </form>
            </div>
        </div>

        {{-- Right: Order summary --}}
        <div class="space-y-5">

            {{-- Car summary --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Order Summary</p>

                <div class="flex items-start gap-3 mb-5">
                    <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center text-[10px] font-black text-slate-500 uppercase shrink-0">
                        {{ strtoupper($order->car->drivetrain) }}
                    </div>
                    <div>
                        <p class="text-sm font-black text-slate-900 uppercase italic tracking-tight leading-tight">
                            {{ $order->car->displayName() }}
                        </p>
                        <p class="text-[11px] text-slate-400 font-medium mt-1">
                            {{ $order->car->location }} · {{ ucfirst($order->car->condition) }}
                        </p>
                    </div>
                </div>

                <div class="space-y-2 pt-4 border-t border-slate-100">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-slate-400">Order ID</p>
                        <p class="text-xs font-mono text-slate-700">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-slate-400">Seller</p>
                        <p class="text-xs font-bold text-slate-700">{{ $order->car->seller->name }}</p>
                    </div>
                    @if($order->car->range_km)
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-slate-400">Range</p>
                        <p class="text-xs font-bold text-[#16a34a]">⚡ {{ $order->car->range_km }} km</p>
                    </div>
                    @endif
                </div>

                {{-- Total --}}
                <div class="mt-4 pt-4 border-t-2 border-dashed border-slate-100 flex items-center justify-between">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Due</p>
                    <p class="text-xl font-black text-slate-900">{{ $order->car->formattedPrice() }}</p>
                </div>
            </div>

            {{-- Info box --}}
            <div class="bg-slate-900 rounded-2xl p-5">
                <p class="text-[10px] font-black text-[#4ade80] uppercase tracking-widest mb-3">⚡ Important</p>
                <ul class="space-y-2">
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-[#4ade80] mt-0.5">→</span>
                        Payment confirmation is final and cannot be reversed.
                    </li>
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-[#4ade80] mt-0.5">→</span>
                        The car will be marked as sold immediately.
                    </li>
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-[#4ade80] mt-0.5">→</span>
                        You can write a review after purchase is complete.
                    </li>
                </ul>
            </div>

        </div>
    </div>

@endsection