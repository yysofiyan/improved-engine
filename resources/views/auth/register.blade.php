@extends('layouts.master-front')

@section('title', 'Aplikasi Adress Book')


@section('content')
<div class="container-fluid page-body-wrapper full-page-wrapper">
    <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
            <div class="col-xl-6 mx-auto">
                <div class="card border-0 shadow rounded">
                    <div class="card-body">
                        <h4 class="font-weight-bold">Daftar Operator PMB</h4>
                        <hr>
                        <form action="{{ route('register') }}" method="POST">
                            @csrf
            
                            <div class="row">
            
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-uppercase">Nama Lengkap </label>
                                        <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan Nama Lengkap">
                                        @error('name')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>    
                                        @enderror
                                    </div>
                                </div>
            
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-uppercase">Email address</label>
                                        <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan Alamat Email">
                                        @error('email')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>    
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-uppercase">Nomor Handphone  </label>
                                        <input type="text" name="handphone" value="{{ old('handphone') }}" class="form-control @error('handphone') is-invalid @enderror" placeholder="Masukkan Nomor Handphone">
                                        @error('handphone')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>    
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-uppercase">Role Akun </label>
                                        <select class="form-control" id="role" name="role" placeholder="Role Akun" required>
                                           
                                            <option value="PMB" >
                                                Operator PMB
                                            </option>
                                            <option value="YPSA" >
                                                Operator Keuangan
                                            </option>
                                       
    
                                        </select>
    
                                    </div>
                                </div>
            
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-uppercase">Password</label>
                                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan Password">
                                        @error('password')
                                            <div class="alert alert-danger mt-2">
                                                {{ $message }}
                                            </div>    
                                        @enderror
                                    </div>
                                </div>
            
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-uppercase">Konfirmasi Password</label>
                                        <input type="password" name="password_confirmation" class="form-control" placeholder="Masukkan Konfirmasi Password">
                                    </div>
                                </div>
            
                            </div>
                            
                            <button type="submit" class="btn btn-primary">REGISTER</button>
                        </form>
                    </div>
                </div>
                <div class="login mt-3 text-center">
                    <p>Sudah punya akun ? Login <a href="/login">Disini</a></p>
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
