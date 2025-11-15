<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error {{ $code }} - {{ $title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen flex justify-center items-center px-6">
    <div class="text-center">
        <h1 class="text-9xl font-extrabold text-blue-600">{{ $code }}</h1>
        <h2 class="text-3xl font-bold mt-4">{{ $title }}</h2>
        <p class="text-gray-600 mt-2">{{ $message }}</p>

        <a href="{{ url('/') }}"
            class="mt-6 inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            Kembali ke Beranda
        </a>
    </div>
</body>

</html>