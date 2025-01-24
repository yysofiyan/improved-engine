@extends('layouts.master-front')

@section('title', 'Login Fakultas UNSAP')

@section('content')
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center">
    <div class="row w-100 justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-sm p-3 p-md-4">
                <div class="text-center mb-4">
                    <img src="{{ url('images/logo-importer.png') }}" alt="logo" class="img-fluid" style="max-width: 200px;">
                </div>
                <h6 class="text-center text-muted mb-4">
                    Silahkan gunakan login dengan menggunakan email unsap untuk Fakultas
                </h6>
                <div class="text-center mt-4">
                    <a href="{{ url('authorized/google') }}" class="d-inline-block">
                        <img src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png" 
                             class="img-fluid" 
                             style="max-width: 250px;"
                             alt="Login dengan Google">
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
