@extends('dashboard.buyer.layout')
@section('title', 'Edit Review')
@section('page-title', 'Edit Review')

@section('content')

    <a href="{{ route('buyer.reviews.index') }}"
        class="inline-flex items-center gap-2 text-[11px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-700 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Reviews
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Form --}}
        <div class="lg:col-span-2">
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Edit Your Review</p>

                <form method="POST" action="{{ route('buyer.reviews.update', $review) }}">
                    @csrf
                    @method('PATCH')

                    {{-- Star rating --}}
                    <div class="mb-6">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-3">
                            Rating
                        </label>
                        <div class="flex items-center gap-2" id="star-rating">
                            @for($i = 1; $i <= 5; $i++)
                            <label class="cursor-pointer">
                                <input type="radio" name="rating" value="{{ $i }}"
                                    class="sr-only"
                                    {{ old('rating', $review->rating) == $i ? 'checked' : '' }}>
                                <svg class="w-10 h-10 transition-all star-icon"
                                    data-value="{{ $i }}"
                                    fill="{{ $i <= old('rating', $review->rating) ? '#f59e0b' : 'none' }}"
                                    stroke="{{ $i <= old('rating', $review->rating) ? '#f59e0b' : '#d1d5db' }}"
                                    stroke-width="1.5"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                            </label>
                            @endfor
                        </div>
                        <p id="rating-label" class="text-[11px] font-black text-slate-400 uppercase tracking-widest mt-2"></p>
                        @error('rating')
                            <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Written review --}}
                    <div class="mb-8">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">
                            Written Review
                            <span class="text-slate-300 normal-case font-medium ml-1">(optional)</span>
                        </label>
                        <textarea
                            name="body"
                            rows="5"
                            placeholder="Share your experience with this vehicle and seller..."
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium text-slate-900 placeholder:text-slate-300 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all resize-none">{{ old('body', $review->body) }}</textarea>
                        @error('body')
                            <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full flex items-center justify-center gap-3 bg-slate-900 text-white py-4 rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all shadow-xl shadow-slate-200">
                        ✓ Update Review
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        {{-- Car summary --}}
        <div>
            <div class="bg-white border border-slate-200 rounded-2xl p-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Reviewing</p>
                <div class="flex items-start gap-3">
                    <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center text-[10px] font-black text-slate-500 uppercase shrink-0">
                        {{ strtoupper($review->car->drivetrain) }}
                    </div>
                    <div>
                        <p class="text-sm font-black text-slate-900 uppercase italic tracking-tight leading-tight">
                            {{ $review->car->displayName() }}
                        </p>
                        <p class="text-[11px] text-slate-400 font-medium mt-1">{{ $review->car->location }}</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Original Rating</p>
                    <p class="text-[#f59e0b] text-lg mt-1">{{ $review->starDisplay() }}</p>
                </div>
            </div>
        </div>

    </div>

<script>
    const stars   = document.querySelectorAll('.star-icon');
    const inputs  = document.querySelectorAll('input[name="rating"]');
    const label   = document.getElementById('rating-label');
    const labels  = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];

    function highlight(val) {
        stars.forEach(s => {
            const v = parseInt(s.dataset.value);
            s.setAttribute('fill', v <= val ? '#f59e0b' : 'none');
            s.setAttribute('stroke', v <= val ? '#f59e0b' : '#d1d5db');
        });
        label.textContent = labels[val] || '';
    }

    stars.forEach(star => {
        star.addEventListener('mouseover', () => highlight(parseInt(star.dataset.value)));
        star.addEventListener('mouseout', () => {
            const checked = document.querySelector('input[name="rating"]:checked');
            highlight(checked ? parseInt(checked.value) : 0);
        });
        star.addEventListener('click', () => {
            const val = parseInt(star.dataset.value);
            inputs[val - 1].checked = true;
            highlight(val);
        });
    });

    const checked = document.querySelector('input[name="rating"]:checked');
    if (checked) highlight(parseInt(checked.value));
</script>

@endsection