@extends('dashboard.business.layout')
@section('title', 'Edit Advertisement')
@section('page-title', 'Edit Advertisement')

@section('content')

    <div class="mb-6">
        <a href="{{ route('business.advertisements.index') }}"
            class="inline-flex items-center gap-2 text-[11px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-700 transition-colors">
            ← Back to Advertisements
        </a>
    </div>

    {{-- Pass pricing rules to JS --}}
    <script>
    const pricingRules = @json($pricingRules);
    </script>

    {{-- Rejected reason notice --}}
    @if ($advertisement->status === 'rejected' && $advertisement->rejection_reason)
        <div class="bg-red-50 border-l-4 border-red-500 rounded-xl px-5 py-4 mb-6">
            <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mb-1">Rejection Reason</p>
            <p class="text-sm text-red-700 leading-relaxed">{{ $advertisement->rejection_reason }}</p>
            <p class="text-[11px] text-red-400 mt-2">Fix the issue below and resubmit — it will go back into review.</p>
        </div>
    @endif

    <div class="flex flex-col xl:flex-row gap-6 items-start">

        {{-- ── FORM (left) ───────────────────────────────────────────────── --}}
        <div class="flex-1 max-w-2xl w-full">
            <form method="POST" action="{{ route('business.advertisements.update', $advertisement) }}"
                enctype="multipart/form-data" id="ad-form">
                @csrf
                @method('PATCH')

                <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-5">

                    {{-- Title --}}
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">
                            Title <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title', $advertisement->title) }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-400 outline-none transition-all">
                        @error('title')<p class="text-red-500 text-[11px] font-bold mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Description</label>
                        <textarea name="description" rows="3"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-900 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-400 outline-none transition-all resize-none">{{ old('description', $advertisement->description) }}</textarea>
                        @error('description')<p class="text-red-500 text-[11px] font-bold mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Placement --}}
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">
                            Placement <span class="text-red-400">*</span>
                        </label>
                        <select name="placement" id="placement-select"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-400 outline-none transition-all appearance-none">
                            @foreach($placements as $value => $label)
                                <option value="{{ $value }}"
                                    {{ old('placement', $advertisement->placement) === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <p id="image-hint" class="text-[11px] text-slate-400 font-medium mt-1.5">
                            <!-- Recommended image: <span id="image-hint-size" class="font-bold text-slate-600">1200×400 px</span> -->
                        </p>
                        @error('placement')<p class="text-red-500 text-[11px] font-bold mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Priority --}}
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">
                            Priority Tier <span class="text-red-400">*</span>
                        </label>
                        <div class="grid grid-cols-3 gap-3" id="priority-group">
                            @foreach($priorities as $value => $label)
                            <label class="relative cursor-pointer">
                                <input type="radio" name="priority" value="{{ $value }}"
                                    {{ old('priority', $advertisement->priority) == $value ? 'checked' : '' }}
                                    class="peer sr-only priority-radio">
                                <div class="w-full text-center px-3 py-3 rounded-xl border-2 transition-all
                                    peer-checked:border-slate-900 peer-checked:bg-slate-900 peer-checked:text-white
                                    border-slate-200 bg-slate-50 hover:border-slate-300
                                    {{ $value === 2 ? 'peer-checked:border-amber-500 peer-checked:bg-amber-500' : ($value === 1 ? 'peer-checked:border-purple-600 peer-checked:bg-purple-600' : '') }}">
                                    <p class="text-[11px] font-black uppercase tracking-wider">
                                        {{ $value === 2 ? '★ ' : ($value === 1 ? '◆ ' : '') }}{{ $label }}
                                    </p>
                                    <p class="text-[9px] mt-0.5 opacity-70" id="tier-price-{{ $value }}">
                                        Loading...
                                    </p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('priority')<p class="text-red-500 text-[11px] font-bold mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Schedule --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">
                                Start Date <span class="text-red-400">*</span>
                            </label>
                            <input type="date" name="starts_at" id="starts-at"
                                value="{{ old('starts_at', $advertisement->starts_at?->format('Y-m-d')) }}"
                                min="{{ now()->toDateString() }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-900 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-400 outline-none transition-all">
                            @error('starts_at')<p class="text-red-500 text-[11px] font-bold mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">
                                End Date <span class="text-red-400">*</span>
                            </label>
                            <input type="date" name="ends_at" id="ends-at"
                                value="{{ old('ends_at', $advertisement->ends_at?->format('Y-m-d')) }}"
                                min="{{ now()->toDateString() }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-900 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-400 outline-none transition-all">
                            @error('ends_at')<p class="text-red-500 text-[11px] font-bold mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Current banner + replace --}}
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Banner Image</label>
                        @if($advertisement->image)
                            <div class="mb-3">
                                <img src="{{ Storage::url($advertisement->image) }}" alt="Current banner"
                                    class="h-24 rounded-xl object-cover border border-slate-200">
                                <p class="text-[11px] text-slate-400 font-medium mt-1">Current banner. Upload a new file below to replace it.</p>
                            </div>
                        @endif
                        <input type="file" name="image" accept="image/*"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-black file:uppercase file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 transition-all">
                        @error('image')<p class="text-red-500 text-[11px] font-bold mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Linked car --}}
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">
                            Link to One of Your Listings
                            <span class="text-slate-400 font-medium normal-case tracking-normal">(optional)</span>
                        </label>
                        <select name="car_id"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-400 outline-none transition-all appearance-none">
                            <option value="">— No specific car —</option>
                            @foreach($cars as $car)
                                <option value="{{ $car->id }}"
                                    {{ old('car_id', $advertisement->car_id) == $car->id ? 'selected' : '' }}>
                                    {{ $car->displayName() }} — NRs {{ number_format($car->price) }}
                                </option>
                            @endforeach
                        </select>
                        @error('car_id')<p class="text-red-500 text-[11px] font-bold mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- External URL --}}
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">
                            External Link URL
                            <span class="text-slate-400 font-medium normal-case tracking-normal">(optional)</span>
                        </label>
                        <input type="url" name="link_url" value="{{ old('link_url', $advertisement->link_url) }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-900 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-400 outline-none transition-all"
                            placeholder="https://...">
                        @error('link_url')<p class="text-red-500 text-[11px] font-bold mt-1">{{ $message }}</p>@enderror
                    </div>

                </div>

                {{-- Submit --}}
                <div class="flex items-center gap-4 mt-5">
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-slate-900 text-white px-6 py-3 rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-purple-700 transition-all shadow-lg">
                        Resubmit for Review
                    </button>
                    <a href="{{ route('business.advertisements.index') }}"
                        class="text-[12px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-700 transition-colors">
                        Cancel
                    </a>
                </div>

            </form>
        </div>

        {{-- ── PRICING PANEL (right) ──────────────────────────────────────── --}}
        <div class="w-full xl:w-80 space-y-4 xl:sticky xl:top-6">

            {{-- Current status --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Current Status</p>
                <span class="inline-block text-xs font-black px-3 py-1 rounded-full
                    @if($advertisement->status === 'rejected') bg-red-100 text-red-700
                    @elseif($advertisement->status === 'pending_review') bg-amber-100 text-amber-700
                    @else bg-slate-100 text-slate-600 @endif">
                    {{ $advertisement->statusLabel() }}
                </span>
                <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                    @if($advertisement->status === 'rejected')
                        Fix the issues above and resubmit. It will go back into pending review.
                    @else
                        Saving changes will reset this ad to <strong class="text-slate-600">pending review</strong>.
                    @endif
                </p>
            </div>

            {{-- How it works --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">How it works</p>
                <ol class="space-y-3">
                    <li class="flex gap-3">
                        <span class="w-5 h-5 rounded-full bg-purple-100 text-purple-700 text-[10px] font-black flex items-center justify-center shrink-0 mt-0.5">1</span>
                        <p class="text-xs text-slate-600 leading-relaxed">Submit goes into <strong class="text-slate-800">pending review</strong>.</p>
                    </li>
                    <li class="flex gap-3">
                        <span class="w-5 h-5 rounded-full bg-purple-100 text-purple-700 text-[10px] font-black flex items-center justify-center shrink-0 mt-0.5">2</span>
                        <p class="text-xs text-slate-600 leading-relaxed">Email with <strong class="text-slate-800">final amount + payment instructions</strong>.</p>
                    </li>
                    <li class="flex gap-3">
                        <span class="w-5 h-5 rounded-full bg-purple-100 text-purple-700 text-[10px] font-black flex items-center justify-center shrink-0 mt-0.5">3</span>
                        <p class="text-xs text-slate-600 leading-relaxed">Pay via <strong class="text-slate-800">cash, eSewa, or bank transfer</strong>.</p>
                    </li>
                    <li class="flex gap-3">
                        <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-black flex items-center justify-center shrink-0 mt-0.5">4</span>
                        <p class="text-xs text-slate-600 leading-relaxed">Payment confirmed → ad goes <strong class="text-slate-800">live</strong>.</p>
                    </li>
                </ol>
            </div>

            {{-- Live cost estimate --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Estimated Cost</p>
                <div id="pricing-content">
                    <p class="text-xs text-slate-400 italic">Select placement, tier, and dates to see an estimate.</p>
                </div>
                <p class="text-[10px] text-slate-400 mt-4 leading-relaxed">
                    * Estimate only. Admin may adjust the final amount.
                </p>
            </div>

            <!-- {{-- Payment methods --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Payment Methods</p>
                <div class="space-y-2.5">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center text-[11px] font-black text-slate-600 shrink-0">₹</span>
                        <div>
                            <p class="text-xs font-black text-slate-700">Cash</p>
                            <p class="text-[11px] text-slate-400">Visit our office</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-lg bg-green-100 flex items-center justify-center text-[11px] font-black text-green-700 shrink-0">eS</span>
                        <div>
                            <p class="text-xs font-black text-slate-700">eSewa</p>
                            <p class="text-[11px] text-slate-400">9800000000</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-lg bg-blue-100 flex items-center justify-center text-[11px] font-black text-blue-700 shrink-0">B</span>
                        <div>
                            <p class="text-xs font-black text-slate-700">Bank Transfer</p>
                            <p class="text-[11px] text-slate-400">Acc: 12345678901 · XYZ Bank</p>
                        </div>
                    </div>
                </div>
            </div> -->

        </div>
    </div>

    <script>
        const verticalPlacements = ['news_sidebar', 'news_detail_sidebar'];
        const placementSelect    = document.getElementById('placement-select');
        const hintSize           = document.getElementById('image-hint-size');
        const startsInput        = document.getElementById('starts-at');
        const endsInput          = document.getElementById('ends-at');
        const pricingContent     = document.getElementById('pricing-content');

        function updateHint() {
            hintSize.textContent = verticalPlacements.includes(placementSelect.value)
                ? '600×800 px (vertical)'
                : '1200×400 px (horizontal)';
        }

        function updateTierLabels() {
            const placement = placementSelect.value;
            [0, 1, 2].forEach(tier => {
                const el   = document.getElementById('tier-price-' + tier);
                const rule = pricingRules[placement]?.[tier];
                if (!el) return;
                if (rule && rule.is_active) {
                    el.textContent = 'Rs ' + rule.price_per_day.toLocaleString() + ' / day · min ' + rule.min_days + ' days';
                } else {
                    el.textContent = 'Not available';
                }
            });
        }

        function updatePricing() {
            const placement = placementSelect.value;
            const priority  = document.querySelector('.priority-radio:checked')?.value;
            const starts    = startsInput.value;
            const ends      = endsInput.value;

            if (!placement || priority === undefined || !starts || !ends) {
                pricingContent.innerHTML = '<p class="text-xs text-slate-400 italic">Select placement, tier, and dates to see an estimate.</p>';
                return;
            }

            const rule = pricingRules[placement]?.[priority];

            if (!rule || !rule.is_active) {
                pricingContent.innerHTML = '<p class="text-xs text-amber-600 font-bold">No pricing available for this combination. Admin will quote manually.</p>';
                return;
            }

            const startDate    = new Date(starts);
            const endDate      = new Date(ends);

            if (endDate < startDate) {
                pricingContent.innerHTML = '<p class="text-xs text-red-500 font-bold">End date must be after start date.</p>';
                return;
            }

            const days         = Math.round((endDate - startDate) / 86400000) + 1;
            const billedDays   = Math.max(days, rule.min_days);
            const total        = billedDays * rule.price_per_day;
            const underMinimum = days < rule.min_days;

            pricingContent.innerHTML = `
                <div class="space-y-2 text-sm mb-3">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Rate</span>
                        <span class="font-bold text-slate-800">Rs ${rule.price_per_day.toLocaleString()} / day</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Duration</span>
                        <span class="font-bold text-slate-800">${days} day${days !== 1 ? 's' : ''}</span>
                    </div>
                    ${underMinimum ? `
                    <div class="flex justify-between">
                        <span class="text-slate-500">Billed days</span>
                        <span class="font-bold text-amber-600">${billedDays} days (minimum)</span>
                    </div>` : ''}
                    <div class="border-t border-slate-100 pt-2 flex justify-between items-baseline">
                        <span class="font-black text-slate-700">Estimated Total</span>
                        <span class="font-black text-purple-600 text-xl">Rs ${total.toLocaleString()}</span>
                    </div>
                </div>
                ${underMinimum ? `<p class="text-[11px] text-amber-600 bg-amber-50 rounded-lg px-3 py-2">Minimum booking is ${rule.min_days} days for this slot.</p>` : ''}
            `;
        }

        placementSelect.addEventListener('change', () => { updateHint(); updateTierLabels(); updatePricing(); });
        startsInput.addEventListener('change', updatePricing);
        endsInput.addEventListener('change', updatePricing);
        document.querySelectorAll('.priority-radio').forEach(r => r.addEventListener('change', updatePricing));

        updateHint();
        updateTierLabels();
        updatePricing();
    </script>

@endsection