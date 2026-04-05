<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bijulicar | Authorize Access</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon" />

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

        <section
            class="w-full lg:w-[45%] h-full flex flex-col justify-center px-8 md:px-16 bg-white relative z-10 shadow-2xl overflow-y-auto no-scrollbar">

            <div class="mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-6 group no-underline">
                    <div class="w-12 h-12 bg-slate-900 rounded-xl flex items-center justify-center shadow-lg group-hover:bg-[#16a34a] transition-all duration-300">
                        <svg class="w-7 h-7 text-[#4ade80]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span class="text-lg font-extrabold tracking-tighter text-slate-900 uppercase">bijuli<span class="text-[#16a34a]">car</span></span>
                </a>
                <h1 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter">
                    Welcome back to <span class="text-[#16a34a]">BijuliCar</span>
                </h1>
                <p class="text-slate-500 text-sm font-medium mt-1">Access the next generation of EV marketplace.</p>
            </div>

            @if (session('status'))
                <div
                    class="mb-4 font-medium text-sm text-green-600 border border-green-200 bg-green-50 px-4 py-3 rounded-xl">
                    {{ session('status') }}
                </div>
            @endif

            <div class="space-y-6">
                {{-- <div class="grid grid-cols-2 gap-4">
                    <button type="button"
                        class="flex items-center justify-center gap-2 py-3 border border-slate-200 rounded-xl hover:bg-slate-50 transition-all">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-4 h-4" alt="Google">
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-700">Google</span>
                    </button>
                    <button type="button"
                        class="flex items-center justify-center gap-2 py-3 border border-slate-200 rounded-xl hover:bg-slate-50 transition-all">
                        <img src="https://www.svgrepo.com/show/475633/apple-black.svg" class="w-4 h-4" alt="Apple">
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-700">Apple ID</span>
                    </button>
                </div> --}}

                <div class="relative flex items-center justify-center">
                    <div class="w-full h-px bg-slate-100"></div>
                    <span
                        class="absolute bg-white px-4 text-[9px] font-black text-slate-300 uppercase tracking-[0.3em]">Use Email</span>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <div class="space-y-1">
                        <label for="email"
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email
                            Address</label>
                        <div class="relative group">
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                autofocus autocomplete="username" placeholder="name@company.com"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all font-medium @error('email') border-red-500 @enderror">
                        </div>
                        @if ($errors->has('email'))
                            <p class="mt-1 text-[11px] text-red-600 font-bold uppercase italic tracking-wider">
                                {{ $errors->first('email') }}</p>
                        @endif
                    </div>

                    <div class="space-y-1">
                        <div class="flex justify-between items-center ml-1">
                            <label for="password"
                                class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Password</label>
                            {{-- @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                    class="text-[9px] font-black text-[#16a34a] hover:underline uppercase tracking-widest">Forgot?</a>
                            @endif --}}
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all font-medium @error('password') border-red-500 @enderror">
                        @if ($errors->has('password'))
                            <p class="mt-1 text-[11px] text-red-600 font-bold uppercase italic tracking-wider">
                                {{ $errors->first('password') }}</p>
                        @endif
                    </div>

                    {{-- <div class="flex items-center gap-2 px-1 py-1">
                        <input type="checkbox" id="remember_me" name="remember"
                            class="w-4 h-4 rounded border-slate-300 text-[#16a34a] focus:ring-[#4ade80]">
                        <label for="remember_me" class="text-[11px] font-bold text-slate-500 cursor-pointer">Stay signed
                            in</label>
                    </div> --}}

                    <button type="submit"
                        class="w-full py-4 bg-slate-900 text-white rounded-xl font-black uppercase italic tracking-widest text-xs hover:bg-[#16a34a] transition-all flex items-center justify-center gap-3 shadow-xl shadow-slate-200 group">
                        Authorize Entry
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>

            <p class="mt-8 text-center text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                New to the community? <a href="{{ route('register') }}" class="text-[#16a34a] hover:underline ml-1">Join
                    Now</a>
            </p>
        </section>

        <section class="hidden lg:block w-[55%] h-full relative bg-slate-900">
            <img src="https://images.unsplash.com/photo-1617788138017-80ad40651399?q=80&w=2070&auto=format&fit=crop"
                class="absolute inset-0 w-full h-full object-cover opacity-60 grayscale-[0.3] hover:grayscale-0 transition-all duration-1000"
                alt="Luxury EV">

            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-slate-900/50"></div>

            <div class="absolute bottom-16 left-16 right-16">
                <div class="flex items-center gap-3 mb-4">
                    <span
                        class="px-3 py-1 bg-[#4ade80]/20 backdrop-blur-md border border-[#4ade80]/30 text-[#4ade80] text-[10px] font-black uppercase tracking-widest rounded-full">System
                        Status: Nominal</span>
                </div>
                <h2 class="text-5xl font-black text-white uppercase italic tracking-tighter leading-none mb-4">
                    THE FUTURE IS <br><span class="text-[#4ade80]">ELECTRIC.</span>
                </h2>
                <p class="text-slate-300 text-sm font-medium max-w-md leading-relaxed">
                    Join thousands of collectors and enthusiasts trading the world's most advanced electric vehicles on
                    the BijuliCar marketplace.
                </p>
            </div>

            <div class="absolute top-12 right-12">
                <div class="bg-white/5 backdrop-blur-xl border border-white/10 p-4 rounded-2xl flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-[#16a34a] flex items-center justify-center">
                        <span class="text-white font-black italic">⚡</span>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Current Listings</p>
                        <p class="text-white font-black text-xl leading-none">2,480+</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

</body>

</html>