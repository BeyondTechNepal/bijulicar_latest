{{--
    Horizontal banner ad component.
    Usage: <x-ads.horizontal-banner :ads="$carDetailAds" />

    Full-width dark banner — text left, image right with left-edge gradient fade.
    Matches home/marketplace ad style.
    Premium = gold, Featured = purple, Standard = green/white.
--}}
@if($ads && $ads->isNotEmpty())
<div class="space-y-4">
    @foreach($ads as $ad)
    @php $target = $ad->targetUrl(); @endphp

    <div class="relative rounded-2xl overflow-hidden border shadow-md group bg-slate-900
        {{ $ad->priority === 2 ? 'border-amber-400/50 shadow-amber-400/10' : ($ad->priority === 1 ? 'border-purple-500/40 shadow-purple-900/10' : 'border-white/10') }}">

        {{-- Top accent line --}}
        <div class="absolute top-0 left-0 right-0 h-[2px] z-20
            {{ $ad->priority === 2 ? 'bg-gradient-to-r from-transparent via-amber-400 to-transparent' : ($ad->priority === 1 ? 'bg-gradient-to-r from-transparent via-purple-400 to-transparent' : 'bg-gradient-to-r from-transparent via-[#4ade80] to-transparent') }}">
        </div>

        <div class="flex flex-col md:flex-row min-h-[200px]">

            {{-- Left: text --}}
            <div class="flex-1 flex flex-col justify-center px-8 py-8 z-10">

                <p class="text-[9px] font-black uppercase tracking-widest mb-3
                    {{ $ad->priority === 2 ? 'text-amber-400' : ($ad->priority === 1 ? 'text-purple-400' : 'text-[#4ade80]') }}">
                    {{ $ad->priority === 2 ? '★ Premium Ad' : ($ad->priority === 1 ? '◆ Featured Ad' : 'Sponsored') }}
                </p>

                <h3 class="text-2xl md:text-3xl font-black text-white uppercase italic tracking-tight leading-tight mb-3">
                    {{ $ad->title }}
                </h3>

                @if($ad->description)
                    <p class="text-slate-400 text-sm font-medium mb-4 max-w-md leading-relaxed">
                        {{ $ad->description }}
                    </p>
                @endif

                @if($ad->car)
                    <div class="flex flex-wrap gap-2 mb-5">
                        <span class="text-[10px] font-black px-3 py-1.5 bg-white/10 text-white rounded-lg uppercase tracking-wider">
                            {{ $ad->car->displayName() }}
                        </span>
                        <span class="text-[10px] font-black px-3 py-1.5 bg-[#4ade80]/20 text-[#4ade80] rounded-lg uppercase tracking-wider border border-[#4ade80]/20">
                            {{ $ad->car->formattedPrice() }}
                        </span>
                        <span class="text-[10px] font-black px-3 py-1.5 bg-white/10 text-white rounded-lg uppercase tracking-wider">
                            {{ strtoupper($ad->car->drivetrain) }}
                        </span>
                        @if($ad->car->range_km)
                        <span class="text-[10px] font-black px-3 py-1.5 bg-white/10 text-white rounded-lg uppercase tracking-wider">
                            {{ $ad->car->range_km }} km range
                        </span>
                        @endif
                    </div>
                @endif

                @if($target)
                    <a href="{{ $target }}"
                       class="self-start px-6 py-2.5 rounded-xl text-[11px] font-black uppercase italic tracking-widest transition-all
                           {{ $ad->priority === 2 ? 'bg-amber-400 text-slate-900 hover:bg-amber-300' : ($ad->priority === 1 ? 'bg-purple-600 text-white hover:bg-purple-500' : 'bg-white text-slate-900 hover:bg-[#4ade80]') }}">
                        {{ $ad->car ? 'View Listing →' : 'Learn More →' }}
                    </a>
                @endif
            </div>

            {{-- Right: image with gradient fade --}}
            @if($ad->image)
            <div class="md:w-2/5 shrink-0 relative min-h-[180px] md:min-h-0">
                <img src="{{ Storage::url($ad->image) }}"
                     alt="{{ $ad->title }}"
                     class="absolute inset-0 w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-700">
                {{-- Left-edge fade so text blends into image --}}
                <div class="hidden md:block absolute inset-y-0 left-0 w-24 bg-gradient-to-r from-slate-900 to-transparent z-10"></div>
            </div>
            @else
            {{-- Dot-grid placeholder --}}
            <div class="hidden md:block md:w-1/4 shrink-0 opacity-5"
                 style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;">
            </div>
            @endif

        </div>
    </div>
    @endforeach
</div>
@endif