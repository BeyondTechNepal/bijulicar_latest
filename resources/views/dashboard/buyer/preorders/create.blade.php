@extends('dashboard.buyer.layout')
@section('title', 'Pre-Order ' . $car->displayName())
@section('page-title', 'Place Pre-Order')

@section('content')

    <a href="{{ route('marketplace') }}"
        class="inline-flex items-center gap-2 text-[11px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-700 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Marketplace
    </a>

    @if($existing)
    <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-2xl p-5 flex items-start gap-3">
        <span class="text-yellow-500 text-lg mt-0.5">⚠</span>
        <div>
            <p class="text-sm font-black text-yellow-800">You already have an active pre-order for this car.</p>
            <p class="text-xs text-yellow-600 font-medium mt-1">
                <a href="{{ route('buyer.preorders.show', $existing) }}" class="underline hover:text-yellow-800">View your existing pre-order →</a>
            </p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Form --}}
        <div class="lg:col-span-2">
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Your Contact Details</p>

                <form method="POST" action="{{ route('buyer.preorders.store') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="car_id" value="{{ $car->id }}">

                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-wider mb-1.5">Full Name</label>
                        <input type="text" name="buyer_name"
                            value="{{ old('buyer_name', auth()->user()->name) }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#4ade80]/40 focus:border-[#4ade80] transition"
                            required>
                        @error('buyer_name')<p class="text-xs text-red-500 font-medium mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-wider mb-1.5">Phone Number</label>
                            <input type="text" name="buyer_phone"
                                value="{{ old('buyer_phone', auth()->user()->phone) }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#4ade80]/40 focus:border-[#4ade80] transition"
                                placeholder="98XXXXXXXX" required>
                            @error('buyer_phone')<p class="text-xs text-red-500 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-wider mb-1.5">Email</label>
                            <input type="email" name="buyer_email"
                                value="{{ old('buyer_email', auth()->user()->email) }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#4ade80]/40 focus:border-[#4ade80] transition"
                                required>
                            @error('buyer_email')<p class="text-xs text-red-500 font-medium mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-wider mb-1.5">
                            Notes <span class="text-slate-300 font-medium normal-case">(optional)</span>
                        </label>
                        <textarea name="notes" rows="3"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#4ade80]/40 focus:border-[#4ade80] transition resize-none"
                            placeholder="Any message for the seller…">{{ old('notes') }}</textarea>
                        @error('notes')<p class="text-xs text-red-500 font-medium mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" @if($existing) disabled @endif
                            class="w-full bg-slate-900 text-white py-4 rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                            ⚡ Place Pre-Order
                        </button>
                        <p class="text-center text-[10px] font-bold text-slate-300 uppercase tracking-widest mt-3">
                            The seller will contact you to arrange the deposit payment.
                        </p>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right: Car summary --}}
        <div class="space-y-5">
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Vehicle</p>

                <div class="flex items-start gap-3 mb-5">
                    <div class="w-14 h-14 bg-slate-100 rounded-2xl overflow-hidden shrink-0 flex items-center justify-center">
                        @if($car->primaryImage ?? false)
                            <img src="{{ $car->primaryImage->url() }}" class="w-full h-full object-cover" alt="{{ $car->displayName() }}">
                        @else
                            <span class="text-2xl opacity-20">⚡</span>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-base font-black text-slate-900 uppercase italic tracking-tight leading-tight">
                            {{ $car->displayName() }}
                        </h2>
                        <p class="text-xs text-slate-400 font-medium mt-1">
                            {{ ucfirst($car->condition) }} · {{ $car->location }}
                        </p>
                    </div>
                </div>

                <div class="space-y-3 border-t border-slate-100 pt-4">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-slate-400">Car Price</p>
                        <p class="text-sm font-black text-slate-800">{{ $car->formattedPrice() }}</p>
                    </div>
                    @if($car->expected_arrival_date)
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-slate-400">Expected Arrival</p>
                        <p class="text-sm font-black text-slate-800">{{ $car->expected_arrival_date->format('M Y') }}</p>
                    </div>
                    @endif
                    <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                        <p class="text-xs font-bold text-slate-400">Deposit Required</p>
                        <p class="text-base font-black text-[#16a34a]">NRs {{ number_format($car->preorder_deposit) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-slate-900 rounded-2xl p-5">
                <p class="text-[10px] font-black text-[#4ade80] uppercase tracking-widest mb-3">⚡ What happens next</p>
                <ul class="space-y-2.5">
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-[#4ade80] mt-0.5 shrink-0">1.</span>
                        You place the pre-order — no payment needed yet.
                    </li>
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-[#4ade80] mt-0.5 shrink-0">2.</span>
                        The seller contacts you to collect the deposit of <strong class="text-white">NRs {{ number_format($car->preorder_deposit) }}</strong> in person.
                    </li>
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-[#4ade80] mt-0.5 shrink-0">3.</span>
                        Seller confirms deposit received — your status updates automatically.
                    </li>
                    <li class="text-xs font-medium text-slate-400 flex items-start gap-2">
                        <span class="text-[#4ade80] mt-0.5 shrink-0">4.</span>
                        When the car arrives, your pre-order becomes a full order.
                    </li>
                </ul>
            </div>
        </div>

    </div>

@endsection