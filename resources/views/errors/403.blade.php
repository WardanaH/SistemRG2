<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 403 - Forbidden</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 h-screen flex justify-center items-center">
    <div class="text-center">
        <h1 class="text-9xl font-bold text-red-500">403</h1>
        <p class="text-2xl font-semibold mt-4">Akses Ditolak</p>
        <p class="text-gray-600 mt-2">Kamu tidak memiliki izin untuk membuka halaman ini.</p>

        <a href="{{ route('dashboard') }}"
            class="mt-6 inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            Kembali ke Beranda
        </a>
    </div>
</body>

</html>