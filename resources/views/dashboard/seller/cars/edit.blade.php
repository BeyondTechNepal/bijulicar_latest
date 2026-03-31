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

                {{-- Existing images --}}
                @if($car->images->isNotEmpty())
                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Current Photos</p>
                    <p class="text-xs text-slate-400 font-medium mb-4">Check the box on any photo to remove it.</p>
                    <div class="grid grid-cols-4 gap-3">
                        @foreach($car->images as $image)
                        <div class="relative group">
                            <img src="{{ $image->url() }}" class="w-full h-24 object-cover rounded-xl" alt="{{ $image->alt }}">
                            @if($image->is_primary)
                                <span class="absolute top-1 left-1 text-[9px] font-black px-1.5 py-0.5 bg-[#4ade80] text-black rounded-lg uppercase tracking-wider">Cover</span>
                            @endif
                            <label class="absolute top-1 right-1 cursor-pointer">
                                <input type="checkbox" name="remove_images[]" value="{{ $image->id }}" class="sr-only peer">
                                <div class="w-6 h-6 bg-white/80 peer-checked:bg-red-500 rounded-lg flex items-center justify-center transition-all border border-slate-200 peer-checked:border-red-500">
                                    <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                    <svg class="w-3 h-3 text-slate-400 block peer-checked:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </div>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Add new images --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-6">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Add More Photos</p>
                    <p class="text-xs text-slate-400 font-medium mb-4">JPG, PNG or WebP, max 3MB each.</p>
                    <input type="file" name="new_images[]" multiple accept="image/*"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-600 focus:outline-none focus:border-[#16a34a] transition-all file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-black file:bg-slate-900 file:text-white hover:file:bg-[#16a34a] file:transition-all file:uppercase">
                    @error('new_images.*')<p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p>@enderror
                </div>

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

                <button type="submit"
                    class="w-full flex items-center justify-center gap-3 bg-slate-900 text-white py-4 rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all shadow-xl">
                    Save Changes →
                </button>

            </div>
        </div>
    </form>

@endsection