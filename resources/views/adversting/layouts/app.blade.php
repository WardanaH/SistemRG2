<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'RG Promosindo') }}</title>
    <meta name="description" content="Percetakan modern dan profesional — Restu Guru Promosindo">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/restugurulogo.webp') }}">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- AOS (scroll animation) -->
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">

    <!-- Global Styles -->
    <link rel="stylesheet" href="/css/main.css">
</head>

<body id="body-root"
    class="opacity-0 transition-opacity duration-500 ease-out bg-white text-gray-800">

    <!-- Overlay Page Transition -->
    <div id="overlay"
        class="fixed inset-0 bg-white z-[9999] opacity-100 pointer-events-none transition-opacity duration-700 ease-out"></div>

    <!-- NAVBAR -->
    @include('adversting.components.navbar')

    <!-- PAGE CONTENT -->
    <main class="pt-20 min-h-screen">
        @yield('content')
    </main>

    <!-- FOOTER -->
    @include('adversting.components.footer')

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

    <!-- Global Animation JS -->
    <script src="/js/anim.js" defer></script>

</body>

</html>
