@extends('frontend.app')

<title>News | BijuliCar</title>

@section('content')
    {{-- News Header --}}
    <section class="relative pt-20 pb-10 lg:pt-32 lg:pb-16 overflow-hidden bg-[#0a0f1e] text-white">
        <div class="absolute inset-0 z-0">
            {{-- <img src="{{ asset('images/news_header.jpg') }}"
                class="w-full h-full object-cover opacity-100 scale-105 blur-[3px]" alt="Automotive News Background"> --}}
            @if ($banner->image)
                <img src="{{ asset('storage/' . $banner->image) }}"
                    class="w-full h-full object-cover opacity-100 scale-105 blur-[3px]" alt="Automotive News Background">
            @endif

            <div class="absolute inset-0 bg-gradient-to-b from-[#0a0f1e]/80 via-[#0a0f1e]/25 to-[#202638]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-10">
                <div class="max-w-3xl">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="w-12 h-[3px] bg-[#4ade80]"></span>
                        <span class="text-[10px] lg:text-[12px] uppercase tracking-[0.5em] text-[#4ade80] font-bold">The
                            Intelligence Hub</span>
                    </div>

                    <h1 class="text-5xl md:text-7xl font-black tracking-tighter uppercase italic leading-[0.8] mb-6">
                        Auto<span class="text-slate-400 block lg:inline lg:ml-4">Intel</span>
                    </h1>

                    <p
                        class="text-slate-400 text-sm lg:text-base font-medium max-w-xl leading-relaxed border-l-2 border-white/10 pl-6">
                        Stay ahead of the curve with expert analysis on <span class="text-white">EV breakthroughs</span>,
                        hybrid efficiency, and the evolving landscape of traditional precision engineering.
                    </p>
                </div>

                <div class="hidden lg:flex flex-col items-end text-right space-y-4">
                    <div class="bg-white/5 border border-white/10 backdrop-blur-md rounded-2xl p-6">
                        <div class="flex items-center justify-end gap-3 mb-1">
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#4ade80] opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-[#4ade80]"></span>
                            </span>
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Live
                                Updates</span>
                        </div>
                        <p class="text-xs font-bold text-white uppercase italic">March 2026 Edition</p>
                    </div>
                </div>
            </div>

            <div class="mt-10 flex flex-wrap gap-3 lg:gap-4 border-t border-white/5 pt-6">
                <button
                    class="px-8 py-3 bg-[#4ade80] text-black rounded-full text-[10px] font-black uppercase tracking-widest italic shadow-lg shadow-[#4ade80]/20 hover:scale-105 transition-transform">
                    Discover
                </button>
                <button
                    class="px-8 py-3 bg-white/5 border border-white/10 hover:border-[#4ade80]/50 rounded-full text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-white transition-all">
                    Electric
                </button>
                <button
                    class="px-8 py-3 bg-white/5 border border-white/10 hover:border-[#4ade80]/50 rounded-full text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-white transition-all">
                    Hybrid
                </button>
                <button
                    class="px-8 py-3 bg-white/5 border border-white/10 hover:border-[#4ade80]/50 rounded-full text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-white transition-all">
                    Markets
                </button>
            </div>
        </div>
        <div
            class="absolute bottom-6 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 animate-bounce opacity-50 hover:opacity-100 transition-opacity">
            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-white/50">Scroll</span>
            <div class="w-5 h-8 border-2 border-white/30 rounded-full flex justify-center p-1">
                <div class="w-1 h-2 bg-[#4ade80] rounded-full"></div>
            </div>
        </div>
    </section>

    {{-- below is the detailed news section, use it in another blade --}}
    <div class="bg-white font-sans antialiased text-slate-900">

        <section class="max-w-7xl mx-auto px-6 py-16">
            <div class="grid lg:grid-cols-12 gap-16">

                {{-- <article class="lg:col-span-8">

                    <nav
                        class="flex items-center gap-4 mb-8 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400">
                        <a href="#" class="hover:text-black transition-colors">Intelligence Hub</a>
                        <span class="text-slate-200">/</span>
                        <a href="#" class="text-[#4ade80]">Advanced Propulsion</a>
                    </nav>

                    <h1 class="text-5xl md:text-7xl font-black uppercase italic leading-[0.85] tracking-tighter mb-10">
                        Hydrogen Combustion:<br>
                        <span class="text-slate-400">The Silent Rival</span> to <br>EV Dominance
                    </h1>

                    <div class="flex flex-wrap items-center gap-8 py-8 mb-12 border-y border-slate-100">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-12 h-12 rounded-full bg-slate-900 flex items-center justify-center text-white text-[11px] font-black italic">
                                AT</div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-tight">Alex Thorne</p>
                                <p class="text-[9px] text-slate-400 font-bold uppercase">Chief Technical Analyst</p>
                            </div>
                        </div>
                        <div class="hidden md:block h-10 w-px bg-slate-100"></div>
                        <div class="flex gap-10">
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase mb-1">Read Time</p>
                                <p class="text-xs font-bold uppercase italic">12 Minutes</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase mb-1">Complexity</p>
                                <p class="text-xs font-bold uppercase italic text-[#4ade80]">Level 4/5</p>
                            </div>
                        </div>
                    </div>

                    <figure class="mb-16">
                        <div class="rounded-[3rem] overflow-hidden aspect-[21/9] bg-slate-100 shadow-2xl mb-4">
                            <img src="https://images.unsplash.com/photo-1593941707882-a5bba14938c7?q=80&w=2000"
                                class="w-full h-full object-cover" alt="Hydrogen Engine Core">
                        </div>
                        <figcaption class="text-[10px] text-slate-400 font-medium uppercase tracking-widest text-center">
                            Fig 1.0: Prototype H2-ICE Direct Injection Rail System (March 2026 Testing Phase)
                        </figcaption>
                    </figure>

                    <div class="prose prose-slate max-w-none">

                        <p
                            class="text-2xl font-bold leading-snug text-slate-800 mb-10 italic border-l-4 border-[#4ade80] pl-8">
                            The automotive industry is currently standing at a crossroads where the chemistry of the fuel
                            tank is battling the energy density of the battery cell. While EVs have captured the public
                            imagination, the engineering underground is betting on the return of the piston.
                        </p>

                        <h2 class="text-3xl font-black uppercase italic tracking-tighter mb-6 mt-12">I. Beyond the Fuel Cell
                        </h2>
                        <p class="text-lg leading-relaxed text-slate-600 mb-8">
                            It is critical to distinguish between Hydrogen Fuel Cells (FCEV) and Hydrogen Internal
                            Combustion Engines (H2-ICE). While the former uses hydrogen to generate electricity via a
                            chemical reaction, H2-ICE utilizes the same mechanical principles that have powered vehicles for
                            a century. The difference? The byproduct is largely water vapor, not CO2.
                        </p>

                        <div class="my-12 p-8 bg-slate-50 rounded-3xl border border-slate-100 grid md:grid-cols-2 gap-8">
                            <div>
                                <h4 class="text-[10px] font-black uppercase tracking-widest text-[#4ade80] mb-4">Comparative
                                    Energy Density</h4>
                                <ul class="space-y-3">
                                    <li class="flex justify-between border-b border-slate-200 pb-2">
                                        <span class="text-xs font-bold uppercase text-slate-500">Hydrogen (Gas)</span>
                                        <span class="text-xs font-black">120 MJ/kg</span>
                                    </li>
                                    <li class="flex justify-between border-b border-slate-200 pb-2">
                                        <span class="text-xs font-bold uppercase text-slate-500">Diesel</span>
                                        <span class="text-xs font-black">45 MJ/kg</span>
                                    </li>
                                    <li class="flex justify-between border-b border-slate-200 pb-2">
                                        <span class="text-xs font-bold uppercase text-slate-500">Li-ion Battery</span>
                                        <span class="text-xs font-black">0.9 MJ/kg</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="flex flex-col justify-center">
                                <p class="text-[11px] leading-relaxed text-slate-500 italic">
                                    <strong>Technical Note:</strong> Despite the higher MJ/kg, hydrogen storage volume
                                    remains the primary engineering challenge for passenger-sized chassis, necessitating
                                    700-bar carbon-fiber tanks.
                                </p>
                            </div>
                        </div>

                        <h2 class="text-3xl font-black uppercase italic tracking-tighter mb-6 mt-12">II. Solving the NOx
                            Problem</h2>
                        <p class="text-lg leading-relaxed text-slate-600 mb-8">
                            Critics of hydrogen combustion often point to Nitrogen Oxides (NOx) as the "dirty secret" of the
                            technology. Since air is 78% nitrogen, burning any fuel at high temperatures in an atmospheric
                            environment will produce NOx. However, the latest generation of <strong>Cryogenic
                                Injection</strong> systems allows the engine to run "ultra-lean."
                        </p>
                        <p class="text-lg leading-relaxed text-slate-600 mb-8">
                            By injecting liquid hydrogen at near-absolute zero temperatures directly into the chamber,
                            engineers can control the flame front with surgical precision. This cooling effect suppresses
                            the formation of NOx to levels that are actually cleaner than the ambient air in many major
                            cities.
                        </p>

                        <div class="my-16 text-center">
                            <span class="inline-block w-12 h-1 bg-[#4ade80] mb-8"></span>
                            <blockquote
                                class="text-4xl md:text-5xl font-black uppercase italic tracking-tighter leading-none text-slate-900 mb-8">
                                "The infrastructure for ICE exists in every corner of the globe. If we change the fuel, we
                                don't have to rebuild the world."
                            </blockquote>
                            <cite class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400">— Dr. Helena
                                Vane, Propulsion Lead</cite>
                        </div>

                        <h2 class="text-3xl font-black uppercase italic tracking-tighter mb-6 mt-12">III. The Industrial
                            Pivot</h2>
                        <p class="text-lg leading-relaxed text-slate-600 mb-8">
                            The most compelling argument for H2-ICE is not ecological—it's economic. A total shift to EVs
                            requires trillions in new battery gigafactories and a complete overhaul of the power grid.
                            Conversely, H2-ICE allows giants like Cummins, Toyota, and JCB to utilize 90% of their existing
                            supply chains.
                        </p>
                        <p class="text-lg leading-relaxed text-slate-600 mb-12">
                            For the consumer, this means the return of the lightweight, high-revving sports car. For the
                            logistics manager, it means a 40-ton truck that refuels in 10 minutes rather than charging for
                            10 hours. As we move into the second half of the decade, the "Silent Rival" is becoming
                            increasingly loud.
                        </p>
                    </div>

                    <div class="flex items-center justify-between py-10 border-t border-slate-100">
                        <div class="flex gap-4">
                            <button
                                class="px-6 py-3 bg-slate-900 text-white rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-[#4ade80] hover:text-black transition-all">Save
                                Report</button>
                            <button
                                class="px-6 py-3 border border-slate-200 rounded-full text-[10px] font-black uppercase tracking-widest hover:border-black transition-all">Print
                                PDF</button>
                        </div>
                        <div class="flex items-center gap-4 text-[10px] font-black uppercase text-slate-400">
                            <span>Share:</span>
                            <a href="#" class="hover:text-black">TW</a>
                            <a href="#" class="hover:text-black">LN</a>
                        </div>
                    </div>
                </article> --}}

                <article class="lg:col-span-8">

                    {{-- Navigation - Dynamic Breadcrumb (if applicable) --}}
                    <nav
                        class="flex items-center gap-4 mb-8 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400">
                        <a href="#" class="hover:text-black transition-colors">Intelligence Hub</a>
                        <span class="text-slate-200">/</span>
                        <a href="#" class="text-[#4ade80]">Advanced Propulsion</a>
                    </nav>

                    {{-- Main Title Section --}}
                    <h1 class="text-5xl md:text-7xl font-black uppercase italic leading-[0.85] tracking-tighter mb-10">
                        {{ $news->title }}:<br>
                        <span class="text-slate-400">{{ $news->title_highlight }}</span> <br>
                        {{ $news->title_suffix }}
                    </h1>

                    {{-- Author & Metadata Section --}}
                    <div class="flex flex-wrap items-center gap-8 py-8 mb-12 border-y border-slate-100">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-12 h-12 rounded-full bg-slate-900 flex items-center justify-center text-white text-[11px] font-black italic">
                                {{ $news->author_initials }}
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-tight">{{ $news->author_name }}</p>
                                <p class="text-[9px] text-slate-400 font-bold uppercase">{{ $news->author_role }}</p>
                            </div>
                        </div>
                        <div class="hidden md:block h-10 w-px bg-slate-100"></div>
                        <div class="flex gap-10">
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase mb-1">Read Time</p>
                                <p class="text-xs font-bold uppercase italic">12 Minutes</p> {{-- Static --}}
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase mb-1">Complexity</p>
                                <p class="text-xs font-bold uppercase italic text-[#4ade80]">Level 4/5</p>
                                {{-- Static --}}
                            </div>
                        </div>
                    </div>

                    {{-- Hero Figure --}}
                    <figure class="mb-16">
                        <div class="rounded-[3rem] overflow-hidden aspect-[21/9] bg-slate-100 shadow-2xl mb-4">
                            <img src="{{ asset('storage/' . $news->hero_image) }}" class="w-full h-full object-cover"
                                alt="{{ $news->title }}">
                        </div>
                        <figcaption class="text-[10px] text-slate-400 font-medium uppercase tracking-widest text-center">
                            {{ $news->figure_caption }}
                        </figcaption>
                    </figure>

                    <div class="prose prose-slate max-w-none">

                        {{-- Lead Paragraph --}}
                        <div
                            class="text-2xl font-bold leading-snug text-slate-800 mb-10 italic border-l-4 border-[#4ade80] pl-8">
                            {!! $news->lead_paragraph !!}
                        </div>

                        {{-- Section I --}}
                        <h2 class="text-3xl font-black uppercase italic tracking-tighter mb-6 mt-12">
                            {{ $news->section_1_title }}
                        </h2>
                        <div class="text-lg leading-relaxed text-slate-600 mb-8">
                            {!! $news->section_1_content !!}
                        </div>

                        {{-- Technical Specs Box (Dynamic from JSON Array) --}}
                        @if ($news->tech_specs)
                            <div
                                class="my-12 p-8 bg-slate-50 rounded-3xl border border-slate-100 grid md:grid-cols-2 gap-8">
                                <div>
                                    <h4 class="text-[10px] font-black uppercase tracking-widest text-[#4ade80] mb-4">
                                        Comparative Energy Density
                                    </h4>
                                    <ul class="space-y-3">
                                        @foreach ($news->tech_specs as $spec)
                                            <li class="flex justify-between border-b border-slate-200 pb-2">
                                                <span
                                                    class="text-xs font-bold uppercase text-slate-500">{{ $spec['key'] }}</span>
                                                <span class="text-xs font-black">{{ $spec['value'] }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="flex flex-col justify-center">
                                    <p class="text-[11px] leading-relaxed text-slate-500 italic">
                                        <strong>Technical Note:</strong> {{ $news->tech_note }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        {{-- Section II --}}
                        <h2 class="text-3xl font-black uppercase italic tracking-tighter mb-6 mt-12">
                            {{ $news->section_2_title }}
                        </h2>
                        <div class="text-lg leading-relaxed text-slate-600 mb-8">
                            {!! $news->section_2_content !!}
                        </div>

                        {{-- Quote Section --}}
                        <div class="my-16 text-center">
                            <span class="inline-block w-12 h-1 bg-[#4ade80] mb-8"></span>
                            <blockquote
                                class="text-4xl md:text-5xl font-black uppercase italic tracking-tighter leading-none text-slate-900 mb-8">
                                "{{ $news->quote_text }}"
                            </blockquote>
                            <cite class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400">
                                — {{ $news->quote_author }}, {{ $news->quote_author_title }}
                            </cite>
                        </div>

                        {{-- Section III --}}
                        <h2 class="text-3xl font-black uppercase italic tracking-tighter mb-6 mt-12">
                            {{ $news->section_3_title }}
                        </h2>
                        <div class="text-lg leading-relaxed text-slate-600 mb-12">
                            {!! $news->section_3_content !!}
                        </div>
                    </div>

                    {{-- Footer Actions --}}
                    <div class="flex items-center justify-between py-10 border-t border-slate-100">
                        <div class="flex gap-4">
                            <button
                                class="px-6 py-3 bg-slate-900 text-white rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-[#4ade80] hover:text-black transition-all">
                                Save Report
                            </button>
                            <button
                                class="px-6 py-3 border border-slate-200 rounded-full text-[10px] font-black uppercase tracking-widest hover:border-black transition-all">
                                Print PDF
                            </button>
                        </div>
                        <div class="flex items-center gap-4 text-[10px] font-black uppercase text-slate-400">
                            <span>Share:</span>
                            <a href="#" class="hover:text-black">TW</a>
                            <a href="#" class="hover:text-black">LN</a>
                        </div>
                    </div>
                </article>

                <aside class="lg:col-span-4">
                    <div class="sticky top-12 space-y-12">

                        <div class="bg-slate-50 rounded-[2rem] p-8 border border-slate-100">
                            <h4 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-6">Market
                                Sentiment</h4>
                            <div class="space-y-6">
                                <div>
                                    <div class="flex justify-between mb-2">
                                        <span class="text-[10px] font-bold uppercase">H2 Adoption Rate</span>
                                        <span class="text-[10px] font-black">74%</span>
                                    </div>
                                    <div class="w-full bg-slate-200 h-1 rounded-full overflow-hidden">
                                        <div class="bg-[#4ade80] h-full w-[74%]"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between mb-2">
                                        <span class="text-[10px] font-bold uppercase">Investor Confidence</span>
                                        <span class="text-[10px] font-black">58%</span>
                                    </div>
                                    <div class="w-full bg-slate-200 h-1 rounded-full overflow-hidden">
                                        <div class="bg-slate-900 h-full w-[58%]"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3
                                class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 mb-8 flex items-center gap-4">
                                Linked Intel <span class="h-px flex-grow bg-slate-100"></span>
                            </h3>
                            <div class="space-y-10">
                                <div class="group cursor-pointer">
                                    <p class="text-[9px] font-black text-[#4ade80] uppercase mb-2">Policy</p>
                                    <h5
                                        class="text-lg font-black uppercase italic leading-tight group-hover:text-slate-500 transition-colors">
                                        US Tax Credits for Non-Electric Zero Emission?</h5>
                                </div>
                                <div class="group cursor-pointer">
                                    <p class="text-[9px] font-black text-[#4ade80] uppercase mb-2">Supply</p>
                                    <h5
                                        class="text-lg font-black uppercase italic leading-tight group-hover:text-slate-500 transition-colors">
                                        The Green Hydrogen Pipeline Bottleneck</h5>
                                </div>
                            </div>
                        </div>

                        <div class="bg-[#502915] rounded-[2rem] p-8 text-center relative overflow-hidden">
                            <h5 class="text-white text-xl font-black uppercase italic mb-4 relative z-10">Hungry for more?
                            </h5>
                            <button
                                class="w-full py-4 bg-[#ed6906] text-white rounded-full text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-transform relative z-10 shadow-xl">Order
                                a Whopper</button>
                        </div>
                    </div>
                </aside>
            </div>
        </section>
    </div>

@endsection
