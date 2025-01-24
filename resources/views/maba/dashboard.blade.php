@extends('layouts.master-maba')

@section('title', 'Dashboard')
@section('content')
    <div class="row" style="margin-top:50px;">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Halo, {{ session('nama_mahasiswa') }}</h3>
                    <h3 class="font-weight-bold">Selamat Datang Di Universitas Sebelas April</h3>
                </div>
                <div class="col-12 col-xl-4">
                    <div class="justify-content-end d-flex">
                        <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                            <button class="btn btn-sm btn-light bg-white" type="button">
                                <span id="tanggal"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Upload Bukti Pembayaran</h4>
                    <div class="card-description">
                        <p>Petunjuk :</p>
                        <ol class="list">
                            <li>Silahkan Melakukan Pembayaran Biaya Pendaftaran dan Aktifasi PIN Melalui Transfer ke No
                                Rekening BNI : <strong style="color: black">22022043</strong> atas nama <strong
                                    style="color: black">Yayasan Pendidikan 11 April</strong> Sejumlah <b>Rp. 200.000</b>
                            </li>
                            <li>Upload Bukti Pembayaran Pada Form ini
                            </li>
                            <li>Silahkan Menunggu Verifikasi Pembayaran Maksimal 2 x 24 Jam
                            </li>
                            <li>Info selanjutnya akan ada notifikasi melalui whatsapp dengan nomor <span
                                    style="background-color: greenyellow;color:black"><strong>0812-2227-0896</strong>
                                </span> <strong style="color: black">(Abaikan jika selain Nomor ini )</strong>
                            </li>
                        </ol>
                    </div>
                    @if ($konfirmasi->verified == '11')
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-success" role="alert">
                                    <h4 class="text-center font-weight-bold m-0">Bukti Bayar Sudah Di Validasi</h4>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-danger" role="alert">
                                    <h4 class="text-center font-weight-bold m-0">Bukti Bayar Belum Di Validasi</h4>
                                </div>
                            </div>
                        </div>
                    @endif
                    <form method="POST" id="UploadBuktiForm" name="UploadBuktiForm" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="form-group col-lg-6 @error('id_channel') has-danger @enderror">
                                <label>Channel Pembayaran</label>
                                <select class="form-control @error('id_channel') form-control-danger @enderror"
                                    name="id_channel" data-display="static">
                                    @foreach ($id_channel as $id_channel)
                                        <option value="{{ $id_channel->id_channel }}"
                                            {{ $konfirmasi->id_channel == $id_channel->id_channel ? 'selected' : '' }}>
                                            {{ $id_channel->nama_channel }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('id_channel')
                                    <label class="error mt-2 text-danger">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-lg-6 @error('pin') has-danger @enderror">
                                <label>Tanggal Bayar</label>
                                <input type="date" class="form-control form-control-danger" name="tanggal_bayar"
                                    data-date-format="yyyy-mm-dd"
                                    value="{{ old('tanggal_bayar', $konfirmasi->tanggal_bayar) }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-lg-6 @error('pin') has-danger @enderror">
                                <label>Nama Rekening Pengirim</label>
                                <input type="text" name="nama_rekening_pengirim"
                                    value="{{ old('nama_rekening_pengirim', $konfirmasi->nama_rekening_pengirim) }}"
                                    class="form-control @error('nama_rekening_pengirim') is-invalid @enderror"
                                    placeholder="Masukkan Nama Rekening Pengirim">
                                @error('nama_rekening_pengirim')
                                    <label class="error mt-2 text-danger">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-lg-6 @error('pin') has-danger @enderror">
                                <label>Upload Bukti Bayar</label>
                                @if ($konfirmasi->verified == '11')
                                    <br />
                                @else
                                    <input type="file" name="bukti_bayar"
                                        class="form-control @error('bukti_bayar') is-invalid @enderror"
                                        placeholder="Upload Bukti Bayar">
                                @endif
                                @error('bukti_bayar')
                                    <label class="error mt-2 text-danger">{{ $message }}</label>
                                @enderror
                                @if ($konfirmasi->bukti_bayar != 'no_photo.png')
                                    <label class="info mt-2 text-white"><a
                                            href="{{ url('images/pmb/' . $konfirmasi->bukti_bayar) }}" target="_blank"> Bukti
                                            Bayar</a></label>
                                @endif

                            </div>
                        </div>
                        @if ($konfirmasi->verified == '11')
                        @else
                            <div class="text-left">
                                <button type="submit" id="saveUpload" class="btn btn-primary">Upload Bukti Bayar</button>
                                <a href="{{ url('keluar') }}" class="btn btn-info">Keluar Aplikasi</a>
                            </div>
                        @endif


                    </form>
                </div>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card position-relative">
                <div class="card-body">
                    <div id="detailedReports" class="carousel slide detailed-report-carousel position-static pt-2"
                        data-ride="carousel">
                        <div class="carousel-inner">

                            <div class="carousel-item active">
                                <div class="row">
                                    <div class="col-md-12 col-xl-12 d-flex flex-column justify-content-start">
                                        <div class="ml-xl-12 mt-3">
                                            <p class="card-title">Identitas Calon Mahasiswa Baru</p>
                                            <table>
                                                <tr>
                                                    <td>
                                                        <h4 class="mb-6 mb-xl-6">PIN </h4>
                                                    </td>
                                                    <td>:</td>
                                                    <td>
                                                        <h4 class="mb-6 mb-xl-6"><b>{{ session('pin') }}</b></h4>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h4 class="mb-6 mb-xl-6">
                                                            <h4 class="mb-6 mb-xl-6">Nomor Pendaftaran </h4>
                                                        </h4>
                                                    </td>
                                                    <td>:</td>
                                                    <td>
                                                        <h4 class="mb-6 mb-xl-6"><b>{{ session('nomor_pendaftaran') }}</b>
                                                        </h4>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h4 class="mb-6 mb-xl-6">NIK </h4>
                                                    </td>
                                                    <td>:</td>
                                                    <td>
                                                        <h4 class="mb-6 mb-xl-6"><b>{{ $pendaftar->nik }}</b></h4>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h4 class="mb-6 mb-xl-6">Nama Lengkap</h4>
                                                    </td>
                                                    <td>:</td>
                                                    <td>
                                                        <h4 class="mb-6 mb-xl-6"><b>{{ $pendaftar->nama_mahasiswa }}</b>
                                                        </h4>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h4 class="mb-6 mb-xl-6">Nomor Handphone :</h4>
                                                    </td>
                                                    <td>:</td>
                                                    <td>
                                                        <h4 class="mb-6 mb-xl-6"><b>+62{{ $pendaftar->handphone }}</b></h4>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h4 class="mb-6 mb-xl-6">STATUS PIN </h4>
                                                    </td>
                                                    <td>:</td>
                                                    <td>
                                                        @if ($pendaftar->is_aktif == '1')
                                                            <button type="button"
                                                                class="btn btn-inverse-success btn-fw">Aktif</button>
                                                        @else
                                                            <button type="button"
                                                                class="btn btn-inverse-danger btn-fw">Belum Aktif</button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>






                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xl-9">

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($pendaftar->is_aktif == '1')
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card position-relative">
                    <div class="card-body">
                        <h4 class="card-title">Langkah-langkah Menjadi Calon Mahasiswa UNSAP</h4>
                        <div class="row mb-5">
                            <div class="col-sm-3">
                                <button class="btn btn-primary" onclick="openFormBiodata()"> <i
                                        class="fa-solid fa-users fa-fw mr-2"></i>Langkah 1 Formulir</button>
                            </div>
                            <div class="col-sm-3">
                                <button class="btn btn-success" onclick="openFormPersyaratan()"> <i
                                        class="fa-solid fa-folder-plus fa-fw mr-2"></i> Langkah 2 Persyaratan</button>
                            </div>

                            <div class="col-sm-3">
                                <button class="btn btn-warning" onclick="openFormUjian()"> <i
                                        class="fa-solid fa-list fa-fw mr-2"></i> Langkah 3 Ujian</button>
                            </div>

                            <div class="col-sm-3">
                                <button class="btn btn-info" onclick="openFormHasil()"> <i
                                        class="fa-solid fa-ticket-alt fa-fw mr-2"></i> Langkah 4 Hasil Kelulusan</button>
                            </div>




                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div id="Biodata" class="formName">
                                    <form id="MabaForm" name="MabaForm">
                                        <input type="hidden" class="form-control form-control-danger" name="id"
                                            value="{{ old('id', $mhs->id) }}">
                                        <input type="hidden" class="form-control form-control-danger" name="kodewilayah"
                                            id="wilayah">
                                        <h2>
                                            PIN PENDAFTARAN : {{ session('pin') }}
                                        </h2>
                                        <p class="card-description">
                                            Tahun Akademik 2024/2025
                                        </p>
                                        <div class="row">
                                            <div class="form-group col-lg-6 @error('nisn') has-danger @enderror">
                                                <label class="col-sm-12 "><strong>Nomor Induk Siswa Nasional (NISN)
                                                    </strong></label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control form-control-danger"
                                                        name="nisn" value="{{ old('nisn', $mhs->nisn) }}">
                                                    @error('nisn')
                                                        <label class="error text-danger">{{ $message }}</label>
                                                    @enderror
                                                </div>


                                            </div>
                                            <div class="form-group col-lg-6 @error('nik') has-danger @enderror">
                                                <label class="col-sm-12 "><strong>Nomor Induk Kependudukan (NIK)
                                                    </strong></label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control form-control-danger"
                                                        name="nik" value="{{ old('nik', $mhs->nik) }}">
                                                    @error('nik')
                                                        <label class="error text-danger">{{ $message }}</label>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-lg-6 @error('nama_lengkap') has-danger @enderror">
                                                <div class="form-group @error('nama_lengkap') has-danger @enderror">
                                                    <label class="col-sm-12 "><strong>Nama Lengkap (Sesuai KTP)
                                                        </strong></label>
                                                    <div class="col-sm-12">
                                                        <input type="text" class="form-control form-control-danger"
                                                            name="nama_mahasiswa"
                                                            value="{{ old('nama_mahasiswa', $mhs->nama_mahasiswa) }}">
                                                        @error('nama_lengkap')
                                                            <label class="error text-danger">{{ $message }}</label>
                                                        @enderror
                                                    </div>
                                                </div>


                                            </div>
                                            <div
                                                class="form-group col-lg-6 @error('nama_ibu_kandung') has-danger @enderror">
                                                <label class="col-sm-12 "><strong>Nama Ibu Kandung</strong></label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control form-control-danger"
                                                        name="nama_ibu_kandung"
                                                        value="{{ old('nama_ibu_kandung', $mhs->nama_ibu_kandung) }}">
                                                    @error('nama_ibu_kandung')
                                                        <label class="error text-danger">{{ $message }}</label>
                                                    @enderror
                                                </div>


                                            </div>


                                        </div>


                                        <div class="row">
                                            <div class="form-group col-lg-4 @error('tempat_lahir') has-danger @enderror">
                                                <label class="col-sm-12 "><strong>Tempat Lahir</strong></label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control form-control-danger"
                                                        name="tempat_lahir"
                                                        value="{{ old('tempat_lahir', $mhs->tempat_lahir) }}">
                                                    @error('tempat_lahir')
                                                        <label class="error text-danger">{{ $message }}</label>
                                                    @enderror
                                                </div>


                                            </div>

                                            <div class="form-group col-lg-4 @error('tanggal_lahir') has-danger @enderror">
                                                <label class="col-sm-12 "><strong>Tanggal Lahir</strong></label>
                                                <div class="col-sm-12">
                                                    <input type="date" class="form-control form-control-danger"
                                                        name="tanggal_lahir" data-date-format="yyyy-mm-dd"
                                                        value="{{ old('tanggal_lahir', $mhs->tanggal_lahir) }}">
                                                    @error('tanggal_lahir')
                                                        <label class="error text-danger">{{ $message }}</label>
                                                    @enderror
                                                </div>


                                            </div>
                                            <div class="form-group col-lg-4 @error('jeniskelamin') has-danger @enderror">
                                                <label class="col-sm-12 "><strong>Jenis Kelamin</strong></label>
                                                <div class="col-sm-12">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-check">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input"
                                                                        name="jenis_kelamin" id="membershipRadios1"
                                                                        value="L"
                                                                        {{ $mhs->jenis_kelamin == 'L' ? 'checked' : '' }}>
                                                                    Laki-Laki
                                                                    <i class="input-helper"></i></label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-check">
                                                                <label class="form-check-label">
                                                                    <input type="radio" class="form-check-input"
                                                                        name="jenis_kelamin" id="membershipRadios1"
                                                                        value="P"
                                                                        {{ $mhs->jenis_kelamin == 'P' ? 'checked' : '' }}>
                                                                    Perempuan
                                                                    <i class="input-helper"></i></label>
                                                            </div>
                                                        </div>

                                                    </div>



                                                </div>


                                            </div>

                                            <div class="form-group col-lg-4 @error('handphone') has-danger @enderror">
                                                <label class="col-sm-12 "><strong>Nomor Handphone (Tanpa +62 / 0)
                                                    </strong></label>
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control form-control-danger"
                                                        name="handphone" value="{{ old('handphone', $mhs->handphone) }}">
                                                    @error('handphone')
                                                        <label class="error text-danger">{{ $message }}</label>
                                                    @enderror
                                                </div>


                                            </div>
                                            <div class="form-group col-lg-4 @error('agama') has-danger @enderror">
                                                <label class="col-sm-12 "><strong>Agama</strong></label>
                                                <div class="col-sm-12">
                                                    <select
                                                        class="form-control @error('agama') form-control-danger @enderror"
                                                        name="agama" data-display="static">
                                                        @foreach ($agama['data'] as $agama)
                                                            <option value="{{ $agama['id_agama'] }}"
                                                                {{ $mhs->agama == $agama['id_agama'] ? 'selected' : '' }}>
                                                                {{ $agama['nama_agama'] }}
                                                            </option>
                                                        @endforeach

                                                    </select>
                                                    @error('agama')
                                                        <label class="error mt-2 text-danger">{{ $message }}</label>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group col-lg-4 @error('jenis_daftar') has-danger @enderror">
                                                <label class="col-sm-12 "><strong>Jenis Pendaftaran</strong></label>
                                                <div class="col-sm-12">
                                                    <select
                                                        class="form-control @error('jenis_daftar') form-control-danger @enderror"
                                                        name="jenis_daftar" data-display="static">
                                                        <option value="1"
                                                            {{ $mhs->jenis_daftar == '1' ? 'selected' : '' }}>Reguler
                                                        </option>
                                                        <option value="2"
                                                            {{ $mhs->jenis_daftar == '2' ? 'selected' : '' }}>Pindahan
                                                        </option>
                                                        <option value="6"
                                                            {{ $mhs->jenis_daftar == '6' ? 'selected' : '' }}>Lanjutan
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-lg-8 @error('alamat_rumah') has-danger @enderror">
                                                <div class="form-group @error('alamat_rumah') has-danger @enderror">
                                                    <label class="col-sm-12 "><strong>Alamat </strong></label>
                                                    <div class="col-sm-12">
                                                        <textarea class="form-control" name="alamat_rumah" id="exampleTextarea1" rows="4">{{ $mhs->alamat_rumah }}</textarea>
                                                        @error('alamat_rumah')
                                                            <label class="error text-danger">{{ $message }}</label>
                                                        @enderror
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="form-group col-lg-4 @error('kelurahan') has-danger @enderror">
                                                <div class="form-group @error('kelurahan') has-danger @enderror">
                                                    <label class="col-sm-12 "><strong>Desa/Kelurahan </strong></label>
                                                    <div class="col-sm-12">
                                                        <input type="text" class="form-control form-control-danger"
                                                            name="kelurahan"
                                                            value="{{ old('kelurahan', $mhs->kelurahan) }}">
                                                        @error('kelurahan')
                                                            <label class="error text-danger">{{ $message }}</label>
                                                        @enderror
                                                    </div>
                                                </div>


                                            </div>


                                        </div>

                                        <div class="row">
                                            <div class="form-group col-lg-6 @error('asal_sekolah') has-danger @enderror">
                                                <label class="col-sm-12 "><strong>Asal SMA/SMK</strong></label>
                                                <div class="col-sm-12">
                                                    <select
                                                        class="form-control @error('asal_sekolah') form-control-danger @enderror"
                                                        name="asal_sekolah" data-display="static">
                                                        @foreach ($sekolah as $sekolah)
                                                            <option value="{{ $sekolah->sekolah }}"
                                                                {{ $mhs->asal_sekolah == $sekolah->sekolah ? 'selected' : '' }}>
                                                                {{ $sekolah->sekolah . ' ( ' . $sekolah->kabupaten_kota . ' ) ' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('asal_sekolah')
                                                        <label class="error mt-2 text-danger">{{ $message }}</label>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group col-lg-6 @error('tahun_lulus') has-danger @enderror">
                                                <label class="col-sm-12 "><strong>Tahun Lulus SMA/SMK</strong></label>
                                                <div class="col-sm-12">
                                                    <input type="text"
                                                        class="form-control @error('tahun_lulus') form-control-danger @enderror"
                                                        name="tahun_lulus"
                                                        value="{{ old('tahun_lulus', $mhs->tahun_lulus) }}"
                                                        data-display="static">


                                                </div>
                                            </div>
                                        </div>

                                        <p class="card-description" style="color:red">
                                            Jika Mahasiwa Pindahan/Lanjutan (Wajib Diisi)
                                        </p>

                                        <div class="row">
                                            <div class="form-group col-lg-6 @error('kode_pt_asal') has-danger @enderror">
                                                <label class="col-sm-12 "><strong>Asal Perguruan Tinggi</strong></label>
                                                <div class="col-sm-12">
                                                    <input type="text"
                                                        class="form-control @error('kode_pt_asal') form-control-danger @enderror"
                                                        name="kode_pt_asal"
                                                        value="{{ old('kode_pt_asal', $mhs->kode_pt_asal) }}"
                                                        data-display="static">
                                                    @error('kode_pt_asal')
                                                        <label class="error mt-2 text-danger">{{ $message }}</label>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div
                                                class="form-group col-lg-6 @error('kode_prodi_asal') has-danger @enderror">
                                                <label class="col-sm-12 "><strong>Asal Program Studi</strong></label>
                                                <div class="col-sm-12">
                                                    <input type="text"
                                                        class="form-control @error('kode_prodi_asal') form-control-danger @enderror"
                                                        name="kode_prodi_asal"
                                                        value="{{ old('kode_prodi_asal', $mhs->kode_prodi_asal) }}"
                                                        data-display="static">

                                                </div>
                                            </div>
                                        </div>

                                        <p class="card-description">
                                            Pilih Program Studi
                                        </p>

                                        <div class="row">
                                            <div
                                                class="form-group col-lg-6 @error('kodeprodi_satu') has-danger @enderror">
                                                <label class="col-sm-12 "><strong>Pilihan 1</strong></label>
                                                <div class="col-sm-12">
                                                    <select
                                                        class="form-control @error('kodeprodi_satu') form-control-danger @enderror"
                                                        name="kodeprodi_satu" data-display="static">
                                                        @foreach ($prodi as $prodi)
                                                            <option value="{{ $prodi->config }}"
                                                                {{ $mhs->kodeprodi_satu == $prodi->config ? 'selected' : '' }}>
                                                                {{ $prodi->nama_fakultas . ' - ' . $prodi->nama_prodi . '(' . $prodi->nama_jenjang . ')' }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    @error('kodeprodi_satu')
                                                        <label class="error mt-2 text-danger">{{ $message }}</label>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group col-lg-6 @error('kodeprodi_dua') has-danger @enderror">
                                                <label class="col-sm-12 "><strong>Pilihan 2</strong></label>
                                                <div class="col-sm-12">
                                                    <select
                                                        class="form-control @error('kodeprodi_dua') form-control-danger @enderror"
                                                        name="kodeprodi_dua" data-display="static">
                                                        @foreach ($prodi_dua as $prodi_dua)
                                                            <option value="{{ $prodi_dua->config }}"
                                                                {{ $mhs->kodeprodi_dua == $prodi_dua->config ? 'selected' : '' }}>
                                                                {{ $prodi_dua->nama_fakultas . ' - ' . $prodi_dua->nama_prodi . '(' . $prodi_dua->nama_jenjang . ')' }}
                                                            </option>
                                                        @endforeach

                                                    </select>

                                                </div>
                                            </div>
                                        </div>

                                        <p class="card-description">
                                            Wilayah
                                        </p>

                                        <div class="row">
                                            <div
                                                class="form-group col-lg-6 @error('kewarganegaraan') has-danger @enderror">
                                                <label class="col-sm-12 "><strong>Kewarganegaraan</strong></label>
                                                <div class="col-sm-12">
                                                    <select
                                                        class="form-control @error('kewarganegaraan') form-control-danger @enderror"
                                                        name="kewarganegaraan" data-display="static">

                                                        <option value="WNI"
                                                            {{ $mhs->kewarganegaraan == 'WNI' ? 'selected' : '' }}>
                                                            WNI
                                                        </option>
                                                        <option value="WNA"
                                                            {{ $mhs->kewarganegaraan == 'WNA' ? 'selected' : '' }}>
                                                            WNA
                                                        </option>
                                                        <option value="WNI Keturunan"
                                                            {{ $mhs->kewarganegaraan == 'WNI Keturunan' ? 'selected' : '' }}>
                                                            WNI Keturunan
                                                        </option>

                                                    </select>

                                                    @error('kewarganegaraan')
                                                        <label class="error mt-2 text-danger">{{ $message }}</label>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-lg-3" id="level1">
                                                <label>Negara</label>
                                                <select class="form-control" id="negara" name="negara" required>
                                                    <option value="ID"
                                                        {{ $mhs->negara == 'ID' ? 'selected' : '' }}>
                                                        Indonesia</option>
                                                    @foreach ($negara as $negara)
                                                        <option
                                                            value="{{ $negara['id_wilayah'] }} {{ $mhs->negara == $negara['id_wilayah'] ? 'selected' : '' }}">
                                                            {{ $negara['nama_wilayah'] }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                            </div>
                                            <div class="form-group col-lg-3" id="level2">
                                                <label>Provinsi</label>
                                                <select class="form-control" id="provinsi" name="provinsi" required>
                                                    @foreach ($provinsi as $provinsi)
                                                        <option value="{{ $provinsi->id_wilayah }}"
                                                            {{ $mhs->provinsi == $provinsi->id_wilayah ? 'selected' : '' }}>
                                                            {{ $provinsi->nama_wilayah }}
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-lg-3" id="level3">
                                                <label>Kota/Kabupaten</label>
                                                <select class="form-control" id="kota" name="kota" required>
                                                    @foreach ($kota as $kota)
                                                        <option value="{{ $kota->id_wilayah }}"
                                                            {{ $mhs->kota == $kota->id_wilayah ? 'selected' : '' }}>
                                                            {{ $kota->nama_wilayah }}
                                                    @endforeach


                                                </select>
                                            </div>
                                            <div class="form-group col-lg-3" id="level4">
                                                <label>Kecamatan</label>
                                                <select class="form-control" id="kecamatan" name="kecamatan" required>
                                                    @foreach ($kecamatan as $kecamatan)
                                                        <option value="{{ $kecamatan->id_wilayah }}"
                                                            {{ $mhs->kecamatan == $kecamatan->id_wilayah ? 'selected' : '' }}>
                                                            {{ $kecamatan->nama_wilayah }}
                                                    @endforeach

                                                </select>

                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-lg-8 @error('catatan') has-danger @enderror">
                                                <div class="form-group @error('catatan') has-danger @enderror">
                                                    <label class="col-sm-12 "><strong>Catatan (Silahkan Isi Informasi Calon
                                                            Mahasiswa yang tidak ada di form diatas) </strong></label>
                                                    <div class="col-sm-12">
                                                        <textarea class="form-control" name="catatan" id="exampleTextarea1" rows="4">{{ $mhs->catatan }}</textarea>

                                                        </textarea>
                                                        @error('catatan')
                                                            <label class="error text-danger">{{ $message }}</label>
                                                        @enderror
                                                    </div>
                                                </div>


                                            </div>



                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-check form-check-flat form-check-primary">

                                                    <label class="form-check-label">
                                                        <input type="checkbox" name="konfirmasi" value="1"
                                                            class="form-check-input" required
                                                            {{ $mhs->konfirmasi == '1' ? 'checked' : '' }}>
                                                        Saya menyetujui data yang sudah diisi pada form di atas dan
                                                        bertanggung jawab terhadap isi data tersebut
                                                        <i class="input-helper"></i></label>
                                                </div>

                                                <button type="submit" id="saveBtn" class="btn btn-primary mr-2"> <i
                                                        class="fa-solid fa-save fa-fw mr-2"></i> Submit</button>
                                                <a href="{{ url('logout') }}" class="btn btn-light">Cancel</a>
                                            </div>

                                        </div>


                                    </form>
                                </div>

                                <div id="Persyaratan" class="formName" style="display:none">
                                    <form method="POST" id="UploadSyaratForm" name="UploadSyaratForm"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <h2>
                                            PIN PENDAFTARAN : {{ session('pin') }}
                                        </h2>
                                        <input type="hidden" class="form-control form-control-danger"
                                            name="id_persyaratan" value="{{ old('id_persyaratan', $persyaratan->id) }}">

                                        <div class="row">
                                            <div class="form-group col-lg-6 @error('file') has-danger @enderror">
                                                <label>Fotocopy STTB/Ijazah SMA/SMK/MA Dilegalisir</label>
                                                <input type="file" name="ijasah"
                                                    class="form-control @error('ijasah') is-invalid @enderror">

                                                @error('file')
                                                    <label class="error mt-2 text-danger">{{ $message }}</label>
                                                @enderror
                                                <label class="info mt-2 text-white"><a
                                                        href="{{ url('images/persyaratan/' . $persyaratan->ijasah) }}"
                                                        target="_blank"> File</a></label>
                                            </div>



                                        </div>

                                        <div class="row">
                                            <div class="form-group col-lg-6 @error('file') has-danger @enderror">
                                                <label>Fotocopy KTP dan KK </label>
                                                <input type="file" name="ktp_kk"
                                                    class="form-control @error('ktp_kk') is-invalid @enderror">

                                                @error('file')
                                                    <label class="error mt-2 text-danger">{{ $message }}</label>
                                                @enderror
                                                <label class="info mt-2 text-white"><a
                                                        href="{{ url('images/persyaratan/' . $persyaratan->ktp_kk) }}"
                                                        target="_blank"> File</a></label>
                                            </div>



                                        </div>

                                        <div class="row">
                                            <div class="form-group col-lg-6 @error('file') has-danger @enderror">
                                                <label>Pas Foto Berwarna 3x4</label>
                                                <input type="file" name="foto"
                                                    class="form-control @error('foto') is-invalid @enderror">

                                                @error('file')
                                                    <label class="error mt-2 text-danger">{{ $message }}</label>
                                                @enderror
                                                <label class="info mt-2 text-white"><a
                                                        href="{{ url('images/persyaratan/' . $persyaratan->foto) }}"
                                                        target="_blank"> File</a></label>
                                            </div>



                                        </div>
                                        @if (\App\Helpers\AkademikHelpers::getFakultas($mhs->kodeprodi_satu) == '13')
                                            <div class="row">
                                                <div class="form-group col-lg-6 @error('file') has-danger @enderror">
                                                    <label>*Khusus Fikes Melampirkan Surat Keterangan sehat dan tidak buta
                                                        warna</label>
                                                    <input type="file" name="ket_sehat"
                                                        class="form-control @error('ket_sehat') is-invalid @enderror">

                                                    @error('file')
                                                        <label class="error mt-2 text-danger">{{ $message }}</label>
                                                    @enderror
                                                    <label class="info mt-2 text-white"><a
                                                            href="{{ url('images/persyaratan/' . $persyaratan->ket_sehat) }}"
                                                            target="_blank"> File</a></label>
                                                </div>



                                            </div>
                                        @endif
                                        @if ($mhs->jenis_daftar == '2')
                                            <p class="card-description">
                                                Khusus Mahasiswa Pindahan
                                            </p>

                                            <div class="row">
                                                <div class="form-group col-lg-6 @error('file') has-danger @enderror">
                                                    <label>Fotocopy KHS Tiap Semester</label>
                                                    <input type="file" name="khs"
                                                        class="form-control @error('khs') is-invalid @enderror">

                                                    @error('file')
                                                        <label class="error mt-2 text-danger">{{ $message }}</label>
                                                    @enderror
                                                    <label class="info mt-2 text-white"><a
                                                            href="{{ url('images/persyaratan/' . $persyaratan->khs) }}"
                                                            target="_blank"> File</a></label>
                                                </div>



                                            </div>

                                            <div class="row">
                                                <div class="form-group col-lg-6 @error('file') has-danger @enderror">
                                                    <label>Fotocopy Kartu Tanda Mahasiswa</label>
                                                    <input type="file" name="ktm"
                                                        class="form-control @error('ktm') is-invalid @enderror">

                                                    @error('file')
                                                        <label class="error mt-2 text-danger">{{ $message }}</label>
                                                    @enderror
                                                    <label class="info mt-2 text-white"><a
                                                            href="{{ url('images/persyaratan/' . $persyaratan->ktm) }}"
                                                            target="_blank"> File</a></label>
                                                </div>



                                            </div>
                                            <div class="row">
                                                <div class="form-group col-lg-6 @error('file') has-danger @enderror">
                                                    <label>Surat Keterangan Pindah Dari PT Asal</label>
                                                    <input type="file" name="surat_pindah"
                                                        class="form-control @error('surat_pindah') is-invalid @enderror">

                                                    @error('file')
                                                        <label class="error mt-2 text-danger">{{ $message }}</label>
                                                    @enderror
                                                    <label class="info mt-2 text-white"><a
                                                            href="{{ url('images/persyaratan/' . $persyaratan->surat_pindah) }}"
                                                            target="_blank"> File</a></label>
                                                </div>



                                            </div>

                                            <div class="row">
                                                <div class="form-group col-lg-6 @error('file') has-danger @enderror">
                                                    <label>Screen Shot Mahasiswa terdaftar di PDDIKTI</label>
                                                    <input type="file" name="screen_pddikti"
                                                        class="form-control @error('screen_pddikti') is-invalid @enderror">

                                                    @error('file')
                                                        <label class="error mt-2 text-danger">{{ $message }}</label>
                                                    @enderror
                                                    <label class="info mt-2 text-white"><a
                                                            href="{{ url('images/persyaratan/' . $persyaratan->screen_pddikti) }}"
                                                            target="_blank"> File</a></label>
                                                </div>



                                            </div>
                                        @endif


                                        @if ($mhs->jenis_daftar == '6')
                                            <p class="card-description">
                                                Khusus Mahasiswa Lanjutan
                                            </p>

                                            <div class="row">
                                                <div class="form-group col-lg-6 @error('file') has-danger @enderror">
                                                    <label>Fotocopy Ijasah D1/D2/D3 di Legalisir</label>
                                                    <input type="file" name="ijasah_lanjutan"
                                                        class="form-control @error('ijasah_lanjutan') is-invalid @enderror">

                                                    @error('file')
                                                        <label class="error mt-2 text-danger">{{ $message }}</label>
                                                    @enderror
                                                    <label class="info mt-2 text-white"><a
                                                            href="{{ url('images/persyaratan/' . $persyaratan->ijasah_lanjutan) }}"
                                                            target="_blank"> File</a></label>
                                                </div>



                                            </div>

                                            <div class="row">
                                                <div class="form-group col-lg-6 @error('file') has-danger @enderror">
                                                    <label>Fotocopy Transkrip Nilai Di Legalisir</label>
                                                    <input type="file" name="transkrip_nilai"
                                                        class="form-control @error('transkrip_nilai') is-invalid @enderror">

                                                    @error('file')
                                                        <label class="error mt-2 text-danger">{{ $message }}</label>
                                                    @enderror
                                                    <label class="info mt-2 text-white"><a
                                                            href="{{ url('images/persyaratan/' . $persyaratan->transkrip_nilai) }}"
                                                            target="_blank"> File</a></label>
                                                </div>
                                                <div class="form-group col-lg-6 @error('file') has-danger @enderror">

                                                </div>




                                            </div>
                                        @endif

                                        <div class="text-left">
                                            @if ($mhs->nisn != '')
                                                <button type="submit" class="btn btn-success"
                                                    id="btnImport">Submit</button>
                                            @endif
                                        </div>








                                </div>


                            </div>


                            </form>


                        </div>

                        <div id="soal" class="formName" style="display:none">
                            <form action="{{ url('operator/import-member/') }}" method="post" id="formImport">
                                @csrf
                                <h2>
                                    PIN PENDAFTARAN : {{ session('pin') }}
                                </h2>
                                <div class="row">
                                    {{-- @if ($persyaratan->ijasah != '')
                    <div class="form-group col-lg-6 mx-auto @error('asal_sekolah') has-danger @enderror">
                        <label class="col-sm-12 btn btn-success mr-2 "><strong>Selamat Anda Mendapatkan Voucher Pendaftaran Tanpa Tes Ujian. Surat Kelulusan dapat di download di Langkah 4 </strong></label>
                       
                    </div>
                    @endif --}}
                                    @if ($persyaratan->ijasah != '' && $soal == 0 && $mhs->nisn != '')
                                        <div
                                            class="form-group col-lg-6 mx-auto @error('asal_sekolah') has-danger @enderror">
                                            <label class="col-sm-12 "><strong>Silahkan Klik Tombol Dibawah untuk memulai
                                                    Ujian </strong></label>
                                            <a href="{{ url('cekUjianOnline') }}" class="btn btn-info mr-2"> <i
                                                    class="fa-solid fa-tasks fa-fw mr-2"></i> Mulai Ujian</a>
                                        </div>
                                    @elseif ($persyaratan->ijasah != '' && $soal != 0 && $mhs->nisn != '')
                                        <div
                                            class="form-group col-lg-6 mx-auto @error('asal_sekolah') has-danger @enderror">
                                            <label class="col-sm-12 btn btn-success mr-2 "><strong>Selamat Anda Telah
                                                    Menyelesaikan Ujian. Hasil Ujian Sudah Ada Di Langkah 4 Silahkan Klik
                                                    Pengumuman Kelulusan </strong></label>

                                        </div>
                                    @endif



                                </div>



                            </form>

                        </div>

                        <div id="hasil" class="formName" style="display:none">

                            <h2>
                                PIN PENDAFTARAN : {{ session('pin') }}
                            </h2>
                            <div class="row">
                                @if ($soal == 0)
                                @else
                                    <div class="form-group col-lg-6 mx-auto @error('asal_sekolah') has-danger @enderror">
                                        <label class="col-sm-12 "><strong>Surat Pengumuman Kelulusan</strong></label>
                                        <a href="{{ url('downloadpdf/' . $mhs->nomor_pendaftaran) }}"
                                            class="btn btn-info mr-2"> <i class="fa-solid fa-receipt fa-fw mr-2"></i>
                                            Download Surat Pengumuman Kelulusan</a>
                                    </div>
                                @endif



                            </div>




                        </div>

                    </div>
                </div>







            </div>
        </div>
        </div>
        </div>
    @else
    @endif








@endsection

@push('page-stylesheet')
@endpush

@push('page-script')
    <script>
        function openFormBiodata() {
            document.getElementById('Biodata').style.display = "block";
            document.getElementById('Persyaratan').style.display = "none";
            document.getElementById('soal').style.display = "none";
            document.getElementById('hasil').style.display = "none";
        }

        function openFormPersyaratan() {
            document.getElementById('Biodata').style.display = "none";
            document.getElementById('Persyaratan').style.display = "block";
            document.getElementById('soal').style.display = "none";
            document.getElementById('hasil').style.display = "none";
        }

        function openFormUjian() {
            document.getElementById('Biodata').style.display = "none";
            document.getElementById('Persyaratan').style.display = "none";
            document.getElementById('soal').style.display = "block";
            document.getElementById('hasil').style.display = "none";
        }

        function openFormHasil() {
            document.getElementById('Biodata').style.display = "none";
            document.getElementById('Persyaratan').style.display = "none";
            document.getElementById('soal').style.display = "none";
            document.getElementById('hasil').style.display = "block";
        }
    </script>
    <script>
        const month = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober",
            "November", "Desember"
        ];
        const weekday = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];

        let d = new Date();
        let day = weekday[d.getDay()];
        let date = d.getDate();
        let name = month[d.getMonth()];
        let year = d.getFullYear();

        let fullDate = `${day}, ${date} ${name} ${year}`;
        document.getElementById("tanggal").innerHTML = fullDate;

        $('.counter').each(function() {
            $(this).prop('Counter', 0).animate({
                Counter: $(this).text()
            }, {
                duration: 1000,
                easing: 'swing',
                step: function(now) {
                    now = Number(Math.ceil(now)).toLocaleString('id');
                    $(this).text(now);
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {


            $('#negara').on('change', function() {
                $('#wilayah').html('');

                let negara = $(this).val();

                if (negara == 'ID') {


                    $.ajax({
                        type: 'get',
                        url: 'ref-wilayah-provinsi',
                        dataType: 'json',
                        beforeSend: function() {
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
                    beforeSend: function() {
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
                    beforeSend: function() {
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
                document.getElementById("wilayah").value = data.kecamatan;
            });
        });
    </script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $('#saveBtn').html('Mengirim Data ..');

                $.ajax({
                    data: $('#MabaForm').serialize(),
                    url: "update-mhs",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        $('#MabaForm').trigger("reset");
                        Swal.fire(
                            'Isi Formulir Berhasil',
                            'Silahkan klik tombol OK',
                            'success'
                        ).then(function(result) {
                            if (result.value) {
                                window.location = "dashboard";
                            }
                        })

                        $('#saveBtn').html('Submit');

                    },
                    error: function(data) {
                        //console.log('Error:', data);
                        Swal.fire(
                            'Terdapat Kesalahan',
                            data.responseJSON.message,
                            'error'
                        )
                        $('#saveBtn').html('Submit');
                    }
                });
            });


        });



        $('#UploadBuktiForm').submit(function(e) {
            e.preventDefault();
            $('#saveUpload').html('Mengirim Data ..');
            $.ajax({
                data: new FormData(this),
                url: "upload-bukti",
                method: "POST",
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    $('#UploadBuktiForm').trigger("reset");
                    Swal.fire(
                        'Uplod Bukti Bayar Berhasil !',
                        'Silahkan Klik Ok ..',
                        'success'
                    ).then(function(result) {
                        if (result.value) {
                            window.location = "dashboard";
                        }
                    });

                    /* Swal.fire(
                        'Uplod Bukti Bayar Berhasil !'
                        'Silahkan klik tombol OK',
                        'success'
                    ).then(function (result) {
                    /* if (result.value) {
                        window.location = "dashboard";
                    } */
                    /*   }) */

                    $('#saveUpload').html('Upload Bukti Bayar');

                },
                error: function(data) {
                    //console.log('Error:', data);
                    Swal.fire(
                        'Terdapat Kesalahan',
                        data.responseJSON.message,
                        'error'
                    )
                    $('#saveUpload').html('Upload Bukti Bayar');
                }
            });
        });


        $('#UploadSyaratForm').submit(function(e) {
            e.preventDefault();
            $('#saveSyarat').html('Mengirim Data ..');

            $.ajax({
                data: new FormData(this),
                url: "upload-syarat",
                method: "POST",
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    $('#UploadSyaratForm').trigger("reset");
                    Swal.fire(
                        data.success,
                        'Silahkan Lanjut Ke Langkah 3 Untuk Ujian',
                        'success'
                    ).then(function(result) {
                        if (result.value) {
                            window.location = "dashboard";
                        }
                    })

                    $('#saveSyarat').html('Submit');

                },
                error: function(data) {
                    //console.log('Error:', data);
                    Swal.fire(
                        'Terdapat Kesalahan',
                        data.responseJSON.message,
                        'error'
                    )
                    $('#saveSyarat').html('Submit');
                }
            });
        });
    </script>
@endpush
