@extends('admin.layout')
@section('title', 'Ad Pricing')
@section('page-title', 'Ad Pricing Rules')

@section('content')

<div class="mb-6">
    <p class="text-sm text-gray-500">
        Set the price per day for each placement and priority tier. These rates are used to auto-calculate the
        suggested charge when you approve an ad. You can always override the amount per ad during review.
    </p>
</div>

<div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">

    {{-- Table header --}}
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Placement × Priority Matrix</p>
        <p class="text-xs text-gray-400">Click any row to edit · Price is per day · Amount = price × days</p>
    </div>

    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100 bg-gray-50">
                <th class="text-left px-6 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Placement</th>
                <th class="text-left px-6 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Tier</th>
                <th class="text-left px-6 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Price / Day (Rs)</th>
                <th class="text-left px-6 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Min Days</th>
                <th class="text-left px-6 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                <th class="text-left px-6 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Last Updated</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach ($placements as $placementKey => $placementLabel)
                @foreach ($priorities as $priorityKey => $priorityLabel)
                    @php $rule = $rules[$placementKey][$priorityKey] ?? null; @endphp
                    @if ($rule)
                        <tr class="hover:bg-gray-50 transition-colors" id="row-{{ $rule->id }}">

                            {{-- Display row --}}
                            <td class="px-6 py-4">
                                <span class="font-bold text-gray-800">{{ $placementLabel['label'] }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-[10px] font-black px-2 py-0.5 rounded-full
                                    @if($priorityKey == 2) bg-amber-100 text-amber-700
                                    @elseif($priorityKey == 1) bg-purple-100 text-purple-700
                                    @else bg-slate-100 text-slate-500 @endif">
                                    {{ $priorityLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-black text-gray-800" id="price-display-{{ $rule->id }}">
                                Rs {{ number_format($rule->price_per_day, 2) }}
                            </td>
                            <td class="px-6 py-4 text-gray-600" id="mindays-display-{{ $rule->id }}">
                                {{ $rule->min_days }} days
                            </td>
                            <td class="px-6 py-4">
                                @if ($rule->is_active)
                                    <span class="text-[10px] font-black bg-emerald-50 text-emerald-600 border border-emerald-100 px-2 py-0.5 rounded-full uppercase">Active</span>
                                @else
                                    <span class="text-[10px] font-black bg-gray-100 text-gray-500 border border-gray-200 px-2 py-0.5 rounded-full uppercase">Disabled</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-400">
                                {{ $rule->updated_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <button onclick="toggleEditForm({{ $rule->id }})"
                                    class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                                    Edit
                                </button>
                            </td>
                        </tr>

                        {{-- Inline edit row (hidden by default) --}}
                        <tr id="edit-{{ $rule->id }}" class="hidden bg-indigo-50/50">
                            <td colspan="7" class="px-6 py-4">
                                <form method="POST" action="{{ route('admin.ad-pricing.update', $rule) }}" class="flex flex-wrap items-end gap-4">
                                    @csrf
                                    @method('PATCH')

                                    <div>
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">
                                            Price per Day (Rs)
                                        </label>
                                        <input type="number" name="price_per_day" step="0.01" required
                                            value="{{ $rule->price_per_day }}"
                                            class="bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm font-bold w-36 focus:outline-none focus:border-indigo-400 transition-all">
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">
                                            Minimum Days
                                        </label>
                                        <input type="number" name="min_days" required min="1" max="365"
                                            value="{{ $rule->min_days }}"
                                            class="bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm font-bold w-24 focus:outline-none focus:border-indigo-400 transition-all">
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">
                                            Status
                                        </label>
                                        <select name="is_active"
                                            class="bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm font-bold focus:outline-none focus:border-indigo-400 transition-all">
                                            <option value="1" {{ $rule->is_active ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ !$rule->is_active ? 'selected' : '' }}>Disabled</option>
                                        </select>
                                    </div>

                                    <div class="flex gap-2">
                                        <button type="submit"
                                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-black uppercase tracking-wider transition-all">
                                            Save
                                        </button>
                                        <button type="button" onclick="toggleEditForm({{ $rule->id }})"
                                            class="px-4 py-2 bg-white hover:bg-gray-100 border border-gray-200 text-gray-600 rounded-lg text-xs font-bold transition-all">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>

<script>
    function toggleEditForm(id) {
        const editRow = document.getElementById('edit-' + id);
        editRow.classList.toggle('hidden');
    }
</script>

@endsection