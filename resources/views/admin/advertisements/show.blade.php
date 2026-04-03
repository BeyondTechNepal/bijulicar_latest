@extends('admin.layout')
@section('title', 'Review Ad — ' . $advertisement->title)
@section('page-title', 'Review Advertisement')

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.advertisements.index') }}"
        class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-gray-800 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Advertisements
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- ── LEFT: Ad preview ────────────────────────────────────────── --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Banner image --}}
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Banner Image</p>
            </div>
            @if ($advertisement->image)
                <div class="p-5">
                    <img src="{{ asset('storage/' . $advertisement->image) }}"
                        alt="{{ $advertisement->title }}"
                        class="w-full rounded-xl border border-gray-100 {{ $advertisement->isVertical() ? 'max-w-[200px]' : '' }}">
                </div>
            @else
                <div class="p-8 text-center">
                    <p class="text-sm font-bold text-gray-400">No image uploaded</p>
                </div>
            @endif
        </div>

        {{-- Ad details --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Ad Details</p>
            <div class="grid grid-cols-2 gap-x-8 gap-y-4">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Title</p>
                    <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $advertisement->title }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Placement</p>
                    <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $advertisement->placementLabel() }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Priority Tier</p>
                    <span class="inline-block mt-0.5 text-xs font-black px-2 py-0.5 rounded-full {{ $advertisement->priorityBadgeClass() }}">
                        {{ $advertisement->priorityLabel() }}
                    </span>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Run Dates</p>
                    <p class="text-sm font-bold text-gray-800 mt-0.5">
                        {{ $advertisement->starts_at?->format('M d, Y') }} – {{ $advertisement->ends_at?->format('M d, Y') }}
                    </p>
                    <p class="text-xs text-gray-400">{{ $advertisement->durationDays() }} days</p>
                </div>
                @if ($advertisement->description)
                <div class="col-span-2">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Description</p>
                    <p class="text-sm text-gray-600 mt-0.5 leading-relaxed">{{ $advertisement->description }}</p>
                </div>
                @endif
                @if ($advertisement->link_url)
                <div class="col-span-2">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Click URL</p>
                    <a href="{{ $advertisement->link_url }}" target="_blank"
                        class="text-sm text-indigo-600 hover:underline mt-0.5 block truncate">
                        {{ $advertisement->link_url }}
                    </a>
                </div>
                @endif
                @if ($advertisement->car)
                <div class="col-span-2">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Linked Car</p>
                    <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $advertisement->car->title ?? 'Car #' . $advertisement->car_id }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Business info --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Submitted By</p>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Name</p>
                    <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $advertisement->owner->name }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Email</p>
                    <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $advertisement->owner->email }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Submitted</p>
                    <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $advertisement->created_at->format('M d, Y · h:i A') }}</p>
                    <p class="text-xs text-gray-400">{{ $advertisement->created_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── RIGHT: Actions panel ─────────────────────────────────────── --}}
    <div class="space-y-5">

        {{-- Pricing info --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Pricing Calculation</p>

            @if ($pricingRule)
                <div class="space-y-2 text-sm mb-4">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Rate</span>
                        <span class="font-bold text-gray-800">Rs {{ number_format($pricingRule->price_per_day, 2) }} / day</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Duration</span>
                        <span class="font-bold text-gray-800">{{ $advertisement->durationDays() }} days</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Min. days</span>
                        <span class="font-bold text-gray-800">{{ $pricingRule->min_days }} days</span>
                    </div>
                    <div class="border-t border-gray-100 pt-2 flex justify-between">
                        <span class="font-black text-gray-700">Suggested Total</span>
                        <span class="font-black text-blue-600 text-base">Rs {{ number_format($suggestedCharge, 2) }}</span>
                    </div>
                </div>
                <p class="text-xs text-gray-400">You can override the amount below before approving.</p>
            @else
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                    <p class="text-xs font-bold text-amber-700">No pricing rule found for this placement + tier. Enter amount manually.</p>
                </div>
            @endif
        </div>

        {{-- Approve form --}}
        <div class="bg-white border border-emerald-200 rounded-2xl p-5">
            <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-4">Approve Ad</p>
            <form method="POST" action="{{ route('admin.advertisements.approve', $advertisement) }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">
                        Charged Amount (Rs)
                    </label>
                    <input type="number" name="charged_amount" step="0.01" required
                        value="{{ $suggestedCharge ?? '' }}"
                        placeholder="Enter amount..."
                        class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5 text-sm font-bold focus:outline-none focus:border-emerald-400 transition-all">
                    @error('charged_amount')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    Approve & Send Invoice
                </button>
            </form>
        </div>

        {{-- Reject form --}}
        <div class="bg-white border border-red-100 rounded-2xl p-5">
            <p class="text-[10px] font-black text-red-500 uppercase tracking-widest mb-4">Reject Ad</p>
            <form method="POST" action="{{ route('admin.advertisements.reject', $advertisement) }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">
                        Reason for Rejection
                    </label>
                    <textarea name="reason" required rows="3"
                        placeholder="Explain why this ad cannot be approved..."
                        class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-red-400 transition-all resize-none"></textarea>
                    @error('reason')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-red-50 hover:bg-red-100 border border-red-200 text-red-600 rounded-xl text-xs font-black uppercase tracking-wider transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Reject Ad
                </button>
            </form>
        </div>

    </div>
</div>

@endsection