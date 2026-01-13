@extends('layouts.app')

@section('content')
<div class="d-flex flex-column align-items-center justify-content-center mt-5">
    <img
        src="{{ asset('images/errors/Error page 403.png') }}"
        class="img-fluid rounded-top"
        alt="error-403"
        style="max-width: 1000px"
    />

    <div class="mt-3">
        <a href="{{ route('home') }}" class="btn btn-primary">Kembali ke Beranda</a>
    </div>
</div>
@endsection
