@extends('frontend.app')

<title>News | BijuliCar</title>

@section('content')
    {{-- News Header --}}
    <section class="relative pt-20 pb-3 lg:pt-32 lg:pb-[100px] overflow-hidden bg-[#0a0f1e] text-white">
        <div class="absolute inset-0 z-0">
            {{-- <img src="{{ asset('images/news_header.jpg') }}"
                class="w-full h-full object-cover opacity-100 scale-105 blur-[3px]" alt="Automotive News Background"> --}}
            @if ($banner)
                <img src="{{ asset('storage/' . $banner->image) }}"
                    class="w-full h-full object-cover opacity-100 scale-105 blur-[3px]" alt="Automotive News Background">
            @endif

            <div class="absolute inset-0 bg-gradient-to-b from-[#0a0f1e]/80 via-[#0a0f1e]/25 to-[#202638]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 relative z-10 pt-7 sm:pt-1 md:pt-7 lg:pt-2">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-10">
                <div class="max-w-3xl">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="w-12 h-[3px] bg-[#4ade80]"></span>
                        <span class="text-[10px] lg:text-[12px] uppercase tracking-[0.5em] text-[#4ade80] font-bold">The Intelligence Hub</span>
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

            {{-- <div class="mt-10 flex flex-wrap gap-3 lg:gap-4 border-t border-white/5 pt-6">
                <button
                    class="px-8 py-3 bg-[#4ade80] text-black rounded-full text-[10px] font-black uppercase tracking-widest italic shadow-lg shadow-[#4ade80]/20 hover:scale-105 transition-transform">
                    All
                </button>
                <button
                    class="px-8 py-3 bg-[#4ade80] text-black rounded-full text-[10px] font-black uppercase tracking-widest italic shadow-lg shadow-[#4ade80]/20 hover:scale-105 transition-transform">
                    EV
                </button>
                <button
                    class="px-8 py-3 bg-white/5 border border-white/10 hover:border-[#4ade80]/50 rounded-full text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-white transition-all">
                    Petrol
                </button>
                <button
                    class="px-8 py-3 bg-white/5 border border-white/10 hover:border-[#4ade80]/50 rounded-full text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-white transition-all">
                    Diesel
                </button>
                <button
                    class="px-8 py-3 bg-white/5 border border-white/10 hover:border-[#4ade80]/50 rounded-full text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-white transition-all">
                    Hybrid
                </button>
            </div> --}}

            {{-- Filter Buttons --}}
            <div class="mt-10 flex flex-wrap gap-3 lg:gap-4 border-t border-white/5 pt-6 max-w-[75%] lg:max-w-full mr-auto" id="filter-container">
                <button data-category="all"
                    class="filter-btn px-8 py-3 bg-[#4ade80] text-black rounded-full text-[10px] font-black uppercase tracking-widest italic active-btn">
                    All
                </button>
                @foreach ($categories as $category)
                    <button data-category="{{ $category->slug }}"
                        class="filter-btn px-8 py-3 bg-white/5 border border-white/10 rounded-full text-[10px] font-black uppercase tracking-widest text-slate-200 hover:text-white transition-all">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>

            {{-- The Target Container --}}
            {{-- <div id="news-list-container" class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12 transition-opacity duration-300">
                @include('frontend.news._list_partial', ['articles' => $newsItems])
            </div> --}}

        </div>

        {{-- the bouncing scroll mouse icon --}}
        <div class="absolute bottom-4 right-6 lg:right-auto lg:left-1/2 lg:-translate-x-1/2 flex flex-col items-center gap-2 animate-bounce opacity-50 hover:opacity-100 transition-opacity">
            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-white/50">Scroll</span>
            <div class="w-5 h-8 border-2 border-white/30 rounded-full flex justify-center p-1">
                <div class="w-1 h-2 bg-[#4ade80] rounded-full"></div>
            </div>
        </div>
    </section>

    <div class="bg-white font-sans antialiased text-slate-900">

        <section class="max-w-7xl mx-auto px-6 py-6 pb-24">
            <div class="grid lg:grid-cols-12 gap-12">

                {{-- <div class="lg:col-span-8 space-y-10">
                    <div class="flex items-center gap-4 mb-8">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-400 whitespace-nowrap">
                            Intelligence Stream</h3>
                        <div class="h-px w-full bg-slate-100"></div>
                    </div>

                    @php
                        $news = [
                            [
                                'cat' => 'Tech',
                                'title' => 'Hydrogen Combustion: The Silent Rival to EV Dominance',
                                'img' => 'https://images.unsplash.com/photo-1593941707882-a5bba14938c7?q=80&w=1000',
                                'date' => 'MAR 30',
                            ],
                            [
                                'cat' => 'Markets',
                                'title' => 'European Lithium Mining Sees 200% Investment Surge',
                                'img' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?q=80&w=1000',
                                'date' => 'MAR 29',
                            ],
                            [
                                'cat' => 'Design',
                                'title' => 'Retro-Futurism: Why Brands are Reviving 80s Silhouettes',
                                'img' => 'https://images.unsplash.com/photo-1542362567-b0526162cd65?q=80&w=1000',
                                'date' => 'MAR 29',
                            ],
                            [
                                'cat' => 'Policy',
                                'title' => 'New Urban Speed Limits: The Impact on Gearbox Longevity',
                                'img' => 'https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?q=80&w=1000',
                                'date' => 'MAR 28',
                            ],
                            [
                                'cat' => 'Performance',
                                'title' => 'Track Test: The New Apex Predator Clocking Sub-7 Nürburgring',
                                'img' => 'https://images.unsplash.com/photo-1544636331-e26879cd4d9b?q=80&w=1000',
                                'date' => 'MAR 27',
                            ],
                            [
                                'cat' => 'Supply',
                                'title' => 'Magnesium Shortages Threaten Lightweight Chassis Production',
                                'img' => 'https://images.unsplash.com/photo-1581092160562-40aa08e78837?q=80&w=1000',
                                'date' => 'MAR 27',
                            ],
                            [
                                'cat' => 'Innovation',
                                'title' => 'Solar Glass Roofs: Finally a Viable Secondary Charge Source?',
                                'img' => 'https://images.unsplash.com/photo-1508138221679-760a23a2285b?q=80&w=1000',
                                'date' => 'MAR 26',
                            ],
                            [
                                'cat' => 'Global',
                                'title' => 'Tokyo Auto Show Highlights: The Rise of Modular City Cars',
                                'img' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=1000',
                                'date' => 'MAR 25',
                            ],
                        ];
                    @endphp

                    @foreach ($news as $item)
                        <div class="group grid md:grid-cols-12 gap-6 pb-10 border-b border-slate-100 last:border-0">
                            <div class="md:col-span-4 overflow-hidden rounded-2xl aspect-video bg-slate-100">
                                <img src="{{ $item['img'] }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </div>
                            <div class="md:col-span-8 flex flex-col justify-center">
                                <div class="flex items-center gap-3 mb-2">
                                    <span
                                        class="text-[9px] font-black uppercase tracking-widest text-[#4ade80]">{{ $item['cat'] }}</span>
                                    <span class="text-slate-300 text-[9px]">•</span>
                                    <span class="text-slate-400 text-[9px] font-bold uppercase">{{ $item['date'] }},
                                        2026</span>
                                </div>
                                <h4
                                    class="text-xl font-black text-slate-900 mb-2 group-hover:text-slate-600 transition-colors uppercase italic leading-tight tracking-tight">
                                    {{ $item['title'] }}
                                </h4>
                                <p class="text-slate-500 text-sm leading-relaxed line-clamp-2">
                                    Deep dive into the architectural shifts and engineering milestones defining this
                                    quarter's industry trajectory...
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div> --}}

                <div class="lg:col-span-8 space-y-10">
                    <div class="flex items-center gap-4 mb-8">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-400 whitespace-nowrap">
                            Intelligence Stream
                        </h3>
                        <div class="h-px w-full bg-slate-100"></div>
                    </div>

                    {{-- @foreach ($newsItems as $item)
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
                                    </div>

                                    <h4
                                        class="text-xl font-black text-slate-900 mb-2 group-hover:text-slate-600 transition-colors uppercase italic leading-tight tracking-tight">
                                        {{ $item->title }} {{ $item->title_highlight }}
                                    </h4>

                                    <p class="text-slate-500 text-sm leading-relaxed line-clamp-2">
                                        {{ strip_tags($item->lead_paragraph) }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endforeach --}}
                    <div id="news-list-container" class="space-y-10 transition-opacity duration-300">
                        {{-- On first load, we tell the partial to use your $newsItems variable --}}
                        @include('frontend.news._list_partial', ['articles' => $newsItems])
                    </div>
                </div>

                <aside class="lg:col-span-4">
                    <div class="sticky top-10 space-y-8">

                        {{-- ── Business sidebar ads (priority: Premium → Featured → Standard) ── --}}
                        @if(isset($newsSidebarAds) && $newsSidebarAds->isNotEmpty())
                            <x-ads.vertical-sidebar :ads="$newsSidebarAds" />
                        @endif

                    </div>
                </aside>

            </div>
        </section>
    </div>


    {{-- below is the detailed news section, use it in another blade --}}
    {{-- <div class="bg-white font-sans antialiased text-slate-900">
        <div class="fixed top-0 left-0 w-full h-1.5 z-50 bg-slate-100">
            <div class="bg-[#4ade80] h-full w-[45%] transition-all duration-300"></div>
        </div>

        <section class="max-w-7xl mx-auto px-6 py-16">
            <div class="grid lg:grid-cols-12 gap-16">

                <article class="lg:col-span-8">

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
    </div> --}}

    <script>
        document.querySelectorAll('.filter-btn').forEach(button => {
            button.addEventListener('click', function() {
                const category = this.getAttribute('data-category');
                const container = document.getElementById('news-list-container');

                // 1. Visual Feedback (Fade out)
                container.style.opacity = '0.5';

                // 2. Update Button Styles
                document.querySelectorAll('.filter-btn').forEach(btn => {
                    btn.classList.remove('bg-[#4ade80]', 'text-black', 'active-btn');
                    btn.classList.add('bg-white/5', 'text-slate-400');
                });
                this.classList.add('bg-[#4ade80]', 'text-black', 'active-btn');
                this.classList.remove('bg-white/5', 'text-slate-400');

                // 3. Fetch Data
                fetch(`/news-filter?category=${category}`, {
                        headers: {
                            "X-Requested-With": "XMLHttpRequest"
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        container.innerHTML = html;
                        container.style.opacity = '1';
                    })
                    .catch(error => console.error('Error fetching filtered news:', error));
            });
        });
    </script>
@endsection