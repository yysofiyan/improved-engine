@extends('layouts.master-fakultas')

@section('title', 'Formulir Pendaftaran Calon Mahasiswa UNSAP')

@section('content')

    @if($pendaftar->is_aktif=='1')
    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card position-relative">
          <div class="card-body">
            <h4 class="card-title">Kelengkapan Form  Calon Mahasiswa UNSAP</h4>
            <div class="row mb-5">
              <div class="col-sm-3 mb-5">
                  <button class="btn btn-primary" onclick="openFormBiodata()"> <i class="fa-solid fa-users fa-fw mr-2"></i>Formulir Calon Mahasiswa Baru</button>
              </div>
              
              <div class="col-sm-3">
                  <button class="btn btn-success" onclick="openFormPersyaratan()"> <i class="fa-solid fa-folder-plus fa-fw mr-2"></i> Persyaratan Calon Mahasiswa Baru</button>
              </div>

              
              
            </div>
  
            <div class="row">
              <div class="col-lg-12">
                <div id="Biodata" class="formName">
                    <form>
                        <input type="hidden" class="form-control form-control-danger" name="id" value="{{old('id', $mhs->id)}}" >
                        <input type="hidden" class="form-control form-control-danger" name="kodewilayah" id="wilayah">
                        <h2>
                            PIN PENDAFTARAN : {{ $mhs->pin }}
                        </h2>
                      <p class="card-description">
                        Tahun Akademik 2024/2025
                      </p>
                      <div class="row">
                        <div class="form-group col-lg-6 @error('nisn') has-danger @enderror">
                            <label class="col-sm-12 "><strong>Nomor Induk Siswa Nasional (NISN) </strong></label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control form-control-danger" name="nisn" value="{{old('nisn', $mhs->nisn)}}">
                                    @error('nisn')
                                    <label class="error text-danger">{{ $message }}</label>
                                    @enderror
                                </div>
        
        
                        </div>
                        <div class="form-group col-lg-6 @error('nik') has-danger @enderror">
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
                        <div class="form-group col-lg-6 @error('nama_lengkap') has-danger @enderror">
                            <div class="form-group @error('nama_lengkap') has-danger @enderror">
                                <label class="col-sm-12 "><strong>Nama Lengkap (Sesuai KTP)  </strong></label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control form-control-danger" name="nama_mahasiswa" value="{{old('nama_mahasiswa', $mhs->nama_mahasiswa)}}">
                                    @error('nama_lengkap')
                                    <label class="error text-danger">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
        
        
                        </div>
                        <div class="form-group col-lg-6 @error('nama_ibu_kandung') has-danger @enderror">
                            <label class="col-sm-12 "><strong>Nama Ibu Kandung</strong></label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control form-control-danger" name="nama_ibu_kandung" value="{{old('nama_ibu_kandung', $mhs->nama_ibu_kandung)}}">
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
                            <label class="col-sm-12 "><strong>Nomor Handphone (Tanpa +62 / 0) </strong></label>
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
                                    <option value="{{ $agama['id_agama'] }}" {{ $mhs->agama==$agama['id_agama'] ? 'selected' : '' }}>
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
                                <select class="form-control @error('jenis_daftar') form-control-danger @enderror" name="jenis_daftar" data-display="static">
                               <option value="1" {{ $mhs->jenis_daftar == '1' ? 'selected':'' }}>Reguler</option>
                               <option value="2" {{ $mhs->jenis_daftar == '2' ? 'selected':'' }}>Pindahan</option>
                               <option value="6" {{ $mhs->jenis_daftar == '6' ? 'selected':'' }} >Lanjutan</option>
                            </select>
                            </div>
                        </div>
                    </div>
        
                    <div class="row">
                        <div class="form-group col-lg-8 @error('alamat_rumah') has-danger @enderror">
                            <div class="form-group @error('alamat_rumah') has-danger @enderror">
                                <label class="col-sm-12 "><strong>Alamat </strong></label>
                                <div class="col-sm-12">
                                    <textarea class="form-control" name="alamat_rumah" id="exampleTextarea1" rows="4">{{ $mhs->alamat_rumah}}</textarea>
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
                                    <input type="text" class="form-control form-control-danger" name="kelurahan" value="{{old('kelurahan', $mhs->kelurahan)}}">
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
                                <select class="form-control @error('asal_sekolah') form-control-danger @enderror"
                                name="asal_sekolah" data-display="static">
                                @foreach ($sekolah as $sekolah)
                                    <option value="{{ $sekolah->sekolah }}" {{ $mhs->asal_sekolah==$sekolah->sekolah ? 'selected' : '' }}>
                                        {{ $sekolah->sekolah.' ( '.$sekolah->kabupaten_kota.' ) ' }}
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
                                <input type="text" class="form-control @error('tahun_lulus') form-control-danger @enderror" name="tahun_lulus" value="{{old('tahun_lulus', $mhs->tahun_lulus)}}" data-display="static">
                               
                        
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
                                <input type="text" class="form-control @error('kode_pt_asal') form-control-danger @enderror" name="kode_pt_asal" value="{{old('kode_pt_asal', $mhs->kode_pt_asal)}}" data-display="static">
                            @error('kode_pt_asal')
                            <label class="error mt-2 text-danger">{{ $message }}</label>
                            @enderror
                            </div>
                        </div>
        
                        <div class="form-group col-lg-6 @error('kode_prodi_asal') has-danger @enderror">
                            <label class="col-sm-12 "><strong>Asal Program Studi</strong></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control @error('kode_prodi_asal') form-control-danger @enderror" name="kode_prodi_asal" value="{{old('kode_prodi_asal', $mhs->kode_prodi_asal)}}" data-display="static">
                               
                            </div>
                        </div>
                    </div>
        
                    <p class="card-description">
                        Pilih Program Studi
                      </p>
        
                    <div class="row">
                        <div class="form-group col-lg-6 @error('kodeprodi_satu') has-danger @enderror">
                            <label class="col-sm-12 "><strong>Pilihan 1</strong></label>
                            <div class="col-sm-12">
                                <select class="form-control @error('kodeprodi_satu') form-control-danger @enderror" name="kodeprodi_satu" data-display="static">
                                @foreach ($prodi as $prodi)
                                    <option value="{{ $prodi->config }}" {{ $mhs->kodeprodi_satu==$prodi->config ? 'selected' : '' }}>
                                {{ $prodi->nama_fakultas.' - '.$prodi->nama_prodi.'('. $prodi->nama_jenjang.')' }}
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
                                <select class="form-control @error('kodeprodi_dua') form-control-danger @enderror" name="kodeprodi_dua" data-display="static">
                                    @foreach ($prodi_dua as $prodi_dua)
                                        <option value="{{ $prodi_dua->config }}" {{ $mhs->kodeprodi_dua==$prodi_dua->config ? 'selected' : '' }}>
                                {{ $prodi_dua->nama_fakultas.' - '.$prodi_dua->nama_prodi.'('. $prodi_dua->nama_jenjang.')' }}
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
                        <div class="form-group col-lg-6 @error('kewarganegaraan') has-danger @enderror">
                            <label class="col-sm-12 "><strong>Kewarganegaraan</strong></label>
                            <div class="col-sm-12">
                                <select class="form-control @error('kewarganegaraan') form-control-danger @enderror" name="kewarganegaraan" data-display="static">
                            
                                    <option value="WNI" {{ $mhs->kewarganegaraan=="WNI" ? 'selected' : '' }} >
                                            WNI
                                    </option>
                                    <option value="WNA" {{ $mhs->kewarganegaraan=="WNA" ? 'selected' : '' }} >
                                        WNA
                                    </option>
                                    <option value="WNI Keturunan" {{ $mhs->kewarganegaraan=="WNI Keturunan" ? 'selected' : '' }} >
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
                                <option value="ID" {{ $mhs->negara=='ID'
                                    ? 'selected' : '' }} >Indonesia</option>
                                @foreach ($negara as $negara)
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
                                @foreach ($provinsi as $provinsi)
                                <option value="{{ $provinsi->id_wilayah }}" {{ $mhs->provinsi==$provinsi->id_wilayah
                                    ? 'selected' : '' }}>
                                    {{ $provinsi->nama_wilayah }}
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-3" id="level3">
                            <label>Kota/Kabupaten</label>
                            <select class="form-control" id="kota" name="kota" required>
                                @foreach ($kota as $kota)
                                <option value="{{ $kota->id_wilayah }}" {{ $mhs->kota==$kota->id_wilayah
                                    ? 'selected' : '' }}>
                                    {{ $kota->nama_wilayah }}
                                @endforeach
                                
                               
                            </select>
                        </div>
                        <div class="form-group col-lg-3" id="level4">
                            <label>Kecamatan</label>
                            <select class="form-control" id="kecamatan" name="kecamatan" required>
                                @foreach ($kecamatan as $kecamatan)
                                <option value="{{ $kecamatan->id_wilayah }}" {{ $mhs->kecamatan==$kecamatan->id_wilayah
                                    ? 'selected' : '' }}>
                                    {{ $kecamatan->nama_wilayah }}
                                @endforeach
                                
                            </select>
        
                        </div>
                    </div>
        
                    <div class="row">
                        <div class="form-group col-lg-8 @error('catatan') has-danger @enderror">
                            <div class="form-group @error('catatan') has-danger @enderror">
                                <label class="col-sm-12 "><strong>Catatan (Silahkan Isi Informasi Calon Mahasiswa yang tidak ada di form diatas) </strong></label>
                                <div class="col-sm-12">
                                    <textarea class="form-control" name="catatan" id="exampleTextarea1" rows="4">{{$mhs->catatan}}</textarea>
        
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
                              <input type="checkbox" name="konfirmasi" value="1" class="form-check-input" required {{ $mhs->konfirmasi == '1' ? 'checked':'' }}>
                              Saya menyetujui data yang sudah diisi pada form di atas dan bertanggung jawab terhadap isi data tersebut
                            <i class="input-helper"></i></label>
                          </div>
        
                        </div>
        
                      </div>
                     
        
                    </form>
                </div>
              </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div id="Persyaratan" class="formName" style="display:none;">
                        <form >
                            @csrf
                            
                            <h2>
                                PIN PENDAFTARAN : {{ $mhs->pin}}
                            </h2>
                            <input type="hidden" class="form-control form-control-danger" name="id_persyaratan" value="{{old('id_persyaratan', $persyaratan->id)}}" >
                        
                            <div class="row">
                                <div class="form-group col-lg-6 @error('file') has-danger @enderror">
                                    <label>Fotocopy STTB/Ijazah SMA/SMK/MA Dilegalisir</label>
                                    <input type="file" name="ijasah" class="form-control @error('ijasah') is-invalid @enderror">
                                    
                                    @error('file')
                                    <label class="error mt-2 text-danger">{{ $message }}</label>
                                    @enderror
                                    <label class="info mt-2 text-white"><a href="{{ url('images/persyaratan/'.$persyaratan->ijasah) }}" target="_blank"> File</a></label>
                                </div>
                                <div class="form-group col-lg-6 @error('file') has-danger @enderror">
                                  <label>Verifikasi</label>
                          
                                  <div class="input-group col-xs-12 ml-4">
                                      <input type="checkbox" name="is_ijasah" value="1" class="form-check-input" required {{ $persyaratan->is_ijasah == '1' ? 'checked':'' }}> Dokumen Ada
                                  </div>
                                  @error('file')
                                  <label class="error mt-2 text-danger">{{ $message }}</label>
                                  @enderror
                              </div>
                                
            
                               
                            </div>
            
                            <div class="row">
                                <div class="form-group col-lg-6 @error('file') has-danger @enderror">
                                    <label>Fotocopy KTP dan KK </label>
                                    <input type="file" name="ktp_kk" class="form-control @error('ktp_kk') is-invalid @enderror">
                                   
                                    @error('file')
                                    <label class="error mt-2 text-danger">{{ $message }}</label>
                                    @enderror
                                    <label class="info mt-2 text-white"><a href="{{ url('images/persyaratan/'.$persyaratan->ktp_kk) }}" target="_blank"> File</a></label>
                                </div>
                                <div class="form-group col-lg-6 @error('file') has-danger @enderror">
                                  <label>Verifikasi</label>
                          
                                  <div class="input-group col-xs-12 ml-4">
                                      <input type="checkbox" name="is_ktp" value="1" class="form-check-input" required {{ $persyaratan->is_ktp == '1' ? 'checked':'' }}> Dokumen Ada
                                  </div>
                                  @error('file')
                                  <label class="error mt-2 text-danger">{{ $message }}</label>
                                  @enderror
                              </div>
                               
            
                               
                            </div>
            
                            <div class="row">
                                <div class="form-group col-lg-6 @error('file') has-danger @enderror">
                                    <label>Pas Foto Berwarna 3x4</label>
                                    <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror">
                                   
                                    @error('file')
                                    <label class="error mt-2 text-danger">{{ $message }}</label>
                                    @enderror
                                    <label class="info mt-2 text-white"><a href="{{ url('images/persyaratan/'.$persyaratan->foto) }}" target="_blank"> File</a></label>
                                </div>
      
                                <div class="form-group col-lg-6 @error('file') has-danger @enderror">
                                  <label>Verifikasi</label>
                          
                                  <div class="input-group col-xs-12 ml-4">
                                      <input type="checkbox" name="is_foto" value="1" class="form-check-input" required {{ $persyaratan->is_foto == '1' ? 'checked':'' }}> Dokumen Ada
                                  </div>
                                  @error('file')
                                  <label class="error mt-2 text-danger">{{ $message }}</label>
                                  @enderror
                              </div>
      
                                
                                
            
                               
                            </div>
                            @if (\App\Helpers\AkademikHelpers::getFakultas($mhs->kodeprodi_satu)=='13')
                            
                            <div class="row">
                                <div class="form-group col-lg-6 @error('file') has-danger @enderror">
                                    <label>*Khusus Fikes Melampirkan Surat Keterangan sehat dan tidak buta warna</label>
                                    <input type="file" name="ket_sehat" class="form-control @error('ket_sehat') is-invalid @enderror">
                                   
                                    @error('file')
                                    <label class="error mt-2 text-danger">{{ $message }}</label>
                                    @enderror
                                    <label class="info mt-2 text-white"><a href="{{ url('images/persyaratan/'.$persyaratan->ket_sehat) }}" target="_blank"> File</a></label>
                                </div>
                                <div class="form-group col-lg-6 @error('file') has-danger @enderror">
                                  <label>Verifikasi</label>
                          
                                  <div class="input-group col-xs-12 ml-4">
                                      <input type="checkbox" name="is_ket_sehat" value="1" class="form-check-input" required {{ $persyaratan->is_ket_sehat == '1' ? 'checked':'' }}> Dokumen Ada
                                  </div>
                                  @error('file')
                                  <label class="error mt-2 text-danger">{{ $message }}</label>
                                  @enderror
                              </div>
                                
            
                               
                            </div>
                            @endif
                            @if ($mhs->jenis_daftar=='2')
                            <p class="card-description">
                                Khusus Mahasiswa Pindahan
                                </p>
                              
                                <div class="row">
                                  <div class="form-group col-lg-6 @error('file') has-danger @enderror">
                                      <label>Fotocopy KHS Tiap Semester</label>
                                      <input type="file" name="khs" class="form-control @error('khs') is-invalid @enderror">
                                     
                                      @error('file')
                                      <label class="error mt-2 text-danger">{{ $message }}</label>
                                      @enderror
                                      <label class="info mt-2 text-white"><a href="{{ url('images/persyaratan/'.$persyaratan->khs) }}" target="_blank"> File</a></label>
                                  </div>
                                  <div class="form-group col-lg-6 @error('file') has-danger @enderror">
                                      <label>Verifikasi</label>
                              
                                      <div class="input-group col-xs-12 ml-4">
                                          <input type="checkbox" name="is_khs" value="1" class="form-check-input" required {{ $persyaratan->is_khs == '1' ? 'checked':'' }}> Dokumen Ada
                                      </div>
                                      @error('file')
                                      <label class="error mt-2 text-danger">{{ $message }}</label>
                                      @enderror
                                  </div>
                                  
              
                                 
                              </div>
              
                              <div class="row">
                                  <div class="form-group col-lg-6 @error('file') has-danger @enderror">
                                      <label>Fotocopy Kartu Tanda Mahasiswa</label>
                                      <input type="file" name="ktm" class="form-control @error('ktm') is-invalid @enderror">
                                     
                                      @error('file')
                                      <label class="error mt-2 text-danger">{{ $message }}</label>
                                      @enderror
                                      <label class="info mt-2 text-white"><a href="{{ url('images/persyaratan/'.$persyaratan->ktm) }}" target="_blank"> File</a></label>
                                  </div>
                                  <div class="form-group col-lg-6 @error('file') has-danger @enderror">
                                      <label>Verifikasi</label>
                              
                                      <div class="input-group col-xs-12 ml-4">
                                          <input type="checkbox" name="is_ktm" value="1" class="form-check-input" required {{ $persyaratan->is_ktm == '1' ? 'checked':'' }}> Dokumen Ada
                                      </div>
                                      @error('file')
                                      <label class="error mt-2 text-danger">{{ $message }}</label>
                                      @enderror
                                  </div>
                                  
              
                                 
                              </div>
                              <div class="row">
                                <div class="form-group col-lg-6 @error('file') has-danger @enderror">
                                    <label>Surat Keterangan Pindah Dari PT Asal</label>
                                    <input type="file" name="surat_pindah" class="form-control @error('surat_pindah') is-invalid @enderror">
                                   
                                    @error('file')
                                    <label class="error mt-2 text-danger">{{ $message }}</label>
                                    @enderror
                                    <label class="info mt-2 text-white"><a href="{{ url('images/persyaratan/'.$persyaratan->surat_pindah) }}" target="_blank"> File</a></label>
                                </div>
                                <div class="form-group col-lg-6 @error('file') has-danger @enderror">
                                  <label>Verifikasi</label>
                          
                                  <div class="input-group col-xs-12 ml-4">
                                      <input type="checkbox" name="is_surat_pindah" value="1" class="form-check-input" required {{ $persyaratan->is_surat_pindah == '1' ? 'checked':'' }}> Dokumen Ada
                                  </div>
                                  @error('file')
                                  <label class="error mt-2 text-danger">{{ $message }}</label>
                                  @enderror
                              </div>
                                
            
                               
                            </div>
            
                            <div class="row">
                                <div class="form-group col-lg-6 @error('file') has-danger @enderror">
                                    <label>Screen Shot Mahasiswa terdaftar di PDDIKTI</label>
                                    <input type="file" name="screen_pddikti" class="form-control @error('screen_pddikti') is-invalid @enderror">
                                   
                                    @error('file')
                                    <label class="error mt-2 text-danger">{{ $message }}</label>
                                    @enderror
                                    <label class="info mt-2 text-white"><a href="{{ url('images/persyaratan/'.$persyaratan->screen_pddikti) }}" target="_blank"> File</a></label>
                                </div>
                                <div class="form-group col-lg-6 @error('file') has-danger @enderror">
                                  <label>Verifikasi</label>
                          
                                  <div class="input-group col-xs-12 ml-4">
                                      <input type="checkbox" name="is_screen_pddikti" value="1" class="form-check-input" required {{ $persyaratan->is_screen_pddikti == '1' ? 'checked':'' }}> Dokumen Ada
                                  </div>
                                  @error('file')
                                  <label class="error mt-2 text-danger">{{ $message }}</label>
                                  @enderror
                              </div>
                                
            
                               
                            </div>
                            @endif
                            
        
                            @if ($mhs->jenis_daftar=='6')
                            <p class="card-description">
                                Khusus Mahasiswa Lanjutan
                                </p>
                              
                                <div class="row">
                                  <div class="form-group col-lg-6 @error('file') has-danger @enderror">
                                      <label>Fotocopy Ijasah D1/D2/D3 di Legalisir</label>
                                      <input type="file" name="ijasah_lanjutan" class="form-control @error('ijasah_lanjutan') is-invalid @enderror">
                                   
                                      @error('file')
                                      <label class="error mt-2 text-danger">{{ $message }}</label>
                                      @enderror
                                      <label class="info mt-2 text-white"><a href="{{ url('images/persyaratan/'.$persyaratan->ijasah_lanjutan) }}" target="_blank"> File</a></label>
                                  </div>
                                  <div class="form-group col-lg-6 @error('file') has-danger @enderror">
                                      <label>Verifikasi</label>
                              
                                      <div class="input-group col-xs-12 ml-4">
                                          <input type="checkbox" name="is_ijasah_lanjutan" value="1" class="form-check-input" required {{ $persyaratan->is_ijasah_lanjutan == '1' ? 'checked':'' }}> Dokumen Ada
                                      </div>
                                      @error('file')
                                      <label class="error mt-2 text-danger">{{ $message }}</label>
                                      @enderror
                                  </div>
                                  
            
                                 
                              </div>
            
                              <div class="row">
                                  <div class="form-group col-lg-6 @error('file') has-danger @enderror">
                                      <label>Fotocopy Transkrip Nilai Di Legalisir</label>
                                      <input type="file" name="transkrip_nilai" class="form-control @error('transkrip_nilai') is-invalid @enderror">
                                   
                                      @error('file')
                                      <label class="error mt-2 text-danger">{{ $message }}</label>
                                      @enderror
                                      <label class="info mt-2 text-white"><a href="{{ url('images/persyaratan/'.$persyaratan->transkrip_nilai) }}" target="_blank"> File</a></label>
                                  </div>
                                  <div class="form-group col-lg-6 @error('file') has-danger @enderror">
                                      <label>Verifikasi</label>
                              
                                      <div class="input-group col-xs-12 ml-4">
                                          <input type="checkbox" name="is_transkrip_nilai" value="1" class="form-check-input" required {{ $persyaratan->is_transkrip_nilai == '1' ? 'checked':'' }}> Dokumen Ada
                                      </div>
                                      @error('file')
                                      <label class="error mt-2 text-danger">{{ $message }}</label>
                                      @enderror
                                  </div>
            
                                 
            
                                 
                              </div>
                            @endif
        
          
                                            
                                    
                                </div>
            
                               
                            </div>
                            
                            
                        </form>
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
    }
  
    function openFormPersyaratan() {
      document.getElementById('Biodata').style.display = "none";  
      document.getElementById('Persyaratan').style.display = "block";
      document.getElementById('Persyaratan').style.width = "100%";
    }
    </script>
  
  @endpush
