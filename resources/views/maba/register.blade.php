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
						 <div class="alert alert-error" role="alert">
                            <a href="{{ url('files/flyer_unsap_2024.pdf') }}" class="btn btn-warning" target="_blank"> <i class="fa-solid fa-receipt fa-fw mr-2"></i> Download Brosur PMB</a>
                        </div>
                        <h4 class="font-weight-bold">DAFTAR CALON MAHASISWA BARU</h4>
                        <hr>
                        <form id="MabaForm" name="MabaForm">
                        
                            <div class="form-group">
                                <label class="font-weight-bold text-uppercase">Nomor Induk Kependudukan (NIK)</label>
                                <input type="text" name="nik" value="{{ old('nik') }}" class="form-control @error('nik') is-invalid @enderror" placeholder="Masukkan NIK" required>
                                @error('nik')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>    
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold text-uppercase">Nama Lengkap (Sesuai KTP)</label>
                                <input type="text" name="nama_mahasiswa" value="{{ old('nama_mahasiswa') }}" class="form-control @error('nama_mahasiswa') is-invalid @enderror" placeholder="Masukkan Nama Lengkap " required> 
                                @error('nama_mahasiswa')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>    
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold text-uppercase">Jenis Kelamin</label>
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                  <input type="radio" class="form-check-input" name="jenis_kelamin" id="membershipRadios1" value="L" checked>
                                                  Laki-Laki
                                                <i class="input-helper"></i></label>
                                              </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                  <input type="radio" class="form-check-input" name="jenis_kelamin" id="membershipRadios1" value="P" >
                                                  Perempuan
                                                <i class="input-helper"></i></label>
                                              </div>
                                        </div>
        
                                    </div>
        
        
        
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold text-uppercase">Nomor Whatsapp (Tanpa Awalan +62 atau 0)</label>
                                <input type="number" name="handphone" value="{{ old('handphone') }}" class="form-control @error('handphone') is-invalid @enderror" placeholder="Masukkan Nomor Whatsapp Tanpa Awalan" required>
                                @error('handphone')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>    
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold text-uppercase">Fakultas / Program Studi</label>
                                <select class="form-control @error('kodeprodi') form-control-danger @enderror" name="kodeprodi" data-display="static">
                                @foreach ($prodi as $prodi)
                                <option value="{{ $prodi->config }}">
                            {{ $prodi->nama_fakultas.' - '.$prodi->nama_prodi.'('. $prodi->nama_jenjang.')' }}
                        </option>
                        @endforeach

                        
                                </select>

                                @error('kodeprodi')
                                    <div class="alert alert-danger mt-2">
                                        {{ $message }}
                                    </div>    
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold text-uppercase">Captcha</label>
                                <div class="col-md-6 captcha">
                                    <span>{!! captcha_img() !!}</span>
                                    <button type="button" class="btn btn-primary" class="reload" id="reload">
                                    &#x21bb;
                                    </button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold text-uppercase">Masukan Captcha</label>
                                <input id="captcha" type="text" class="form-control" placeholder="Masukan Captcha" name="captcha">
                            </div>
                            
                            <button type="submit" id="saveBtn" class="btn btn-primary"> DAFTAR </button>
                            <hr>
    
                
            
                        </form>
                    </div>
                </div>
                <div class="register mt-3 text-center">
                    <p>Sudah punya akun ? Silahkan Login <a href="/login-maba">Disini</a></p>
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
        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });

      $('#saveBtn').click(function (e) {
          e.preventDefault();
          $('#saveBtn').html('Mengirim Data ..');

          $.ajax({
            data: $('#MabaForm').serialize(),
            url: "simpan-daftar",
            type: "POST",
            dataType: 'json',
            success: function (data) {
                $('#MabaForm').trigger("reset");
                Swal.fire(
                    'Pendaftaran Berhasil',
                    'Silahkan klik tombol OK',
                    'success'
                ).then(function (result) {
                if (result.value) {
                    window.location = "maba/dashboard";
                }
            })
                
                $('#saveBtn').html('Daftar');
        
            },
            error: function (data) {
                //console.log('Error:', data);
                Swal.fire(
                    'Terdapat Kesalahan',
                    data.responseJSON.message,
                    'error'
                )
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


