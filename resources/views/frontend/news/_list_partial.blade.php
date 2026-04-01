{{--
    This partial receives $articles which is a collection of normalised arrays
    with keys: title, slug, route, hero_image, lead_paragraph, author_name,
               category, created_at, type, business_name, business_id
--}}

@forelse ($articles as $item)
    <a href="{{ route($item['route'], $item['slug']) }}"
        class="group block border-b border-slate-100 last:border-0 pb-10">
        <div class="grid md:grid-cols-12 gap-6">

            {{-- Hero image --}}
            <div class="md:col-span-4 overflow-hidden rounded-2xl aspect-video bg-slate-100">
                @if($item['hero_image'])
                    <img src="{{ asset('storage/' . $item['hero_image']) }}"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                        alt="{{ $item['title'] }}">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-slate-100">
                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
            </div>

            {{-- Content --}}
            <div class="md:col-span-8 flex flex-col justify-center">
                <div class="flex items-center gap-3 mb-2 flex-wrap">

                    {{-- Business badge (only for business news) --}}
                    @if($item['type'] === 'business')
                        <a href="{{ route('businesses.show', $item['business_id']) }}"
                            onclick="event.stopPropagation()"
                            class="text-[9px] font-black uppercase tracking-widest text-[#a855f7] bg-purple-50 border border-purple-200 px-2 py-0.5 rounded-full hover:bg-purple-100 transition-colors">
                            {{ $item['business_name'] }}
                        </a>
                    @else
                        <span class="text-[9px] font-black uppercase tracking-widest text-[#4ade80]">
                            {{ $item['author_name'] }}
                        </span>
                    @endif

                    <span class="text-slate-300 text-[9px]">•</span>
                    <span class="text-slate-400 text-[9px] font-bold uppercase">
                        {{ \Carbon\Carbon::parse($item['created_at'])->format('M d, Y') }}
                    </span>

                    @if($item['category'])
                        <span class="text-slate-300 text-[9px]">•</span>
                        <span class="text-indigo-500 text-[9px] font-black uppercase italic">
                            {{ $item['category'] }}
                        </span>
                    @endif

                    {{-- Type badge --}}
                    @if($item['type'] === 'business')
                        <span class="text-[9px] font-black uppercase tracking-widest text-purple-600 bg-purple-50 border border-purple-200 px-2 py-0.5 rounded-full">
                            Business
                        </span>
                    @endif
                </div>

                <h4 class="text-xl font-black text-slate-900 mb-2 group-hover:text-slate-600 transition-colors uppercase italic leading-tight tracking-tight">
                    {{ $item['title'] }}
                </h4>

                <p class="text-slate-500 text-sm leading-relaxed line-clamp-2">
                    {{ strip_tags($item['lead_paragraph']) }}
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