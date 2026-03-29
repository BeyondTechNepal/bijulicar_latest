<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bijulicar | Join the Future</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            overflow: hidden;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>

<body class="bg-[#f1f5f9] h-screen w-screen overflow-hidden">

    <main class="flex h-full w-full">

        <section class="hidden lg:block w-[50%] h-full relative bg-slate-900">
            <img src="https://images.unsplash.com/photo-1593941707882-a5bba14938c7?q=80&w=2072&auto=format&fit=crop"
                class="absolute inset-0 w-full h-full object-cover opacity-50 grayscale-[0.2]" alt="EV Technology">

            <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-transparent to-transparent"></div>

            <div class="absolute top-1/2 left-16 -translate-y-1/2">
                <div class="flex items-center gap-2 mb-6">
                    <span class="w-12 h-1 bg-[#4ade80] rounded-full"></span>
                    <p class="text-[10px] font-black text-[#4ade80] uppercase tracking-[0.4em]">Marketplace Access</p>
                </div>
                <h2 class="text-6xl font-black text-white uppercase italic tracking-tighter leading-[0.9] mb-6">
                    POWER YOUR <br>NEXT <span class="text-[#4ade80]">JOURNEY.</span>
                </h2>
                <ul class="space-y-4 text-slate-300 text-sm font-bold uppercase tracking-widest">
                    <li class="flex items-center gap-3"><span class="text-[#4ade80]">✔</span> Expert EV Valuation</li>
                    <li class="flex items-center gap-3"><span class="text-[#4ade80]">✔</span> Verified Private Sellers
                    </li>
                    <li class="flex items-center gap-3"><span class="text-[#4ade80]">✔</span> Secure Digital Title</li>
                </ul>
            </div>
        </section>

        <section
            class="w-full lg:w-[50%] h-full flex flex-col justify-center px-8 md:px-20 bg-white relative z-10 overflow-y-auto no-scrollbar">

            <div class="mb-8">
                <div class="flex justify-between items-center mb-3 px-1">
                    <span class="text-[10px] font-black text-[#16a34a] uppercase tracking-widest">01. Identity</span>
                </div>
                <div class="w-full h-1 bg-slate-100 rounded-full">
                    <div class="w-1/2 h-full bg-[#16a34a] shadow-[0_0_8px_rgba(22,163,74,0.4)]"></div>
                </div>
            </div>

            <div class="mb-6">
                <h1 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter">
                    Create <span class="text-[#16a34a]">Account</span>
                </h1>
                <p class="text-slate-500 text-sm font-medium mt-1">Join the community to start listing your vehicles.
                </p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Full
                            Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus
                            placeholder="John Doe"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all font-medium @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-[9px] text-red-500 font-bold uppercase mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-1">
                        <label
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            placeholder="john@example.com"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all font-medium @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-[9px] text-red-500 font-bold uppercase mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Password</label>
                        <input type="password" name="password" required autocomplete="new-password"
                            placeholder="••••••••"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all font-medium @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="text-[9px] text-red-500 font-bold uppercase mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-1">
                        <label
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Confirm</label>
                        <input type="password" name="password_confirmation" required placeholder="••••••••"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all font-medium">
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Select Account
                        Type</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        <label class="group cursor-pointer">
                            <input type="radio" name="role" value="buyer" class="sr-only peer"
                                {{ old('role') === 'buyer' ? 'checked' : '' }}>
                            <div
                                class="relative border-2 border-slate-100 rounded-2xl p-5 text-center transition-all 
                        peer-checked:border-[#16a34a] peer-checked:bg-green-50/50 
                        group-hover:border-slate-200 group-hover:shadow-md">
                                <div class="mb-2 text-xl group-hover:scale-110 transition-transform">🛒</div>
                                <div class="font-black text-xs text-slate-900 uppercase italic tracking-tight">Buyer
                                </div>
                                <div class="text-[10px] text-slate-500 font-bold mt-1 leading-tight">Browse & Purchase
                                </div>

                                <div
                                    class="absolute top-2 right-2 w-2 h-2 rounded-full bg-[#16a34a] opacity-0 peer-checked:opacity-100 transition-opacity">
                                </div>
                            </div>
                        </label>

                        <label class="group cursor-pointer">
                            <input type="radio" name="role" value="seller" class="sr-only peer"
                                {{ old('role') === 'seller' ? 'checked' : '' }}>
                            <div
                                class="relative border-2 border-slate-100 rounded-2xl p-5 text-center transition-all 
                        peer-checked:border-[#16a34a] peer-checked:bg-green-50/50 
                        group-hover:border-slate-200 group-hover:shadow-md">
                                <div class="mb-2 text-xl group-hover:scale-110 transition-transform">⚡</div>
                                <div class="font-black text-xs text-slate-900 uppercase italic tracking-tight">Seller
                                </div>
                                <div class="text-[10px] text-slate-500 font-bold mt-1 leading-tight">List Your EV</div>

                                <div
                                    class="absolute top-2 right-2 w-2 h-2 rounded-full bg-[#16a34a] opacity-0 peer-checked:opacity-100 transition-opacity">
                                </div>
                            </div>
                        </label>

                        <label class="group cursor-pointer">
                            <input type="radio" name="role" value="business" class="sr-only peer"
                                {{ old('role') === 'business' ? 'checked' : '' }}>
                            <div
                                class="relative border-2 border-slate-100 rounded-2xl p-5 text-center transition-all 
                        peer-checked:border-[#16a34a] peer-checked:bg-green-50/50 
                        group-hover:border-slate-200 group-hover:shadow-md">
                                <div class="mb-2 text-xl group-hover:scale-110 transition-transform">🏢</div>
                                <div class="font-black text-xs text-slate-900 uppercase italic tracking-tight">Business
                                </div>
                                <div class="text-[10px] text-slate-500 font-bold mt-1 leading-tight">Bulk & Ads</div>

                                <div
                                    class="absolute top-2 right-2 w-2 h-2 rounded-full bg-[#16a34a] opacity-0 peer-checked:opacity-100 transition-opacity">
                                </div>
                            </div>
                        </label>
                    </div>

                    @error('role')
                        <p class="text-[10px] text-red-500 font-bold uppercase italic tracking-wider mt-2 ml-1">
                            ⚠ {{ $message }}
                        </p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full py-4 bg-slate-900 text-white rounded-xl font-black uppercase italic tracking-widest text-xs hover:bg-[#16a34a] transition-all flex items-center justify-center gap-3 shadow-xl group">
                    Initialize Account
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>

            <p class="mt-8 text-center text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                Already have an account? <a href="{{ route('login') }}"
                    class="text-[#16a34a] hover:underline ml-1">Authorize Entry</a>
            </p>
        </section>
    </main>

</body>

</html>
