@extends($layout)
@section('title', 'New Listing')
@section('page-title', 'New Listing')

@section('content')

    <a href="{{ route($prefix . '.cars.index') }}"
        class="inline-flex items-center gap-2 text-[11px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-700 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Listings
    </a>

    <form method="POST" action="{{ route($prefix . '.cars.store') }}" enctype="multipart/form-data"
          onsubmit="this.querySelector('[type=submit]').disabled=true; this.querySelector('[type=submit]').textContent='Publishing…'">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Left: Main details --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Basic info --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-5">Vehicle Details</p>
                    <div class="grid grid-cols-2 gap-4">

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Brand <span class="text-red-400">*</span></label>
                            <input type="text" name="brand" value="{{ old('brand') }}" placeholder="e.g. BYD, Tesla"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 placeholder:text-slate-300 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                            @error('brand')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Model <span class="text-red-400">*</span></label>
                            <input type="text" name="model" value="{{ old('model') }}" placeholder="e.g. Atto 3, Model 3"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 placeholder:text-slate-300 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                            @error('model')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Year <span class="text-red-400">*</span></label>
                            <input type="number" name="year" value="{{ old('year') }}" placeholder="e.g. 2023"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 placeholder:text-slate-300 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                            @error('year')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Variant</label>
                            <input type="text" name="variant" value="{{ old('variant') }}" placeholder="e.g. Extended Range"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 placeholder:text-slate-300 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                            @error('variant')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Drivetrain <span class="text-red-400">*</span></label>
                            <select name="drivetrain"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                                <option value="">Select drivetrain</option>
                                @foreach(['ev' => 'Electric (EV)', 'hybrid' => 'Hybrid', 'petrol' => 'Petrol', 'diesel' => 'Diesel'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('drivetrain') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('drivetrain')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Condition <span class="text-red-400">*</span></label>
                            <select name="condition"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                                <option value="">Select condition</option>
                                @foreach(['new' => 'New', 'used' => 'Used', 'certified' => 'Certified Pre-Owned'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('condition') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('condition')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                        </div>

                    </div>
                </div>

                {{-- Specs --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-5">Specifications</p>
                    <div class="grid grid-cols-2 gap-4">

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Mileage (km) <span class="text-red-400">*</span></label>
                            <input type="number" name="mileage" value="{{ old('mileage', 0) }}" min="0"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                            @error('mileage')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Color</label>
                            <input type="text" name="color" value="{{ old('color') }}" placeholder="e.g. Pearl White"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 placeholder:text-slate-300 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                            @error('color')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">EV Range (km)</label>
                            <input type="number" name="range_km" value="{{ old('range_km') }}" placeholder="e.g. 480"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 placeholder:text-slate-300 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                            @error('range_km')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Battery (kWh)</label>
                            <input type="number" name="battery_kwh" value="{{ old('battery_kwh') }}" placeholder="e.g. 60" step="any" min="0"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 placeholder:text-slate-300 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                            @error('battery_kwh')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                        </div>

                    </div>
                </div>

                {{-- Description --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-5">Description</p>
                    <textarea name="description" rows="4" placeholder="Describe the vehicle condition, history, features..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 placeholder:text-slate-300 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all resize-none">{{ old('description') }}</textarea>
                    @error('description')<p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- ── Photo Upload ───────────────────────────────────────────── --}}
                {{--
                    HOW THIS WORKS (no DataTransfer.files assignment — browsers block it):
                    Each accepted file gets its own hidden <input type="file" name="images[]">
                    appended to the <form>. On remove, that input is deleted from the DOM.
                    The form then submits the real file objects natively, no JS hacks needed.
                --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-6" id="photo-panel">
                    <div class="flex items-center justify-between mb-1">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Photos</p>
                        <span class="text-[10px] font-bold text-slate-400" id="photo-count-label">0 / 8 photos</span>
                    </div>
                    <p class="text-xs text-slate-400 font-medium mb-4">The first photo becomes the <span class="font-black text-slate-600">Cover</span>. JPG, PNG or WebP · max 3 MB each.</p>

                    {{-- Size-error banner (hidden by default) --}}
                    <div id="size-error" class="hidden mb-3 px-3 py-2 bg-red-50 border border-red-200 rounded-xl text-xs font-bold text-red-500"></div>

                    {{-- Preview grid --}}
                    <div class="grid grid-cols-4 gap-3 mb-3 hidden" id="preview-grid"></div>

                    {{-- Drop zone / trigger --}}
                    <div class="relative border-2 border-dashed border-slate-200 rounded-xl p-5 text-center hover:border-[#16a34a] transition-colors cursor-pointer" id="upload-area">
                        <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-xs font-bold text-slate-400">Click or drag photos here</p>
                        <p class="text-[10px] text-slate-300 mt-1">Up to 8 photos · max 3 MB each</p>
                        {{-- Invisible trigger input – only used to open the file picker --}}
                        <input type="file" multiple accept="image/*" id="photo-picker"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </div>
                    @error('images.*')<p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p>@enderror
                </div>

                <script>
                (function () {
                    const MAX        = 8;
                    const MB3        = 3 * 1024 * 1024;
                    const picker     = document.getElementById('photo-picker');
                    const previewGrid= document.getElementById('preview-grid');
                    const uploadArea = document.getElementById('upload-area');
                    const countLabel = document.getElementById('photo-count-label');
                    const sizeError  = document.getElementById('size-error');
                    // The real <form> — hidden inputs get appended here
                    const theForm    = picker.closest('form');

                    // uid → { hiddenInput, wrapEl }
                    const slots = {};
                    let   uidSeq = 0;

                    function slotCount()  { return Object.keys(slots).length; }

                    function updateLabel() {
                        countLabel.textContent = slotCount() + ' / ' + MAX + ' photos';
                    }

                    function syncCoverBadge() {
                        previewGrid.querySelectorAll('.preview-wrap').forEach((el, i) => {
                            el.querySelector('.cover-badge').classList.toggle('hidden', i !== 0);
                        });
                    }

                    function showSizeError(names) {
                        sizeError.textContent = 'Skipped (over 3 MB): ' + names.join(', ');
                        sizeError.classList.remove('hidden');
                        clearTimeout(sizeError._t);
                        sizeError._t = setTimeout(() => sizeError.classList.add('hidden'), 5000);
                    }

                    function addFiles(files) {
                        const tooBig = [];
                        Array.from(files).forEach(file => {
                            if (!file.type.match(/image\/(jpeg|png|webp)/)) return;
                            if (file.size > MB3) { tooBig.push(file.name); return; }
                            if (slotCount() >= MAX) return;

                            const uid = ++uidSeq;

                            // Hidden input that actually carries the file in the form POST
                            const hidden = document.createElement('input');
                            hidden.type  = 'file';
                            hidden.name  = 'images[]';
                            hidden.style.display = 'none';
                            // Attach the file via a fresh DataTransfer (setting on a NEW input works)
                            const dt = new DataTransfer();
                            dt.items.add(file);
                            hidden.files = dt.files;
                            theForm.appendChild(hidden);

                            // Preview card
                            const wrap = document.createElement('div');
                            wrap.className = 'relative group preview-wrap';
                            const reader = new FileReader();
                            reader.onload = ev => {
                                wrap.innerHTML = `
                                    <img src="${ev.target.result}" class="w-full h-24 object-cover rounded-xl">
                                    <span class="cover-badge hidden absolute top-1 left-1 text-[9px] font-black px-1.5 py-0.5 bg-[#4ade80] text-black rounded-lg uppercase tracking-wider">Cover</span>
                                    <button type="button" data-uid="${uid}"
                                        onclick="removePhoto(${uid})"
                                        class="absolute top-1 right-1 w-6 h-6 bg-white/80 hover:bg-red-500 rounded-lg flex items-center justify-center transition-all border border-slate-200 hover:border-red-500 group/btn">
                                        <svg class="w-3 h-3 text-slate-400 group-hover/btn:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>`;
                                syncCoverBadge();
                            };
                            reader.readAsDataURL(file);
                            previewGrid.appendChild(wrap);

                            slots[uid] = { hidden, wrap };
                        });

                        if (tooBig.length) showSizeError(tooBig);
                        updateLabel();
                        if (slotCount() > 0) previewGrid.classList.remove('hidden');
                        picker.value = ''; // reset picker so same file can trigger change again
                    }

                    window.removePhoto = function (uid) {
                        const slot = slots[uid];
                        if (!slot) return;
                        slot.hidden.remove();
                        slot.wrap.remove();
                        delete slots[uid];
                        syncCoverBadge();
                        updateLabel();
                        if (slotCount() === 0) previewGrid.classList.add('hidden');
                    };

                    picker.addEventListener('change', () => addFiles(picker.files));

                    uploadArea.addEventListener('dragover',  e => { e.preventDefault(); uploadArea.classList.add('border-[#16a34a]'); });
                    uploadArea.addEventListener('dragleave', ()  => uploadArea.classList.remove('border-[#16a34a]'));
                    uploadArea.addEventListener('drop', e => {
                        e.preventDefault();
                        uploadArea.classList.remove('border-[#16a34a]');
                        addFiles(e.dataTransfer.files);
                    });
                }());
                </script>

            </div>

            {{-- Right: Price, stock, location --}}
            <div class="space-y-5">

                {{-- Pricing --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-5">Pricing</p>
                    <div class="space-y-4">
                        <div class="space-y-2" id="price-field">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Price (NRs) <span class="text-red-400" id="price-required-star">*</span></label>
                            <input type="number" name="price" id="price-input" value="{{ old('price') }}" placeholder="e.g. 5500000"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 placeholder:text-slate-300 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all disabled:opacity-40 disabled:cursor-not-allowed">
                            <p class="text-xs text-slate-400 font-medium hidden" id="price-rent-note">Not required for rent-only listings.</p>
                            @error('price')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                        </div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="price_negotiable" value="1"
                                class="w-4 h-4 rounded border-slate-300 text-[#16a34a] focus:ring-[#4ade80]"
                                {{ old('price_negotiable') ? 'checked' : '' }}>
                            <span class="text-sm font-bold text-slate-600">Price is negotiable</span>
                        </label>
                    </div>
                </div>

                {{-- Stock --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Stock</p>
                    <p class="text-xs text-slate-400 font-medium mb-4">How many units of this vehicle do you have available to sell?</p>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Quantity <span class="text-red-400">*</span></label>
                        <input type="number" name="stock_quantity" value="{{ old('stock_quantity', 1) }}" min="1" max="1000"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                        @error('stock_quantity')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                    </div>
                    <!-- <div class="mt-3 p-3 bg-slate-50 rounded-xl">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Examples</p>
                        <p class="text-xs text-slate-500 font-medium">Private seller with 1 car → <span class="font-black text-slate-700">1</span></p>
                        <p class="text-xs text-slate-500 font-medium mt-1">Dealership with 5 units → <span class="font-black text-slate-700">5</span></p>
                    </div> -->
                </div>

                {{-- Pre-Order --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <div class="flex items-start justify-between gap-4 mb-1">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pre-Order</p>
                            <p class="text-xs text-slate-400 font-medium mt-1 leading-relaxed">
                                Car not in stock yet? Let buyers reserve it with a deposit.
                            </p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer shrink-0 mt-0.5">
                            <input type="checkbox" name="is_preorder" id="is_preorder" value="1"
                                class="sr-only peer" {{ old('is_preorder') ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:bg-[#16a34a]
                                after:content-[''] after:absolute after:top-0.5 after:left-[2px]
                                after:bg-white after:rounded-full after:h-5 after:w-5
                                after:transition-all peer-checked:after:translate-x-full"></div>
                        </label>
                    </div>
                
                    <div id="preorder-fields" class="{{ old('is_preorder') ? '' : 'hidden' }} space-y-4 mt-5 pt-5 border-t border-slate-100">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                Expected Arrival <span class="text-red-400">*</span>
                            </label>
                            <input type="date" name="expected_arrival_date"
                                value="{{ old('expected_arrival_date') }}"
                                min="{{ now()->addDay()->format('Y-m-d') }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                            @error('expected_arrival_date')
                                <p class="text-red-500 text-xs font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                Deposit Amount (NRs) <span class="text-red-400">*</span>
                            </label>
                            <input type="number" name="preorder_deposit"
                                value="{{ old('preorder_deposit') }}"
                                placeholder="e.g. 50000"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 placeholder:text-slate-300 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                            @error('preorder_deposit')
                                <p class="text-red-500 text-xs font-bold">{{ $message }}</p>
                            @enderror
                            <p class="text-[11px] text-slate-400 font-medium">Buyers pay this to secure their slot.</p>
                        </div>
                    </div>
                </div>
                
                <script>
                    document.getElementById('is_preorder').addEventListener('change', function () {
                        document.getElementById('preorder-fields').classList.toggle('hidden', !this.checked);
                    });
                </script>

                {{-- Location --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-5">Location</p>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">City <span class="text-red-400">*</span></label>
                        <input type="text" name="location" value="{{ old('location') }}" placeholder="e.g. Kathmandu"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 placeholder:text-slate-300 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                        @error('location')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Listing Type --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Listing Type <span class="text-red-400">*</span></p>
                    <p class="text-xs text-slate-400 font-medium mb-4">Choose how buyers can engage with this listing.</p>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach(['sale' => ['label' => 'For Sale', 'desc' => 'Buyers can purchase', 'icon' => '🏷️'], 'rent' => ['label' => 'For Rent', 'desc' => 'Buyers can rent', 'icon' => '📅'], 'both' => ['label' => 'Both', 'desc' => 'Sale & rental', 'icon' => '⚡']] as $val => $opt)
                        <label class="relative cursor-pointer">
                            <input type="radio" name="listing_type" value="{{ $val }}" class="sr-only peer"
                                {{ old('listing_type', 'sale') === $val ? 'checked' : '' }}>
                            <div class="border-2 border-slate-200 rounded-xl p-3 text-center transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:border-slate-300">
                                <span class="text-xl">{{ $opt['icon'] }}</span>
                                <p class="text-[11px] font-black text-slate-900 uppercase tracking-tight mt-1">{{ $opt['label'] }}</p>
                                <p class="text-[10px] text-slate-400 font-medium mt-0.5">{{ $opt['desc'] }}</p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('listing_type')<p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p>@enderror

                    {{-- Rental fields — revealed when rent or both is selected --}}
                    <div id="rental-fields" class="{{ in_array(old('listing_type'), ['rent','both']) ? '' : 'hidden' }} mt-5 pt-5 border-t border-slate-100 space-y-4">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Rental Settings</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Daily Rate (NRs) <span class="text-red-400">*</span></label>
                                <input type="number" name="rent_price_per_day" value="{{ old('rent_price_per_day') }}" min="1" placeholder="e.g. 3000"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 placeholder:text-slate-300 focus:outline-none focus:border-blue-400 focus:bg-white transition-all">
                                @error('rent_price_per_day')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Security Deposit (NRs)</label>
                                <input type="number" name="rent_deposit" value="{{ old('rent_deposit') }}" min="0" placeholder="e.g. 10000"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 placeholder:text-slate-300 focus:outline-none focus:border-blue-400 focus:bg-white transition-all">
                                @error('rent_deposit')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Min Days</label>
                                <input type="number" name="rent_min_days" value="{{ old('rent_min_days', 1) }}" min="1" placeholder="1"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 placeholder:text-slate-300 focus:outline-none focus:border-blue-400 focus:bg-white transition-all">
                                @error('rent_min_days')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Max Days <span class="normal-case font-medium text-slate-300">(blank = no limit)</span></label>
                                <input type="number" name="rent_max_days" value="{{ old('rent_max_days') }}" min="1" placeholder="e.g. 30"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 placeholder:text-slate-300 focus:outline-none focus:border-blue-400 focus:bg-white transition-all">
                                @error('rent_max_days')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                    <script>
                    (function(){
                        const radios      = document.querySelectorAll('input[name="listing_type"]');
                        const panel       = document.getElementById('rental-fields');
                        const priceInput  = document.getElementById('price-input');
                        const priceStar   = document.getElementById('price-required-star');
                        const priceNote   = document.getElementById('price-rent-note');
                        function toggle(){
                            const v = document.querySelector('input[name="listing_type"]:checked')?.value;
                            panel.classList.toggle('hidden', !['rent','both'].includes(v));
                            const rentOnly = v === 'rent';
                            priceInput.disabled = rentOnly;
                            if (rentOnly) { priceInput.value = ''; }
                            priceStar.classList.toggle('hidden', rentOnly);
                            priceNote.classList.toggle('hidden', !rentOnly);
                        }
                        radios.forEach(r => r.addEventListener('change', toggle));
                        toggle(); // run on page load in case old() restores rent
                    })();
                    </script>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full flex items-center justify-center gap-3 bg-slate-900 text-white py-4 rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all shadow-xl disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-slate-900">
                    Publish Listing →
                </button>
                <p class="text-center text-[10px] font-bold text-slate-300 uppercase tracking-widest">
                    Your listing goes live immediately after publishing.
                </p>

            </div>
        </div>
    </form>

@endsection