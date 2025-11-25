<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
    <title>Document</title>
</head>

<body>
    <div class="container text-center py-5">
        <h1 class="display-3 text-danger">404</h1>
        <h3 class="mb-3">Halaman Tidak Ditemukan</h3>
        <p class="text-muted">
            Maaf, halaman yang kamu cari tidak tersedia atau sudah dipindahkan.
        </p>

        <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3">
            ⬅️ Kembali ke Dashboard
        </a>
    </div>
</body>

</html>
