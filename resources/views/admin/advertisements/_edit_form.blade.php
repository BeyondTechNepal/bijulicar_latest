{{--
    Partial: admin/advertisements/_edit_form.blade.php
    Variables expected:
      $ad               — Advertisement model instance
      $placementOptions — ['home' => 'Home Page ...', ...] (flat key => label)
      $priorityOptions  — [0 => 'Standard', 1 => 'Featured', 2 => 'Premium']

    Used in: admin.advertisements.index (both Published and Approved sections)
--}}

<form method="POST"
      action="{{ route('admin.advertisements.force-update', $ad) }}"
      enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-4">Edit Advertisement</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

        {{-- Title --}}
        <div class="md:col-span-2">
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">
                Title <span class="text-red-400">*</span>
            </label>
            <input type="text" name="title" required
                value="{{ old('title', $ad->title) }}"
                class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-400 transition-all">
        </div>

        {{-- Description --}}
        <div class="md:col-span-2">
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Description</label>
            <textarea name="description" rows="2"
                class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-400 transition-all resize-none">{{ old('description', $ad->description) }}</textarea>
        </div>

        {{-- Placement --}}
        <div>
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">
                Placement <span class="text-red-400">*</span>
            </label>
            <select name="placement" required
                class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-400 transition-all">
                @foreach ($placementOptions as $value => $label)
                    <option value="{{ $value }}" {{ old('placement', $ad->placement) === $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Priority --}}
        <div>
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">
                Priority Tier <span class="text-red-400">*</span>
            </label>
            <select name="priority" required
                class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-400 transition-all">
                @foreach ($priorityOptions as $value => $label)
                    <option value="{{ $value }}" {{ old('priority', $ad->priority) == $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Start Date --}}
        <div>
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">
                Start Date <span class="text-red-400">*</span>
            </label>
            <input type="date" name="starts_at" required
                value="{{ old('starts_at', $ad->starts_at?->toDateString()) }}"
                class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-400 transition-all">
        </div>

        {{-- End Date --}}
        <div>
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">
                End Date <span class="text-red-400">*</span>
            </label>
            <input type="date" name="ends_at" required
                value="{{ old('ends_at', $ad->ends_at?->toDateString()) }}"
                class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-400 transition-all">
        </div>

        {{-- External Link URL --}}
        <div>
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">External Link URL</label>
            <input type="url" name="link_url"
                value="{{ old('link_url', $ad->link_url) }}"
                placeholder="https://..."
                class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-400 transition-all">
        </div>

        {{-- Car ID (linked listing) --}}
        <div>
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Linked Car ID</label>
            <input type="number" name="car_id" min="1"
                value="{{ old('car_id', $ad->car_id) }}"
                placeholder="Leave blank to unlink"
                class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-400 transition-all">
            @if ($ad->car)
                <p class="text-[11px] text-gray-400 mt-1">Currently linked: {{ $ad->car->displayName() }}</p>
            @endif
        </div>

        {{-- Banner Image --}}
        <div class="md:col-span-2">
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Banner Image</label>

            {{-- Current image preview --}}
            @if ($ad->image)
                <div class="mb-2 flex items-center gap-3">
                    <img src="{{ asset('storage/' . $ad->image) }}"
                        alt="Current banner"
                        class="h-16 rounded-lg border border-gray-200 object-cover {{ $ad->isVertical() ? 'w-10' : 'w-28' }}">
                    <p class="text-[11px] text-gray-400">Current banner. Upload below to replace it.</p>
                </div>
            @endif

            <input type="file" name="image" accept="image/jpg,image/jpeg,image/png,image/webp"
                class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm
                       file:mr-3 file:py-1 file:px-3 file:rounded file:border-0
                       file:text-[11px] file:font-black file:uppercase
                       file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all">
            <p class="text-[11px] text-gray-400 mt-1">JPG, PNG or WebP — max 2 MB. Leave blank to keep current image.</p>
        </div>

        {{-- Active toggle --}}
        <div class="md:col-span-2">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1"
                    {{ $ad->is_active ? 'checked' : '' }}
                    class="w-4 h-4 rounded text-blue-600 border-gray-300 focus:ring-blue-500">
                <span class="text-sm font-bold text-gray-700">Active (visible to site visitors)</span>
            </label>
        </div>

    </div>

    {{-- Form actions --}}
    <div class="flex items-center gap-2">
        <button type="submit"
            class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-black uppercase tracking-wider transition-all">
            Save Changes
        </button>
        <button type="button" onclick="toggleForm('edit-ad-{{ $ad->id }}')"
            class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-xs font-bold transition-all">
            Cancel
        </button>
    </div>

</form>