@extends('layouts.master-front')


@section('title', 'Penerimaan Mahasiswa Baru - UNSAP')


@section('content')

    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
            <div class="row w-100 mx-0">
                <div class="col-xl-6 mx-auto">
                    <div class="card border-0 shadow rounded">
                        <div class="card-body">

                            <!--
                        <div class="alert alert-success" role="alert">
                                <h4 class="text-center font-weight-bold m-0">Untuk Melanjutkan Pendaftaran dapat dilakukan di Sekretariat Yayasan, Kampus 1 UNSAP Jl Angkrek Situ No. 19 Sumedang </h4>
                            </div>
          -->

                            {{-- <div class="alert alert-success" role="alert">
                            <h4 class="text-center font-weight-bold m-0">Untuk Melanjutkan Pendaftaran dapat dilakukan di Sekretariat Yayasan, Kampus 1 UNSAP Jl Angkrek Situ No. 19 Sumedang </h4>
                        </div> --}}

                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <h4 class="font-weight-bold">LOGIN CALON MAHASISWA BARU</h4>
                            <hr>
                            <form action="{{ route('cek.pin') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="font-weight-bold text-uppercase">Nomor Whatsapp (+62)</label>
                                    <input type="handphone" name="handphone" value="{{ old('handphone') }}"
                                        class="form-control @error('handphone') is-invalid @enderror"
                                        placeholder="Masukkan Alamat handphone">
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
