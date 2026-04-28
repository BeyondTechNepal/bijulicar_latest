@extends('dashboard.business.layout')
@section('title', 'Advertisements')
@section('page-title', 'Advertisements')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Business Portal</p>
            <p class="text-sm font-bold text-slate-600 mt-0.5">Create and manage your promotional campaigns.</p>
        </div>
        <a href="{{ route('business.advertisements.create') }}"
            class="inline-flex items-center gap-2 bg-slate-900 text-white px-4 py-2.5 rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-purple-700 transition-all">
            + New Ad
        </a>
    </div>

    {{-- Priority legend --}}
    <div class="flex items-center gap-3 mb-5 flex-wrap">
        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Priority tiers:</span>
        <span class="text-[10px] font-black px-2.5 py-1 rounded-full bg-amber-100 text-amber-700 uppercase tracking-wider">★
            Premium — shown first</span>
        <span
            class="text-[10px] font-black px-2.5 py-1 rounded-full bg-purple-100 text-purple-700 uppercase tracking-wider">◆
            Featured — shown second</span>
        <span
            class="text-[10px] font-black px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 uppercase tracking-wider">Standard
            — shown after</span>
    </div>

    @if ($ads->isNotEmpty())
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">

            {{-- Table header - Hidden on mobile, shown on Large screens --}}
            <div class="hidden lg:grid grid-cols-12 gap-4 px-6 py-3 border-b border-slate-100 bg-slate-50">
                <div class="col-span-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Title</div>
                <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Placement</div>
                <div class="col-span-1 text-[10px] font-black text-slate-400 uppercase tracking-widest">Priority</div>
                <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Schedule</div>
                <div class="col-span-1 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</div>
                <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Actions</div>
            </div>

            @foreach ($ads as $ad)
                <div
                    class="grid grid-cols-1 lg:grid-cols-12 gap-4 px-6 py-5 lg:py-4 border-b border-slate-100 last:border-0 items-center hover:bg-slate-50/50 transition-colors">

                    {{-- Title + image thumb (Stays prominent) --}}
                    <div class="col-span-1 lg:col-span-4 flex items-center gap-3">
                        @if ($ad->image)
                            <img src="{{ Storage::url($ad->image) }}" alt="{{ $ad->title }}"
                                class="w-12 h-12 lg:w-10 lg:h-10 rounded-xl object-cover shrink-0 border border-slate-200">
                        @else
                            <div
                                class="w-12 h-12 lg:w-10 lg:h-10 bg-purple-50 border border-purple-100 rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 lg:w-5 lg:h-5 text-purple-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                </svg>
                            </div>
                        @endif
                        <div class="min-w-0">
                            <p
                                class="text-sm font-black text-slate-900 uppercase italic tracking-tight leading-tight truncate">
                                {{ $ad->title }}</p>
                            @if ($ad->car)
                                <p class="text-[11px] text-slate-400 font-medium mt-0.5 truncate">→
                                    {{ $ad->car->displayName() }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Wrapped Group for Mobile --}}
                    <div class="col-span-1 lg:col-span-6 grid grid-cols-2 lg:grid-cols-5 gap-4 items-center">
                        {{-- Placement --}}
                        <div class="lg:col-span-2">
                            <span
                                class="lg:hidden block text-[9px] font-bold text-slate-400 uppercase mb-1">Placement</span>
                            <span
                                class="text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider bg-purple-50 text-purple-700 inline-block leading-tight">
                                {{ $ad->placementLabel() }}
                            </span>
                        </div>

                        {{-- Priority --}}
                        <div class="lg:col-span-1">
                            <span class="lg:hidden block text-[9px] font-bold text-slate-400 uppercase mb-1">Priority</span>
                            <span
                                class="text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider inline-block {{ $ad->priorityBadgeClass() }}">
                                {{ $ad->priorityLabel() }}
                            </span>
                        </div>

                        {{-- Schedule --}}
                        <div class="lg:col-span-2">
                            <span class="lg:hidden block text-[9px] font-bold text-slate-400 uppercase mb-1">Schedule</span>
                            @if ($ad->starts_at || $ad->ends_at)
                                <p class="text-[11px] text-slate-500 font-medium">
                                    {{ $ad->starts_at?->format('d M') ?? '—' }} → {{ $ad->ends_at?->format('d M') ?? '—' }}
                                </p>
                            @else
                                <p class="text-[11px] text-slate-400 font-medium">No schedule</p>
                            @endif
                        </div>
                    </div>

                    {{-- Status + Actions (Grouped on mobile for space) --}}
                    <div
                        class="col-span-1 lg:col-span-2 flex items-center justify-between lg:justify-start gap-4 pt-4 lg:pt-0 border-t lg:border-0 border-slate-50">
                        <div>
                            <span class="lg:hidden block text-[9px] font-bold text-slate-400 uppercase mb-1">Status</span>
                            @if ($ad->status === 'rejected')
                                <span
                                    class="text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider bg-red-100 text-red-700">Rejected</span>
                            @elseif($ad->status === 'pending_review')
                                <span
                                    class="text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider bg-yellow-100 text-yellow-700">In
                                    Review</span>
                            @elseif($ad->status === 'approved')
                                <span
                                    class="text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider bg-blue-100 text-blue-700">Approved</span>
                            @elseif($ad->isLive())
                                <span
                                    class="text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider bg-green-100 text-green-700">Live</span>
                            @elseif($ad->is_active)
                                <span
                                    class="text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider bg-yellow-100 text-yellow-700">Scheduled</span>
                            @else
                                <span
                                    class="text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider bg-slate-100 text-slate-500">Inactive</span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2">
                            @if (in_array($ad->status, ['pending_review', 'rejected']))
                                <a href="{{ route('business.advertisements.edit', $ad) }}"
                                    class="w-9 h-9 lg:w-8 lg:h-8 bg-slate-100 hover:bg-slate-900 hover:text-white rounded-xl flex items-center justify-center transition-all group">
                                    <svg class="w-4 h-4 text-slate-500 group-hover:text-white" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                            @endif
                            <form method="POST" action="{{ route('business.advertisements.destroy', $ad) }}"
                                onsubmit="return confirm('Delete this advertisement?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="w-9 h-9 lg:w-8 lg:h-8 bg-slate-100 hover:bg-red-600 rounded-xl flex items-center justify-center transition-all group">
                                    <svg class="w-4 h-4 text-slate-500 group-hover:text-white" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    @endif

@endsection
