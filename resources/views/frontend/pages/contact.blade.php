@extends('frontend.app')

<title>Contact | BijuliCar</title>

@section('content')
    {{-- header section --}}
    <section class="relative bg-[#0a0f1e] pt-24 pb-7 lg:pt-32 lg:pb-8 overflow-hidden">

        <div class="absolute inset-0 z-0">
            @if ($banner)
                <img src="{{ asset('storage/' . $banner->image) }}" class="w-full h-full object-cover opacity-70 scale-105"
                    alt="{{ $banner->title }}">
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-[#0a0f1e] via-[#0a0f1e]/40 to-[#0a0f1e]/80"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_transparent_0%,_#0a0f1e_100%)]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="grid lg:grid-cols-2 gap-10 items-center">

                <div class="space-y-6">
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-[2px] bg-[#4ade80]"></span>
                            <span class="text-[9px] uppercase tracking-[0.3em] text-[#4ade80] font-black">Liaison
                                Office</span>
                        </div>
                        <h1
                            class="text-4xl md:text-6xl font-black text-white uppercase italic tracking-tighter leading-[0.80]">
                            Get In <br>
                            <span class="text-slate-500">The Orbit</span>
                        </h1>
                        <p class="text-slate-400 text-xs md:text-sm max-w-sm font-medium leading-relaxed">
                            Connect with our automotive specialists for fleet inquiries or appointments.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div
                            class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/10 backdrop-blur-md">
                            <div class="w-9 h-9 rounded-lg bg-[#4ade80]/10 flex items-center justify-center">
                                <svg class="w-4 h-4 text-[#4ade80]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[8px] font-black text-slate-500 uppercase tracking-widest leading-none mb-1">
                                    Hotline</p>
                                @if ($contact_details)
                                    <p class="text-[11px] font-black text-white">{{ $contact_details->phone_no }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hidden lg:flex justify-end">
                    <div class="w-full max-w-sm bg-gradient-to-br from-white/10 to-transparent p-px rounded-[2rem]">
                        <div class="bg-[#0a0f1e]/80 backdrop-blur-3xl rounded-[2rem] p-7 relative overflow-hidden">
                            <div class="relative z-10 space-y-4">
                                <div class="space-y-1">
                                    <h3 class="text-white font-black uppercase italic tracking-widest text-base">Support
                                        Status</h3>
                                    <div class="flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#4ade80] animate-pulse"></span>
                                        <span class="text-[9px] text-[#4ade80] font-bold uppercase tracking-[0.2em]">Systems
                                            Nominal</span>
                                    </div>
                                </div>
                                <p class="text-slate-400 text-xs leading-relaxed">Response time: <span
                                        class="text-white font-bold italic">45 mins</span></p>

                                <div class="pt-4 border-t border-white/10">
                                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2">
                                        Headquarters</p>
                                    @if ($contact_details)
                                        <p class="text-xs text-white font-medium">{{ $contact_details->address }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="absolute bottom-6 left-1/2 -translate-x-1/2 hidden md:flex flex-col items-center gap-1 animate-bounce opacity-30 pointer-events-none">
            <span class="text-[8px] font-black uppercase tracking-[0.3em] text-white/60">Scroll</span>
            <div class="w-4 h-6 border-2 border-white/10 rounded-full flex justify-center p-0.5">
                <div class="w-1 h-1.5 bg-[#4ade80] rounded-full"></div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-[#f8fafc] relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>
        <div class="absolute inset-0 z-0 opacity-40">
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-[#4ade80]/5 blur-[120px] rounded-full"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

                <div
                    class="group p-8 rounded-[2.5rem] bg-white border border-slate-200 hover:border-[#4ade80] hover:shadow-2xl hover:shadow-[#4ade80]/10 transition-all duration-500">
                    <div
                        class="w-14 h-14 bg-[#4ade80]/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-[#4ade80] transition-all duration-500 group-hover:rotate-6">
                        <svg class="w-6 h-6 text-[#4ade80] group-hover:text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-slate-900 font-black uppercase italic tracking-wider mb-2">Email Us</h3>
                    @if ($contact_details)
                        <p class="text-[#16a34a] text-sm font-bold mb-4">{{ $contact_details->email }}</p>
                    @endif
                    <p class="text-slate-500 text-xs font-semibold leading-relaxed">Encrypted support line. We respond
                        within 24 hours.</p>
                </div>

                <div
                    class="group p-8 rounded-[2.5rem] bg-white border border-slate-200 hover:border-blue-500 hover:shadow-2xl hover:shadow-blue-500/10 transition-all duration-500">
                    <div
                        class="w-14 h-14 bg-blue-50/80 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-600 transition-all duration-500 group-hover:rotate-6">
                        <svg class="w-6 h-6 text-blue-600 group-hover:text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <h3 class="text-slate-900 font-black uppercase italic tracking-wider mb-2">Call Us</h3>
                    @if ($contact_details)
                        <p class="text-slate-900 text-sm font-bold mb-4">{{ $contact_details->phone_no }}</p>
                    @endif

                    @if ($contact_details)
                        <p class="text-slate-500 text-xs font-semibold leading-relaxed">
                            {{ $contact_details->working_hours }}</p>
                    @endif
                </div>

                <div
                    class="group p-8 rounded-[2.5rem] bg-white border border-slate-200 hover:border-[#4ade80] hover:shadow-2xl hover:shadow-[#4ade80]/10 transition-all duration-500">
                    <div
                        class="w-14 h-14 bg-[#4ade80]/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-[#4ade80] transition-all duration-500 group-hover:rotate-6">
                        <svg class="w-6 h-6 text-[#4ade80] group-hover:text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        </svg>
                    </div>
                    <h3 class="text-slate-900 font-black uppercase italic tracking-wider mb-2">Visit Us</h3>
                    @if ($contact_details)
                        <p class="text-slate-900 text-sm font-bold mb-4">{{ $contact_details->address }}</p>
                    @endif
                    <p class="text-slate-500 text-xs font-semibold leading-relaxed">Our headquarters in the heart of Silicon
                        Valley.</p>
                </div>

                <div
                    class="group p-8 rounded-[2.5rem] bg-white border border-slate-200 hover:border-slate-900 hover:shadow-2xl hover:shadow-slate-900/10 transition-all duration-500">
                    <div
                        class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-slate-900 transition-all duration-500 group-hover:rotate-6">
                        <svg class="w-6 h-6 text-slate-600 group-hover:text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-slate-900 font-black uppercase italic tracking-wider mb-2">Business Hours</h3>
                    @if ($contact_details)
                        <p class="text-slate-900 text-sm font-bold mb-4">{{ $contact_details->working_hours }}</p>
                    @endif
                    <p class="text-slate-500 text-xs font-semibold leading-relaxed">Weekend support available via email.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <section class="py-24 bg-[#f1f5f9] relative">
        <div class="max-w-7xl mx-auto px-6">

            <div class="grid lg:grid-cols-12 gap-8 items-start">

                <div
                    class="lg:col-span-8 bg-white border border-slate-200 rounded-[2.5rem] shadow-xl shadow-slate-200/60 overflow-hidden">
                    <div class="p-10 pb-0">
                        <div class="flex items-center gap-2 mb-4">
                            <span
                                class="px-3 py-1 bg-[#4ade80]/10 text-[#16a34a] text-[10px] font-black uppercase tracking-widest rounded-full">Secure
                                Transmission</span>
                        </div>
                        <h2 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter mb-2">Send us a
                            <span class="text-[#16a34a]">Message</span>
                        </h2>

                        <div id="successMessage" class="hidden bg-green-100 text-green-700 p-4 rounded-lg mb-4"></div>

                        <div id="errorMessage" class="hidden bg-red-100 text-red-700 p-4 rounded-lg"></div>
                    </div>


                    {{-- <form action="#" class="p-10 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Full
                                    Name *</label>
                                <input type="text" placeholder="Your full name"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl py-4 px-4 text-slate-900 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email
                                    Address *</label>
                                <input type="email" placeholder="name@domain.com"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl py-4 px-4 text-slate-900 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Phone
                                    Number</label>
                                <input type="tel" placeholder="+977"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl py-4 px-4 text-slate-900 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all">
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Category
                                    *</label>
                                <select
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl py-4 px-4 text-slate-900 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all cursor-pointer">
                                    <option>General Support</option>
                                    <option>Sales Team</option>
                                    <option>Technical Support</option>
                                    <option>Partnership</option>
                                </select>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Your
                                Message *</label>
                            <textarea rows="4" placeholder="How can we help you?"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-4 px-4 text-slate-900 focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all"></textarea>
                        </div>
                        <button
                            class="w-full py-5 bg-slate-900 text-white rounded-xl font-black uppercase italic tracking-widest text-sm hover:bg-[#16a34a] transition-all flex items-center justify-center gap-3 shadow-lg">
                            Send Transmission
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </form> --}}
                    <form class="p-10 space-y-2" id="contactForm">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                                    Full Name *
                                </label>
                                <input type="text" name="full_name" value="{{ old('full_name') }}"
                                    placeholder="Your full name"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl py-4 px-4">
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                                    Email Address *
                                </label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    placeholder="name@domain.com"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl py-4 px-4">
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                                    Phone Number
                                </label>
                                <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+977"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl py-4 px-4">
                            </div>

                            <div class="space-y-2" x-data="{
                                open: false,
                                selected: '',
                                label: 'Select Category'
                            }">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 block">
                                    Category *
                                </label>

                                <div class="w-full relative">
                                    <input type="hidden" name="category" :value="selected">

                                    <button type="button" @click="open = !open" @click.away="open = false"
                                        class="w-full flex items-center justify-between bg-slate-100/80 lg:bg-slate-100/50 border-none rounded-2xl py-4 lg:py-6 px-8 text-sm font-black text-slate-900 cursor-pointer focus:ring-2 focus:ring-[#4ade80]/20 uppercase tracking-tight transition-all"
                                        :class="open ? 'ring-2 ring-[#4ade80]/20 bg-white' : ''">

                                        <span x-text="label || 'Select Category'">Select Category</span>

                                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200"
                                            :class="open ? 'rotate-180 text-[#4ade80]' : ''" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                            <path d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>

                                    <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        class="absolute z-50 w-full mt-2 bg-white border border-slate-100 rounded-2xl shadow-xl overflow-hidden"
                                        style="display: none;">

                                        <div class="py-2">
                                            <button type="button"
                                                @click="selected = 'General Support'; label = 'General Support'; open = false"
                                                class="w-full text-left px-8 py-3 text-sm font-bold transition-colors uppercase tracking-tight"
                                                :class="selected === 'General Support' ? 'text-[#4ade80] bg-slate-50' :
                                                    'text-slate-900 hover:bg-slate-50 hover:text-[#4ade80]'">
                                                General Support
                                            </button>

                                            <button type="button"
                                                @click="selected = 'Sales Team'; label = 'Sales Team'; open = false"
                                                class="w-full text-left px-8 py-3 text-sm font-bold transition-colors uppercase tracking-tight"
                                                :class="selected === 'Sales Team' ? 'text-[#4ade80] bg-slate-50' :
                                                    'text-slate-900 hover:bg-slate-50 hover:text-[#4ade80]'">
                                                Sales Team
                                            </button>

                                            <button type="button"
                                                @click="selected = 'Technical Support'; label = 'Technical Support'; open = false"
                                                class="w-full text-left px-8 py-3 text-sm font-bold transition-colors uppercase tracking-tight"
                                                :class="selected === 'Technical Support' ? 'text-[#4ade80] bg-slate-50' :
                                                    'text-slate-900 hover:bg-slate-50 hover:text-[#4ade80]'">
                                                Technical Support
                                            </button>

                                            <button type="button"
                                                @click="selected = 'Partnership'; label = 'Partnership'; open = false"
                                                class="w-full text-left px-8 py-3 text-sm font-bold transition-colors uppercase tracking-tight"
                                                :class="selected === 'Partnership' ? 'text-[#4ade80] bg-slate-50' :
                                                    'text-slate-900 hover:bg-slate-50 hover:text-[#4ade80]'">
                                                Partnership
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                                Your Message *
                            </label>
                            <textarea name="message" rows="4" class="w-full bg-slate-50 border border-slate-200 rounded-xl py-4 px-4"
                                placeholder="How can we help you?">{{ old('message') }}</textarea>
                        </div>

                        <button
                            class="w-full py-5 bg-slate-900 text-white rounded-xl font-black uppercase italic tracking-widest text-sm">
                            Send Transmission
                        </button>
                    </form>
                </div>

                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white border border-slate-200 rounded-[2.5rem] p-10 shadow-xl shadow-slate-200/60">
                        <div class="flex items-center gap-3 mb-8">
                            <span class="w-1.5 h-8 bg-[#16a34a] rounded-full"></span>
                            <h3 class="text-2xl font-black text-slate-900 uppercase italic tracking-tight">Contact
                                Departments</h3>
                        </div>

                        <div class="space-y-8">
                            <div class="group">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">General
                                    Support</p>
                                <a href="mailto:support@bijulicar.com"
                                    class="text-lg font-bold text-[#16a34a] hover:text-slate-900 transition-colors">support@bijulicar.com</a>
                                <p class="text-xs text-slate-500 font-medium mt-1">General questions and account support
                                </p>
                            </div>

                            <div class="group">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Sales Team
                                </p>
                                <a href="mailto:sales@bijulicar.com"
                                    class="text-lg font-bold text-[#16a34a] hover:text-slate-900 transition-colors">sales@bijulicar.com</a>
                                <p class="text-xs text-slate-500 font-medium mt-1">Vehicle listings and marketplace
                                    inquiries</p>
                            </div>

                            <div class="group">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Technical
                                    Support</p>
                                <a href="mailto:tech@bijulicar.com"
                                    class="text-lg font-bold text-[#16a34a] hover:text-slate-900 transition-colors">tech@bijulicar.com</a>
                                <p class="text-xs text-slate-500 font-medium mt-1">Website issues and technical problems
                                </p>
                            </div>

                            <div class="group">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">
                                    Partnership</p>
                                <a href="mailto:partners@bijulicar.com"
                                    class="text-lg font-bold text-[#16a34a] hover:text-slate-900 transition-colors">partners@bijulicar.com</a>
                                <p class="text-xs text-slate-500 font-medium mt-1">Business partnerships and collaborations
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="py-24 bg-[#1e293b] relative overflow-hidden">
        <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-[#4ade80]/5 blur-[120px] rounded-full"></div>

        <div class="max-w-4xl mx-auto px-6 relative z-10">
            <div class="text-center mb-16">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 backdrop-blur-md mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#4ade80]"></span>
                    <span class="text-[10px] uppercase tracking-[0.3em] text-slate-300 font-bold">Support Center</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-black text-white uppercase italic tracking-tighter">
                    Frequently Asked <span class="text-slate-500">Questions</span>
                </h2>
            </div>

            <div class="space-y-4">

                <div
                    class="group rounded-2xl bg-slate-800/40 border border-slate-700 hover:border-[#4ade80]/30 transition-all duration-300">
                    <details class="p-6">
                        <summary class="flex items-center justify-between cursor-pointer list-none">
                            <span
                                class="text-lg font-bold text-white tracking-tight group-hover:text-[#4ade80] transition-colors">
                                How do I list my electric vehicle?
                            </span>
                            <div
                                class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-[#4ade80]/10 transition-colors">
                                <svg class="w-5 h-5 text-slate-400 group-hover:text-[#4ade80]" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </summary>
                        <div class="mt-4 pt-4 border-t border-white/5">
                            <p class="text-slate-400 leading-relaxed font-medium">
                                Simply click the <span class="text-white italic">“Sell”</span> button in the header, create
                                an account if you haven't already, and follow our streamlined step-by-step listing process.
                            </p>
                        </div>
                    </details>
                </div>

                <div
                    class="group rounded-2xl bg-slate-800/40 border border-slate-700 hover:border-[#4ade80]/30 transition-all duration-300">
                    <details class="p-6">
                        <summary class="flex items-center justify-between cursor-pointer list-none">
                            <span
                                class="text-lg font-bold text-white tracking-tight group-hover:text-[#4ade80] transition-colors">
                                Is there a fee to list my vehicle?
                            </span>
                            <div
                                class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-[#4ade80]/10 transition-colors">
                                <svg class="w-5 h-5 text-slate-400 group-hover:text-[#4ade80]" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </summary>
                        <div class="mt-4 pt-4 border-t border-white/5">
                            <p class="text-slate-400 leading-relaxed font-medium">
                                Basic listings are <span class="text-[#4ade80] font-bold">free</span>. We offer premium
                                listing options with enhanced visibility and top-of-page placement for a small professional
                                fee.
                            </p>
                        </div>
                    </details>
                </div>

                <div
                    class="group rounded-2xl bg-slate-800/40 border border-slate-700 hover:border-[#4ade80]/30 transition-all duration-300">
                    <details class="p-6">
                        <summary class="flex items-center justify-between cursor-pointer list-none">
                            <span
                                class="text-lg font-bold text-white tracking-tight group-hover:text-[#4ade80] transition-colors">
                                How do I contact a seller?
                            </span>
                            <div
                                class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-[#4ade80]/10 transition-colors">
                                <svg class="w-5 h-5 text-slate-400 group-hover:text-[#4ade80]" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </summary>
                        <div class="mt-4 pt-4 border-t border-white/5">
                            <p class="text-slate-400 leading-relaxed font-medium">
                                Click on any vehicle listing and use the <span class="text-white">secure contact
                                    form</span> or direct phone number provided by the seller in the unit overview.
                            </p>
                        </div>
                    </details>
                </div>

                <div
                    class="group rounded-2xl bg-slate-800/40 border border-slate-700 hover:border-[#4ade80]/30 transition-all duration-300">
                    <details class="p-6">
                        <summary class="flex items-center justify-between cursor-pointer list-none">
                            <span
                                class="text-lg font-bold text-white tracking-tight group-hover:text-[#4ade80] transition-colors">
                                Can I list non-electric vehicles?
                            </span>
                            <div
                                class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:bg-[#4ade80]/10 transition-colors">
                                <svg class="w-5 h-5 text-slate-400 group-hover:text-[#4ade80]" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </summary>
                        <div class="mt-4 pt-4 border-t border-white/5">
                            <p class="text-slate-400 leading-relaxed font-medium">
                                Yes! Our ecosystem is built for the future but respects the present. We accept <span
                                    class="text-white">electric vehicles, hybrids, and traditional ICE</span> vehicles on
                                our platform.
                            </p>
                        </div>
                    </details>
                </div>

            </div>

            <div class="mt-16 text-center">
                <p class="text-slate-500 text-sm font-medium">
                    Still have questions?
                    <a href="#"
                        class="text-[#4ade80] border-b border-[#4ade80]/30 hover:border-[#4ade80] transition-all ml-1">Speak
                        to a Specialist</a>
                </p>
            </div>
        </div>
    </section>

    <section class="py-24 bg-[#fafcfd] relative">
        <div class="max-w-7xl mx-auto px-6">

            <div
                class="bg-white border border-slate-200 rounded-[2.5rem] p-4 shadow-xl shadow-slate-200/60 relative overflow-hidden">

                <div class="absolute top-8 left-8 z-[1] hidden md:block">
                    <div
                        class="bg-slate-900/90 backdrop-blur-md border border-white/10 p-5 rounded-2xl shadow-2xl max-w-xs">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="w-2 h-2 rounded-full bg-[#4ade80] animate-pulse"></span>
                            <p class="text-[10px] font-black text-[#4ade80] uppercase tracking-widest">Global Headquarters
                            </p>
                        </div>
                        <h3 class="text-white font-bold text-lg leading-tight mb-2">BijuliCar Plaza, Naxal</h3>
                        <p class="text-slate-400 text-xs font-medium leading-relaxed">
                            Kathmandu 44600, Nepal. <br>
                            Level 4, Tech Hub West.
                        </p>
                        <div class="mt-4 pt-4 border-t border-white/10 flex items-center justify-between">
                            <a href="https://maps.google.com" target="_blank"
                                class="text-[#4ade80] text-[10px] font-black uppercase tracking-widest hover:text-white transition-colors">
                                Open in Google Maps →
                            </a>
                        </div>
                    </div>
                </div>

                <div id="contactMap"
                    class="w-full h-[500px] rounded-[2rem] z-0 grayscale-[0.2] hover:grayscale-0 transition-all duration-700">
                </div>

            </div>
        </div>
    </section>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Map (Coordinates for Kathmandu/Naxal as an example)
            var map = L.map('contactMap', {
                scrollWheelZoom: false, // Prevents accidental zoom while scrolling the page
                zoomControl: false // We'll move zoom control to a cleaner spot
            }).setView([27.7149, 85.3298], 15);

            // Professional Clean Map Tiles (CartoDB Positron)
            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // Custom Green Brand Marker
            var customIcon = L.divIcon({
                className: 'custom-div-icon',
                html: `<div class="w-10 h-10 bg-slate-900 rounded-2xl border-2 border-[#4ade80] flex items-center justify-center shadow-2xl shadow-[#4ade80]/40 transform -translate-x-1/2 -translate-y-1/2">
                    <svg class="w-5 h-5 text-[#4ade80]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    </svg>
                   </div>`,
                iconSize: [40, 40],
                iconAnchor: [20, 20]
            });

            L.marker([27.7149, 85.3298], {
                icon: customIcon
            }).addTo(map);

            // Add Zoom Control back to a professional position
            L.control.zoom({
                position: 'bottomright'
            }).addTo(map);
        });
    </script>

    {{-- script for ajax form submission --}}
    <script>
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let form = this;
            let formData = new FormData(form);

            let successBox = document.getElementById('successMessage');
            let errorBox = document.getElementById('errorMessage');

            // reset messages
            successBox.classList.add('hidden');
            errorBox.classList.add('hidden');
            errorBox.innerHTML = '';

            fetch("{{ route('contact.store') }}", {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {

                    if (data.success) {
                        successBox.innerHTML = data.message;
                        successBox.classList.remove('hidden');

                        form.reset();
                    }

                    if (data.errors) {
                        let errors = Object.values(data.errors).flat();

                        errors.forEach(err => {
                            errorBox.innerHTML += `<div>${err}</div>`;
                        });

                        errorBox.classList.remove('hidden');
                    }

                })
                .catch(error => {
                    errorBox.innerHTML = "Something went wrong.";
                    errorBox.classList.remove('hidden');
                });
        });
    </script>

    <style>
        /* Styling for the custom marker to ensure it centers correctly */
        .leaflet-div-icon {
            background: transparent !important;
            border: none !important;
        }
    </style>
@endsection
