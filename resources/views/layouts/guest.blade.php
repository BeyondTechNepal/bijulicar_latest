<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BijuliCar') }}</title>

        <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon" />
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Inter', sans-serif; }
        </style>
    </head>
    <body class="bg-[#f1f5f9] min-h-screen flex flex-col items-center justify-center p-4 antialiased">

        {{-- Logo / Home link --}}
        <a href="{{ route('home') }}" class="flex items-center gap-2 mb-8 group no-underline">
            <div class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center shadow-lg group-hover:bg-[#16a34a] transition-all duration-300">
                <svg class="w-6 h-6 text-[#4ade80]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <span class="text-xl font-extrabold tracking-tighter text-slate-900 uppercase">bijuli<span class="text-[#16a34a]">car</span></span>
        </a>

        {{-- Card --}}
        <div class="w-full max-w-md bg-white rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.08)] border border-slate-100 px-8 py-8">
            {{ $slot }}
        </div>

        {{-- Back to login --}}
        <p class="mt-6 text-[11px] font-bold text-slate-400 uppercase tracking-widest">
            <a href="{{ route('login') }}" class="text-[#16a34a] hover:underline">← Back to Login</a>
        </p>

    </body>
</html>