@extends('layouts.master-admin')

@section('title', 'Form Panitia PMB UNSAP YPSA')

@section('content')
<div class="content-wrapper d-flex align-items-center auth px-0">
    <div class="col-12 grid-margin">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Form Panitia PMB UNSAP YPSA</h4>
            <form id="MabaForm" name="MabaForm" >
                <input type="hidden" name="id" value="{{ old('id',$data->id) }}" >

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold text-uppercase">Nama Lengkap </label>
                            <input type="text" name="name" value="{{ old('name',$data->name) }}" class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan Nama Lengkap">
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
                            <input type="email" name="email" value="{{ old('email',$data->email )}}" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan Alamat Email">
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
                            <input type="text" name="handphone" value="{{ old('handphone',$data->handphone) }}" class="form-control @error('handphone') is-invalid @enderror" placeholder="Masukkan Nomor Handphone">
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
                            <select class="form-control" id="role" name="role" placeholder="Role Akun"  onchange="showDiv('hidden_div', this)"  required>
                                <option value="ADMIN" {{ $data->role=="ADMIN" ? 'selected' : '' }} >
                                    Operator Admin
                                </option>
                                <option value="YPSA" {{ $data->role=="YPSA" ? 'selected' : '' }} >
                                    Operator Yayasan
                                </option>
                                <option value="PMB" {{ $data->role=="PMB" ? 'selected' : '' }} >
                                    Operator PMB
                                </option>
                           

                            </select>

                        </div>
                    </div>
                   
                

                

                </div>
                
                <button type="submit" id="saveBtn" class="btn btn-primary">Simpan</button>
            </form>




          </div>
        </div>
      </div>

  </div>
@endsection

@push('page-stylesheet')
<style type="text/css">
    #hidden_div {
        display: none;
    }
    
    </style>
@endpush

@push('page-script')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    function showDiv(divId, element)
    {
        document.getElementById(divId).style.display = element.value == "FAKULTAS" ? 'block' : 'none';
    }
    
</script>

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
            url: "/admin/panitia",
            type: "POST",
            dataType: 'json',
            success: function (data) {
                if (data.status=='200')
              {
                  $('#MabaForm').trigger("reset");
                Swal.fire(
                    'Tambah/Ubah Pengguna Berhasil',
                    'Silahkan klik tombol OK ' + data.success,
                    'success'
                ).then(function (result) {
                if (result.value) {
                    window.location = '/admin/panitia';
                }
            })
              }else
              {
                  Swal.fire(
                    'Terdapat Kesalahan',
                    data.error,
                    'error'
                )
              }
                $('#saveBtn').html('Simpan');

        
            },
            error: function (data) {
                console.log('Error:', data);
                Swal.fire(
                    'Terdapat Kesalahan',
                    data.responseJSON.message,
                    'error'
                )
                $('#saveBtn').html('Simpan');
            }
        });
      });


    });
  </script>
  
@endpush
