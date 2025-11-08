<!DOCTYPE html>
<html lang="id" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name', 'Sistem Restu Guru Promosindo') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.png') }}">

    <!-- Style utama Focus template -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <!-- Font Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body, h1, h2, h3, h4, h5, h6, label {
            font-family: 'Poppins', sans-serif !important;
        }
        .auth-form h4 {
            font-weight: 600;
            color: #333;
        }
        .btn-primary {
            background-color: #4B28D2 !important;
            border: none !important;
        }
        .btn-primary:hover {
            background-color: #3a20a8 !important;
        }
    </style>
</head>

<body class="h-100">
    <div class="authincation h-100">
        <div class="container-fluid h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6 col-lg-4">
                    <div class="authincation-content shadow-lg">
                        <div class="auth-form p-4">
                            <h4 class="text-center mb-4">Masuk ke Akun Anda</h4>

                            {{-- Alert pesan sukses atau error --}}
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if($errors->any())
                                <div class="alert alert-danger">{{ $errors->first() }}</div>
                            @endif

                            <form method="POST" action="{{ route('login.post') }}">
                                @csrf
                                <div class="form-group mb-3">
                                    <label><strong>Username</strong></label>
                                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autofocus>
                                </div>
                                <div class="form-group mb-3">
                                    <label><strong>Password</strong></label>
                                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                                </div>
                                <div class="form-row d-flex justify-content-between mt-3 mb-3">
                                    <div class="form-group form-check">
                                        <input type="checkbox" class="form-check-input" id="remember">
                                        <label class="form-check-label" for="remember">Ingat saya</label>
                                    </div>
                                    <div class="form-group">
                                        <a href="#" class="text-primary">Lupa Password?</a>
                                    </div>
                                </div>
                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary btn-block">Masuk</button>
                                </div>
                            </form>

                            <div class="new-account mt-3 text-center">
                                <p>Belum punya akun? <a class="text-primary" href="#">Hubungi Admin</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS bawaan Focus template -->
    <script src="{{ asset('vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('js/quixnav-init.js') }}"></script>
    <script src="{{ asset('js/custom.min.js') }}"></script>
</body>
</html>
