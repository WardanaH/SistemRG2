@extends('operator.layout.app')

@section('content')
<div class="container-fluid">
    <div class="row page-titles mx-0">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4>Profil Pengguna</h4>
                <p class="mb-0">Informasi akun dan pengaturan pribadi</p>
            </div>
        </div>
    </div>

    {{-- ==== isi profil di sini ==== --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="profile">
                <div class="profile-head">
                    <div class="photo-content">
                        <div class="cover-photo"></div>
                        <div class="profile-photo">
                            <img src="{{ asset('images/profile/profile.png') }}" class="img-fluid rounded-circle" alt="Foto Profil">
                        </div>
                    </div>
                    <div class="profile-info text-center mt-3">
                        <h4 class="text-primary">{{ Auth::user()->nama ?? 'Nama Pengguna' }}</h4>
                        <p class="text-muted">{{ Auth::user()->email ?? 'Email pengguna' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tab pengaturan --}}
    <div class="row mt-4">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="profile-tab">
                        <div class="custom-tab-1">
                            <ul class="nav nav-tabs">
                                <li class="nav-item"><a href="#about" data-toggle="tab" class="nav-link active">Tentang</a></li>
                                <li class="nav-item"><a href="#settings" data-toggle="tab" class="nav-link">Pengaturan</a></li>
                            </ul>
                            <div class="tab-content pt-3">
                                <div id="about" class="tab-pane fade active show">
                                    <h5>Nama: {{ Auth::user()->nama ?? '-' }}</h5>
                                    <h6>Email: {{ Auth::user()->email ?? '-' }}</h6>
                                </div>
                                <div id="settings" class="tab-pane fade">
                                    <form>
                                        <div class="form-group">
                                            <label>Nama</label>
                                            <input type="text" class="form-control" value="{{ Auth::user()->nama ?? '' }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" class="form-control" value="{{ Auth::user()->email ?? '' }}">
                                        </div>
                                        <div class="text-end mt-3">
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
