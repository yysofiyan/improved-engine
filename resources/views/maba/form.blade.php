@extends('layouts.master-maba')

@section('title', 'Form Biodata Mahasiswa UNSAP')

@section('content')
<div class="content-wrapper d-flex align-items-center auth px-0">
    <div class="col-12 grid-margin">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Konfirmasi Identitas Mahasiswa Baru UNSAP</h4>
            <form class="form-sample" action="{{ url('update-mhs') }}" method="post">
                @csrf
                @method('PUT')
                <input type="hidden" class="form-control form-control-danger" name="id" value="{{old('id', $mhs->id)}}" >
              <p class="card-description">
                Tahun Akademik 2024/2025
              </p>
              <div class="row">
                <div class="form-group col-lg-4 @error('nim') has-danger @enderror">
                    <div class="form-group @error('nim') has-danger @enderror">
                        <label class="col-sm-12 "><strong>Nomor Induk Mahasiswa (NIM)</strong></label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control form-control-danger" name="nim" value="{{old('nim', $mhs->nim)}}" readonly>
                            @error('nim')
                            <label class="error text-danger">{{ $message }}</label>
                            @enderror
                        </div>
                    </div>


                </div>
                <div class="form-group col-lg-4 @error('nisn') has-danger @enderror">
                    <label class="col-sm-12 "><strong>Nomor Induk Siswa Nasional (NISN) </strong></label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control form-control-danger" name="nisn" value="{{old('nisn', $mhs->nisn)}}">
                            @error('nisn')
                            <label class="error text-danger">{{ $message }}</label>
                            @enderror
                        </div>


                </div>
                <div class="form-group col-lg-4 @error('nik') has-danger @enderror">
                    <label class="col-sm-12 "><strong>Nomor Induk Kependudukan (NIK) </strong></label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control form-control-danger" name="nik" value="{{old('nik', $mhs->nik)}}">
                        @error('nik')
                        <label class="error text-danger">{{ $message }}</label>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-lg-4 @error('nama_lengkap') has-danger @enderror">
                    <div class="form-group @error('nama_lengkap') has-danger @enderror">
                        <label class="col-sm-12 "><strong>Nama Lengkap  </strong></label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control form-control-danger" name="nama_lengkap" value="{{old('nama_mahasiswa', $mhs->nama_mahasiswa)}}">
                            @error('nama_lengkap')
                            <label class="error text-danger">{{ $message }}</label>
                            @enderror
                        </div>
                    </div>


                </div>
                <div class="form-group col-lg-4 @error('tempat_lahir') has-danger @enderror">
                    <label class="col-sm-12 "><strong>Tempat Lahir</strong></label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control form-control-danger" name="tempat_lahir" value="{{old('tempat_lahir', $mhs->tempat_lahir)}}">
                            @error('tempat_lahir')
                            <label class="error text-danger">{{ $message }}</label>
                            @enderror
                        </div>


                </div>

                <div class="form-group col-lg-4 @error('tanggal_lahir') has-danger @enderror">
                    <label class="col-sm-12 "><strong>Tanggal Lahir</strong></label>
                        <div class="col-sm-12">
                            <input type="date" class="form-control form-control-danger" name="tanggal_lahir" data-date-format="yyyy-mm-dd" value="{{old('tanggal_lahir', $mhs->tanggal_lahir)}}">
                            @error('tanggal_lahir')
                            <label class="error text-danger">{{ $message }}</label>
                            @enderror
                        </div>


                </div>

            </div>


            <div class="row">

                <div class="form-group col-lg-4 @error('jeniskelamin') has-danger @enderror">
                    <label class="col-sm-12 "><strong>Jenis Kelamin</strong></label>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                          <input type="radio" class="form-check-input" name="jenis_kelamin" id="membershipRadios1" value="L" {{ $mhs->jenis_kelamin == 'L' ? 'checked':'' }}>
                                          Laki-Laki
                                        <i class="input-helper"></i></label>
                                      </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                          <input type="radio" class="form-check-input" name="jenis_kelamin" id="membershipRadios1" value="P" {{ $mhs->jenis_kelamin == 'P' ? 'checked':'' }}>
                                          Perempuan
                                        <i class="input-helper"></i></label>
                                      </div>
                                </div>

                            </div>



                        </div>


                </div>

                <div class="form-group col-lg-4 @error('handphone') has-danger @enderror">
                    <label class="col-sm-12 "><strong>Nomor Handphone (Ex: 085666xxxxx)</strong></label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control form-control-danger" name="handphone" value="{{old('handphone', $mhs->handphone)}}">
                            @error('handphone')
                            <label class="error text-danger">{{ $message }}</label>
                            @enderror
                        </div>


                </div>
                <div class="form-group col-lg-4 @error('agama') has-danger @enderror">
                    <label class="col-sm-12 "><strong>Agama</strong></label>
                    <div class="col-sm-12">
                        <select class="form-control @error('agama') form-control-danger @enderror"
                        name="agama" data-display="static">
                        @foreach ($agama['data'] as $agama)
                        <option value="{{ $agama['id_agama'] }}" {{ $mhs->agama==$agama['id_agama']
                            ? 'selected' : '' }}>
                            {{ $agama['nama_agama'] }}
                        </option>
                        @endforeach
                    </select>
                    @error('agama')
                    <label class="error mt-2 text-danger">{{ $message }}</label>
                    @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-lg-4 @error('kelurahan') has-danger @enderror">
                    <div class="form-group @error('kelurahan') has-danger @enderror">
                        <label class="col-sm-12 "><strong>Desa/Kelurahan </strong></label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control form-control-danger" name="kelurahan" value="{{old('kelurahan', $mhs->kelurahan)}}">
                            @error('kelurahan')
                            <label class="error text-danger">{{ $message }}</label>
                            @enderror
                        </div>
                    </div>


                </div>
                <div class="form-group col-lg-4 @error('nama_ibu_kandung') has-danger @enderror">
                    <label class="col-sm-12 "><strong>Nama Ibu Kandung</strong></label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control form-control-danger" name="nama_ibu_kandung" value="{{old('nama_ibu_kandung', $mhs->nama_ibu_kandung)}}">
                            @error('nama_ibu_kandung')
                            <label class="error text-danger">{{ $message }}</label>
                            @enderror
                        </div>


                </div>
                <div class="form-group col-lg-4 @error('jenis_daftar') has-danger @enderror">
                    <label class="col-sm-12 "><strong>Jenis Pendaftaran</strong></label>
                    <div class="col-sm-12">
                        <select class="form-control @error('jenis_daftar') form-control-danger @enderror" name="jenis_daftar" data-display="static">
                        @foreach ($jenisdaftar['data'] as $jenis)
                        <option value="{{ $jenis['id_jenis_daftar'] }}" {{ $mhs->jenis_daftar==$jenis['id_jenis_daftar']
                            ? 'selected' : '' }}>
                            {{ $jenis['nama_jenis_daftar'] }}
                        </option>
                        @endforeach
                    </select>
                    </div>
                </div>
            </div>

            <p class="card-description">
                Wilayah
              </p>
              @if ($mhs->konfirmasi=='0')
              <div class="row">
                <div class="form-group col-lg-3" id="level1">
                    <label>Negara</label>
                    <select class="form-control" id="negara" name="negara" required>
                        <option value="ID" >Indonesia</option>
                        @foreach ($negara['data'] as $negara)
                        <option value="{{ $negara['id_wilayah'] }} {{ $mhs->negara==$negara['id_wilayah']
                            ? 'selected' : '' }}">
                            {{ $negara['nama_wilayah'] }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-lg-3" id="level2">
                    <label>Provinsi</label>
                    <select class="form-control" id="provinsi" name="provinsi" required>
                        @foreach ($provinsi['data'] as $provinsi)
                        <option value="{{ $provinsi['id_wilayah'] }}" {{ $mhs->provinsi==$provinsi['id_wilayah']
                            ? 'selected' : '' }}>
                            {{ $provinsi['nama_wilayah'] }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-lg-3" id="level3">
                    <label>Kota/Kabupaten</label>
                    <select class="form-control" id="kota" name="kota" required>
                        @foreach ($kota['data'] as $kota)
                        <option value="{{ $kota['id_wilayah'] }}" {{ $mhs->kota==$kota['id_wilayah']
                            ? 'selected' : '' }}>
                            {{ $kota['nama_wilayah'] }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-lg-3" id="level4">
                    <label>Kecamatan</label>
                    <select class="form-control" id="kecamatan" name="kecamatan" required>
                        @foreach ($kecamatan['data'] as $kecamatan)
                        <option value="{{ $kecamatan['id_wilayah'] }}" {{ $mhs->kecamatan==$kecamatan['id_wilayah']
                            ? 'selected' : '' }}>
                            {{ $kecamatan['nama_wilayah'] }}
                        </option>
                        @endforeach
                    </select>

                </div>
            </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-check form-check-flat form-check-primary">

                    <label class="form-check-label">
                      <input type="checkbox" name="konfirmasi" value="1" class="form-check-input" required>
                      Saya menyetujui data yang sudah diisi pada form di atas dan bertanggung jawab terhadap isi data tersebut
                    <i class="input-helper"></i></label>
                  </div>

                  <button type="submit" class="btn btn-primary mr-2"> <i class="fa-solid fa-save fa-fw mr-2"></i> Submit</button>
                  <a href="{{ url('logout')}}" class="btn btn-light">Cancel</a>
                </div>

              </div>
              @else
              <div class="row">
                <div class="form-group col-lg-3" id="level1">
                    <label><strong>Negara</strong></label>
                    @if ($mhs->negara=='ID')
                    <h4>Indonesia</h4>
                    @else
                    <p>Negara Belum Dipilih </p>
                    @endif


                    </select>
                </div>
                <div class="form-group col-lg-3" id="level2">
                    <label>Provinsi</label>
                    @foreach ($provinsi['data'] as $provinsi)
                    <h4>{{ $provinsi['nama_wilayah'] }}</h4>
                        @endforeach

                    </select>
                </div>
                <div class="form-group col-lg-3" id="level3">
                    <label>Kota/Kabupaten</label>
                    @foreach ($kota['data'] as $kota)
                    <h4>{{ $kota['nama_wilayah'] }}</h4>
                        @endforeach

                    </select>
                </div>
                <div class="form-group col-lg-3" id="level4">
                    <label>Kecamatan</label>
                    @foreach ($kecamatan['data'] as $kecamatan)
                    <h4>{{ $kecamatan['nama_wilayah'] }}</h4>
                    @endforeach

                    </select>

                </div>
            </div>
              @endif

            </form>




          </div>
        </div>
      </div>

  </div>
@endsection

@push('page-stylesheet')
@endpush

@push('page-script')
<script>
    $(document).ready(function() {


        $('#negara').on('change', function() {
            $('#wilayah').html('');

            let negara = $(this).val();

            if(negara == 'ID') {


                $.ajax({
                    type: 'get',
                    url: 'ref-wilayah-provinsi',
                    dataType: 'json',
                    beforeSend: function () {
                        $('#provinsi').html('');
                    },
                    success: function(response) {
                        $('#level2').show('');
                        $.each(response.data, function(i, val) {
                            $('#provinsi').append(
                                `
                                <option value="${val.id_wilayah}">${val.nama_wilayah}</option>
                                `
                            );
                        });
                        $('#provinsi').selectpicker('refresh');
                        $('#provinsi').selectpicker('render');
                    }
                });
            } else {
                $('#provinsi').html('');

                $('#wilayah').html(negara);
            }
        });

        $('#provinsi').on('change', function() {
            let data = {
                provinsi: $(this).val(),
            }

            $.ajax({
                type: 'get',
                url: 'ref-wilayah-kota',
                data: data,
                dataType: 'json',
                beforeSend: function () {
                    $('#kota').html('');
                    $('#wilayah').html('');

                },
                success: function(response) {
                    $('#level3').show('');
                    $.each(response.data, function(i, val) {
                        $('#kota').append(
                            `
                            <option value="${val.id_wilayah}">${val.nama_wilayah}</option>
                            `
                        );
                    });
                    $('#kota').selectpicker('refresh');
                    $('#kota').selectpicker('render');
                }
            });
        });

        $('#kota').on('change', function() {
            let data = {
                kota: $(this).val(),
            }

            $.ajax({
                type: 'get',
                url: 'ref-wilayah-kecamatan',
                data: data,
                dataType: 'json',
                beforeSend: function () {
                    $('#kecamatan').html('');
                    $('#wilayah').html('');
                },
                success: function(response) {
                    $('#level4').show('');
                    $.each(response.data, function(i, val) {
                        $('#kecamatan').append(
                            `
                            <option value="${val.id_wilayah}">${val.nama_wilayah}</option>
                            `
                        );
                    });
                    $('#kecamatan').selectpicker('refresh');
                    $('#kecamatan').selectpicker('render');
                }
            });
        });

        $('#kecamatan').on('change', function() {
            let data = {
                kecamatan: $(this).val(),
            }

            $('#wilayah').html(data.kecamatan);
            document.getElementById("wilayah").value=data.kecamatan;
        });
    });
</script>
@endpush
