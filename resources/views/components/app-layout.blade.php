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
            {{-- Center: logo --}}
            <div class="flex items-center shrink-0 px-2 xl:px-4">
                <a href="{{ route('home') }}" class="flex items-center gap-2 no-underline group">
                    <img src="{{ asset('images/logo.png') }}" alt="BijuliCar Logo"
                        class="h-12 md:h-14 lg:h-16 w-auto object-contain transition-all duration-500 group-hover:scale-110 drop-shadow-sm">
                </a>
            </div>
        </a>
        <a href="{{ url()->previous() }}"
            class="text-xs font-black text-slate-500 uppercase tracking-widest hover:text-slate-900 transition-colors">
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
