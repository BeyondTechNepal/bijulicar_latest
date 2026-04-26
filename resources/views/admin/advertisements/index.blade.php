@extends('admin.layout')
@section('title', 'Advertisements')
@section('page-title', 'Advertisement Review')

@section('content')

@php
    $totalPending   = $pending->count();
    $totalApproved  = $approved->count();
    $totalPublished = $published->total();
    $totalRejected  = $rejected->total();
@endphp

{{-- Stats row --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white border border-gray-200 rounded-2xl p-5">
        <div class="text-2xl font-black text-amber-500">{{ $totalPending }}</div>
        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Pending Review</div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5">
        <div class="text-2xl font-black text-blue-500">{{ $totalApproved }}</div>
        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Awaiting Payment</div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5">
        <div class="text-2xl font-black text-emerald-500">{{ $totalPublished }}</div>
        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Live</div>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5">
        <div class="text-2xl font-black text-red-500">{{ $totalRejected }}</div>
        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">Rejected</div>
    </div>
</div>

{{-- ── PENDING REVIEW ──────────────────────────────────────────────────── --}}
<div class="mb-10">
    <div class="flex items-center gap-3 mb-4">
        <h2 class="text-sm font-black text-gray-700 uppercase tracking-widest">Pending Review</h2>
        @if ($totalPending > 0)
            <span class="text-[10px] font-black bg-amber-100 text-amber-700 border border-amber-200 px-2 py-0.5 rounded-full">
                {{ $totalPending }} pending
            </span>
        @endif
    </div>

    @if ($pending->isEmpty())
        <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center">
            <p class="text-sm font-bold text-gray-400">No ads waiting for review</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($pending as $ad)
                <div class="bg-white border border-gray-200 rounded-2xl p-5">
                    <div class="flex flex-col lg:flex-row lg:items-center gap-4">

                        {{-- Ad info --}}
                        <div class="flex-1 grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Business</p>
                                <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $ad->owner->name }}</p>
                                <p class="text-xs text-gray-400">{{ $ad->owner->email }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Ad Title</p>
                                <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $ad->title }}</p>
                                <p class="text-xs text-gray-400">{{ $ad->placementLabel() }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Tier / Dates</p>
                                <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $ad->priorityLabel() }}</p>
                                <p class="text-xs text-gray-400">
                                    {{ $ad->starts_at?->format('M d') }} – {{ $ad->ends_at?->format('M d, Y') }}
                                    ({{ $ad->durationDays() }} days)
                                </p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Submitted</p>
                                <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $ad->created_at->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-400">{{ $ad->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-2 shrink-0">
                            <a href="{{ route('admin.advertisements.show', $ad) }}"
                                class="inline-flex items-center gap-1.5 px-3 py-2 bg-gray-100 hover:bg-gray-200 border border-gray-200 rounded-lg text-xs font-bold text-gray-700 transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Review
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- ── AWAITING PAYMENT ────────────────────────────────────────────────── --}}
<div class="mb-10">
    <div class="flex items-center gap-3 mb-4">
        <h2 class="text-sm font-black text-gray-700 uppercase tracking-widest">Awaiting Payment</h2>
        @if ($totalApproved > 0)
            <span class="text-[10px] font-black bg-blue-100 text-blue-700 border border-blue-200 px-2 py-0.5 rounded-full">
                {{ $totalApproved }} approved
            </span>
        @endif
    </div>

    @if ($approved->isEmpty())
        <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center">
            <p class="text-sm font-bold text-gray-400">No ads waiting for payment confirmation</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($approved as $ad)
                <div class="bg-white border border-blue-100 rounded-2xl p-5">
                    <div class="flex flex-col lg:flex-row lg:items-center gap-4">

                        <div class="flex-1 grid grid-cols-2 md:grid-cols-5 gap-4">
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Business</p>
                                <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $ad->owner->name }}</p>
                                <p class="text-xs text-gray-400">{{ $ad->owner->email }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Ad Title</p>
                                <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $ad->title }}</p>
                                <p class="text-xs text-gray-400">{{ $ad->placementLabel() }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Tier / Dates</p>
                                <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $ad->priorityLabel() }}</p>
                                <p class="text-xs text-gray-400">
                                    {{ $ad->starts_at?->format('M d') }} – {{ $ad->ends_at?->format('M d, Y') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Amount Due</p>
                                <p class="text-sm font-black text-blue-600 mt-0.5">Rs {{ number_format($ad->charged_amount, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Approved</p>
                                <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $ad->reviewed_at?->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-400">{{ $ad->reviewed_at?->diffForHumans() }}</p>
                            </div>
                        </div>

                        {{-- Confirm Payment toggle --}}
                        <div class="shrink-0">
                            <button onclick="toggleForm('payment-{{ $ad->id }}')"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-black uppercase tracking-wider transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                Confirm Payment
                            </button>
                        </div>
                    </div>

                    {{-- Payment confirmation form (hidden by default) --}}
                    <div id="payment-{{ $ad->id }}" class="hidden mt-4 pt-4 border-t border-gray-100">
                        <form method="POST" action="{{ route('admin.advertisements.confirm-payment', $ad) }}">
                            @csrf
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Amount Paid (Rs)</label>
                                    <input type="number" name="amount_paid" step="0.01" required
                                        value="{{ $ad->charged_amount }}"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-400 transition-all">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Payment Method</label>
                                    <select name="payment_method" required
                                        class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-400 transition-all">
                                        <option value="">Select...</option>
                                        <option value="cash">Cash</option>
                                        <option value="bank">Bank Transfer</option>
                                        <option value="esewa">eSewa</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Date Paid</label>
                                    <input type="date" name="paid_at" required
                                        value="{{ now()->toDateString() }}"
                                        max="{{ now()->toDateString() }}"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-400 transition-all">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Note (optional)</label>
                                    <input type="text" name="payment_note" placeholder="e.g. receipt #123"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-400 transition-all">
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button type="submit"
                                    class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-black uppercase tracking-wider transition-all">
                                    Publish Ad
                                </button>
                                <button type="button" onclick="toggleForm('payment-{{ $ad->id }}')"
                                    class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-xs font-bold transition-all">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- ── PUBLISHED ───────────────────────────────────────────────────────── --}}
<div class="mb-10">
    <div class="flex items-center gap-3 mb-4">
        <h2 class="text-sm font-black text-gray-700 uppercase tracking-widest">Live Ads</h2>
        <span class="text-[10px] font-black bg-emerald-100 text-emerald-700 border border-emerald-200 px-2 py-0.5 rounded-full">
            {{ $totalPublished }} total
        </span>
    </div>

    @if ($published->isEmpty())
        <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center">
            <p class="text-sm font-bold text-gray-400">No live ads yet</p>
        </div>
    @else
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden mb-4">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Business</th>
                        <th class="text-left px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Ad</th>
                        <th class="text-left px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Placement</th>
                        <th class="text-left px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Dates</th>
                        <th class="text-left px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Paid</th>
                        <th class="text-left px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Method</th>
                        <th class="text-left px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($published as $ad)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3.5">
                            <div class="font-bold text-gray-800">{{ $ad->owner->name }}</div>
                            <div class="text-xs text-gray-400">{{ $ad->owner->email }}</div>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="font-bold text-gray-800">{{ $ad->title }}</div>
                            <span class="text-[10px] font-black px-1.5 py-0.5 rounded-full {{ $ad->priorityBadgeClass() }}">
                                {{ $ad->priorityLabel() }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-gray-600">{{ $ad->placementLabel() }}</td>
                        <td class="px-5 py-3.5 text-xs text-gray-600">
                            {{ $ad->starts_at?->format('M d') }} – {{ $ad->ends_at?->format('M d, Y') }}
                        </td>
                        <td class="px-5 py-3.5 text-xs font-black text-emerald-600">
                            Rs {{ number_format($ad->amount_paid, 2) }}
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="text-[10px] font-black bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full uppercase">
                                {{ \App\Models\Advertisement::PAYMENT_METHODS[$ad->payment_method] ?? $ad->payment_method }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2">
                                {{-- Edit toggle --}}
                                <button onclick="toggleForm('edit-ad-{{ $ad->id }}')"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded-lg text-[10px] font-black uppercase tracking-wider transition-all">
                                    Edit
                                </button>
                                {{-- Delete --}}
                                <form method="POST" action="{{ route('admin.advertisements.force-delete', $ad) }}"
                                    onsubmit="return confirm('Delete \"{{ addslashes($ad->title) }}\"? This cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-red-50 hover:bg-red-600 hover:text-white text-red-600 border border-red-200 rounded-lg text-[10px] font-black uppercase tracking-wider transition-all">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    {{-- Inline edit form row (hidden by default) --}}
                    <tr id="edit-ad-{{ $ad->id }}" class="hidden bg-blue-50/40">
                        <td colspan="7" class="px-5 py-4">
                            <form method="POST" action="{{ route('admin.advertisements.force-update', $ad) }}">
                                @csrf
                                @method('PATCH')
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-3">
                                    <div class="md:col-span-2">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Title</label>
                                        <input type="text" name="title" required
                                            value="{{ old('title', $ad->title) }}"
                                            class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-400 transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Start Date</label>
                                        <input type="date" name="starts_at" required
                                            value="{{ $ad->starts_at?->toDateString() }}"
                                            class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-400 transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">End Date</label>
                                        <input type="date" name="ends_at" required
                                            value="{{ $ad->ends_at?->toDateString() }}"
                                            class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-400 transition-all">
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="hidden" name="is_active" value="0">
                                        <input type="checkbox" name="is_active" value="1"
                                            {{ $ad->is_active ? 'checked' : '' }}
                                            class="w-4 h-4 rounded text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <span class="text-sm font-bold text-gray-700">Active (showing to visitors)</span>
                                    </label>
                                    <button type="submit"
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-black uppercase tracking-wider transition-all">
                                        Save Changes
                                    </button>
                                    <button type="button" onclick="toggleForm('edit-ad-{{ $ad->id }}')"
                                        class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-xs font-bold transition-all">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $published->links() }}
    @endif
</div>

{{-- ── REJECTED ────────────────────────────────────────────────────────── --}}
<div>
    <div class="flex items-center gap-3 mb-4">
        <h2 class="text-sm font-black text-gray-700 uppercase tracking-widest">Rejected</h2>
        <span class="text-[10px] font-black bg-red-100 text-red-700 border border-red-200 px-2 py-0.5 rounded-full">
            {{ $totalRejected }} total
        </span>
    </div>

    @if ($rejected->isEmpty())
        <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center">
            <p class="text-sm font-bold text-gray-400">No rejected ads</p>
        </div>
    @else
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden mb-4">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Business</th>
                        <th class="text-left px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Ad</th>
                        <th class="text-left px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Placement</th>
                        <th class="text-left px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Reason</th>
                        <th class="text-left px-5 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest">Rejected</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($rejected as $ad)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3.5">
                            <div class="font-bold text-gray-800">{{ $ad->owner->name }}</div>
                            <div class="text-xs text-gray-400">{{ $ad->owner->email }}</div>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="font-bold text-gray-800">{{ $ad->title }}</div>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-gray-600">{{ $ad->placementLabel() }}</td>
                        <td class="px-5 py-3.5 text-xs text-gray-500 max-w-xs">{{ $ad->rejection_reason }}</td>
                        <td class="px-5 py-3.5 text-xs text-gray-400">{{ $ad->reviewed_at?->format('M d, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $rejected->links() }}
    @endif
</div>

<script>
    function toggleForm(id) {
        const el = document.getElementById(id);
        el.classList.toggle('hidden');
    }
</script>

@endsection