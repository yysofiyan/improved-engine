@extends('layouts.master-keuangan')

@section('title', 'Form Generate NIM Calon Mahasiswa UNSAP')

@section('content')
<div class="content-wrapper d-flex align-items-center auth px-0">
    <div class="col-12 grid-margin">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Form Generate NIM Calon Mahasiswa UNSAP</h4>
            <form id="MabaForm" name="MabaForm">
                        
                <div class="form-group">
                    <label class="font-weight-bold text-uppercase">Nomor PIN</label>
                    <input type="text" name="pin" value="{{ old('pin') }}" class="form-control @error('pin') is-invalid @enderror" placeholder="Masukkan Nomor Pin Pendaftaran" required>
                    @error('pin')
                        <div class="alert alert-danger mt-2">
                            {{ $message }}
                        </div>    
                    @enderror
                </div>
                
                <button type="submit" id="saveBtn" class="btn btn-primary"> Buat NIM </button>
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
            url: "simpan-nim",
            type: "POST",
            dataType: 'json',
            success: function (data) {
                $('#MabaForm').trigger("reset");
                if (data.status=='200')
                {
                    Swal.fire(
                    'Pembuatan NIM Berhasil',
                    'Silahkan klik tombol OK  ' + data.success,
                    'success'
                ).then(function (result) {
                if (result.value) {
                    window.location = "buatnim";
                }
            })
                }else
                {
                    Swal.fire(
                    'Terdapat Kesalahan',
                    data.success,
                    'error'
                )
                }
               
               
                
                $('#saveBtn').html('Buat NIM');
        
            },
            error: function (data) {
                //console.log('Error:', data);
                Swal.fire(
                    'Terdapat Kesalahan',
                    data.responseJSON.message,
                    'error'
                )
                $('#saveBtn').html('Buat NIM');
            }
        });
      });


    });
  </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  
@endpush
