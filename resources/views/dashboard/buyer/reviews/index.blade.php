@extends('dashboard.buyer.layout')
@section('title', 'My Reviews')
@section('page-title', 'My Reviews')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Buyer Portal</p>
            <p class="text-sm font-bold text-slate-600 mt-0.5">Reviews you have written for purchased vehicles.</p>
        </div>
    </div>

    @if($reviews->isNotEmpty())
    <div class="space-y-4">
        @foreach($reviews as $review)
        <div class="bg-white border border-slate-200 rounded-2xl p-6">
            <div class="flex items-start justify-between gap-4">

                {{-- Car info + stars --}}
                <div class="flex items-start gap-4 flex-1">
                    <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center text-[10px] font-black text-slate-500 uppercase shrink-0">
                        EV
                    </div>
                    <div>
                        <p class="text-sm font-black text-slate-900 uppercase italic tracking-tight">
                            {{ $review->car ? $review->car->displayName() : 'Listing removed' }}
                        </p>
                        <p class="text-[#f59e0b] text-base mt-1 tracking-wider">{{ $review->starDisplay() }}</p>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-0.5">
                            {{ $review->rating }}/5 · {{ $review->created_at->format('d M Y') }}
                        </p>
                        @if($review->body)
                        <p class="text-sm text-slate-600 font-medium mt-3 leading-relaxed">
                            "{{ $review->body }}"
                        </p>
                        @else
                        <p class="text-sm text-slate-300 font-medium mt-3 italic">No written review.</p>
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('buyer.reviews.edit', $review) }}"
                        class="w-8 h-8 bg-slate-100 hover:bg-slate-900 hover:text-white rounded-xl flex items-center justify-center transition-all group"
                        title="Edit">
                        <svg class="w-4 h-4 text-slate-500 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('buyer.reviews.destroy', $review) }}"
                        onsubmit="return confirm('Delete this review?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-8 h-8 bg-red-50 hover:bg-red-500 rounded-xl flex items-center justify-center transition-all group"
                            title="Delete">
                            <svg class="w-4 h-4 text-red-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>

            </div>
        </div>
        @endforeach
    </div>

    @if($reviews->hasPages())
    <div class="mt-5">{{ $reviews->links() }}</div>
    @endif

    @else
    <div class="bg-white border border-dashed border-slate-200 rounded-2xl p-14 text-center">
        <p class="text-5xl mb-4">⭐</p>
        <p class="font-black text-slate-900 uppercase italic tracking-tight text-lg">No reviews yet</p>
        <p class="text-sm text-slate-500 font-medium mt-2 mb-6">
            Complete a purchase to unlock the ability to write a review.
        </p>
        <a href="{{ route('buyer.purchases.index') }}"
            class="inline-flex items-center gap-2 bg-slate-900 text-white px-6 py-3 rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all shadow-lg">
            View My Purchases →
        </a>
    </div>
    @endif

@endsection