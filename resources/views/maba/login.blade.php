@extends('layouts.master-front')


@section('title', 'Login Mahasiswa Baru')


@section('content')

    <head>
        <!-- Memuat jQuery versi terbaru -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

        <!-- Memuat Bootstrap CSS versi terbaru -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

        <!-- Memuat Font Awesome untuk ikon -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
            integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
            <div class="row w-100 mx-0">
                <div class="col-xl-6 mx-auto">
                    <div class="card border-0 shadow rounded">
                        <div class="card-body">

{{-- 
                            <div class="alert alert-success" role="alert">
                                    <h4 class="text-center font-weight-bold m-0">Untuk Melanjutkan Pendaftaran dapat dilakukan di Sekretariat Yayasan, Kampus 1 UNSAP Jl Angkrek Situ No. 19 Sumedang </h4>
                                </div>
              

                                <div class="alert alert-success" role="alert">
                            <h4 class="text-center font-weight-bold m-0">Untuk Melanjutkan Pendaftaran dapat dilakukan di Sekretariat Yayasan, Kampus 1 UNSAP Jl Angkrek Situ No. 19 Sumedang </h4>
                        </div>  --}}

                                @if (session('status'))
                                    <div class="alert alert-success" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @endif
                                <div class="text-center mb-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <img src="{{ asset('images/logo-importer-mini.png') }}" alt="UNSAP"
                                            class="logo mb-3" style="width: 100px; height: auto;">

                                        <div class="d-flex justify-content-center align-items-center mb-4 text-daintree">
                                            <div class="text-end pe-3">
                                                <h6 class="m-0 text-uppercase fw-bold">pmb</h6>
                                                {{-- <h6 class="m-0 text-uppercase fw-bold">{{ env('TAHUN_AKTIF') }}</h6> --}}
                                                <h6 class="m-0 text-uppercase fw-bold">{{ date('Y') }}</h6>
                                            </div>
                                            <div class="border-start border-3 border-warning ps-3">
                                                <h6 class="m-0 fw-bold">Sistem Penerimaan Mahasiswa Baru</h6>
                                                <h6 class="m-0 fw-bold">Universitas Sebelas April</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h6 class="text-center text-dark mb-4 fw-bold">Login</h6>
                                <hr>
                                <form action="{{ route('cek.pin') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label class="font-weight-bold text-uppercase">Nomor Whatsapp (Tanpa Awalan +62 atau 0)</label>
                                        <input type="handphone" name="handphone" value="{{ old('handphone') }}"
                                            class="form-control @error('handphone') is-invalid @enderror"
                                            placeholder="Contoh. 81394543943" required>
                                        @error('handphone')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-bold text-uppercase">PIN Pendaftaran</label>
                                        <input type="pin" name="pin"
                                            class="form-control @error('pin') is-invalid @enderror"
                                            placeholder="Masukkan Pin Pendaftaran">
                                        @error('pin')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <!-- Field Turnstile Cloudflare -->
                                    <div class="form-group">
                                        <label class="font-weight-bold text-uppercase">Verifikasi Keamanan</label>
                                        <div style="display: block; flex-flow: row;">
                                            <div class="cf-turnstile" data-sitekey="0x4AAAAAAA-RoAZoVha2NyLa" data-size="flexible" data-theme="light"></div>
                                        </div>
                                        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
                                    </div>
                                    <button type="submit" class="btn btn-primary">LOGIN</button>
                                    <hr>



                                </form>
                            </div>
                        </div>
                        <div class="register mt-3 text-center">
                            <p>Belum punya PIN Pendaftaran ? Daftar <a href="/">Disini</a></p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endsection

    @push('page-stylesheet')
    @endpush

    @push('page-script')
        <script>
            $(".toggle-password").click(function() {
                $(this).toggleClass("fa-eye fa-eye-slash");
                var input = $($(this).attr("toggle"));
                if (input.attr("type") == "password") {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                }
            });
        </script>
    @endpush
