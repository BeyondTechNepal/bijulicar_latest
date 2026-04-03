{{--
    Vertical sidebar ad component.
    Usage: <x-ads.vertical-sidebar :ads="$newsSidebarAds" />
--}}
@if($ads && $ads->isNotEmpty())
<div class="space-y-0">
    @foreach($ads as $ad)
    @php $target = $ad->targetUrl(); @endphp

    {{-- Outer wrapper with priority glow --}}
    <div class="relative group
        {{ $ad->priority === 2 ? 'p-[1.5px] rounded-2xl bg-gradient-to-b from-amber-400 via-amber-300/50 to-transparent' : ($ad->priority === 1 ? 'p-[1.5px] rounded-2xl bg-gradient-to-b from-purple-500 via-purple-400/40 to-transparent' : '') }}">

        <div class="relative rounded-2xl overflow-hidden bg-slate-900 flex flex-col"
             style="min-height: 480px;">

            {{-- ── IMAGE SECTION (top 55%) ── --}}
            <div class="relative flex-shrink-0" style="height: 260px;">

                @if($ad->image)
                    <img src="{{ Storage::url($ad->image) }}"
                         alt="{{ $ad->title }}"
                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                @else
                    <div class="absolute inset-0 bg-gradient-to-br from-slate-700 to-slate-900"
                         style="background-image: radial-gradient(#ffffff18 1px, transparent 1px); background-size: 16px 16px;"></div>
                @endif

                {{-- Dark vignette so bottom text is readable --}}
                <div class="absolute inset-0 bg-gradient-to-b from-black/20 via-transparent to-slate-900/80"></div>

                {{-- AD label watermark — top right --}}
                <div class="absolute top-3 right-3 z-10">
                    <span class="text-[8px] font-black uppercase tracking-[0.2em] px-2 py-0.5 rounded
                        {{ $ad->priority === 2 ? 'bg-amber-400/90 text-slate-900' : ($ad->priority === 1 ? 'bg-purple-600/90 text-white' : 'bg-black/50 text-white/70 border border-white/20') }}
                        backdrop-blur-sm">
                        AD
                    </span>
                </div>

                {{-- Priority badge — top left --}}
                @if($ad->priority >= 1)
                <div class="absolute top-3 left-3 z-10">
                    <span class="text-[8px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full
                        {{ $ad->priority === 2 ? 'bg-amber-400 text-slate-900' : 'bg-purple-600 text-white' }}">
                        {{ $ad->priority === 2 ? '★ Premium' : '◆ Featured' }}
                    </span>
                </div>
                @endif

            </div>

            {{-- ── CONTENT SECTION (bottom) ── --}}
            <div class="flex flex-col flex-1 px-5 py-5 border-t
                {{ $ad->priority === 2 ? 'border-amber-400/30' : ($ad->priority === 1 ? 'border-purple-500/30' : 'border-white/10') }}">

                {{-- Sponsored label --}}
                <div class="flex items-center gap-2 mb-3">
                    <span class="w-3 h-[1.5px]
                        {{ $ad->priority === 2 ? 'bg-amber-400' : ($ad->priority === 1 ? 'bg-purple-400' : 'bg-[#4ade80]') }}"></span>
                    <span class="text-[9px] font-black uppercase tracking-[0.25em]
                        {{ $ad->priority === 2 ? 'text-amber-400' : ($ad->priority === 1 ? 'text-purple-400' : 'text-[#4ade80]') }}">
                        Sponsored Content
                    </span>
                </div>

                <h4 class="text-[15px] font-black text-white uppercase italic tracking-tight leading-snug mb-2">
                    {{ $ad->title }}
                </h4>

                @if($ad->description)
                    <p class="text-slate-400 text-[11px] leading-relaxed mb-4 line-clamp-3">
                        {{ $ad->description }}
                    </p>
                @endif

                {{-- Car chips --}}
                @if($ad->car)
                    <div class="flex flex-wrap gap-1.5 mb-4">
                        <span class="text-[9px] font-black px-2.5 py-1 bg-[#4ade80]/15 text-[#4ade80] rounded-lg uppercase tracking-wider border border-[#4ade80]/25">
                            {{ $ad->car->formattedPrice() }}
                        </span>
                        <span class="text-[9px] font-black px-2.5 py-1 bg-white/8 text-slate-300 rounded-lg uppercase tracking-wider border border-white/10">
                            {{ strtoupper($ad->car->drivetrain) }}
                        </span>
                        @if($ad->car->range_km)
                        <span class="text-[9px] font-black px-2.5 py-1 bg-white/8 text-slate-300 rounded-lg uppercase tracking-wider border border-white/10">
                            {{ $ad->car->range_km }}km
                        </span>
                        @endif
                    </div>
                @endif

                {{-- CTA --}}
                @if($target)
                    <a href="{{ $target }}"
                       class="mt-auto block w-full text-center text-[11px] font-black py-3 rounded-xl uppercase tracking-widest transition-all duration-200
                           {{ $ad->priority === 2
                               ? 'bg-amber-400 text-slate-900 hover:bg-amber-300 shadow-lg shadow-amber-400/20'
                               : ($ad->priority === 1
                                   ? 'bg-purple-600 text-white hover:bg-purple-500 shadow-lg shadow-purple-900/30'
                                   : 'bg-white text-slate-900 hover:bg-[#4ade80] shadow-lg shadow-white/10') }}">
                        {{ $ad->car ? 'View Listing →' : 'Learn More →' }}
                    </a>
                @endif

            </div>

            {{-- Bottom accent strip --}}
            <div class="h-[3px] w-full flex-shrink-0
                {{ $ad->priority === 2 ? 'bg-gradient-to-r from-amber-400/0 via-amber-400 to-amber-400/0' : ($ad->priority === 1 ? 'bg-gradient-to-r from-purple-500/0 via-purple-500 to-purple-500/0' : 'bg-gradient-to-r from-[#4ade80]/0 via-[#4ade80]/60 to-[#4ade80]/0') }}">
            </div>

        </div>
    </div>
    @endforeach
</div>
@endif