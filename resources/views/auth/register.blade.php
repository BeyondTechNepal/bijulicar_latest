<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bijulicar | Join the Future</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Hide scrollbar but allow functionality */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="bg-white lg:bg-[#f1f5f9] min-h-screen">

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
                    <li class="flex items-center gap-3"><span class="text-[#4ade80]">✔</span> Verified Private Sellers</li>
                    <li class="flex items-center gap-3"><span class="text-[#4ade80]">✔</span> Secure Digital Title</li>
                </ul>
            </div>
        </section>

        {{-- Right Section: Form --}}
        <section class="w-full lg:w-[50%] flex flex-col justify-center px-6 md:px-16 lg:px-20 py-10 lg:py-5 bg-white relative z-10 min-h-screen">

            {{-- Brand Logo --}}
            <div class="mb-2"> 
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-4 group no-underline">
                    <div class="w-8 h-8 bg-slate-900 rounded-lg flex items-center justify-center shadow-lg group-hover:bg-[#16a34a] transition-all duration-300">
                        <svg class="w-5 h-5 text-[#4ade80]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span class="text-lg font-extrabold tracking-tighter text-slate-900 uppercase">bijuli<span class="text-[#16a34a]">car</span></span>
                </a>
                <div class="w-full h-1 bg-slate-100 rounded-full">
                    <div class="w-1/2 h-full bg-[#16a34a] shadow-[0_0_8px_rgba(22,163,74,0.4)]"></div>
                </div>
            </div>

            <div class="mb-2"> 
                <h1 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter">
                    Create <span class="text-[#16a34a]">Account</span>
                </h1>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Join the EV ecosystem today</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4"> 
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Amrit Nepal"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-[#16a34a]/20 focus:border-[#16a34a] transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="amrit@example.com"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-[#16a34a]/20 focus:border-[#16a34a] transition-all">
                    </div>
                    <div class="space-y-1 md:col-span-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Phone Number</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="+977 98XXXXXXXX"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-[#16a34a]/20 focus:border-[#16a34a] transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Password</label>
                        <input type="password" name="password" required placeholder="••••••••"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-[#16a34a]/20 focus:border-[#16a34a] transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" required placeholder="••••••••"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:ring-2 focus:ring-[#16a34a]/20 focus:border-[#16a34a] transition-all">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Account Type</label>
                    @php
                        $roleOptions = [
                            'buyer'      => ['icon' => '<i class="fa-solid fa-cart-arrow-down" style="color: rgb(46, 204, 113);"></i>', 'label' => 'Buyer'],
                            'seller'     => ['icon' => '<i class="fa-solid fa-car-side" style="color: #3498db;"></i>',                  'label' => 'Seller'],
                            'ev-station' => ['icon' => '<i class="fa-solid fa-charging-station" style="color: #f1c40f;"></i>',          'label' => 'Charging Station'],
                            'garage'     => ['icon' => '<i class="fa-solid fa-screwdriver-wrench" style="color: #9b59b6;"></i>',        'label' => 'Garage'],
                            'business'   => ['icon' => '<i class="fa-solid fa-building" style="color: #34495e;"></i>',                 'label' => 'Business'],
                        ];
                    @endphp
                    <div class="grid grid-cols-3 sm:grid-cols-5 gap-2">
                        @foreach($roleOptions as $role => $opt)
                        <label class="group cursor-pointer">
                            <input type="radio" name="role" value="{{ $role }}" class="sr-only peer" {{ old('role') === $role ? 'checked' : '' }}>
                            <div class="relative h-full border border-slate-100 rounded-xl p-2 text-center transition-all peer-checked:border-[#16a34a] peer-checked:bg-green-50/50 hover:shadow-sm flex flex-col items-center justify-center">
                                
                                <div class="text-base mb-1 text-slate-600 peer-checked:text-[#16a34a] transition-colors">
                                    {!! $opt['icon'] !!}
                                </div>
                                
                                <div class="font-black text-[10px] text-slate-900 uppercase tracking-tighter leading-none">
                                    {{ $opt['label'] }}
                                </div>
                                
                                <div class="absolute top-1 right-1 w-1.5 h-1.5 rounded-full bg-[#16a34a] opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex flex-col gap-3 py-2">
                    <div class="flex items-start gap-3">
                        <input type="checkbox" name="terms_and_conditions" id="terms" required 
                            class="mt-0.5 w-4 h-4 text-[#16a34a] border-slate-300 rounded focus:ring-[#16a34a] cursor-pointer">
                        <label for="terms" class="text-[10px] font-bold text-slate-500 uppercase tracking-wider leading-relaxed cursor-pointer">
                            I agree to the <a href="#" class="text-[#16a34a] underline underline-offset-2 transition-colors">terms and conditions</a> and user agreement.
                        </label>
                    </div>
                    <div class="flex items-center gap-3 px-0.5">
                        <input type="checkbox" name="wants_newsletter" id="news" class="w-4 h-4 text-[#16a34a] border-slate-300 rounded">
                        <label for="news" class="text-[10px] font-bold text-slate-500 uppercase tracking-wider cursor-pointer">Subscribe to Newsletter</label>
                    </div>
                </div>

                <button type="submit"
                    class="w-full py-4 bg-slate-900 text-white rounded-xl font-black uppercase italic tracking-widest text-xs hover:bg-[#16a34a] transition-all flex items-center justify-center gap-2 shadow-xl active:scale-[0.98] group">
                    Initialize Account
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>

            <p class="mt-8 text-center text-[11px] font-bold text-slate-400 uppercase tracking-[0.2em]">
                Already a member? <a href="{{ route('login') }}" class="text-[#16a34a] hover:text-slate-900 ml-1 transition-colors">Log In</a>
            </p>
        </section>
    </main>
</body>
</html>