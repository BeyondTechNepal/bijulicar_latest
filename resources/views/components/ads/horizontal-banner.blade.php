{{--
    Horizontal banner ad component.
    Usage: <x-ads.horizontal-banner :ads="$businessBannerAds" />

    Displays each live ad as a dark full-width banner with text on the left
    and the banner image on the right. Premium ads get a gold shimmer border.
--}}
@if($ads && $ads->isNotEmpty())
<div class="space-y-4">
    @foreach($ads as $ad)
    @php $target = $ad->targetUrl(); @endphp

    <div class="relative rounded-2xl overflow-hidden border shadow-sm group bg-slate-900
        {{ $ad->priority === 2 ? 'border-amber-400/50 shadow-amber-400/10' : ($ad->priority === 1 ? 'border-purple-400/40' : 'border-slate-700') }}">

        {{-- Premium shimmer line --}}
        @if($ad->priority === 2)
            <div class="absolute top-0 left-0 right-0 h-[2px] bg-gradient-to-r from-transparent via-amber-400 to-transparent opacity-80"></div>
        @endif

        <div class="flex flex-col md:flex-row min-h-[160px]">

            {{-- Left: text content --}}
            <div class="flex-1 flex flex-col justify-center px-7 py-6 z-10">

                <div class="flex items-center gap-2 mb-3">
                    <span class="text-[9px] font-black uppercase tracking-widest
                        {{ $ad->priority === 2 ? 'text-amber-400' : ($ad->priority === 1 ? 'text-purple-400' : 'text-slate-500') }}">
                        {{ $ad->priority === 2 ? '★ Premium Ad' : ($ad->priority === 1 ? '◆ Featured Ad' : 'Sponsored') }}
                    </span>
                </div>

                <h3 class="text-xl md:text-2xl font-black text-white uppercase italic tracking-tight leading-tight mb-2">
                    {{ $ad->title }}
                </h3>

                @if($ad->description)
                    <p class="text-slate-400 text-sm font-medium mb-4 max-w-lg leading-relaxed">
                        {{ $ad->description }}
                    </p>
                @endif

                {{-- Car details if linked --}}
                @if($ad->car)
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="text-[10px] font-black px-3 py-1 bg-white/10 text-white rounded-lg uppercase tracking-wider">
                            {{ $ad->car->displayName() }}
                        </span>
                        <span class="text-[10px] font-black px-3 py-1 bg-[#4ade80]/20 text-[#4ade80] rounded-lg uppercase tracking-wider border border-[#4ade80]/20">
                            {{ $ad->car->formattedPrice() }}
                        </span>
                        <span class="text-[10px] font-black px-3 py-1 bg-white/10 text-white rounded-lg uppercase tracking-wider">
                            {{ strtoupper($ad->car->drivetrain) }}
                        </span>
                    </div>
                @endif

                @if($target)
                    <a href="{{ $target }}"
                       class="self-start text-[11px] font-black px-5 py-2.5 rounded-xl uppercase tracking-widest transition-all
                           {{ $ad->priority === 2 ? 'bg-amber-400 text-slate-900 hover:bg-amber-300' : ($ad->priority === 1 ? 'bg-purple-600 text-white hover:bg-purple-500' : 'bg-white/10 text-white hover:bg-white/20') }}">
                        Learn More →
                    </a>
                @endif
            </div>

            {{-- Right: banner image --}}
            @if($ad->image)
            <div class="md:w-56 lg:w-72 flex-shrink-0 overflow-hidden">
                <img src="{{ Storage::url($ad->image) }}"
                     alt="{{ $ad->title }}"
                     class="w-full h-full object-cover opacity-70 group-hover:opacity-90 transition-opacity duration-300">
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endif