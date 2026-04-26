<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bijulicar | Join the Future</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Hide scrollbar but allow functionality */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Add these: */
        .legal-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .legal-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .legal-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
    </style>
</head>

<body class="bg-white lg:bg-[#f1f5f9] min-h-screen" x-data="{
    openTerms: false,
    acceptedTerms: false,
    activeSection: 's1',

    scrollToSection(id) {
        const container = this.$refs.scrollContainer;
        const el = container.querySelector('#' + id);
        if (!el) return;
        container.scrollTo({ top: el.offsetTop, behavior: 'smooth' });
        this.activeSection = id;
    },

    setActive() {
        const container = this.$refs.scrollContainer;
        if (!container) return;
        const sections = container.querySelectorAll('section[id]');
        const triggerPoint = container.scrollTop + container.clientHeight / 3;
        sections.forEach(sec => {
            if (triggerPoint >= sec.offsetTop && triggerPoint < sec.offsetTop + sec.offsetHeight) {
                this.activeSection = sec.id;
            }
        });
    }
}">

    <main class="flex flex-col lg:flex-row min-h-screen w-full">

        {{-- Left Section: Hero (Hidden on Mobile) --}}
        <section class="hidden lg:block lg:w-[50%] h-screen sticky top-0 bg-slate-900">
            <img src="https://images.unsplash.com/photo-1593941707882-a5bba14938c7?q=80&w=2072&auto=format&fit=crop"
                class="absolute inset-0 w-full h-full object-cover opacity-50 grayscale-[0.2]" alt="EV Technology">

            <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-transparent to-transparent"></div>

            <div class="absolute top-1/2 left-16 -translate-y-1/2">
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-12 h-1 bg-[#4ade80] rounded-full"></span>
                    <p class="text-[10px] font-black text-[#4ade80] uppercase tracking-[0.4em]">Marketplace Access</p>
                </div>
                <h2 class="text-6xl font-black text-white uppercase italic tracking-tighter leading-[0.9] mb-6">
                    POWER YOUR <br>NEXT <span class="text-[#4ade80]">JOURNEY.</span>
                </h2>
                <ul class="space-y-3 text-slate-300 text-sm font-bold uppercase tracking-widest">
                    <li class="flex items-center gap-3"><span class="text-[#4ade80]">✔</span> Expert EV Valuation</li>
                    <li class="flex items-center gap-3"><span class="text-[#4ade80]">✔</span> Verified Private Sellers
                    </li>
                    <li class="flex items-center gap-3"><span class="text-[#4ade80]">✔</span> Secure Digital Title</li>
                </ul>
            </div>
        </section>

        {{-- Right Section: Form --}}
        <section
            class="w-full lg:w-[50%] flex flex-col justify-center px-6 md:px-16 lg:px-20 py-10 lg:py-5 bg-white relative z-10 min-h-screen">

            {{-- Brand Logo --}}
            <div class="mb-2">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-4 group no-underline">
                    <div
                        class="w-8 h-8 bg-slate-900 rounded-lg flex items-center justify-center shadow-lg group-hover:bg-[#16a34a] transition-all duration-300">
                        <svg class="w-5 h-5 text-[#4ade80]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span class="text-lg font-extrabold tracking-tighter text-slate-900 uppercase">bijuli<span
                            class="text-[#16a34a]">car</span></span>
                </a>
                <div class="w-full h-1 bg-slate-100 rounded-full">
                    <div class="w-1/2 h-full bg-[#16a34a] shadow-[0_0_8px_rgba(22,163,74,0.4)]"></div>
                </div>
            </div>

            <div class="mb-2">
                <h1 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter">
                    Create <span class="text-[#16a34a]">Account</span>
                </h1>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Join the EV ecosystem
                    today</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3">
                    <i class="fa-solid fa-triangle-exclamation text-red-500 mt-0.5 shrink-0"></i>
                    <div>
                        <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mb-1">Please fix the
                            following</p>
                        <ul class="space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li class="text-[11px] text-red-500 font-medium">• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Full Name -->
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Full
                            Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            placeholder="Amrit Nepal"
                            class="w-full bg-slate-50 border @error('name') border-red-400 bg-red-50 @else border-slate-200 @enderror rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-[#16a34a]/20 focus:border-[#16a34a] transition-all">
                        @error('name')
                            <p
                                class="text-[10px] font-bold text-red-500 uppercase tracking-wider ml-1 flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation text-[8px]"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Email
                            Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            placeholder="amrit@example.com"
                            class="w-full bg-slate-50 border @error('email') border-red-400 bg-red-50 @else border-slate-200 @enderror rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-[#16a34a]/20 focus:border-[#16a34a] transition-all">
                        @error('email')
                            <p
                                class="text-[10px] font-bold text-red-500 uppercase tracking-wider ml-1 flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation text-[8px]"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="space-y-1 md:col-span-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Phone
                            Number</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required
                            placeholder="+977 98XXXXXXXX"
                            class="w-full bg-slate-50 border @error('phone') border-red-400 bg-red-50 @else border-slate-200 @enderror rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-[#16a34a]/20 focus:border-[#16a34a] transition-all">
                        @error('phone')
                            <p
                                class="text-[10px] font-bold text-red-500 uppercase tracking-wider ml-1 flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation text-[8px]"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="space-y-1">
                        <label
                            class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Password</label>
                        <input type="password" name="password" required placeholder="••••••••"
                            class="w-full bg-slate-50 border @error('password') border-red-400 bg-red-50 @else border-slate-200 @enderror rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-[#16a34a]/20 focus:border-[#16a34a] transition-all">
                        @error('password')
                            <p
                                class="text-[10px] font-bold text-red-500 uppercase tracking-wider ml-1 flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation text-[8px]"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Confirm
                            Password</label>
                        <input type="password" name="password_confirmation" required placeholder="••••••••"
                            class="w-full bg-slate-50 border @error('password_confirmation') border-red-400 bg-red-50 @else border-slate-200 @enderror rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-[#16a34a]/20 focus:border-[#16a34a] transition-all">
                        @error('password_confirmation')
                            <p
                                class="text-[10px] font-bold text-red-500 uppercase tracking-wider ml-1 flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation text-[8px]"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Account
                        Type</label>
                    <div class="grid grid-cols-3 sm:grid-cols-5 gap-2">
                        @php
                            $roleOptions = [
                                'buyer' => [
                                    'icon' =>
                                        '<i class="fa-solid fa-cart-arrow-down" style="color: rgb(46, 204, 113);"></i>',
                                    'label' => 'Buyer',
                                ],
                                'seller' => [
                                    'icon' => '<i class="fa-solid fa-car-side" style="color: #3498db;"></i>',
                                    'label' => 'Seller',
                                ],
                                'ev-station' => [
                                    'icon' => '<i class="fa-solid fa-charging-station" style="color: #f1c40f;"></i>',
                                    'label' => 'Charging Station',
                                ],
                                'garage' => [
                                    'icon' => '<i class="fa-solid fa-screwdriver-wrench" style="color: #9b59b6;"></i>',
                                    'label' => 'Garage',
                                ],
                                'business' => [
                                    'icon' => '<i class="fa-solid fa-building" style="color: #34495e;"></i>',
                                    'label' => 'Business',
                                ],
                            ];
                        @endphp
                        @foreach ($roleOptions as $role => $opt)
                            <label class="group cursor-pointer">
                                <input type="radio" name="role" value="{{ $role }}"
                                    class="sr-only peer" {{ old('role') === $role ? 'checked' : '' }}>
                                <div
                                    class="relative h-full border @error('role') border-red-300 bg-red-50/30 @else border-slate-100 @enderror rounded-xl p-2 text-center transition-all peer-checked:border-[#16a34a] peer-checked:bg-green-50/50 hover:shadow-sm flex flex-col items-center justify-center">
                                    <div
                                        class="text-base mb-1 text-slate-600 peer-checked:text-[#16a34a] transition-colors">
                                        {!! $opt['icon'] !!}
                                    </div>
                                    <div
                                        class="font-black text-[10px] text-slate-900 uppercase tracking-tighter leading-none">
                                        {{ $opt['label'] }}
                                    </div>
                                    <div
                                        class="absolute top-1 right-1 w-1.5 h-1.5 rounded-full bg-[#16a34a] opacity-0 peer-checked:opacity-100 transition-opacity">
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('role')
                        <p
                            class="text-[10px] font-bold text-red-500 uppercase tracking-wider ml-1 flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation text-[8px]"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex flex-col gap-3 py-2">
                    <div class="flex items-start gap-3">
                        <input type="checkbox" name="terms" id="terms" x-model="acceptedTerms"
                            class="mt-0.5 w-4 h-4 @error('terms') accent-red-500 outline outline-2 outline-red-400 @else text-[#16a34a] @enderror border-slate-300 rounded focus:ring-[#16a34a] cursor-pointer">
                        <label for="terms"
                            class="text-[10px] font-bold @error('terms') text-red-500 @else text-slate-500 @enderror uppercase tracking-wider leading-relaxed cursor-pointer">
                            I agree to the <button type="button" @click="openTerms = true"
                                class="text-[#16a34a] underline underline-offset-2 transition-colors">TERMS AND
                                CONDITIONS</button> and user agreement.
                        </label>
                    </div>
                    @error('terms')
                        <p
                            class="text-[10px] font-bold text-red-500 uppercase tracking-wider ml-1 flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation text-[8px]"></i> {{ $message }}
                        </p>
                    @enderror
                    <div class="flex items-center gap-3 px-0.5">
                        <input type="checkbox" name="wants_newsletter" id="news"
                            class="w-4 h-4 text-[#16a34a] border-slate-300 rounded">
                        <label for="news"
                            class="text-[10px] font-bold text-slate-500 uppercase tracking-wider cursor-pointer">Subscribe
                            to Newsletter</label>
                    </div>
                </div>

                <button type="submit" :disabled="!acceptedTerms"
                    :class="!acceptedTerms ? 'opacity-50 cursor-not-allowed' : ''"
                    class="w-full py-4 bg-slate-900 text-white rounded-xl font-black uppercase italic tracking-widest text-xs hover:bg-[#16a34a] transition-all">
                    Initialize Account
                </button>
            </form>

            <p class="mt-8 text-center text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em]">
                Already a member? <a href="{{ route('login') }}"
                    class="text-[#16a34a] hover:text-slate-900 ml-1 transition-colors">Log In</a>
            </p>
        </section>
    </main>



    <div x-show="openTerms" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70" style="display: none;">
        <div @click.away="openTerms = false"
            class="bg-white w-full max-w-5xl max-h-[92vh] rounded-[2.5rem] shadow-2xl flex flex-col overflow-hidden border border-white/20">

            <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <div>
                    <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-1">Legal
                        Registry
                        v2.0</p>
                    <h2 class="text-2xl font-black text-slate-900 uppercase italic tracking-tight leading-none">
                        Terms &
                        Conditions</h2>
                </div>
                <button @click="openTerms = false"
                    class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-slate-100 transition-colors">
                    <i class="fa-solid fa-xmark text-slate-400"></i>
                </button>
            </div>

            <div class="flex flex-1 overflow-hidden">

                <aside
                    class="hidden lg:block w-72 bg-slate-50/50 border-r border-slate-100 p-8 overflow-y-auto legal-scroll">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Contents</p>
                    <nav class="space-y-1">
                        <a href="#s1" @click.prevent="scrollToSection('s1')"
                            :class="activeSection === 's1' ? 'text-emerald-600 bg-emerald-50' : 'text-slate-500'"
                            class="block px-3 py-2 text-xs font-bold hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition">
                            01. Introduction
                        </a>

                        <a href="#s2" @click.prevent="scrollToSection('s2')"
                            :class="activeSection === 's2' ? 'text-emerald-600 bg-emerald-50' : 'text-slate-500'"
                            class="block px-3 py-2 text-xs font-bold hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition">
                            02. Platform Role
                        </a>

                        <a href="#s3" @click.prevent="scrollToSection('s3')"
                            :class="activeSection === 's3' ? 'text-emerald-600 bg-emerald-50' : 'text-slate-500'"
                            class="block px-3 py-2 text-xs font-bold hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition">
                            03. User Types
                        </a>

                        <a href="#s4" @click.prevent="scrollToSection('s4')"
                            :class="activeSection === 's4' ? 'text-emerald-600 bg-emerald-50' : 'text-slate-500'"
                            class="block px-3 py-2 text-xs font-bold hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition">
                            04. Registration
                        </a>

                        <a href="#s5" @click.prevent="scrollToSection('s5')"
                            :class="activeSection === 's5' ? 'text-emerald-600 bg-emerald-50' : 'text-slate-500'"
                            class="block px-3 py-2 text-xs font-bold hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition">
                            05. Listings Policy
                        </a>

                        <a href="#s6" @click.prevent="scrollToSection('s6')"
                            :class="activeSection === 's6' ? 'text-emerald-600 bg-emerald-50' : 'text-slate-500'"
                            class="block px-3 py-2 text-xs font-bold hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition">
                            06. Verification
                        </a>

                        <a href="#s7" @click.prevent="scrollToSection('s7')"
                            :class="activeSection === 's7' ? 'text-emerald-600 bg-emerald-50' : 'text-slate-500'"
                            class="block px-3 py-2 text-xs font-bold hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition">
                            07. Buying & Selling
                        </a>

                        <a href="#s8" @click.prevent="scrollToSection('s8')"
                            :class="activeSection === 's8' ? 'text-emerald-600 bg-emerald-50' : 'text-slate-500'"
                            class="block px-3 py-2 text-xs font-bold hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition">
                            08. Rental Terms
                        </a>

                        <a href="#s9" @click.prevent="scrollToSection('s9')"
                            :class="activeSection === 's9' ? 'text-emerald-600 bg-emerald-50' : 'text-slate-500'"
                            class="block px-3 py-2 text-xs font-bold hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition">
                            09. Payments
                        </a>

                        <a href="#s10" @click.prevent="scrollToSection('s10')"
                            :class="activeSection === 's10' ? 'text-emerald-600 bg-emerald-50' : 'text-slate-500'"
                            class="block px-3 py-2 text-xs font-bold hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition">
                            10. Fraud Policy
                        </a>

                        <a href="#s11" @click.prevent="scrollToSection('s11')"
                            :class="activeSection === 's11' ? 'text-emerald-600 bg-emerald-50' : 'text-slate-500'"
                            class="block px-3 py-2 text-xs font-bold hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition">
                            11. Liability
                        </a>

                        <a href="#s12" @click.prevent="scrollToSection('s12')"
                            :class="activeSection === 's12' ? 'text-emerald-600 bg-emerald-50' : 'text-slate-500'"
                            class="block px-3 py-2 text-xs font-bold hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition">
                            12. Intellectual Property
                        </a>

                        <a href="#s13" @click.prevent="scrollToSection('s13')"
                            :class="activeSection === 's13' ? 'text-emerald-600 bg-emerald-50' : 'text-slate-500'"
                            class="block px-3 py-2 text-xs font-bold hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition">
                            13. Data Usage
                        </a>

                        <a href="#s14" @click.prevent="scrollToSection('s14')"
                            :class="activeSection === 's14' ? 'text-emerald-600 bg-emerald-50' : 'text-slate-500'"
                            class="block px-3 py-2 text-xs font-bold hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition">
                            14. Termination
                        </a>

                        <a href="#s15" @click.prevent="scrollToSection('s15')"
                            :class="activeSection === 's15' ? 'text-emerald-600 bg-emerald-50' : 'text-slate-500'"
                            class="block px-3 py-2 text-xs font-bold hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition">
                            15. Availability
                        </a>

                        <a href="#s16" @click.prevent="scrollToSection('s16')"
                            :class="activeSection === 's16' ? 'text-emerald-600 bg-emerald-50' : 'text-slate-500'"
                            class="block px-3 py-2 text-xs font-bold hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition">
                            16. Changes
                        </a>

                        <a href="#s17" @click.prevent="scrollToSection('s17')"
                            :class="activeSection === 's17' ? 'text-emerald-600 bg-emerald-50' : 'text-slate-500'"
                            class="block px-3 py-2 text-xs font-bold hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition">
                            17. Governing Law
                        </a>

                        <a href="#s18" @click.prevent="scrollToSection('s18')"
                            :class="activeSection === 's18' ? 'text-emerald-600 bg-emerald-50' : 'text-slate-500'"
                            class="block px-3 py-2 text-xs font-bold hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition">
                            18. Contact
                        </a>
                    </nav>
                </aside>

                <div x-ref="scrollContainer" @scroll="setActive"
                    class="flex-1 overflow-y-auto p-8 lg:p-16 legal-scroll scroll-smooth bg-white">

                    <div
                        class="bg-blue-600 rounded-3xl p-8 mb-12 text-white shadow-xl shadow-blue-100 flex flex-col md:flex-row items-center gap-8">
                        <div class="flex-1 text-center md:text-left">
                            <h3 class="text-xl font-bold mb-2">Effective: April 19, 2026</h3>
                            <p class="text-blue-100 text-sm leading-relaxed">BijuliCar is a marketplace
                                intermediary.
                                We connect parties but do not own vehicles or guarantee private transactions.</p>
                        </div>
                        <div
                            class="shrink-0 bg-white/10 p-5 rounded-2xl text-center backdrop-blur-sm border border-white/10">
                            <span
                                class="block text-3xl font-black italic tracking-tighter leading-none mb-1">NEPAL</span>
                            <span class="text-[9px] uppercase font-black opacity-60 tracking-[0.2em]">Governing
                                Law</span>
                        </div>
                    </div>

                    <div class="space-y-16 text-slate-600 text-sm leading-relaxed">

                        <!-- 1 -->
                        <section id="s1">
                            <h3 class="text-lg font-black text-slate-900 uppercase italic mb-4">01. Introduction
                            </h3>
                            <p>Welcome to <strong>BijuliCar</strong>, a digital platform that connects car buyers,
                                sellers, dealers, and renters across Nepal.</p>
                            <p class="mt-3">BijuliCar acts strictly as an intermediary marketplace and does not
                                own,
                                sell, or rent vehicles directly unless explicitly stated.</p>
                            <p class="mt-3">By using this platform, you agree to these Terms & Conditions.</p>
                        </section>

                        <!-- 2 -->
                        <section id="s2" class="bg-amber-50 rounded-[2rem] p-8 border border-amber-100">
                            <h3 class="text-lg font-black text-amber-900 uppercase italic mb-4">02. Platform Role
                            </h3>

                            <div class="grid md:grid-cols-2 gap-8">
                                <div>
                                    <h4 class="font-bold mb-3">BijuliCar:</h4>
                                    <ul class="space-y-2">
                                        <li>• Provides vehicle listings & discovery</li>
                                        <li>• Enables showcasing of vehicles</li>
                                        <li>• Facilitates rental connections</li>
                                    </ul>
                                </div>
                                <div>
                                    <h4 class="font-bold text-red-600 mb-3">NOT Responsible for:</h4>
                                    <ul class="space-y-2 text-red-600">
                                        <li>• Ownership verification</li>
                                        <li>• Final transactions</li>
                                        <li>• Delivery or condition</li>
                                    </ul>
                                </div>
                            </div>
                        </section>

                        <!-- 3 -->
                        <section id="s3">
                            <h3 class="text-lg font-black text-slate-900 uppercase italic mb-4">03. User Types</h3>

                            <div class="space-y-4">
                                <div class="p-5 bg-slate-50 rounded-2xl">
                                    <strong>a) Buyers / Renters:</strong>
                                    <p>Browse, contact sellers, and must verify vehicles independently.</p>
                                </div>

                                <div class="p-5 bg-slate-50 rounded-2xl">
                                    <strong>b) Sellers / Owners:</strong>
                                    <p>List vehicles and ensure legal ownership.</p>
                                </div>

                                <div class="p-5 bg-slate-50 rounded-2xl">
                                    <strong>c) Dealers:</strong>
                                    <p>Manage multiple listings and represent businesses.</p>
                                </div>
                            </div>
                        </section>

                        <!-- 4 -->
                        <section id="s4">
                            <h3 class="text-lg font-black text-slate-900 uppercase italic mb-4">04. Account
                                Registration</h3>
                            <ul class="list-disc pl-5 space-y-2">
                                <li>Accurate information required</li>
                                <li>One user = one account</li>
                            </ul>
                            <p class="mt-4">BijuliCar may suspend accounts or remove suspicious activity.</p>
                        </section>

                        <!-- 5 -->
                        <section id="s5">
                            <h3 class="text-lg font-black text-slate-900 uppercase italic mb-4">05. Vehicle
                                Listings
                                Policy</h3>
                            <p>All listings must be accurate, complete, and honest.</p>

                            <div class="bg-red-50 p-6 rounded-2xl mt-4">
                                <h4 class="font-bold text-red-600 mb-2">❌ Prohibited:</h4>
                                <ul class="grid grid-cols-2 gap-2 text-xs font-bold text-red-700">
                                    <li>Fake listings</li>
                                    <li>Duplicate vehicles</li>
                                    <li>Misleading content</li>
                                    <li>False info</li>
                                </ul>
                            </div>
                        </section>

                        <!-- 6 -->
                        <section id="s6">
                            <h3 class="text-lg font-black text-slate-900 uppercase italic mb-4">06. Verification
                                System
                            </h3>
                            <p>BijuliCar may provide badges like Verified Seller or Vehicle.</p>
                            <p class="mt-2">Verification is limited and not a guarantee.</p>
                        </section>

                        <!-- 7 -->
                        <section id="s7">
                            <h3 class="text-lg font-black text-slate-900 uppercase italic mb-4">07. Buying &
                                Selling
                            </h3>
                            <p>All transactions occur between users.</p>
                            <ul class="mt-3 space-y-1">
                                <li>• No payment guarantees</li>
                                <li>• No ownership transfer handling</li>
                            </ul>
                        </section>

                        <!-- 8 -->
                        <section id="s8">
                            <h3 class="text-lg font-black text-slate-900 uppercase italic mb-4">08. Rental Terms
                            </h3>
                            <p>Owners must provide registered vehicles and valid insurance.</p>
                            <p class="mt-2">Renters must follow traffic laws and return vehicles properly.</p>
                        </section>

                        <!-- 9 -->
                        <section id="s9">
                            <h3 class="text-lg font-black text-slate-900 uppercase italic mb-4">09. Payments</h3>
                            <p>BijuliCar may charge listing fees, commissions, or subscriptions.</p>
                            <p class="mt-2">Payments may be offline or via third-party services.</p>
                        </section>

                        <!-- 10 -->
                        <section id="s10">
                            <h3 class="text-lg font-black text-slate-900 uppercase italic mb-4">10. Fraud Policy
                            </h3>
                            <ul class="space-y-2">
                                <li>• No scams or fake listings</li>
                                <li>• No stolen vehicles</li>
                                <li>• No fake documents</li>
                            </ul>
                        </section>

                        <!-- 11 -->
                        <section id="s11">
                            <h3 class="text-lg font-black text-slate-900 uppercase italic mb-4">11. Limitation of
                                Liability</h3>
                            <p>BijuliCar is not responsible for defects, losses, disputes, or accidents.</p>
                        </section>

                        <!-- 12 -->
                        <section id="s12">
                            <h3 class="text-lg font-black text-slate-900 uppercase italic mb-4">12. Intellectual
                                Property</h3>
                            <p>BijuliCar owns the platform design and branding.</p>
                            <p>Users allow usage of their listings for promotion.</p>
                        </section>

                        <!-- 13 -->
                        <section id="s13">
                            <h3 class="text-lg font-black text-slate-900 uppercase italic mb-4">13. Data Usage</h3>
                            <p>We collect and use data for functionality, analytics, and marketing.</p>
                            <p class="mt-2">Users can opt out of marketing anytime.</p>
                        </section>

                        <!-- 14 -->
                        <section id="s14">
                            <h3 class="text-lg font-black text-slate-900 uppercase italic mb-4">14. Termination
                            </h3>
                            <p>Accounts may be suspended or removed if terms are violated.</p>
                        </section>

                        <!-- 15 -->
                        <section id="s15">
                            <h3 class="text-lg font-black text-slate-900 uppercase italic mb-4">15. Availability
                            </h3>
                            <p>We do not guarantee uninterrupted or error-free service.</p>
                        </section>

                        <!-- 16 -->
                        <section id="s16">
                            <h3 class="text-lg font-black text-slate-900 uppercase italic mb-4">16. Changes to
                                Terms
                            </h3>
                            <p>Terms may be updated anytime. Continued use means acceptance.</p>
                        </section>

                        <!-- 17 -->
                        <section id="s17">
                            <h3 class="text-lg font-black text-slate-900 uppercase italic mb-4">17. Governing Law
                            </h3>
                            <p>These Terms are governed by the laws of Nepal.</p>
                        </section>

                        <!-- 18 -->
                        <section id="s18">
                            <h3 class="text-lg font-black text-slate-900 uppercase italic mb-4">18. Contact</h3>
                            <p>Email: info@bijulicar.com</p>
                            <p>Phone: [Your number]</p>
                        </section>

                    </div>
                </div>
            </div>

            <div
                class="px-8 py-6 border-t border-slate-100 bg-white flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-[10px] text-slate-400 font-medium text-center md:text-left">By proceeding, you
                    acknowledge that you have read and agree to all 18 sections of the BijuliCar agreement.</p>
                <div class="flex gap-3 w-full md:w-auto">
                    <button @click="openTerms = false"
                        class="flex-1 md:flex-none px-10 py-3 bg-emerald-500 hover:bg-emerald-600 text-white text-[11px] font-black uppercase tracking-widest rounded-xl transition shadow-lg shadow-emerald-100">I
                        Understand</button>
                </div>
            </div>
        </div>
    </div>



</body>

</html>
