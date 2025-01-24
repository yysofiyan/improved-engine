@extends('layouts.master-dashboard')

@section('title', 'Form Registrasi Sekolah')

@section('content')
<div class="content-wrapper d-flex align-items-center auth px-0">
    <div class="col-12 grid-margin">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Form Registrasi Sekolah</h4>
            <form id="MabaForm" name="MabaForm">
                        
                <div class="form-group">
                    <label class="font-weight-bold text-uppercase">Nama Sekolah</label>
                    <input type="text" name="sekolah" value="{{ old('sekolah') }}" class="form-control @error('sekolah') is-invalid @enderror" placeholder="Masukkan sekolah" required>
                    @error('sekolah')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>    
                    @enderror
                </div>
                <div class="form-group">
                    <label class="font-weight-bold text-uppercase">Kabupaten</label>
                    <input type="text" name="kabupaten_kota" value="{{ old('kabupaten_kota') }}" class="form-control @error('kabupaten_kota') is-invalid @enderror" placeholder="Masukkan Kabupaten " required> 
                    @error('kabupaten_kota')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>    
                    @enderror
                </div>
                <div class="form-group">
                    <label class="font-weight-bold text-uppercase">Kecamatan</label>
                    <input type="text" name="kecamatan" value="{{ old('kecamatan') }}" class="form-control @error('kecamatan') is-invalid @enderror" placeholder="Masukkan Kecamatan " required> 
                    @error('kecamatan')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>    
                    @enderror
                </div>
                <div class="form-group">
                    <label class="font-weight-bold text-uppercase">Jenis Sekolah</label>
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <label class="form-check-label">
                                      <input type="radio" class="form-check-input" name="bentuk" id="membershipRadios1" value="SMA" checked>
                                      SMA
                                    <i class="input-helper"></i></label>
                                  </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <label class="form-check-label">
                                      <input type="radio" class="form-check-input" name="bentuk" id="membershipRadios1" value="SMK" >
                                      SMK
                                    <i class="input-helper"></i></label>
                                  </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <label class="form-check-label">
                                      <input type="radio" class="form-check-input" name="bentuk" id="membershipRadios1" value="MA" >
                                      MA
                                    <i class="input-helper"></i></label>
                                  </div>
                            </div>

                        </div>



                    </div>
                </div>
                
                

                
               
                <button type="submit" id="saveBtn" class="btn btn-primary"> SIMPAN </button>
                <hr>

    

            </form>




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
            url: "simpan-sekolah",
            type: "POST",
            dataType: 'json',
            success: function (data) {
                $('#MabaForm').trigger("reset");
                console.log(data);
                Swal.fire(
                    'Register Sekolah Berhasil',
                    'Silahkan klik tombol OK',
                    'success'
                ).then(function (result) {
                if (result.value) {
                    window.location = "data-sekolah";
                }
            })
                
                $('#saveBtn').html('Simpan');
        
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
  
@endpush
