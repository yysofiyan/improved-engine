@extends('layouts.master-front')

@section('title', 'Login Fakultas UNSAP')

@section('content')

    <head>
        <!-- Memuat jQuery dan Bootstrap CSS dengan versi terbaru -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>

    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
            <div class="row w-100 mx-0">
                <div class="col-lg-8 col-xl-6 mx-auto">
                    <div class="card border-0 shadow rounded">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="text-center mb-4">
                            <div class="d-flex flex-column align-items-center">
                                <img src="{{ asset('images/logo-importer-mini.png') }}" alt="UNSAP" class="logo mb-3"
                                    style="width: 80px; height: auto;">

                                <div class="d-flex flex-column flex-md-row justify-content-center align-items-center mb-4 text-daintree">
                                    <div class="text-center text-md-end pe-md-3 mb-2 mb-md-0">
                                        <h6 class="m-0 text-uppercase fw-bold">pmb</h6>
                                        <h6 class="m-0 text-uppercase fw-bold">{{ date('Y') }}</h6>
                                    </div>
                                    <div class="border-start border-3 border-warning ps-md-3 text-center text-md-start">
                                        <h6 class="m-0 fw-bold">Sistem Penerimaan</h6>
                                        <h6 class="m-0 fw-bold">Mahasiswa Baru</h6>
                                        <h6 class="m-0 fw-bold">Universitas Sebelas April</h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="auth-content px-3 px-md-4">
                            <h3 class="auth-title">LOGIN FAKULTAS</h3>
                            <p class="auth-subtitle">Silahkan Login Menggunakan Email Institusi UNSAP</p>

                            <div class="google-btn-container text-center mb-4">
                                <a href="{{ url('authorized/google') }}"
                                    class="google-auth-btn d-inline-flex align-items-center p-2 p-md-3 rounded text-decoration-none">
                                    <span class="google-icon me-2 me-md-3">
                                        <svg xmlns="http://www3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.9 3.28-4.74 3.28-8.09z" />
                                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                                        </svg>
                                    </span>
                                    <span class="btn-text fw-medium">Lanjutkan dengan Google</span>
                                </a>
                            </div>
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-uppercase">Verifikasi Keamanan</label>
                                <div class="d-flex justify-content-center">
                                    <div class="cf-turnstile" 
                                        data-sitekey="0x4AAAAAAA-RoAZoVha2NyLa" 
                                        data-size="normal" 
                                        data-theme="light"
                                        style="width: 100%; max-width: 300px;">
                                    </div>
                                </div>
                                <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .auth-wrapper {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin: 1rem;
        }

        .auth-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }

        .auth-content {
            text-align: center;
            padding: 0 1rem;
        }

        .auth-title {
            color: #2b3445;
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-size: 1.5rem;
        }

        .auth-subtitle {
            color: #6b7280;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .google-auth-btn {
            display: inline-flex;
            align-items: center;
            background: #ffffff;
            color: #3c4043;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 300px;
            justify-content: center;
        }

        .google-auth-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        @media (min-width: 768px) {
            .auth-card {
                padding: 2.5rem;
            }
            
            .auth-title {
                font-size: 1.8rem;
            }
            
            .auth-subtitle {
                font-size: 0.95rem;
            }
            
            .google-auth-btn {
                padding: 12px 24px;
            }
        }

        @media (max-width: 576px) {
            .logo {
                width: 60px;
            }
            
            .auth-card {
                border-radius: 15px;
                margin: 0.5rem;
            }
            
            .auth-title {
                font-size: 1.3rem;
            }
            
            .auth-subtitle {
                font-size: 0.85rem;
            }
        }
    </style>
@endsection
