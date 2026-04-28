<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'BijuliCar') }} — Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon" />

</head>
<body class="font-sans antialiased bg-slate-100 min-h-screen">

    {{-- Top nav bar --}}
    <nav class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-2 no-underline group">
            <div class="w-9 h-9 bg-slate-900 rounded-lg flex items-center justify-center group-hover:bg-[#16a34a] transition-all duration-500 shadow-lg group-hover:rotate-[360deg]">
                <span class="text-white font-bold text-sm italic">BC</span>
            </div>
            <span class="text-lg font-extrabold tracking-tighter text-slate-900 uppercase">bijuli<span class="text-[#16a34a]">car</span></span>
        </a>
        <a href="{{ url()->previous() }}" class="text-xs font-black text-slate-500 uppercase tracking-widest hover:text-slate-900 transition-colors">
            ← Back
        </a>
    </nav>

    {{-- Page header slot --}}
    @isset($header)
        <header class="bg-white border-b border-slate-200">
            <div class="max-w-4xl mx-auto px-6 py-6">
                {{ $header }}
            </div>
        </header>
    @endisset

    {{-- Main content --}}
    <main>
        {{ $slot }}
    </main>

</body>
</html>