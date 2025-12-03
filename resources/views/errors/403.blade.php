@extends('layouts.app')

@section('content')
<div class="text-center mt-5">
    <h1 class="fw-bold text-danger" style="font-size:70px;">403</h1>
    <h3>Akses Ditolak</h3>
    <p class="text-muted">Anda tidak memiliki permission untuk mengakses halaman ini.</p>
    <a href="{{ url()->previous() }}" class="btn btn-primary mt-3">Kembali</a>
</div>
@endsection
