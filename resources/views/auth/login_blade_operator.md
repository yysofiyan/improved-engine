@extends('layouts.master-front')

@section('title', 'Penerimaan Mahasiswa Baru - UNSAP')


@section('content')
<div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
            <div class="col-xl-6 mx-auto">
                <div class="card border-0 shadow rounded">
                    <div class="card-body">
                        @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                        @endif
                        <h4 class="font-weight-bold">LOGIN OPERATOR PMB UNSAP</h4>
                        <hr>
                        <div class="alert alert-success" role="alert">
                            <h4 class="p-1">Login untuk Fakultas silahkan klik <a href="/login-fakultas">Disini</a></h4> 
                        </div>

                        <form action="{{ url('login-operator') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label class="font-weight-bold text-uppercase">Email address</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan Alamat Email">
                                @error('email')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>    
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold text-uppercase">Password</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan Password">
                                @error('password')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>    
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">LOGIN</button>
                            <hr>
            
                           {{--  <a href="/forgot-password">Lupa Password ?</a> --}}
            
                        </form>
                    </div>
                </div>
                {{-- <div class="register mt-3 text-center">
                    <p>Belum punya akun ? Daftar <a href="/register">Disini</a></p>
                </div> --}}
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
