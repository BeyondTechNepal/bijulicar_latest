<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Bijulicar</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon" />

    {{-- <link rel="icon" type="image/png" href="{{ asset('imagess/logo.svg') }}" /> --}}

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- Tailwind CSS (CDN for demonstration) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- import for card slider --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    {{-- import for font awesome icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- import for spreadsheet --}}
    <script src="https://cdn.sheetjs.com/xlsx-0.20.0/package/dist/xlsx.full.min.js"></script>

        

        <style>

        /* Hide scrollbar utility */
        .scrollbar-hide-nav::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide-nav {
            -ms-overflow-style: none;
            /* IE & Edge */
            scrollbar-width: none;
            /* Firefox */
        }
    </style>

</head>

<body class="flex flex-col min-h-screen bg-white">

@include('frontend.layouts.navigation')

<main class="flex-grow">
    @yield('content')
</main>

@include('frontend.layouts.footer')

</body>

</html>
