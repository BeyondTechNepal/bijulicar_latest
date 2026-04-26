@extends('dashboard.business.layout')
@section('title', 'New Advertisement')
@section('page-title', 'New Advertisement')

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

    <div class="flex flex-col xl:flex-row gap-6 items-start">

        {{-- ── FORM (left) ───────────────────────────────────────────────── --}}
        <div class="flex-1 max-w-2xl w-full">
            <form method="POST" action="{{ route('business.advertisements.store') }}" enctype="multipart/form-data"
                id="ad-form">
                @csrf

                <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-5">

                    {{-- Title --}}
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">
                            Title <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-400 outline-none transition-all"
                            placeholder="e.g. Summer EV Sale — 0% EMI">
                        @error('title')
                            <p class="text-red-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Description</label>
                        <textarea name="description" rows="3"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-900 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-400 outline-none transition-all resize-none"
                            placeholder="Short promotional copy shown with the banner...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Placement --}}
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">
                            Placement <span class="text-red-400">*</span>
                        </label>
                        <select name="placement" id="placement-select" onchange="showPlacementPreview()"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-900">

                            @foreach ($placements as $value => $placement)
                                <option value="{{ $value }}" data-image="{{ $placement['image'] }}"
                                    data-video="{{ $placement['video'] }}"
                                    {{ old('placement') === $value ? 'selected' : '' }}>

                                    {{ $placement['label'] }}
                                </option>
                            @endforeach

                        </select>

                        <div id="placement-preview" class="mt-3 hidden">
                            <img id="preview-image" class="rounded-lg shadow-md max-w-full hidden" />
                            <iframe 
        id="preview-video"
        class="rounded-lg shadow-md max-w-full hidden"
        width="100%"
        height="300"
        frameborder="0"
        allow="autoplay; fullscreen; picture-in-picture"
        allowfullscreen>
    </iframe>
                        </div>

                        @error('placement')
                            <p class="text-red-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Priority --}}
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">
                            Priority Tier <span class="text-red-400">*</span>
                        </label>
                        <div class="grid grid-cols-3 gap-3" id="priority-group">
                            @foreach ($priorities as $value => $label)
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="priority" value="{{ $value }}"
                                        {{ old('priority', '0') == $value ? 'checked' : '' }}
                                        class="peer sr-only priority-radio">
                                    <div
                                        class="w-full text-center px-3 py-3 rounded-xl border-2 transition-all
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
                        @error('priority')
                            <p class="text-red-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Schedule --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">
                                Start Date <span class="text-red-400">*</span>
                            </label>
                            <input type="date" name="starts_at" id="starts-at" value="{{ old('starts_at') }}"
                                min="{{ now()->toDateString() }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-900 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-400 outline-none transition-all">
                            @error('starts_at')
                                <p class="text-red-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">
                                End Date <span class="text-red-400">*</span>
                            </label>
                            <input type="date" name="ends_at" id="ends-at" value="{{ old('ends_at') }}"
                                min="{{ now()->toDateString() }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-900 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-400 outline-none transition-all">
                            @error('ends_at')
                                <p class="text-red-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Banner image --}}
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Banner
                            Image</label>
                        <input type="file" name="image" accept="image/*" id="image-input"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-black file:uppercase file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 transition-all">
                        <p class="text-[11px] text-slate-400 font-medium mt-1">JPG, PNG or WebP — max 2 MB.</p>
                        <p id="image-size-error" class="text-red-500 text-[11px] font-bold mt-1 hidden">
                            Image is too large. Please use an image under 2 MB.
                        </p>
                        @error('image')
                            <p class="text-red-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Linked car (optional) --}}
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">
                            Link to One of Your Listings
                            <span class="text-slate-400 font-medium normal-case tracking-normal">(optional)</span>
                        </label>
                        <select name="car_id"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-400 outline-none transition-all appearance-none">
                            <option value="">— No specific car —</option>
                            @foreach ($cars as $car)
                                <option value="{{ $car->id }}" {{ old('car_id') == $car->id ? 'selected' : '' }}>
                                    {{ $car->displayName() }} — NRs {{ number_format($car->price) }}
                                </option>
                            @endforeach
                        </select>
                        @error('car_id')
                            <p class="text-red-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- External URL --}}
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">
                            External Link URL
                            <span class="text-slate-400 font-medium normal-case tracking-normal">(optional)</span>
                        </label>
                        <input type="url" name="link_url" value="{{ old('link_url') }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-900 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-400 outline-none transition-all"
                            placeholder="https://...">
                        @error('link_url')
                            <p class="text-red-500 text-[11px] font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Submit --}}
                <div class="flex items-center gap-4 mt-5">
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-slate-900 text-white px-6 py-3 rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-purple-700 transition-all shadow-lg">
                        Submit for Review
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

            {{-- How it works --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">How it works</p>
                <ol class="space-y-3">
                    <li class="flex gap-3">
                        <span
                            class="w-5 h-5 rounded-full bg-purple-100 text-purple-700 text-[10px] font-black flex items-center justify-center shrink-0 mt-0.5">1</span>
                        <p class="text-xs text-slate-600 leading-relaxed">You submit your ad. It goes into <strong
                                class="text-slate-800">pending review</strong>.</p>
                    </li>
                    <li class="flex gap-3">
                        <span
                            class="w-5 h-5 rounded-full bg-purple-100 text-purple-700 text-[10px] font-black flex items-center justify-center shrink-0 mt-0.5">2</span>
                        <p class="text-xs text-slate-600 leading-relaxed">Our team reviews the image and placement. You'll
                            get an email with the <strong class="text-slate-800">final amount and payment
                                instructions</strong>.</p>
                    </li>
                    <li class="flex gap-3">
                        <span
                            class="w-5 h-5 rounded-full bg-purple-100 text-purple-700 text-[10px] font-black flex items-center justify-center shrink-0 mt-0.5">3</span>
                        <p class="text-xs text-slate-600 leading-relaxed">Pay via <strong class="text-slate-800">cash,
                                eSewa, or bank transfer</strong>.</p>
                    </li>
                    <li class="flex gap-3">
                        <span
                            class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-black flex items-center justify-center shrink-0 mt-0.5">4</span>
                        <p class="text-xs text-slate-600 leading-relaxed">Once payment is confirmed, your ad goes <strong
                                class="text-slate-800">live automatically</strong>.</p>
                    </li>
                </ol>
            </div>

            {{-- Live cost estimate --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5" id="pricing-panel">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Estimated Cost</p>

                <div id="pricing-content">
                    <p class="text-xs text-slate-400 italic">Select placement, tier, and dates to see an estimate.</p>
                </div>

                <p class="text-[10px] text-slate-400 mt-4 leading-relaxed">
                    * This is an estimate only. The admin may adjust the final amount before sending the invoice.
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
        const placementSelect = document.getElementById('placement-select');
        const startsInput = document.getElementById('starts-at');
        const endsInput = document.getElementById('ends-at');
        const pricingContent = document.getElementById('pricing-content');


        // ── Tier price labels ────────────────────────────────────────────
        function updateTierLabels() {
            const placement = placementSelect.value;
            [0, 1, 2].forEach(tier => {
                const el = document.getElementById('tier-price-' + tier);
                const rule = pricingRules[placement]?.[tier];
                if (!el) return;
                if (rule && rule.is_active) {
                    el.textContent = 'Rs ' + rule.price_per_day.toLocaleString() + ' / day · min ' + rule.min_days +
                        ' days';
                } else {
                    el.textContent = 'Not available';
                }
            });
        }

        // ── Live cost estimate ───────────────────────────────────────────
        function updatePricing() {
            const placement = placementSelect.value;
            const priority = document.querySelector('.priority-radio:checked')?.value;
            const starts = startsInput.value;
            const ends = endsInput.value;

            if (!placement || priority === undefined || !starts || !ends) {
                pricingContent.innerHTML =
                    '<p class="text-xs text-slate-400 italic">Select placement, tier, and dates to see an estimate.</p>';
                return;
            }

            const rule = pricingRules[placement]?.[priority];

            if (!rule || !rule.is_active) {
                pricingContent.innerHTML =
                    '<p class="text-xs text-amber-600 font-bold">No pricing available for this placement + tier. Admin will quote manually.</p>';
                return;
            }

            const startDate = new Date(starts);
            const endDate = new Date(ends);

            if (endDate < startDate) {
                pricingContent.innerHTML =
                    '<p class="text-xs text-red-500 font-bold">End date must be after start date.</p>';
                return;
            }

            const days = Math.round((endDate - startDate) / 86400000) + 1;
            const billedDays = Math.max(days, rule.min_days);
            const total = billedDays * rule.price_per_day;
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

        // ── Bind all events ──────────────────────────────────────────────
        placementSelect.addEventListener('change', () => {
            updateTierLabels();
            updatePricing();
        });
        startsInput.addEventListener('change', updatePricing);
        endsInput.addEventListener('change', updatePricing);
        document.querySelectorAll('.priority-radio').forEach(r => r.addEventListener('change', updatePricing));

        // Run on load
        updateTierLabels();
        updatePricing();
    </script>

    {{-- jscript for picture or video to be shown as a hint for the advertisement --}}
    <script>
        function showPlacementPreview() {
    let select = document.getElementById("placement-select");
    let selected = select.options[select.selectedIndex];

    let imageSrc = selected.dataset.image;
    let videoSrc = selected.dataset.video;

    let previewBox = document.getElementById("placement-preview");
    let image = document.getElementById("preview-image");
    let video = document.getElementById("preview-video");

    // reset
    image.classList.add("hidden");
    video.classList.add("hidden");
    video.src = ""; // important to stop previous video

    if (imageSrc) {
        image.src = imageSrc;
        image.classList.remove("hidden");
        previewBox.classList.remove("hidden");
    } 
    else if (videoSrc) {
        video.src = videoSrc;
        video.classList.remove("hidden");
        previewBox.classList.remove("hidden");
    } 
    else {
        previewBox.classList.add("hidden");
    }
}
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            showPlacementPreview(); 
        });
    </script>

    <script>
        // ── Instant client-side image size check ──────────────────────────────
        // Validates the file the moment the user picks it — before any form
        // submission — so they don't have to wait for a server round-trip to
        // discover the file is too large.
        (function () {
            const MAX_BYTES = 2 * 1024 * 1024; // 2 MB — matches server-side max:2048
            const input    = document.getElementById('image-input');
            const error    = document.getElementById('image-size-error');
            const form     = input ? input.closest('form') : null;

            if (!input || !error || !form) return;

            input.addEventListener('change', function () {
                const file = this.files[0];
                if (file && file.size > MAX_BYTES) {
                    error.classList.remove('hidden');
                    // Reset the input so the oversized file is not submitted
                    this.value = '';
                } else {
                    error.classList.add('hidden');
                }
            });

            // Belt-and-suspenders: also block submission in case JS ran out of order
            form.addEventListener('submit', function (e) {
                const file = input.files[0];
                if (file && file.size > MAX_BYTES) {
                    e.preventDefault();
                    error.classList.remove('hidden');
                    input.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        })();
    </script>
@endsection