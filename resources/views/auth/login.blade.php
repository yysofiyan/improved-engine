@extends('layouts.master-front')

@section('title', 'Penerimaan Mahasiswa Baru - UNSAP')

@section('content')
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center auth-wrapper">
    <div class="row w-100 justify-content-center">
        <div class="col-xl-6 mx-auto">
            <div class="auth-card">
                <div class="logo-container">
                    <img src="{{ url('images/logo-importer-mini.png') }}" 
                         alt="UNSAP Logo" 
                         class="auth-logo">
                </div>
                
                <div class="auth-content">
                    <h3 class="auth-title">LOGIN OPERATOR PMB UNSAP</h3>
                    <hr class="auth-divider">
                    
                    <div class="alert alert-success auth-alert" role="alert">
                        <h4 class="alert-text">Login untuk Fakultas silahkan klik <a href="/login-fakultas">Disini</a></h4> 
                    </div>

                    @if (session('status'))
                    <div class="alert alert-success auth-alert" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <form action="{{ url('login-operator') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Email address</label>
                            <input type="email" name="email" value="{{ old('email') }}" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   placeholder="Masukkan Alamat Email">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>    
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   placeholder="Masukkan Password">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>    
                            @enderror
                        </div>
                        
                        <button type="submit" class="auth-btn">LOGIN</button>
                        
                        <div class="text-center mt-3">
                            {{-- <a href="/forgot-password" class="auth-link">Lupa Password?</a> --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.auth-wrapper {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.auth-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 20px;
    padding: 2.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: transform 0.3s ease;
}

.auth-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
}

.logo-container {
    text-align: center;
    margin-bottom: 2rem;
}

.auth-logo {
    width: 120px;
    height: auto;
    transition: transform 0.3s ease;
}

.auth-logo:hover {
    transform: scale(1.05);
}

.auth-title {
    color: #2b3445;
    font-weight: 700;
    margin-bottom: 1.5rem;
    font-size: 1.8rem;
    text-align: center;
}

.auth-divider {
    border-color: rgba(0,0,0,0.1);
    margin: 1.5rem 0;
}

.form-label {
    color: #4a5568;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.form-control {
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    padding: 12px 16px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #4299e1;
    box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
}

.invalid-feedback {
    margin-top: 0.5rem;
    font-size: 0.875rem;
}

.auth-btn {
    width: 100%;
    background: #4299e1;
    color: white;
    padding: 14px 20px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.auth-btn:hover {
    background: #3182ce;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(66, 153, 225, 0.3);
}

.auth-btn:active {
    transform: translateY(0);
}

.auth-alert {
    border-radius: 8px;
    border: none;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

.alert-text {
    font-size: 1rem;
    margin-bottom: 0;
}

.auth-link {
    color: #4299e1;
    text-decoration: none;
    font-weight: 500;
}

.auth-link:hover {
    text-decoration: underline;
}

@media (max-width: 576px) {
    .auth-card {
        padding: 1.5rem;
        border-radius: 15px;
    }
    
    .auth-title {
        font-size: 1.5rem;
    }
}
</style>
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