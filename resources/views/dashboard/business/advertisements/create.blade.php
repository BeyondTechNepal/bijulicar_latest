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

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('business.advertisements.store') }}" enctype="multipart/form-data">
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
                    @error('title')<p class="text-red-500 text-[11px] font-bold mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-900 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-400 outline-none transition-all resize-none"
                        placeholder="Short promotional copy shown with the banner...">{{ old('description') }}</textarea>
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
                            <option value="{{ $value }}" {{ old('placement') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    {{-- Dynamic image size hint --}}
                    <p id="image-hint" class="text-[11px] text-slate-400 font-medium mt-1.5">
                        Recommended image: <span id="image-hint-size" class="font-bold text-slate-600">1200×400 px</span>
                    </p>
                    @error('placement')<p class="text-red-500 text-[11px] font-bold mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Priority --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">
                        Priority Tier <span class="text-red-400">*</span>
                    </label>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach($priorities as $value => $label)
                        <label class="relative cursor-pointer">
                            <input type="radio" name="priority" value="{{ $value }}"
                                {{ old('priority', '0') == $value ? 'checked' : '' }}
                                class="peer sr-only">
                            <div class="w-full text-center px-3 py-3 rounded-xl border-2 transition-all
                                peer-checked:border-slate-900 peer-checked:bg-slate-900 peer-checked:text-white
                                border-slate-200 bg-slate-50 hover:border-slate-300
                                {{ $value === 2 ? 'peer-checked:border-amber-500 peer-checked:bg-amber-500' : ($value === 1 ? 'peer-checked:border-purple-600 peer-checked:bg-purple-600' : '') }}">
                                <p class="text-[11px] font-black uppercase tracking-wider">
                                    {{ $value === 2 ? '★ ' : ($value === 1 ? '◆ ' : '') }}{{ $label }}
                                </p>
                                <p class="text-[9px] mt-0.5 opacity-70">
                                    {{ $value === 2 ? 'Shown first always' : ($value === 1 ? 'Shown after Premium' : 'Shown after Featured') }}
                                </p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('priority')<p class="text-red-500 text-[11px] font-bold mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Banner image --}}
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Banner Image</label>
                    <input type="file" name="image" accept="image/*"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-black file:uppercase file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 transition-all">
                    <p class="text-[11px] text-slate-400 font-medium mt-1">JPG, PNG or WebP — max 2 MB.</p>
                    @error('image')<p class="text-red-500 text-[11px] font-bold mt-1">{{ $message }}</p>@enderror
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
                        @foreach($cars as $car)
                            <option value="{{ $car->id }}" {{ old('car_id') == $car->id ? 'selected' : '' }}>
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
                    <input type="url" name="link_url" value="{{ old('link_url') }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-900 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-400 outline-none transition-all"
                        placeholder="https://...">
                    @error('link_url')<p class="text-red-500 text-[11px] font-bold mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Schedule --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">
                            Start Date <span class="text-slate-400 font-medium normal-case tracking-normal">(optional)</span>
                        </label>
                        <input type="date" name="starts_at" value="{{ old('starts_at') }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-900 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-400 outline-none transition-all">
                        @error('starts_at')<p class="text-red-500 text-[11px] font-bold mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">
                            End Date <span class="text-slate-400 font-medium normal-case tracking-normal">(optional)</span>
                        </label>
                        <input type="date" name="ends_at" value="{{ old('ends_at') }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-900 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-400 outline-none transition-all">
                        @error('ends_at')<p class="text-red-500 text-[11px] font-bold mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Active toggle --}}
                <div class="flex items-center gap-3 pt-1">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                        {{ old('is_active', '1') ? 'checked' : '' }}
                        class="w-4 h-4 rounded accent-purple-600">
                    <label for="is_active" class="text-sm font-bold text-slate-700 cursor-pointer">
                        Active — show this ad on the site immediately
                    </label>
                </div>

            </div>

            {{-- Submit --}}
            <div class="flex items-center gap-4 mt-5">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-slate-900 text-white px-6 py-3 rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-purple-700 transition-all shadow-lg">
                    Create Advertisement
                </button>
                <a href="{{ route('business.advertisements.index') }}"
                    class="text-[12px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-700 transition-colors">
                    Cancel
                </a>
            </div>

        </form>
    </div>

    {{-- Dynamic image size hint based on placement --}}
    <script>
        const verticalPlacements = ['news_sidebar', 'news_detail_sidebar', 'car_detail_horizontal'];
        const select = document.getElementById('placement-select');
        const hintSize = document.getElementById('image-hint-size');

        function updateHint() {
            const isVertical = verticalPlacements.includes(select.value);
            hintSize.textContent = isVertical ? '600×800 px (vertical)' : '1200×400 px (horizontal)';
        }

        select.addEventListener('change', updateHint);
        updateHint(); // run on load in case old() restores a value
    </script>

@endsection