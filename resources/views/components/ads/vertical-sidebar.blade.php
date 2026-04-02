{{--
    Vertical sidebar ad component.
    Usage: <x-ads.vertical-sidebar :ads="$newsSidebarAds" />

    Renders each live ad as a stacked card — image on top, text below.
    Premium ads get a gold accent; Featured get purple.
--}}
@if($ads && $ads->isNotEmpty())
<div class="space-y-5">
    @foreach($ads as $ad)
    @php $target = $ad->targetUrl(); @endphp

    <div class="rounded-2xl overflow-hidden border bg-slate-900 group
        {{ $ad->priority === 2 ? 'border-amber-400/50 shadow-lg shadow-amber-400/10' : ($ad->priority === 1 ? 'border-purple-400/30' : 'border-slate-700') }}">

        {{-- Priority accent line --}}
        @if($ad->priority >= 1)
            <div class="h-[2px] w-full
                {{ $ad->priority === 2 ? 'bg-gradient-to-r from-amber-400 via-amber-300 to-amber-400' : 'bg-gradient-to-r from-purple-500 via-purple-400 to-purple-500' }}">
            </div>
        @endif

        {{-- Banner image --}}
        @if($ad->image)
        <div class="overflow-hidden h-44">
            <img src="{{ Storage::url($ad->image) }}"
                 alt="{{ $ad->title }}"
                 class="w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500">
        </div>
        @else
        {{-- Placeholder when no image --}}
        <div class="h-24 bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center">
            <svg class="w-8 h-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
            </svg>
        </div>
        @endif

        {{-- Text content --}}
        <div class="p-4">
            <span class="text-[9px] font-black uppercase tracking-widest block mb-2
                {{ $ad->priority === 2 ? 'text-amber-400' : ($ad->priority === 1 ? 'text-purple-400' : 'text-slate-500') }}">
                {{ $ad->priority === 2 ? '★ Premium' : ($ad->priority === 1 ? '◆ Featured' : 'Sponsored') }}
            </span>

            <h4 class="text-sm font-black text-white uppercase italic tracking-tight leading-tight mb-2">
                {{ $ad->title }}
            </h4>

            @if($ad->description)
                <p class="text-slate-400 text-xs font-medium mb-3 leading-relaxed">
                    {{ Str::limit($ad->description, 90) }}
                </p>
            @endif

            @if($ad->car)
                <div class="flex items-center gap-1.5 mb-3">
                    <span class="text-[9px] font-black px-2 py-0.5 bg-[#4ade80]/20 text-[#4ade80] rounded-md uppercase tracking-wider">
                        {{ $ad->car->formattedPrice() }}
                    </span>
                    <span class="text-[9px] font-black px-2 py-0.5 bg-white/10 text-slate-300 rounded-md uppercase">
                        {{ strtoupper($ad->car->drivetrain) }}
                    </span>
                </div>
            @endif

            @if($target)
                <a href="{{ $target }}"
                   class="block text-center text-[10px] font-black px-3 py-2.5 rounded-xl uppercase tracking-widest transition-all
                       {{ $ad->priority === 2 ? 'bg-amber-400 text-slate-900 hover:bg-amber-300' : ($ad->priority === 1 ? 'bg-purple-600 text-white hover:bg-purple-500' : 'bg-white/10 text-white hover:bg-white/20') }}">
                    View →
                </a>
            @endif
        </div>

    </div>
    @endforeach
</div>
@endif