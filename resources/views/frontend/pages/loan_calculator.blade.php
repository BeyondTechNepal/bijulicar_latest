@extends('frontend.app')

<title>Loan Calculator | BijuliCar</title>

@section('content')
    {{-- HERO SECTION: The "Hook" --}}
    <section class="relative bg-[#050a15] pt-[110px] pb-10 lg:pt-32 lg:pb-5 overflow-hidden border-b border-white/5">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1563720223185-11003d516935?auto=format&fit=crop&q=80&w=2071"
                class="w-full h-full object-cover opacity-90 mix-blend-luminosity scale-110 blur-[2px]"
                alt="Finance Background">
            <div class="absolute inset-0 bg-gradient-to-r from-[#050a15] via-[#050a15]/80 to-transparent"></div>
            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_left,_rgba(74,222,128,0.1)_0%,_transparent_40%)]">
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-3">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 backdrop-blur-md">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#4ade80] animate-pulse"></span>
                        <span class="text-[10px] uppercase tracking-[0.3em] text-slate-300 font-bold">Finance
                            Intelligence</span>
                    </div>

                    <h1 class="text-4xl md:text-6xl font-black text-white uppercase italic tracking-tighter leading-[0.9]">
                        EMI <span class="text-[#4ade80]">Architect</span>
                    </h1>

                    <p class="text-slate-400 text-base md:text-lg max-w-lg font-medium leading-relaxed">
                        Calculate your path to ownership with precision. Adjust terms, explore rates, and visualize your
                        <span class="text-white font-bold italic underline decoration-[#4ade80]/30">monthly
                            investment</span> in real-time.
                    </p>

                    <div class="flex items-center gap-8 pt-4 border-t border-white/5 text-left">
                        <div>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Interest Rates
                                From</p>
                            <p class="text-xl font-black text-white">8.5<span class="text-[#4ade80]">%</span></p>
                        </div>
                        <div class="w-px h-8 bg-white/10"></div>
                        <div>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Max Tenure</p>
                            <p class="text-xl font-black text-white">84 <span
                                    class="text-slate-500 text-sm italic font-medium">Months</span></p>
                        </div>
                    </div>
                </div>

                {{-- Visual Card Side --}}
                <div class="hidden lg:block">
                    <div class="relative group">
                        <div
                            class="absolute -inset-1 bg-gradient-to-r from-[#4ade80]/20 to-blue-500/20 rounded-3xl blur opacity-75 group-hover:opacity-100 transition duration-1000">
                        </div>
                        <div class="relative bg-white/5 border border-white/10 backdrop-blur-2xl rounded-3xl p-8">
                            <div class="flex justify-between items-start mb-8">
                                <div>
                                    <h3 class="text-white font-black uppercase italic text-sm tracking-wider">Algorithmic
                                        Precision</h3>
                                    <p class="text-slate-500 text-[11px] font-bold uppercase tracking-tight">Real-time
                                        Amortization Engine</p>
                                </div>
                                <div class="p-2 bg-[#4ade80]/10 rounded-xl">
                                    <svg class="w-5 h-5 text-[#4ade80]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-slate-400">Monthly Commitment</span>
                                    <span id="heroEmi" class="text-white font-black">NPR --,---</span>
                                </div>
                                <div class="w-full bg-white/5 h-1.5 rounded-full overflow-hidden">
                                    <div id="progressBar"
                                        class="bg-[#4ade80] h-full w-[0%] transition-all duration-700 shadow-[0_0_10px_rgba(74,222,128,0.5)]">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="hidden md:flex flex-col items-center gap-2 animate-bounce opacity-40 hover:opacity-100 transition-opacity pointer-events-none">
                <span class="text-[9px] font-black uppercase tracking-[0.4em] text-white/60">Scroll Down</span>
                <div class="w-5 h-8 border-2 border-white/20 rounded-full flex justify-center p-1">
                    <div class="w-1 h-2 bg-[#4ade80] rounded-full"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- CALCULATOR SECTION: The "Utility" --}}
    <section class="py-14 bg-[#f8fafc]">
        <div class="max-w-7xl mx-auto px-6">
            <div class="mb-12">
                <h2 class="text-4xl font-black text-slate-900 uppercase italic tracking-tighter">
                    Loan <span class="text-[#16a34a]">Intelligence</span>
                </h2>
                <p class="text-slate-500 font-medium mt-2">Configure your financing parameters for instant feedback.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                {{-- Input Panel --}}
                <div class="lg:col-span-7">
                    <div class="bg-white p-10 rounded-[2.5rem] border border-slate-200 shadow-sm h-full">
                        <h3
                            class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-8 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[#16a34a]"></span> Parameters Configuration
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Asset
                                    Value (NRs)</label>
                                <div class="relative group">
                                    <span
                                        class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs group-focus-within:text-green-600 transition-colors">NRs</span>
                                    <input type="number" id="carPrice" value="4500000" oninput="calculateLoan()"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl py-4 pl-14 pr-4 text-slate-900 font-bold focus:bg-white focus:ring-4 focus:ring-green-50 focus:border-[#16a34a] outline-none transition-all">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Down
                                    Payment (NRs)</label>
                                <div class="relative group">
                                    <span
                                        class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs group-focus-within:text-green-600 transition-colors">NRs</span>
                                    <input type="number" id="downPayment" value="900000" oninput="calculateLoan()"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl py-4 pl-14 pr-4 text-slate-900 font-bold focus:bg-white focus:ring-4 focus:ring-green-50 focus:border-[#16a34a] outline-none transition-all">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Interest
                                    APR (%)</label>
                                <input type="number" id="interestRate" value="11.5" oninput="calculateLoan()"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl py-4 px-4 text-slate-900 font-bold focus:bg-white focus:ring-4 focus:ring-green-50 focus:border-[#16a34a] outline-none transition-all">
                            </div>

                            <div class="space-y-2" x-data="{ 
                                open: false, 
                                selected: '5', 
                                label: '5 Years (60 Months)',
                                update(val, text) {
                                    this.selected = val;
                                    this.label = text;
                                    this.open = false;
                                    // Trigger your existing calculation function
                                    this.$nextTick(() => { calculateLoan(); });
                                }
                            }">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                        <div class="space-y-2">
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Tenure (Years)</label>
                                        <input type="number" id="tenure" value="5" oninput="calculateLoan()"
                                            class="w-full bg-slate-50 border border-slate-200 rounded-xl py-4 px-4 text-slate-900 font-bold focus:bg-white focus:ring-4 focus:ring-green-50 focus:border-[#16a34a] outline-none transition-all">
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Start Date</label>
                                        <input type="month" id="startDate" oninput="calculateLoan()"
                                            class="w-full bg-slate-50 border border-slate-200 rounded-xl py-4 px-4 text-slate-900 font-bold focus:bg-white focus:ring-4 focus:ring-green-50 focus:border-[#16a34a] outline-none transition-all">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center shadow-sm">
                                    <span class="text-xs">💡</span>
                                </div>
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-tight">Pro Tip:
                                    Increasing your down payment by 10% can significantly reduce your total interest paid.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Result Panel --}}
                <div class="lg:col-span-5">
                    <div id="loanResult"
                        class="bg-slate-900 rounded-[2.5rem] p-10 text-white relative overflow-hidden shadow-2xl flex flex-col justify-center transition-all duration-500 border border-slate-800">
                        <div class="relative z-10">
                            <span
                                class="text-[10px] font-black text-[#4ade80] uppercase tracking-[0.4em] mb-4 block">Monthly
                                Investment</span>

                            <div class="flex items-baseline gap-2 mb-2">
                                <span class="text-xl font-bold opacity-30 text-slate-400">NRs</span>
                                <span id="monthlyEMI"
                                    class="text-5xl font-black italic tracking-tighter leading-none text-white animate-in fade-in zoom-in duration-500">----</span>
                            </div>

                            <p id="emiDetails" class="text-slate-500 text-[10px] font-bold uppercase tracking-[0.2em]">
                                Awaiting input...</p>

                            <div class="mt-10 space-y-6 pt-8 border-t border-white/5">
                                <div class="flex justify-between items-center group">
                                    <span
                                        class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] group-hover:text-slate-300 transition-colors">Principal
                                        Amount</span>
                                    <span id="loanAmount" class="text-sm font-black text-slate-400 italic">----</span>
                                </div>
                                <div class="flex justify-between items-center group">
                                    <span
                                        class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] group-hover:text-slate-300 transition-colors">Interest
                                        Payload</span>
                                    <span id="totalInterest" class="text-sm font-black text-slate-400 italic">----</span>
                                </div>
                                <div class="flex justify-between items-center group">
                                    <span
                                        class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] group-hover:text-slate-300 transition-colors">Total
                                        Repayment</span>
                                    <span id="totalRepayment" class="text-sm font-black text-[#4ade80] italic">----</span>
                                </div>
                            </div>
                        </div>

                        <div id="glowEffect"
                            class="absolute -bottom-20 -right-20 w-64 h-64 bg-green-500/10 rounded-full blur-[100px] transition-all duration-1000">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 mt-12">
            <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-8 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="font-black text-slate-900 uppercase tracking-tighter">Amortization Schedule</h3>
                    <span class="text-[10px] bg-slate-100 text-slate-500 px-3 py-1 rounded-full font-bold">FULL REPORT</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                <th class="px-8 py-4">Installment</th>
                                <th class="px-8 py-4">Date</th>
                                <th class="px-8 py-4">Principal</th>
                                <th class="px-8 py-4">Interest</th>
                                <th class="px-8 py-4">Total</th>
                                <th class="px-8 py-4">Balance</th>
                            </tr>
                        </thead>
                        <tbody id="amortizationBody" class="text-sm font-medium text-slate-600">
                            </tbody>
                    </table>
                </div>
            </div>

            <div class="flex justify-center mt-8">
                <button onclick="exportToExcel()" id="downloadButton"
                    class="mt-6 w-full py-4 bg-slate-900 border border-white/10 text-white font-black uppercase tracking-widest rounded-xl hover:bg-[#16a34a] transition-all duration-300 flex items-center justify-center gap-3 group">
                    <svg class="w-5 h-5 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Export to Excel (.xlsx)
                </button>
            </div>
        </div>
    </section>

    {{-- FINANCE ENCYCLOPEDIA SECTION --}}
    <section class="pb-24 bg-[#f8fafc]">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

                {{-- Block 1: The Principal --}}
                <div
                    class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m.5-1c.28 0 .551-.024.811-.07M12 16c-1.11 0-2.08-.402-2.599-1M12 16v1m-1.5-1c-.28 0-.551-.024-.811-.07" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-3">The Principal</h3>
                    <p class="text-slate-500 text-sm leading-relaxed font-medium">
                        In car financing, the <span class="text-slate-900 font-bold">Principal</span> is the actual amount
                        of money you borrow from the lender. It is calculated by taking the vehicle's "On-Road" price and
                        subtracting your Down Payment. Every EMI you pay goes toward reducing this balance and covering the
                        interest.
                    </p>
                </div>

                {{-- Block 2: Interest Rates (APR) --}}
                <div
                    class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-3">Annual Percentage Rate
                    </h3>
                    <p class="text-slate-500 text-sm leading-relaxed font-medium">
                        The <span class="text-slate-900 font-bold">APR</span> represents the annual cost of your loan,
                        including interest and certain fees. In Nepal, vehicle loan rates are often "Floating," meaning they
                        can adjust based on the bank's base rate. A lower APR directly results in a lower Total Repayment
                        amount.
                    </p>
                </div>

                {{-- Block 3: Loan Tenure --}}
                <div
                    class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest mb-3">Tenure Strategy</h3>
                    <p class="text-slate-500 text-sm leading-relaxed font-medium">
                        Tenure is the duration of your loan. While a <span class="text-slate-900 font-bold">longer
                            tenure</span> (e.g., 7-8 years) makes your monthly EMI more affordable, it increases the <span
                            class="text-slate-900 font-bold">Total Interest</span> paid over time. Balancing your monthly
                        budget with the total cost of ownership is key.
                    </p>
                </div>

            </div>

            {{-- Amortization Explanation --}}
            <div class="mt-12 bg-slate-900 rounded-[3rem] p-8 lg:p-12 overflow-hidden relative">
                <div class="relative z-10 grid lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-3xl font-black text-white uppercase italic tracking-tighter mb-6 leading-tight">
                            Understanding <br><span class="text-[#4ade80]">Amortization</span>
                        </h2>
                        <div class="space-y-4 text-slate-400 text-sm font-medium leading-relaxed">
                            <p>
                                Amortization is the process of spreading out a loan into a series of fixed payments. At the
                                beginning of your loan term, a larger portion of your EMI goes toward paying off the <span
                                    class="text-white">Interest</span>.
                            </p>
                            <p>
                                As the principal balance decreases over time, the interest portion of your payment shrinks,
                                and more of your money goes toward the <span class="text-white">Principal</span>. This is
                                why paying off a loan early can save you a significant amount in interest charges.
                            </p>
                            <div class="pt-4 flex gap-4">
                                <div class="px-4 py-2 bg-white/5 border border-white/10 rounded-xl">
                                    <span
                                        class="block text-[10px] text-slate-500 uppercase font-black tracking-widest">Calculated
                                        using</span>
                                    <span class="text-white font-mono text-xs italic">Reducing Balance Method</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Visual Aid placeholder --}}
                    <div class="relative">
                        <div
                            class="aspect-video bg-white/5 backdrop-blur-sm rounded-3xl border border-white/10 p-8 flex flex-col justify-between overflow-hidden group">

                            <div class="flex items-end justify-between gap-1 h-32 mb-4">
                                <div
                                    class="w-full bg-white/5 rounded-t-sm h-[90%] relative group-hover:bg-[#4ade80]/20 transition-all duration-500">
                                </div>
                                <div class="w-full bg-white/5 rounded-t-sm h-[85%]"></div>
                                <div class="w-full bg-white/5 rounded-t-sm h-[75%]"></div>
                                <div class="w-full bg-white/5 rounded-t-sm h-[60%]"></div>
                                <div class="w-full bg-white/10 rounded-t-sm h-[45%] border-t border-[#4ade80]/50"></div>
                                <div class="w-full bg-white/5 rounded-t-sm h-[35%]"></div>
                                <div class="w-full bg-white/5 rounded-t-sm h-[25%]"></div>
                                <div
                                    class="w-full bg-[#4ade80]/40 rounded-t-sm h-[15%] shadow-[0_0_15px_rgba(74,222,128,0.3)]">
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex justify-between items-end">
                                    <div>
                                        <p class="text-[10px] font-black text-[#4ade80] uppercase tracking-widest">
                                            Efficiency Trend</p>
                                        <p class="text-white text-lg font-bold italic tracking-tighter leading-none">+12.5%
                                            <span class="text-slate-500 text-[10px] not-italic font-medium">Equity
                                                Gain/Year</span>
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <svg class="w-8 h-8 text-white/20 ml-auto" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                                        </svg>
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-white/10 flex justify-between">
                                    <div class="flex gap-2 items-center">
                                        <div class="w-2 h-2 rounded-full bg-[#4ade80]"></div>
                                        <span
                                            class="text-[8px] font-black text-slate-400 uppercase tracking-tighter">Principal</span>
                                    </div>
                                    <div class="flex gap-2 items-center">
                                        <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                        <span
                                            class="text-[8px] font-black text-slate-400 uppercase tracking-tighter">Interest
                                            Paid</span>
                                    </div>
                                </div>
                            </div>

                            <div class="absolute inset-0 pointer-events-none opacity-10">
                                <div class="h-full w-full"
                                    style="background-image: linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg, #fff 1px, transparent 1px); background-size: 20px 20px;">
                                </div>
                            </div>
                        </div>

                        <div
                            class="absolute -top-12 -right-12 w-64 h-64 bg-[#4ade80]/10 rounded-full blur-[100px] animate-pulse">
                        </div>
                        <div class="absolute -bottom-12 -left-12 w-48 h-48 bg-blue-500/10 rounded-full blur-[80px]"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Run once on load to populate initial state
        document.addEventListener('DOMContentLoaded', () => {
            // Set default date to today
            const now = new Date();
            document.getElementById('startDate').value = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`;
            calculateLoan();
        });

        function calculateLoan() {
            const carPrice = parseFloat(document.getElementById('carPrice').value) || 0;
            const downPayment = parseFloat(document.getElementById('downPayment').value) || 0;
            const rawInterest = parseFloat(document.getElementById('interestRate').value) || 0;
            const tenureYears = parseFloat(document.getElementById('tenure').value) || 0;
            const startDateVal = document.getElementById('startDate').value;

            const principal = carPrice - downPayment;
            const tenureMonths = Math.round(tenureYears * 12);
            const monthlyRate = rawInterest / 100 / 12;

            if (principal > 0 && monthlyRate > 0 && tenureMonths > 0) {
                const emi = (principal * monthlyRate * Math.pow(1 + monthlyRate, tenureMonths)) /
                            (Math.pow(1 + monthlyRate, tenureMonths) - 1);

                const totalRepayment = emi * tenureMonths;
                const totalInterest = totalRepayment - principal;
                const downPaymentPercent = (downPayment / carPrice) * 100;

                updateUI(emi, principal, totalInterest, totalRepayment, tenureMonths, rawInterest, downPaymentPercent);
                generateAmortizationTable(principal, monthlyRate, emi, tenureMonths, startDateVal);
            } else {
                resetUI();
            }
        }

        function generateAmortizationTable(principal, monthlyRate, emi, months, startMonthStr) {
            const tbody = document.getElementById('amortizationBody');
            tbody.innerHTML = '';
            let remainingBalance = principal;
            let currentDate = startMonthStr ? new Date(startMonthStr + '-01') : new Date();

            const fmt = (num) => Math.round(num).toLocaleString();

            for (let i = 1; i <= months; i++) {
                const interestForMonth = remainingBalance * monthlyRate;
                const principalForMonth = emi - interestForMonth;
                remainingBalance -= principalForMonth;

                const row = `
                    <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                        <td class="px-8 py-4 font-black text-slate-900">#${i}</td>
                        <td class="px-8 py-4 text-slate-500 uppercase text-[11px] font-bold">
                            ${currentDate.toLocaleString('default', { month: 'short', year: 'numeric' })}
                        </td>
                        <td class="px-8 py-4 text-slate-700 font-bold">NRs ${fmt(principalForMonth)}</td>
                        <td class="px-8 py-4 text-red-400 font-bold">NRs ${fmt(interestForMonth)}</td>
                        <td class="px-8 py-4 text-green-600 font-bold">NRs ${fmt(emi)}</td>
                        <td class="px-8 py-4 text-slate-400 font-mono text-xs italic">NRs ${fmt(Math.max(0, remainingBalance))}</td>
                    </tr>
                `;
                tbody.innerHTML += row;
                currentDate.setMonth(currentDate.getMonth() + 1);
            }
        }

        function updateUI(emi, principal, interest, total, months, rate) {
            const fmt = (num) => Math.round(num).toLocaleString();

            // Updates the big Result Card
            document.getElementById('monthlyEMI').innerText = fmt(emi);
            document.getElementById('emiDetails').innerText = `${months} Installments • ${rate}% APR Fixed`;

            // Updates the summary numbers
            document.getElementById('loanAmount').innerText = 'NRs ' + fmt(principal);
            document.getElementById('totalInterest').innerText = 'NRs ' + fmt(interest);
            document.getElementById('totalRepayment').innerText = 'NRs ' + fmt(total);

            // Keep the glow effect for a nice touch
            const glow = document.getElementById('glowEffect');
            if(glow) glow.classList.replace('bg-green-500/10', 'bg-green-500/30');
        }

        function resetUI() {
            document.getElementById('monthlyEMI').innerText = '----';
            document.getElementById('progressBar').style.width = '0%';
        }

    </script>

{{-- script for excel report Download --}}
    <script>
        function exportToExcel() {
            // 1. Prepare Summary Data (Inputs)
            const summaryData = [
                ["LOAN INTELLIGENCE REPORT"],
                ["Generated on:", new Date().toLocaleDateString()],
                [],
                ["PARAMETER", "VALUE"],
                ["Asset Value", document.getElementById('carPrice').value],
                ["Down Payment", document.getElementById('downPayment').value],
                ["Interest Rate (APR %)", document.getElementById('interestRate').value],
                ["Tenure (Years)", document.getElementById('tenure').value],
                ["Start Date", document.getElementById('startDate').value],
                [],
                ["FINANCIAL SUMMARY"],
                ["Monthly EMI", document.getElementById('monthlyEMI').innerText],
                ["Total Interest", document.getElementById('totalInterest').innerText],
                ["Total Repayment", document.getElementById('totalRepayment').innerText],
                []
            ];

            // 2. Prepare Amortization Data (Table)
            const table = document.querySelector("table");
            const scheduleData = [];

            // Get headers
            const headers = [];
            table.querySelectorAll("thead th").forEach(th => headers.push(th.innerText));
            scheduleData.push(headers);

            // Get rows
            table.querySelectorAll("tbody tr").forEach(tr => {
                const row = [];
                tr.querySelectorAll("td").forEach(td => row.push(td.innerText));
                scheduleData.push(row);
            });

            // 3. Create Workbook and Worksheet
            const wb = XLSX.utils.book_new();

            // Combine Summary and Schedule into one sheet
            const finalData = summaryData.concat(scheduleData);
            const ws = XLSX.utils.aoa_to_sheet(finalData);

            // 4. Basic Styling (Column Widths)
            const wscols = [
                {wch: 20}, // A
                {wch: 20}, // B
                {wch: 15}, // C
                {wch: 15}, // D
                {wch: 15}, // E
                {wch: 20}  // F
            ];
            ws['!cols'] = wscols;

            // 5. Append and Download
            XLSX.utils.book_append_sheet(wb, ws, "Loan Report");
            XLSX.writeFile(wb, "Loan-Intelligence-Report.xlsx");
        }
    </script>
@endsection
