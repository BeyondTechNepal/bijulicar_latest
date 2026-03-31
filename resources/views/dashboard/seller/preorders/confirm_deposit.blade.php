@extends($layout)
@section('title', 'Confirm Deposit')
@section('page-title', 'Confirm Deposit Received')

@section('content')

    <a href="{{ route($prefix . '.preorders.show', $preOrder) }}"
        class="inline-flex items-center gap-2 text-[11px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-700 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Pre-Order
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Payment form --}}
        <div class="lg:col-span-2">
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Deposit Payment Details</p>

                <form method="POST" action="{{ route($prefix . '.preorders.confirm_deposit', $preOrder) }}">
                    @csrf
                    @method('PATCH')

                    {{-- Payment method --}}
                    <div class="mb-6">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-3">
                            Payment Method <span class="text-red-400">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach([
                                'cash'          => '💵 Cash',
                                'bank_transfer' => '🏦 Bank Transfer',
                                'emi'           => '📅 EMI',
                                'other'         => '💳 Other',
                            ] as $value => $label)
                            <label class="relative cursor-pointer">
                                <input type="radio" name="payment_method" value="{{ $value }}"
                                    class="peer sr-only"
                                    {{ old('payment_method', 'cash') === $value ? 'checked' : '' }}>
                                <div class="w-full flex items-center gap-3 px-4 py-3 border-2 border-slate-100 rounded-xl font-bold text-sm text-slate-600
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
                    <div class="mb-5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">
                            Transaction Reference
                            <span class="text-slate-300 normal-case font-medium ml-1">(optional)</span>
                        </label>
                        <input type="text"
                            name="transaction_ref"
                            value="{{ old('transaction_ref') }}"
                            placeholder="e.g. cheque no., bank ref, receipt no."
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 placeholder:text-slate-300 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                        @error('transaction_ref')
                            <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remarks --}}
                    <div class="mb-8">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">
                            Remarks
                            <span class="text-slate-300 normal-case font-medium ml-1">(optional)</span>
                        </label>
                        <textarea
                            name="remarks"
                            rows="3"
                            placeholder="e.g. Buyer paid in cash at our showroom."
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 placeholder:text-slate-300 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all resize-none">{{ old('remarks') }}</textarea>
                        @error('remarks')
                            <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-3 bg-slate-900 text-white py-4 rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all shadow-xl shadow-slate-200">
                        ✓ Confirm Deposit Received
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </button>

                    <p class="text-center text-[10px] font-bold text-slate-300 uppercase tracking-widest mt-4">
                        This will update the pre-order status to Deposit Paid automatically.
                    </p>
                </form>
            </div>
        </div>

        {{-- Right: Pre-order summary --}}
        <div class="space-y-5">
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Pre-Order Summary</p>

                <div class="flex items-start gap-3 mb-5">
                    <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center text-lg shrink-0">⚡</div>
                    <div>
                        <p class="text-sm font-black text-slate-900 uppercase italic tracking-tight leading-tight">
                            {{ $preOrder->car->displayName() }}
                        </p>
                        <p class="text-[11px] text-slate-400 font-medium mt-1">
                            {{ $preOrder->car->location }} · {{ ucfirst($preOrder->car->condition) }}
                        </p>
                    </div>
                </div>

                <div class="space-y-2.5 pt-4 border-t border-slate-100">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-slate-400">Pre-Order ID</p>
                        <p class="text-xs font-mono text-slate-700">#{{ str_pad($preOrder->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-slate-400">Buyer</p>
                        <p class="text-xs font-bold text-slate-700">{{ $preOrder->buyer_name }}</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-slate-400">Phone</p>
                        <p class="text-xs font-medium text-slate-600">{{ $preOrder->buyer_phone }}</p>
                    </div>
                    <div class="pt-3 border-t-2 border-dashed border-slate-100 flex items-center justify-between">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Deposit Amount</p>
                        <p class="text-xl font-black text-[#16a34a]">{{ $preOrder->formattedDeposit() }}</p>
                    </div>
                </div>
            </div>

            @if($preOrder->notes)
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Buyer's Note</p>
                <p class="text-sm text-slate-600 font-medium leading-relaxed italic">"{{ $preOrder->notes }}"</p>
            </div>
            @endif

            <div class="bg-slate-900 rounded-2xl p-5">
                <p class="text-[10px] font-black text-[#4ade80] uppercase tracking-widest mb-3">⚡ What happens next</p>
                <ul class="space-y-2">
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-[#4ade80] mt-0.5 shrink-0">→</span>
                        Pre-order status changes to <strong class="text-white">Deposit Paid</strong>.
                    </li>
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-[#4ade80] mt-0.5 shrink-0">→</span>
                        Buyer's dashboard updates automatically.
                    </li>
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-[#4ade80] mt-0.5 shrink-0">→</span>
                        When the car arrives, you can convert this to a full confirmed order.
                    </li>
                </ul>
            </div>
        </div>

    </div>

@endsection