@extends($layout)
@section('title', 'Edit Listing')
@section('page-title', 'Edit Listing')

@section('content')

    <a href="{{ route($prefix . '.cars.index') }}"
        class="inline-flex items-center gap-2 text-[11px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-700 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Listings
    </a>

    <form method="POST" action="{{ route($prefix . '.cars.update', $car) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Left: Main details --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Basic info --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-5">Vehicle Details</p>
                    <div class="grid grid-cols-2 gap-4">

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Brand <span class="text-red-400">*</span></label>
                            <input type="text" name="brand" value="{{ old('brand', $car->brand) }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                            @error('brand')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Model <span class="text-red-400">*</span></label>
                            <input type="text" name="model" value="{{ old('model', $car->model) }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                            @error('model')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Year <span class="text-red-400">*</span></label>
                            <input type="number" name="year" value="{{ old('year', $car->year) }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                            @error('year')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Variant</label>
                            <input type="text" name="variant" value="{{ old('variant', $car->variant) }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                            @error('variant')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Drivetrain <span class="text-red-400">*</span></label>
                            <select name="drivetrain" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                                @foreach(['ev' => 'Electric (EV)', 'hybrid' => 'Hybrid', 'petrol' => 'Petrol', 'diesel' => 'Diesel'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('drivetrain', $car->drivetrain) === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('drivetrain')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Condition <span class="text-red-400">*</span></label>
                            <select name="condition" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                                @foreach(['new' => 'New', 'used' => 'Used', 'certified' => 'Certified Pre-Owned'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('condition', $car->condition) === $val ? 'selected' : '' }}>{{ $label }}</option>
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
                            <input type="number" name="mileage" value="{{ old('mileage', $car->mileage) }}" min="0"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                            @error('mileage')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Color</label>
                            <input type="text" name="color" value="{{ old('color', $car->color) }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                            @error('color')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">EV Range (km)</label>
                            <input type="number" name="range_km" value="{{ old('range_km', $car->range_km) }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                            @error('range_km')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Battery (kWh)</label>
                            <input type="number" name="battery_kwh" value="{{ old('battery_kwh', $car->battery_kwh) }}" step="any" min="0"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                            @error('battery_kwh')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-5">Description</p>
                    <textarea name="description" rows="4"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all resize-none">{{ old('description', $car->description) }}</textarea>
                    @error('description')<p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- ── Photo Manager ──────────────────────────────────────────── --}}
                {{--
                    New images use per-file hidden <input type="file" name="new_images[]"> appended
                    to the form — the only reliable cross-browser way to submit programmatic files.
                    Saved images are deleted instantly via AJAX (no save needed).
                --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-6" id="photo-manager">
                    <div class="flex items-center justify-between mb-1">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Photos</p>
                        <span class="text-[10px] font-bold text-slate-400" id="photo-count-label"></span>
                    </div>
                    <p class="text-xs text-slate-400 font-medium mb-4">The first photo is the <span class="font-black text-slate-600">Cover</span>. Click <svg class="w-3 h-3 inline-block text-slate-400 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg> on a saved photo to delete it instantly.</p>

                    {{-- Size-error banner --}}
                    <div id="size-error" class="hidden mb-3 px-3 py-2 bg-red-50 border border-red-200 rounded-xl text-xs font-bold text-red-500"></div>

                    {{-- Saved images --}}
                    <div class="grid grid-cols-4 gap-3 mb-3" id="saved-images-grid">
                        @foreach($car->images as $image)
                        <div class="relative group" id="img-wrap-{{ $image->id }}" data-image-id="{{ $image->id }}">
                            <img src="{{ $image->url() }}" class="w-full h-24 object-cover rounded-xl" alt="{{ $image->alt }}">
                            <span class="cover-badge absolute top-1 left-1 text-[9px] font-black px-1.5 py-0.5 bg-[#4ade80] text-black rounded-lg uppercase tracking-wider {{ $image->is_primary ? '' : 'hidden' }}">Cover</span>
                            <button type="button"
                                data-delete-url="{{ route($prefix . '.car-images.destroy', $image) }}"
                                data-img-id="{{ $image->id }}"
                                onclick="deleteImage(this)"
                                class="delete-btn absolute top-1 right-1 w-6 h-6 bg-white/80 hover:bg-red-500 rounded-lg flex items-center justify-center transition-all border border-slate-200 hover:border-red-500 group/btn">
                                <svg class="w-3 h-3 text-slate-400 group-hover/btn:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                        @endforeach
                    </div>

                    {{-- Pending-upload previews (before saving) --}}
                    <div class="grid grid-cols-4 gap-3 mb-3 hidden" id="new-previews-grid"></div>

                    {{-- Drop zone --}}
                    <div class="relative border-2 border-dashed border-slate-200 rounded-xl p-5 text-center hover:border-[#16a34a] transition-colors cursor-pointer" id="upload-area">
                        <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-xs font-bold text-slate-400">Click or drag photos here</p>
                        <p class="text-[10px] text-slate-300 mt-1">JPG, PNG or WebP · max 3 MB each · up to 8 total</p>
                        <input type="file" multiple accept="image/*" id="photo-picker"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </div>
                    @error('new_images.*')<p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p>@enderror
                </div>

                <script>
                (function () {
                    const MAX_TOTAL  = 8;
                    const MB3        = 3 * 1024 * 1024;
                    const csrfToken  = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

                    const picker     = document.getElementById('photo-picker');
                    const previewGrid= document.getElementById('new-previews-grid');
                    const uploadArea = document.getElementById('upload-area');
                    const sizeError  = document.getElementById('size-error');
                    const theForm    = picker.closest('form');

                    // uid → { hiddenInput, wrapEl }
                    const newSlots = {};
                    let   uidSeq   = 0;

                    // ── Counts ────────────────────────────────────────────────────
                    function savedCount()   { return document.querySelectorAll('#saved-images-grid [data-image-id]').length; }
                    function pendingCount() { return Object.keys(newSlots).length; }

                    function updateCountLabel() {
                        const el = document.getElementById('photo-count-label');
                        if (el) el.textContent = (savedCount() + pendingCount()) + ' / ' + MAX_TOTAL + ' photos';
                    }

                    // ── Cover badge helpers ───────────────────────────────────────
                    function refreshSavedCoverBadge(newPrimaryId) {
                        document.querySelectorAll('#saved-images-grid [data-image-id]').forEach(el => {
                            const badge = el.querySelector('.cover-badge');
                            if (!badge) return;
                            badge.classList.toggle('hidden', parseInt(el.dataset.imageId) !== newPrimaryId);
                        });
                        syncNewPreviewCoverBadge();
                    }

                    function syncNewPreviewCoverBadge() {
                        previewGrid.querySelectorAll('.preview-wrap').forEach((el, i) => {
                            const badge = el.querySelector('.new-cover-badge');
                            if (!badge) return;
                            badge.classList.toggle('hidden', !(i === 0 && savedCount() === 0));
                        });
                    }

                    function showSizeError(names) {
                        sizeError.textContent = 'Skipped (over 3 MB): ' + names.join(', ');
                        sizeError.classList.remove('hidden');
                        clearTimeout(sizeError._t);
                        sizeError._t = setTimeout(() => sizeError.classList.add('hidden'), 5000);
                    }

                    // ── Delete saved image via AJAX ───────────────────────────────
                    window.deleteImage = function (btn) {
                        if (btn.disabled) return;
                        btn.disabled = true;
                        btn.innerHTML = '<svg class="w-3 h-3 text-slate-400 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>';

                        fetch(btn.dataset.deleteUrl, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.ok) {
                                document.getElementById('img-wrap-' + btn.dataset.imgId)?.remove();
                                refreshSavedCoverBadge(data.new_primary_id);
                                updateCountLabel();
                            } else {
                                btn.disabled = false;
                            }
                        })
                        .catch(() => { btn.disabled = false; });
                    };

                    // ── Add new images ────────────────────────────────────────────
                    function addFiles(files) {
                        const tooBig = [];
                        Array.from(files).forEach(file => {
                            if (!file.type.match(/image\/(jpeg|png|webp)/)) return;
                            if (file.size > MB3) { tooBig.push(file.name); return; }
                            if (savedCount() + pendingCount() >= MAX_TOTAL) return;

                            const uid = ++uidSeq;

                            // Per-file hidden input — the reliable way to submit files programmatically
                            const hidden = document.createElement('input');
                            hidden.type  = 'file';
                            hidden.name  = 'new_images[]';
                            hidden.style.display = 'none';
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
                                    <span class="new-cover-badge hidden absolute top-1 left-1 text-[9px] font-black px-1.5 py-0.5 bg-[#4ade80] text-black rounded-lg uppercase tracking-wider">Cover</span>
                                    <button type="button" onclick="removePending(${uid})"
                                        class="absolute top-1 right-1 w-6 h-6 bg-white/80 hover:bg-red-500 rounded-lg flex items-center justify-center transition-all border border-slate-200 hover:border-red-500 group/btn">
                                        <svg class="w-3 h-3 text-slate-400 group-hover/btn:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>`;
                                syncNewPreviewCoverBadge();
                            };
                            reader.readAsDataURL(file);
                            previewGrid.appendChild(wrap);

                            newSlots[uid] = { hidden, wrap };
                        });

                        if (tooBig.length) showSizeError(tooBig);
                        updateCountLabel();
                        if (pendingCount() > 0) previewGrid.classList.remove('hidden');
                        picker.value = '';
                    }

                    window.removePending = function (uid) {
                        const slot = newSlots[uid];
                        if (!slot) return;
                        slot.hidden.remove();
                        slot.wrap.remove();
                        delete newSlots[uid];
                        syncNewPreviewCoverBadge();
                        updateCountLabel();
                        if (pendingCount() === 0) previewGrid.classList.add('hidden');
                    };

                    picker.addEventListener('change', () => addFiles(picker.files));

                    uploadArea.addEventListener('dragover',  e => { e.preventDefault(); uploadArea.classList.add('border-[#16a34a]'); });
                    uploadArea.addEventListener('dragleave', ()  => uploadArea.classList.remove('border-[#16a34a]'));
                    uploadArea.addEventListener('drop', e => {
                        e.preventDefault();
                        uploadArea.classList.remove('border-[#16a34a]');
                        addFiles(e.dataTransfer.files);
                    });

                    updateCountLabel();
                }());
                </script>

            </div>

            {{-- Right: Price, stock, location, status --}}
            <div class="space-y-5">

                {{-- Pricing --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-5">Pricing</p>
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Price (NRs) <span class="text-red-400">*</span></label>
                            <input type="number" name="price" value="{{ old('price', $car->price) }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                            @error('price')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                        </div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="price_negotiable" value="1"
                                class="w-4 h-4 rounded border-slate-300 text-[#16a34a] focus:ring-[#4ade80]"
                                {{ old('price_negotiable', $car->price_negotiable) ? 'checked' : '' }}>
                            <span class="text-sm font-bold text-slate-600">Price is negotiable</span>
                        </label>
                    </div>
                </div>

                {{-- Stock --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Stock</p>
                    <p class="text-xs text-slate-400 font-medium mb-4">
                        Current stock: <span class="font-black text-slate-700">{{ $car->stock_quantity }} unit(s)</span>
                    </p>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Update Quantity <span class="text-red-400">*</span></label>
                        <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $car->stock_quantity) }}" min="0" max="1000"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                        @error('stock_quantity')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                    </div>
                    @if($car->status === 'sold')
                    <div class="mt-3 p-3 bg-yellow-50 border border-yellow-100 rounded-xl">
                        <p class="text-[10px] font-black text-yellow-700 uppercase tracking-widest">Sold Out</p>
                        <p class="text-xs text-yellow-600 font-medium mt-0.5">Set quantity above 0 to relist this car.</p>
                    </div>
                    @endif
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
                        <input type="text" name="location" value="{{ old('location', $car->location) }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                        @error('location')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Status --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-5">Listing Status</p>
                    <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                        @foreach(['available' => 'Available', 'reserved' => 'Reserved', 'inactive' => 'Inactive (Hidden)'] as $val => $label)
                            <option value="{{ $val }}" {{ old('status', $car->status) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="text-[10px] text-slate-400 font-medium mt-2">Inactive hides the listing from the marketplace.</p>
                    @error('status')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                </div>

                {{-- Listing Type --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Listing Type <span class="text-red-400">*</span></p>
                    <p class="text-xs text-slate-400 font-medium mb-4">Choose how buyers can engage with this listing.</p>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach(['sale' => ['label' => 'For Sale', 'desc' => 'Buyers can purchase', 'icon' => '🏷️'], 'rent' => ['label' => 'For Rent', 'desc' => 'Buyers can rent', 'icon' => '📅'], 'both' => ['label' => 'Both', 'desc' => 'Sale & rental', 'icon' => '⚡']] as $val => $opt)
                        <label class="relative cursor-pointer">
                            <input type="radio" name="listing_type" value="{{ $val }}" class="sr-only peer"
                                {{ old('listing_type', $car->listing_type ?? 'sale') === $val ? 'checked' : '' }}>
                            <div class="border-2 border-slate-200 rounded-xl p-3 text-center transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:border-slate-300">
                                <span class="text-xl">{{ $opt['icon'] }}</span>
                                <p class="text-[11px] font-black text-slate-900 uppercase tracking-tight mt-1">{{ $opt['label'] }}</p>
                                <p class="text-[10px] text-slate-400 font-medium mt-0.5">{{ $opt['desc'] }}</p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('listing_type')<p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p>@enderror

                    {{-- Rental fields --}}
                    @php $showRental = in_array(old('listing_type', $car->listing_type), ['rent','both']); @endphp
                    <div id="rental-fields" class="{{ $showRental ? '' : 'hidden' }} mt-5 pt-5 border-t border-slate-100 space-y-4">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Rental Settings</p>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Daily Rate (NRs) <span class="text-red-400">*</span></label>
                                <input type="number" name="rent_price_per_day" value="{{ old('rent_price_per_day', $car->rent_price_per_day) }}" min="1" placeholder="e.g. 3000"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 placeholder:text-slate-300 focus:outline-none focus:border-blue-400 focus:bg-white transition-all">
                                @error('rent_price_per_day')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Security Deposit (NRs)</label>
                                <input type="number" name="rent_deposit" value="{{ old('rent_deposit', $car->rent_deposit) }}" min="0" placeholder="e.g. 10000"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 placeholder:text-slate-300 focus:outline-none focus:border-blue-400 focus:bg-white transition-all">
                                @error('rent_deposit')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Min Days</label>
                                <input type="number" name="rent_min_days" value="{{ old('rent_min_days', $car->rent_min_days ?? 1) }}" min="1" placeholder="1"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 placeholder:text-slate-300 focus:outline-none focus:border-blue-400 focus:bg-white transition-all">
                                @error('rent_min_days')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Max Days <span class="normal-case font-medium text-slate-300">(blank = no limit)</span></label>
                                <input type="number" name="rent_max_days" value="{{ old('rent_max_days', $car->rent_max_days) }}" min="1" placeholder="e.g. 30"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 placeholder:text-slate-300 focus:outline-none focus:border-blue-400 focus:bg-white transition-all">
                                @error('rent_max_days')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                    <script>
                    (function(){
                        const radios = document.querySelectorAll('input[name="listing_type"]');
                        const panel  = document.getElementById('rental-fields');
                        function toggle(){ const v = document.querySelector('input[name="listing_type"]:checked')?.value; panel.classList.toggle('hidden', !['rent','both'].includes(v)); }
                        radios.forEach(r => r.addEventListener('change', toggle));
                    })();
                    </script>
                </div>

                <button type="submit"
                    class="w-full flex items-center justify-center gap-3 bg-slate-900 text-white py-4 rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all shadow-xl">
                    Save Changes →
                </button>

            </div>
        </div>
    </form>

@endsection