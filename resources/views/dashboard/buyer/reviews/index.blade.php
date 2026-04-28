@extends('dashboard.buyer.layout')
@section('title', 'My Reviews')
@section('page-title', 'My Reviews')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Buyer Portal</p>
            <p class="text-sm font-bold text-slate-600 mt-0.5">Reviews you have written for purchased and rented vehicles.
            </p>
        </div>
    </div>
    @if ($reviews->isNotEmpty())
        <div class="space-y-4">
            @foreach ($reviews as $review)
                <div class="bg-white border border-slate-200 rounded-2xl p-4 md:p-6">

                    <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">

                        {{-- Car info + content --}}
                        <div class="flex items-start gap-3 md:gap-4 flex-1">

                            {{-- Car photo --}}
                            <div
                                class="w-10 h-10 md:w-12 md:h-12 bg-slate-100 rounded-xl overflow-hidden shrink-0 flex items-center justify-center border border-slate-200">
                                @if ($review->car && $review->car->primaryImage)
                                    <img src="{{ $review->car->primaryImage->url() }}" class="w-full h-full object-cover"
                                        alt="{{ $review->car->displayName() }}">
                                @else
                                    <span class="text-base opacity-20">⚡</span>
                                @endif
                            </div>

                            <div class="w-full">

                                {{-- Title + source --}}
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="text-sm font-black text-slate-900 uppercase italic tracking-tight">
                                        {{ $review->car ? $review->car->displayName() : 'Listing removed' }}
                                    </p>

                                    <span
                                        class="text-[10px] font-black px-2.5 py-0.5 rounded-full uppercase tracking-wider {{ $review->sourceBadgeClasses() }}">
                                        {{ $review->sourceLabel() }}
                                    </span>
                                </div>

                                {{-- Stars --}}
                                <p class="text-[#f59e0b] text-base mt-1 tracking-wider">
                                    {{ $review->starDisplay() }}
                                </p>

                                {{-- Meta --}}
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-0.5">
                                    {{ $review->rating }}/5 · {{ $review->created_at->format('d M Y') }}
                                    @if ($review->isRentalReview() && $review->carRental)
                                        · Rented
                                        {{ $review->carRental->pickup_date->format('d M') }}–{{ $review->carRental->return_date->format('d M Y') }}
                                    @endif
                                </p>

                                {{-- Review body --}}
                                @if ($review->body)
                                    <p class="text-sm text-slate-600 font-medium mt-3 leading-relaxed">
                                        "{{ $review->body }}"
                                    </p>
                                @else
                                    <p class="text-sm text-slate-300 font-medium mt-3 italic">
                                        No written review.
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center justify-end gap-2 md:justify-start shrink-0">

                            {{-- Edit --}}
                            <a href="{{ route('buyer.reviews.edit', $review) }}"
                                class="w-9 h-9 bg-slate-100 hover:bg-slate-900 hover:text-white rounded-xl flex items-center justify-center transition-all"
                                title="Edit">
                                <i class="fa-solid fa-pen text-sm"></i>
                            </a>

                            {{-- Delete --}}
                            <form method="POST" action="{{ route('buyer.reviews.destroy', $review) }}"
                                onsubmit="return confirm('Delete this review?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-9 h-9 bg-red-50 hover:bg-red-500 hover:text-white rounded-xl flex items-center justify-center transition-all"
                                    title="Delete">
                                    <i class="fa-solid fa-trash text-sm text-red-500 hover:text-white"></i>
                                </button>
                            </form>

                        </div>

                    </div>

                </div>
            @endforeach
        </div>

        @if ($reviews->hasPages())
            <div class="mt-5">{{ $reviews->links() }}</div>
        @endif
    @else
        <div class="bg-white border border-dashed border-slate-200 rounded-2xl p-14 text-center">
            <p class="text-5xl mb-4">⭐</p>
            <p class="font-black text-slate-900 uppercase italic tracking-tight text-lg">No reviews yet</p>
            <p class="text-sm text-slate-500 font-medium mt-2 mb-6">
                Complete a purchase or rental to unlock the ability to write a review.
            </p>
            <div class="flex items-center justify-center gap-3 flex-wrap">
                <a href="{{ route('buyer.purchases.index') }}"
                    class="inline-flex items-center gap-2 bg-slate-900 text-white px-5 py-2.5 rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all shadow-lg">
                    View My Purchases →
                </a>
                <a href="{{ route('buyer.rentals.index') }}"
                    class="inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-blue-700 transition-all shadow-lg">
                    View My Rentals →
                </a>
            </div>
        </div>
    @endif

@endsection