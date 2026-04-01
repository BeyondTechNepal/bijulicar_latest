@forelse ($articles as $item)
    <a href="{{ route('news.show', $item->slug) }}"
        class="group block border-b border-slate-100 last:border-0 pb-10">
        <div class="grid md:grid-cols-12 gap-6">
            <div class="md:col-span-4 overflow-hidden rounded-2xl aspect-video bg-slate-100">
                <img src="{{ asset('storage/' . $item->hero_image) }}"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                    alt="{{ $item->title }}">
            </div>

            <div class="md:col-span-8 flex flex-col justify-center">
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-[9px] font-black uppercase tracking-widest text-[#4ade80]">
                        {{ $item->author_name }}
                    </span>
                    <span class="text-slate-300 text-[9px]">•</span>
                    <span class="text-slate-400 text-[9px] font-bold uppercase">
                        @if ($item->created_at)
                            {{ $item->created_at->format('M d, Y') }}
                        @endif
                    </span>
                    {{-- Added Category Badge so user sees the filter worked --}}
                    <span class="text-slate-300 text-[9px]">•</span>
                    <span class="text-indigo-500 text-[9px] font-black uppercase italic">
                        {{ $item->newscategory?->name }}
                    </span>
                </div>

                <h4 class="text-xl font-black text-slate-900 mb-2 group-hover:text-slate-600 transition-colors uppercase italic leading-tight tracking-tight">
                    {{ $item->title }} {{ $item->title_highlight }}
                </h4>

                <p class="text-slate-500 text-sm leading-relaxed line-clamp-2">
                    {{ strip_tags($item->lead_paragraph) }}
                </p>
            </div>
        </div>
    </a>
@empty
    <div class="py-20 text-center">
        <p class="text-slate-400 font-mono text-[10px] uppercase tracking-widest">No articles found in this category.</p>
    </div>
@endforelse

@if ($articles instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="mt-10">
        {{ $articles->links() }}
    </div>
@endif