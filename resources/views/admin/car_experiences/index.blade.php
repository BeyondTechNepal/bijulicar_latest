@extends('admin.layout')
@section('title', 'Car Experiences')
@section('page-title', 'Car Experiences')

@section('content')

{{-- Flash messages --}}
@if (session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 rounded-2xl px-5 py-4 text-sm font-bold text-green-700">
        <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
    </div>
@endif

{{-- Stats row --}}
<div class="grid grid-cols-3 gap-4 mb-8">
    <div class="bg-white border border-gray-200 rounded-2xl p-5">
        <div class="text-2xl font-black text-amber-500">{{ $pending->count() }}</div>
        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Pending Review</div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5">
        <div class="text-2xl font-black text-emerald-500">{{ $approved->total() }}</div>
        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Approved</div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5">
        <div class="text-2xl font-black text-red-500">{{ $rejected->total() }}</div>
        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Rejected</div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════
     POST AN EXPERIENCE (admin)
══════════════════════════════════════════════════════════════════════════ --}}
<div class="mb-10" x-data="adminExpForm()">
    <div class="flex items-center gap-3 mb-4">
        <h2 class="text-sm font-black text-gray-700 uppercase tracking-widest">Post an Experience</h2>
        <span class="text-[10px] font-black bg-slate-100 text-slate-600 border border-slate-200 px-2 py-0.5 rounded-full">
            Published immediately
        </span>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl p-6">
        <form action="{{ route('admin.car_experiences.store') }}" method="POST" novalidate>
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Author name --}}
                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5">
                        Author Name <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="author_name"
                        value="{{ old('author_name') }}"
                        maxlength="100"
                        placeholder="e.g. Ramesh Shrestha"
                        class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-green-400 @error('author_name') border-red-400 @enderror"
                    />
                    @error('author_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Experience type --}}
                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5">
                        Type <span class="text-red-500">*</span>
                    </label>
                    <select
                        name="experience_type"
                        class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-green-400 @error('experience_type') border-red-400 @enderror"
                    >
                        <option value="general"  {{ old('experience_type') === 'general'  ? 'selected' : '' }}>General</option>
                        <option value="rental"   {{ old('experience_type') === 'rental'   ? 'selected' : '' }}>Rental</option>
                        <option value="purchase" {{ old('experience_type') === 'purchase' ? 'selected' : '' }}>Purchase</option>
                    </select>
                    @error('experience_type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Title --}}
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
                        maxlength="150"
                        placeholder="e.g. Amazing performance on the Prithvi Highway"
                        class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-green-400 @error('title') border-red-400 @enderror"
                    />
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Trip context --}}
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5">
                        Trip / Context <span class="text-gray-400 font-medium normal-case text-[10px]">(optional)</span>
                    </label>
                    <input
                        type="text"
                        name="trip_context"
                        value="{{ old('trip_context') }}"
                        maxlength="150"
                        placeholder="e.g. Kathmandu to Pokhara road trip"
                        class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-green-400"
                    />
                </div>

                {{-- Car toggle --}}
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5">
                        Car <span class="text-red-500">*</span>
                    </label>

                    {{-- Toggle --}}
                    <div class="flex rounded-xl overflow-hidden border border-gray-200 w-fit mb-3">
                        <button
                            type="button"
                            @click="linked = true; externalCarName = ''"
                            :class="linked ? 'bg-gray-900 text-white font-black' : 'bg-white text-gray-500 hover:bg-gray-50'"
                            class="px-5 py-2 text-xs font-semibold transition-all"
                        >
                            <i class="fa-solid fa-link mr-1.5"></i> BijuliCar Listing
                        </button>
                        <button
                            type="button"
                            @click="linked = false; selectedCarId = null; selectedCarName = ''; carQuery = ''; carResults = []"
                            :class="!linked ? 'bg-gray-900 text-white font-black' : 'bg-white text-gray-500 hover:bg-gray-50'"
                            class="px-5 py-2 text-xs font-semibold transition-all"
                        >
                            <i class="fa-solid fa-pen mr-1.5"></i> Other Car
                        </button>
                    </div>

                    {{-- Hidden fields --}}
                    <input type="hidden" name="linked_to_bijulicar" :value="linked ? 1 : 0" />
                    <input type="hidden" name="car_id" :value="selectedCarId" />

                    {{-- BijuliCar search --}}
                    <div x-show="linked" class="relative">
                        <input
                            x-model="carQuery"
                            @input.debounce.350ms="searchCars()"
                            type="text"
                            placeholder="Search cars on BijuliCar…"
                            class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-green-400"
                        />
                        {{-- Dropdown --}}
                        <div
                            x-show="carResults.length > 0"
                            class="absolute left-0 right-0 top-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg z-10 max-h-48 overflow-y-auto"
                            style="display:none"
                        >
                            <template x-for="car in carResults" :key="car.id">
                                <button
                                    type="button"
                                    @click="pickCar(car)"
                                    class="w-full text-left px-4 py-2.5 text-sm hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0"
                                    x-text="car.name"
                                ></button>
                            </template>
                        </div>
                        {{-- Selected chip --}}
                        <div x-show="selectedCarId" class="mt-2 flex items-center gap-2 bg-green-50 border border-green-200 rounded-xl px-4 py-2.5">
                            <i class="fa-solid fa-circle-check text-green-600 text-xs"></i>
                            <span class="text-sm text-green-700 font-bold flex-1" x-text="selectedCarName"></span>
                            <button type="button" @click="selectedCarId = null; selectedCarName = ''; carQuery = ''" class="text-gray-400 hover:text-gray-600">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </button>
                        </div>
                        @error('car_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- External car name --}}
                    <div x-show="!linked">
                        <input
                            type="text"
                            name="external_car_name"
                            value="{{ old('external_car_name') }}"
                            maxlength="100"
                            placeholder="e.g. Tata Nexon EV, Honda City 2023…"
                            x-model="externalCarName"
                            class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-green-400 @error('external_car_name') border-red-400 @enderror"
                        />
                        @error('external_car_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Body --}}
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5">
                        Experience <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        name="body"
                        rows="6"
                        maxlength="3000"
                        x-model="body"
                        placeholder="Paste or write the full experience here…"
                        class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl resize-none focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-green-400 @error('body') border-red-400 @enderror"
                    >{{ old('body') }}</textarea>
                    <div class="flex justify-between mt-1">
                        @error('body')
                            <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-400 ml-auto" x-text="body.length + ' / 3000'"></p>
                    </div>
                </div>

            </div>

            <div class="mt-5 flex justify-end">
                <button
                    type="submit"
                    class="bg-gray-900 text-white font-black px-6 py-2.5 rounded-xl text-sm hover:bg-gray-700 active:scale-95 transition-all"
                >
                    <i class="fa-solid fa-paper-plane mr-2"></i>Post & Publish
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════
     PENDING REVIEW
══════════════════════════════════════════════════════════════════════════ --}}
<div class="mb-10">
    <div class="flex items-center gap-3 mb-4">
        <h2 class="text-sm font-black text-gray-700 uppercase tracking-widest">Pending Review</h2>
        @if ($pending->count() > 0)
            <span class="text-[10px] font-black bg-amber-100 text-amber-700 border border-amber-200 px-2 py-0.5 rounded-full">
                {{ $pending->count() }} pending
            </span>
        @endif
    </div>

    @if ($pending->isEmpty())
        <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center">
            <p class="text-sm font-bold text-gray-400">No experiences waiting for review</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($pending as $exp)
                <div class="bg-white border border-gray-200 rounded-2xl p-5">

                    {{-- Top row --}}
                    <div class="flex flex-col lg:flex-row lg:items-start gap-4">

                        {{-- Info --}}
                        <div class="flex-1 grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Author</p>
                                <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $exp->authorDisplayName() }}</p>
                                <p class="text-xs text-gray-400">{{ $exp->user->email ?? '—' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Car</p>
                                <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $exp->carDisplayName() }}</p>
                                <p class="text-xs text-gray-400">{{ $exp->isLinkedToBijuliCar() ? 'BijuliCar listing' : 'External' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Type</p>
                                <span class="inline-block mt-0.5 px-2 py-0.5 rounded-full text-[10px] font-black uppercase {{ $exp->experienceTypeBadgeClasses() }}">
                                    {{ $exp->experienceTypeLabel() }}
                                </span>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Submitted</p>
                                <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $exp->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                    </div>

                    {{-- Title + body preview --}}
                    <div class="mt-4 bg-slate-50 rounded-xl p-4">
                        <p class="text-sm font-black text-gray-800">{{ $exp->title }}</p>
                        @if ($exp->trip_context)
                            <p class="text-xs text-gray-400 italic mt-0.5">📍 {{ $exp->trip_context }}</p>
                        @endif
                        <p class="text-xs text-gray-600 mt-2 leading-relaxed line-clamp-4">{{ $exp->body }}</p>
                    </div>

                    {{-- Actions --}}
                    <div class="mt-4 flex flex-wrap items-center gap-3">

                        {{-- Approve --}}
                        <form action="{{ route('admin.car_experiences.approve', $exp) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl text-xs font-black hover:bg-emerald-100 transition-all">
                                <i class="fa-solid fa-circle-check"></i> Approve
                            </button>
                        </form>

                        {{-- Reject --}}
                        <button
                            type="button"
                            x-data
                            @click="$dispatch('open-reject', { id: {{ $exp->id }} })"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 text-red-600 border border-red-200 rounded-xl text-xs font-black hover:bg-red-100 transition-all"
                        >
                            <i class="fa-solid fa-ban"></i> Reject
                        </button>

                        {{-- Delete --}}
                        <form action="{{ route('admin.car_experiences.destroy', $exp) }}" method="POST"
                              onsubmit="return confirm('Delete this experience permanently?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 text-gray-500 border border-gray-200 rounded-xl text-xs font-black hover:bg-gray-100 transition-all">
                                <i class="fa-solid fa-trash"></i> Delete
                            </button>
                        </form>

                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- ══════════════════════════════════════════════════════════════════════
     APPROVED
══════════════════════════════════════════════════════════════════════════ --}}
<div class="mb-10">
    <div class="flex items-center gap-3 mb-4">
        <h2 class="text-sm font-black text-gray-700 uppercase tracking-widest">Approved</h2>
        <span class="text-[10px] font-black bg-emerald-100 text-emerald-700 border border-emerald-200 px-2 py-0.5 rounded-full">
            {{ $approved->total() }} total
        </span>
    </div>

    @if ($approved->isEmpty())
        <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center">
            <p class="text-sm font-bold text-gray-400">No approved experiences yet</p>
        </div>
    @else
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Author</th>
                        <th class="text-left px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Title</th>
                        <th class="text-left px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Car</th>
                        <th class="text-left px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Type</th>
                        <th class="text-left px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Approved</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($approved as $exp)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3">
                                <p class="font-bold text-gray-800">{{ $exp->authorDisplayName() }}</p>
                                <p class="text-xs text-gray-400">{{ $exp->user->email ?? '—' }}</p>
                            </td>
                            <td class="px-5 py-3">
                                <p class="font-semibold text-gray-700 line-clamp-1">{{ $exp->title }}</p>
                                @if ($exp->trip_context)
                                    <p class="text-xs text-gray-400 italic">📍 {{ $exp->trip_context }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <p class="text-gray-700">{{ $exp->carDisplayName() }}</p>
                                <p class="text-xs text-gray-400">{{ $exp->isLinkedToBijuliCar() ? 'BijuliCar' : 'External' }}</p>
                            </td>
                            <td class="px-5 py-3">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase {{ $exp->experienceTypeBadgeClasses() }}">
                                    {{ $exp->experienceTypeLabel() }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-xs text-gray-400">
                                {{ $exp->approved_at?->diffForHumans() ?? '—' }}
                            </td>
                            <td class="px-5 py-3 text-right">
                                <form action="{{ route('admin.car_experiences.destroy', $exp) }}" method="POST"
                                      onsubmit="return confirm('Delete this experience permanently?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-400 hover:text-red-600 font-bold transition-colors">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $approved->links() }}
            </div>
        </div>
    @endif
</div>

{{-- ══════════════════════════════════════════════════════════════════════
     REJECTED
══════════════════════════════════════════════════════════════════════════ --}}
<div class="mb-10">
    <div class="flex items-center gap-3 mb-4">
        <h2 class="text-sm font-black text-gray-700 uppercase tracking-widest">Rejected</h2>
        <span class="text-[10px] font-black bg-red-100 text-red-600 border border-red-200 px-2 py-0.5 rounded-full">
            {{ $rejected->total() }} total
        </span>
    </div>

    @if ($rejected->isEmpty())
        <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center">
            <p class="text-sm font-bold text-gray-400">No rejected experiences</p>
        </div>
    @else
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Author</th>
                        <th class="text-left px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Title</th>
                        <th class="text-left px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Car</th>
                        <th class="text-left px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Reason</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($rejected as $exp)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3">
                                <p class="font-bold text-gray-800">{{ $exp->authorDisplayName() }}</p>
                                <p class="text-xs text-gray-400">{{ $exp->user->email ?? '—' }}</p>
                            </td>
                            <td class="px-5 py-3">
                                <p class="font-semibold text-gray-700 line-clamp-1">{{ $exp->title }}</p>
                            </td>
                            <td class="px-5 py-3 text-gray-700">{{ $exp->carDisplayName() }}</td>
                            <td class="px-5 py-3 text-xs text-gray-500 max-w-xs">
                                {{ $exp->admin_note ?? '—' }}
                            </td>
                            <td class="px-5 py-3 text-right">
                                <form action="{{ route('admin.car_experiences.destroy', $exp) }}" method="POST"
                                      onsubmit="return confirm('Delete this experience permanently?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-400 hover:text-red-600 font-bold transition-colors">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $rejected->links() }}
            </div>
        </div>
    @endif
</div>

{{-- ══════════════════════════════════════════════════════════════════════
     REJECT MODAL
══════════════════════════════════════════════════════════════════════════ --}}
<div
    x-data="{ open: false, id: null }"
    @open-reject.window="open = true; id = $event.detail.id"
    @keydown.escape.window="open = false"
>
    <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center px-4" style="display:none">

        {{-- Backdrop --}}
        <div @click="open = false" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

        {{-- Modal --}}
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 z-10">
            <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-1">Reject Experience</h3>
            <p class="text-xs text-gray-400 mb-5">This reason will be sent to the user as a notification.</p>

            <form
                :action="`{{ url('admin/car-experiences') }}/${id}/reject`"
                method="POST"
            >
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5">
                        Reason <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        name="admin_note"
                        rows="4"
                        maxlength="500"
                        placeholder="e.g. Content does not focus on the car experience…"
                        class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl resize-none focus:outline-none focus:ring-2 focus:ring-red-300 focus:border-red-400"
                        required
                    ></textarea>
                </div>
                <div class="mt-4 flex gap-3 justify-end">
                    <button
                        type="button"
                        @click="open = false"
                        class="px-4 py-2 text-xs font-bold text-gray-500 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors"
                    >Cancel</button>
                    <button
                        type="submit"
                        class="px-4 py-2 text-xs font-black text-white bg-red-500 rounded-xl hover:bg-red-600 transition-colors"
                    >Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Alpine component for the post form --}}
<script>
function adminExpForm() {
    return {
        linked:          false,
        carQuery:        '',
        carResults:      [],
        selectedCarId:   null,
        selectedCarName: '',
        externalCarName: '',
        body:            '{{ old('body', '') }}',

        async searchCars() {
            if (this.carQuery.length < 1) { this.carResults = []; return; }
            try {
                const res = await fetch(`/experiences/cars?q=${encodeURIComponent(this.carQuery)}`);
                this.carResults = await res.json();
            } catch (e) {
                this.carResults = [];
            }
        },

        pickCar(car) {
            this.selectedCarId   = car.id;
            this.selectedCarName = car.name;
            this.carResults      = [];
            this.carQuery        = '';
        },
    };
}
</script>

@endsection