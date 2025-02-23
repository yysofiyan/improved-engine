@extends('layouts.master-front')

@section('title', 'Pendaftaran Mahasiswa Baru')

@section('content')
<head>
    <!-- Memuat jQuery versi terbaru -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" 
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" 
        crossorigin="anonymous"></script>
        
    <!-- Memuat Bootstrap CSS versi terbaru -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
        crossorigin="anonymous">
        
    <!-- Memuat Font Awesome untuk ikon -->
    <link rel="stylesheet" 
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" 
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" 
        crossorigin="anonymous" 
        referrerpolicy="no-referrer" />
    <!-- Load Tailwind CSS dari CDN -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>

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
                        
                        <div class="alert alert-error" role="alert">
                            <!-- <a href="{{ url('files/flyer_unsap_2024.pdf') }}" class="btn btn-warning" target="_blank"> 
                                <i class="fa-solid fa-receipt fa-fw mr-2"></i> Download Brosur PMB
                            </a> -->
                        </div>

                        <div class="text-center mb-4">
                            <div class="d-flex flex-column align-items-center">
                                <img src="{{ asset('images/logo-importer-mini.png') }}" 
                                     alt="UNSAP" 
                                     class="logo mb-3" 
                                     style="width: 100px; height: auto;">
                                
                                <div class="d-flex justify-content-center align-items-center mb-4 text-daintree">
                                    <div class="text-end pe-3">
                                        <h6 class="m-0 text-uppercase fw-bold">pmb</h6>
                                        <h6 class="m-0 text-uppercase fw-bold">{{ env('TAHUN_AKTIF')}}</h6>
                                    </div>
                                    <div class="border-l-4 border-yellow-400 pl-3">
                                        <h6 class="m-0 font-bold">Sistem Penerimaan Mahasiswa Baru</h6>
                                        <h6 class="m-0 font-bold">Universitas Sebelas April</h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <form id="MabaForm" name="MabaForm" class="space-y-4">
                            <!-- Field NIK -->
                            <div class="form-group">
                                <label class="font-weight-bold text-uppercase">Nomor Induk Kependudukan (NIK)</label>
                                <input type="text" name="nik" value="{{ old('nik') }}" 
                                       class="form-control @error('nik') is-invalid @enderror" 
                                       placeholder="Masukkan NIK" required>
                                @error('nik')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>    
                                @enderror
                            </div>

                            <!-- Field Nama Lengkap -->
                            <div class="form-group">
                                <label class="font-weight-bold text-uppercase">Nama Lengkap (Sesuai KTP)</label>
                                <input type="text" name="nama_mahasiswa" value="{{ old('nama_mahasiswa') }}" 
                                       class="form-control @error('nama_mahasiswa') is-invalid @enderror" 
                                       placeholder="Masukkan Nama Lengkap" required> 
                                @error('nama_mahasiswa')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>    
                                @enderror
                            </div>

                            <!-- Field Jenis Kelamin -->
                            <div class="form-group">
                                <label class="font-weight-bold text-uppercase">Jenis Kelamin</label>
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="radio" class="form-check-input" 
                                                           name="jenis_kelamin" value="L" checked>
                                                    Laki-Laki
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="radio" class="form-check-input" 
                                                           name="jenis_kelamin" value="P">
                                                    Perempuan
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Field Nomor Whatsapp -->
                            <div class="form-group">
                                <label class="font-weight-bold text-uppercase">Nomor Whatsapp (Tanpa Awalan +62 atau 0)</label>
                                <input type="number" name="handphone" value="{{ old('handphone') }}" 
                                       class="form-control @error('handphone') is-invalid @enderror" 
                                       placeholder="Contoh. 81394543943" required>
                                @error('handphone')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>    
                                @enderror
                            </div>

                            <!-- Field Program Studi -->
                            <div class="form-group">
                                <label class="font-weight-bold text-uppercase">Fakultas / Program Studi</label>
                                <select class="form-control @error('kodeprodi_satu') is-invalid @enderror" 
                                        name="kodeprodi_satu" required>
                                    <option value="">-- Pilih Program Studi --</option>
                                    @foreach ($prodi as $item)
                                        <option value="{{ $item->kode_prodi }}" {{ old('kodeprodi_satu') == $item->kode_prodi ? 'selected' : '' }}>
                                            {{ $item->nama_fakultas.' - '.$item->nama_prodi.' ('.$item->nama_jenjang.')' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kodeprodi_satu')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Field Captcha -->
                            {{-- <div class="form-group">
                                <label class="font-weight-bold text-uppercase">Captcha</label>
                                <div class="col-md-6 captcha d-flex align-items-center">
                                    <span>{!! captcha_img() !!}</span>
                                    <button type="button" class="btn btn-primary reload ml-2" id="reload">
                                        &#x21bb;
                                    </button>
                                </div>
                            </div> --}}

                            <!-- Field Turnstile Cloudflare -->
                            <div class="form-group">
                                <label class="font-weight-bold text-uppercase">Verifikasi Keamaman</label>
                                <div style="display: block; flex-flow: row;">
                                    <div class="cf-turnstile" data-sitekey="0x4AAAAAAA-RoAZoVha2NyLa" data-size="flexible" data-theme="light"></div>
                                </div>
                                <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
                            </div>
                            
                            <!-- Tombol Submit -->
                            <button type="submit" id="saveBtn" class="btn btn-primary btn-block"> DAFTAR </button>
                            <hr>
                        </form>
                    </div>
                </div>

                <div class="register mt-3 text-center">
                    <p>Sudah punya akun? Silahkan Login <a href="/login-maba">Disini</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page-stylesheet')
@endpush

@push('page-script')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
    $(function () {
        // Setup AJAX dengan header CSRF token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Event handler untuk tombol submit
        $('#saveBtn').click(function (e) {
            e.preventDefault();
            $('#saveBtn').html('Mengirim Data ..');

            // Kirim data form via AJAX
            $.ajax({
                data: $('#MabaForm').serialize(),
                url: "simpan-daftar",
                type: "POST",
                dataType: 'json',
                success: function (data) {
                    // Reset form setelah sukses
                    $('#MabaForm').trigger("reset");
                    
                    // Tampilkan notifikasi sukses
                    Swal.fire(
                        'Pendaftaran Berhasil',
                        'Silahkan klik tombol OK',
                        'success'
                    ).then(function (result) {
                        if (result.value) {
                            window.location = "maba/dashboard";
                        }
                    });
                    
                    // Kembalikan teks tombol
                    $('#saveBtn').html('Daftar');
                },
                error: function (data) {
                    // Tampilkan notifikasi error
                    Swal.fire(
                        'Terdapat Kesalahan',
                        data.responseJSON.message,
                        'error'
                    );
                    
                    // Kembalikan teks tombol
                    $('#saveBtn').html('Daftar');
                }
            });
        });
    });
  </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script type="text/javascript">
  let input = document.getElementById('captcha');
    $('#reload').click(function () {
        $.ajax({
            type: 'GET',
            url: 'reload-captcha',
            success: function (data) {
                $(".captcha span").html(data.captcha);
                input.value = '';
                input.focus();
            }
        });
    });
</script>

@endpush