<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bijulicar | Email Verified</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }

        @keyframes pop {
            0%   { transform: scale(0.8); opacity: 0; }
            70%  { transform: scale(1.08); }
            100% { transform: scale(1); opacity: 1; }
        }
        .pop { animation: pop 0.4s cubic-bezier(0.22, 1, 0.36, 1) both; }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-6px); }
        }
        .float { animation: float 3s ease-in-out infinite; }
    </style>
</head>

<body class="bg-[#f1f5f9] min-h-screen flex items-center justify-center p-4 md:p-6">

    <div class="w-full max-w-lg">

        {{-- Logo --}}
        <div class="flex items-center justify-center">
            <div class="flex items-center shrink-0 px-2 xl:px-4">
            <a href="{{ route('home') }}" class="flex items-center gap-2 no-underline group">
                <img src="{{ asset('images/logo.png') }}" alt="BijuliCar Logo"
                    class="h-12 md:h-14 lg:h-16 w-auto object-contain transition-all duration-500 group-hover:scale-110 drop-shadow-sm">
            </a>
            </div>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl md:rounded-3xl shadow-sm border border-slate-200 overflow-hidden">

            @if ($status === 'success')

                {{-- Success state --}}
                <div class="h-1.5 w-full bg-green-500"></div>

                <div class="p-6 md:p-10 text-center">

                    {{-- Animated tick icon --}}
                    <div class="flex items-center justify-center mb-6 md:mb-8">
                        <div class="float w-16 h-16 md:w-20 md:h-20 bg-green-50 border-2 border-green-200 rounded-2xl flex items-center justify-center pop">
                            <svg class="w-8 h-8 md:w-10 md:h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>

                    <p class="text-[9px] md:text-[10px] font-black text-green-500 uppercase tracking-[0.3em] mb-2">
                        Verified
                    </p>
                    <h1 class="text-xl md:text-2xl font-black text-slate-900 uppercase italic tracking-tight mb-3">
                        Email <span class="text-green-500">Confirmed!</span>
                    </h1>

                    {{-- Main message --}}
                    <div class="mt-4 bg-green-50 border border-green-200 rounded-2xl p-4 md:p-6 text-left">
                        <p class="text-sm font-bold text-green-800 mb-1">You can close this tab.</p>
                        <p class="text-xs font-medium text-green-700 leading-relaxed">
                            Your email has been verified successfully. Go back to the browser
                            where you originally registered — it will automatically take you
                            to the next step.
                        </p>
                    </div>

                    {{-- What happens next --}}
                    <div class="mt-5 bg-slate-50 border border-slate-200 rounded-2xl p-4 md:p-5 text-left space-y-3">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">What happens next</p>
                        <div class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-green-100 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <p class="text-xs font-medium text-slate-600">
                                Your original browser has detected the verification and will
                                <span class="font-bold text-slate-900">automatically advance</span> to the document submission step.
                            </p>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-slate-200 flex items-center justify-center shrink-0 mt-0.5">
                                <span class="text-[9px] font-black text-slate-500">2</span>
                            </div>
                            <p class="text-xs font-medium text-slate-600">
                                Submit your documents for admin review — approval usually takes
                                <span class="font-bold text-slate-900">under 24 hours.</span>
                            </p>
                        </div>
                    </div>

                    {{-- Optional: login link if they want to continue on this browser too --}}
                    <p class="text-[10px] md:text-[11px] text-slate-400 font-medium mt-6">
                        Want to continue on this browser instead?
                        <a href="{{ route('login') }}"
                           class="text-blue-500 font-bold underline hover:text-blue-700 transition-colors">
                            Sign in here
                        </a>
                    </p>

                </div>

            @else

                {{-- Invalid / error state --}}
                <div class="h-1.5 w-full bg-red-500"></div>

                <div class="p-6 md:p-10 text-center">

                    <div class="flex items-center justify-center mb-6 md:mb-8">
                        <div class="float w-16 h-16 md:w-20 md:h-20 bg-red-50 border-2 border-red-200 rounded-2xl flex items-center justify-center pop">
                            <svg class="w-8 h-8 md:w-10 md:h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                    </div>

                    <p class="text-[9px] md:text-[10px] font-black text-red-500 uppercase tracking-[0.3em] mb-2">
                        Invalid Link
                    </p>
                    <h1 class="text-xl md:text-2xl font-black text-slate-900 uppercase italic tracking-tight mb-3">
                        Link <span class="text-red-500">Expired</span>
                    </h1>

                    <div class="mt-4 bg-red-50 border border-red-200 rounded-2xl p-4 md:p-6 text-left">
                        <p class="text-xs font-medium text-red-700 leading-relaxed">
                            {{ $message }}
                        </p>
                    </div>

                    <p class="text-xs text-slate-500 font-medium mt-5 leading-relaxed">
                        Log in to your account and request a fresh verification link.
                    </p>

                    <div class="mt-5">
                        <a href="{{ route('login') }}"
                           class="w-full py-3.5 md:py-4 bg-slate-900 text-white rounded-xl font-black uppercase italic tracking-widest text-[10px] md:text-xs hover:bg-blue-600 transition-all flex items-center justify-center gap-3 group">
                            Go to Login
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>

                </div>

            @endif

        </div>

    </div>

</body>
</html>