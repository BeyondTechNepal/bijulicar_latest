<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BijuliCar') }}</title>

        <link rel="icon" href="{{ asset('images/logo.svg') }}" type="image/x-icon" />
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Inter', sans-serif; }
        </style>
    </head>
    <body class="bg-[#f1f5f9] min-h-screen flex flex-col items-center justify-center p-4 antialiased">

        {{-- Logo / Home link --}}
         <div class="flex items-center shrink-0 px-2 xl:px-4">
            <a href="{{ route('home') }}" class="flex items-center gap-2 no-underline group">
                <img src="{{ asset('images/logo.png') }}" alt="BijuliCar Logo"
                    class="h-12 md:h-14 lg:h-16 w-auto object-contain transition-all duration-500 group-hover:scale-110 drop-shadow-sm">
                    </a>
        </div>

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