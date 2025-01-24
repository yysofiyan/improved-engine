@extends('layouts.master-fakultas')

@section('title', 'Dashboard')
@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Halo, {{ \App\Helpers\AkademikHelpers::getFakultasNama(session('kodeprodi')) }}</h3>
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
                <h4 class="card-title mb-5">Pilih Sesi Prodi</h4>
                <div class="card-description">
                    <p>Petunjuk :</p>
                    <ol class="list">
                        <li>Silahkan Pilih Prodi yang akan ditampilkan
                        </li>
                    </ol>
                </div>
                <form action="{{ url('fakultas/get-prodi/') }}" method="post" 
                    id="formImport">
                    @csrf
                    
                    <div class="row">
                        <div class="form-group col-lg-4 @error('pilihprodi') has-danger @enderror">
                            <label>Pilih Prodi</label>
                            <select class="form-control @error('pilihprodi') form-control-danger @enderror"
                              name="pilihprodi" data-display="static">
                              @foreach ($prodi_list as $prodis)
                                  <option value="{{ $prodis->config }}" {{$prodis->config==session('pilihprodi') ? 'selected' : '' }}>
                                      {{ $prodis->nama_prodi. ' ('.$prodis->nama_jenjang.')' }}
                                  </option>
                          @endforeach
                          </select>
                        </div>
                    </div>
                    <div class="text-left">
                        <button type="submit" class="btn btn-primary" id="btnImport"><i class="fa-solid fa-filter fa-fw mr-2"></i>Simpan</button>
                        <a href="{{ url('/fakultas/reset-prodi')}}" class="btn btn-warning mr-2"> <i class="fa-solid fa-repeat fa-fw mr-2"></i> Reset</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
      <div class="card tale-bg">
        <div class="card-people mt-auto">
            <img src="{{ url('images/student.svg') }}" alt="people">
          <div class="weather-info">
            <div class="d-flex">
              <div>
               
              </div>
              <div class="ml-2">
                
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 grid-margin transparent">
      <div class="row">
        <div class="col-md-6 mb-4 stretch-card transparent">
          <div class="card card-tale">
            <div class="card-body">
              <p class="mb-4">Pendaftar Hari Ini</p>
              <p class="fs-30 mb-2">{{ number_format($pendaftarHariIni,0)}}</p>
            
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-4 stretch-card transparent">
          <div class="card card-dark-blue">
            <div class="card-body">
              <p class="mb-4">Total Pendaftar</p>
              <p class="fs-30 mb-2">{{ number_format($totalPendaftar,0)}}</p>
              
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
          <div class="card card-light-blue">
            <div class="card-body">
              <p class="mb-4">Jumlah Calon MABA</p>
              <p class="fs-30 mb-2">{{ number_format($totalLulus,0)}}</p>
             
            </div>
          </div>
        </div>
        <div class="col-md-6 stretch-card transparent">
          <div class="card card-light-danger">
            <div class="card-body">
              <p class="mb-4">Jumlah PIN Aktif</p>
              <p class="fs-30 mb-2">{{ number_format($totalPin,0)}}</p>
             
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
         <div class="d-flex justify-content-between">
          <p class="card-title">Rekap Pendaftaran PMB</p>
          <a href="#" class="text-info">Lihat</a>
         </div>
          <p class="font-weight-500">Jumlah Pendaftaran Calon Mahasiswa Baru UNSAP 2024</p>
          <div class="table-responsive">
            <table id="refMember" class="display expandable-table table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fakultas/Prodi</th>
                        <th class="text-center">Jumlah Pendaftar</th>
                        <th class="text-center">Jumlah PIN Aktif</th>
                        <th class="text-center">Jumlah Lulus</th>
                    </tr>
                </thead>
                <tbody>
                  
                  <tr>
                    <td>1</td>
                    <td><b>Fakultas Keguruan dan Ilmu Pendidikan</b></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                  </tr> 

                  <tr>
                    <td></td>
                    <td>Pendidikan Bahasa Indonesia(S2)</td>
                    <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar('8813') }} </td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin('8813') }}</td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus('8813') }}</td>
                    
                  </tr> 

                  <tr>
                    <td></td>
                    <td>Pendidikan Matematika (S2)</td>
                    <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar('84102') }} </td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin('84102') }}</td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus('84102') }}</td>
                    
                  </tr> 


                  <tr>
                    <td></td>
                    <td>Pendidikan Bahasa dan Sastra Indonesia (S1)</td>
                    <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar('5b3ff355-1e20-4c1d-8b47-e559b6991036') }} </td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin('5b3ff355-1e20-4c1d-8b47-e559b6991036') }}</td>
                    
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus('5b3ff355-1e20-4c1d-8b47-e559b6991036') }}</td>
                    
                  </tr> 

                  <tr>
                    <td></td>
                    <td>Pendidikan Guru Pendidikan Anak Usia Dini (S1)</td>
                    <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar('f48cbc83-b3c6-4e66-9e68-209b52a275e4') }} </td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin('f48cbc83-b3c6-4e66-9e68-209b52a275e4') }}</td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus('f48cbc83-b3c6-4e66-9e68-209b52a275e4') }}</td>
                  </tr> 

                  <tr>
                    <td></td>
                    <td>Pendidikan Guru Sekolah Dasar (S1)</td>
                    <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar('eca49026-745e-451c-8121-bfc81d4e9fe4') }} </td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin('eca49026-745e-451c-8121-bfc81d4e9fe4') }}</td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus('eca49026-745e-451c-8121-bfc81d4e9fe4') }}</td>
                  </tr> 

                  <tr>
                    <td></td>
                    <td>Pendidikan Jasmani Kesehatan dan Rekreasi (S1)</td>
                    <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar('adc77657-6904-4aa3-bc6e-40565bdc27bf') }} </td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin('adc77657-6904-4aa3-bc6e-40565bdc27bf') }}</td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus('adc77657-6904-4aa3-bc6e-40565bdc27bf') }}</td>
                  </tr> 

                  <tr>
                    <td></td>
                    <td>Pendidikan Matematika (S1)</td>
                    <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar('5f69f4f0-ebbb-4638-8df1-4632b05326de') }} </td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin('5f69f4f0-ebbb-4638-8df1-4632b05326de') }}</td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus('5f69f4f0-ebbb-4638-8df1-4632b05326de') }}</td>
                  </tr> 

                  <tr>
                    <td></td>
                    <td>Pendidikan Teknik Mesin (S1)</td>
                    <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar('2cadf663-1d4c-4fd4-9457-6fc2b50bd1b3') }} </td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin('2cadf663-1d4c-4fd4-9457-6fc2b50bd1b3') }}</td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus('2cadf663-1d4c-4fd4-9457-6fc2b50bd1b3') }}</td>
                  </tr> 

                  

                  <tr>
                    <td>2</td>
                    <td><b>Fakultas Ilmu Budaya</b></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                  </tr>  
                  <tr>
                    <td></td>
                    <td>Sastra Inggris (S1)</td>
                    <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar('1daad851-b93f-4860-b37d-ddae33f1b860') }} </td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin('1daad851-b93f-4860-b37d-ddae33f1b860') }}</td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus('1daad851-b93f-4860-b37d-ddae33f1b860') }}</td>
                  </tr> 

                  <tr>
                    <td>3</td>
                    <td><b>Fakultas Ekonomi Bisnis</b></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                  </tr> 
                  <tr>
                    <td></td>
                    <td>Manajemen (S2)</td>
                    <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar('1ff84166-cc64-48aa-a38e-3c6a952a8b90') }} </td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin('1ff84166-cc64-48aa-a38e-3c6a952a8b90') }}</td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus('1ff84166-cc64-48aa-a38e-3c6a952a8b90') }}</td>
                  </tr> 
                  <tr>
                    <td></td>
                    <td>Akuntansi (S1)</td>
                    <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar('08b181bc-1860-4c7e-8bda-ef4fbd59d869') }} </td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin('08b181bc-1860-4c7e-8bda-ef4fbd59d869') }}</td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus('08b181bc-1860-4c7e-8bda-ef4fbd59d869') }}</td>
                  </tr> 
                  <tr>
                    <td></td>
                    <td>Manajemen (S1)</td>
                    <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar('8c56f8a8-8f27-4e2f-8376-a433b2862f36') }} </td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin('8c56f8a8-8f27-4e2f-8376-a433b2862f36') }}</td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus('8c56f8a8-8f27-4e2f-8376-a433b2862f36') }}</td>
                  </tr> 

                  <tr>
                    <td>4</td>
                    <td><b>Fakultas Ilmu Sosial dan Ilmu Politik</b></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td>Administrasi Publik (S2)</td>
                    <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar('d778be91-7bc7-4757-bd22-9038fa8adeb4') }} </td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin('d778be91-7bc7-4757-bd22-9038fa8adeb4') }}</td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus('d778be91-7bc7-4757-bd22-9038fa8adeb4') }}</td>
                  </tr> 
                  <tr>
                    <td></td>
                    <td>Administrasi Publik (S1)</td>
                    <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar('e992676b-23e6-49e1-a2f6-81f90876b7da') }} </td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin('e992676b-23e6-49e1-a2f6-81f90876b7da') }}</td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus('e992676b-23e6-49e1-a2f6-81f90876b7da') }}</td>
                  </tr> 

                  <tr>
                    <td>5</td>
                    <td><b>Fakultas Ilmu Kesehatan</b></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                  </tr>  
                  <tr>
                    <td></td>
                    <td>Profesi Ners (Profesi)</td>
                    <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar('93366fa0-45df-457c-a723-01b78226ad34') }} </td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin('93366fa0-45df-457c-a723-01b78226ad34') }}</td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus('93366fa0-45df-457c-a723-01b78226ad34') }}</td>
                  </tr> 
                  <tr>
                    <td></td>
                    <td>Ilmu Keperawatan (S1)</td>
                    <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar('303e6a30-c87a-4f70-8431-8ccc03b058f4') }} </td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin('303e6a30-c87a-4f70-8431-8ccc03b058f4') }}</td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus('303e6a30-c87a-4f70-8431-8ccc03b058f4') }}</td>
                  </tr> 
                  <tr>
                    <td></td>
                    <td>Kesehatan Masyarakat (S1)</td>
                    <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar('b41b8150-b1e6-4c63-9455-26f91174933c') }} </td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin('b41b8150-b1e6-4c63-9455-26f91174933c') }}</td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus('b41b8150-b1e6-4c63-9455-26f91174933c') }}</td>
                  </tr> 
                  <tr>
                    <td>6</td>
                    <td><b>Fakultas Teknologi Informasi</b></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                  </tr> 

                  <tr>
                    <td></td>
                    <td>Teknik Informatika (S1)</td>
                    <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar('a74fffa1-43f1-4ab5-baca-dfbd08b22d20') }} </td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin('a74fffa1-43f1-4ab5-baca-dfbd08b22d20') }}</td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus('a74fffa1-43f1-4ab5-baca-dfbd08b22d20') }}</td>
                  </tr> 
                  <tr>
                    <td></td>
                    <td>Sistem Informasi (S1)</td>
                    <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar('aaf15037-cd57-4743-a5f8-fd30840f221e') }} </td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin('aaf15037-cd57-4743-a5f8-fd30840f221e') }}</td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus('aaf15037-cd57-4743-a5f8-fd30840f221e') }}</td>
                  </tr> 
                  <tr>
                    <td>7</td>
                    <td><b>Sekolah Tinggi Agama Islam (Dalam Proses Penyatuan)</b></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                  </tr> 
                  <tr>
                    <td></td>
                    <td>Pendidikan Agama Islam (S1)</td>
                    <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar('1') }} </td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin('1') }}</td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus('1') }}</td>
                  </tr> 
                  <tr>
                    <td></td>
                    <td>Ekonomi Syariah (S1)</td>
                    <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar('2') }} </td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin('2') }}</td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus('2') }}</td>
                  </tr> 
                  <tr>
                    <td></td>
                    <td><b>Total Keseluruhan</b></td>
                    <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getTotalDaftar() }} </td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getJumlahPin() }}</td>
                    <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalLulus() }}</td>
                  </tr>
                  
                  
                 
                 
                </tbody>
               
            </table>
        </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card position-relative">
        <div class="card-body">
          <div id="detailedReports" class="carousel slide detailed-report-carousel position-static pt-2" data-ride="carousel">
            <div class="carousel-inner">

              <div class="carousel-item active">
                <div class="row">
                  <div class="col-md-12 col-xl-3 d-flex flex-column justify-content-start">
                    <div class="ml-xl-4 mt-3">
                    <p class="card-title">Rekap Pendaftaran</p>
                      <h1 class="text-primary">{{ number_format($totalLulus,0)}} Maba</h1>
                      
                      <p class="mb-2 mb-xl-0">Jumlah MABA yang sudah resmi menjadi calon Mahasiswa Baru UNSAP 2024</p>
                    </div>  
                    </div>
                  <div class="col-md-12 col-xl-9">
                    <div class="row">
                      <div class="col-md-6 border-right">
                        <div class="table-responsive mb-3 mb-md-0 mt-3">
                          <table class="table table-borderless report-table">
                            <tbody><tr>
                              <td class="text-muted">Fakultas FKIP</td>
                              <td class="w-100 px-0">
                                <div class="progress progress-md mx-4">
                                  <div class="progress-bar bg-primary" role="progressbar" style="width: {{ \App\Helpers\AkademikHelpers::getLulusPersen(['5b3ff355-1e20-4c1d-8b47-e559b6991036','f48cbc83-b3c6-4e66-9e68-209b52a275e4','eca49026-745e-451c-8121-bfc81d4e9fe4','adc77657-6904-4aa3-bc6e-40565bdc27bf','5f69f4f0-ebbb-4638-8df1-4632b05326de','2cadf663-1d4c-4fd4-9457-6fc2b50bd1b3','8813','84102']) }}"></div>
                                </div>
                              </td>
                              <td><h5 class="font-weight-bold mb-0">{{ \App\Helpers\AkademikHelpers::getLulusFakultas(['5b3ff355-1e20-4c1d-8b47-e559b6991036','f48cbc83-b3c6-4e66-9e68-209b52a275e4','eca49026-745e-451c-8121-bfc81d4e9fe4','adc77657-6904-4aa3-bc6e-40565bdc27bf','5f69f4f0-ebbb-4638-8df1-4632b05326de','2cadf663-1d4c-4fd4-9457-6fc2b50bd1b3','8813','84102']) }}</h5></td>
                            </tr>
                            <tr>
                              <td class="text-muted">Fakultas FEB</td>
                              <td class="w-100 px-0">
                                <div class="progress progress-md mx-4">
                                  <div class="progress-bar bg-warning" role="progressbar" style="width: {{ \App\Helpers\AkademikHelpers::getLulusPersen(['1ff84166-cc64-48aa-a38e-3c6a952a8b90','08b181bc-1860-4c7e-8bda-ef4fbd59d869','8c56f8a8-8f27-4e2f-8376-a433b2862f36']) }}" aria-valuenow="30" aria-valuemin="0" aria-valuemax="250"></div>
                                </div>
                              </td>
                              <td><h5 class="font-weight-bold mb-0">{{ \App\Helpers\AkademikHelpers::getLulusFakultas(['1ff84166-cc64-48aa-a38e-3c6a952a8b90','08b181bc-1860-4c7e-8bda-ef4fbd59d869','8c56f8a8-8f27-4e2f-8376-a433b2862f36']) }}</h5></td>
                            </tr>
                            <tr>
                              <td class="text-muted">Fakultas FISIP</td>
                              <td class="w-100 px-0">
                                <div class="progress progress-md mx-4">
                                  <div class="progress-bar bg-danger" role="progressbar" style="width: {{ \App\Helpers\AkademikHelpers::getLulusPersen(['d778be91-7bc7-4757-bd22-9038fa8adeb4','e992676b-23e6-49e1-a2f6-81f90876b7da']) }}" aria-valuenow="95" aria-valuemin="0" aria-valuemax="250"></div>
                                </div>
                              </td>
                              <td><h5 class="font-weight-bold mb-0">{{ \App\Helpers\AkademikHelpers::getLulusFakultas(['d778be91-7bc7-4757-bd22-9038fa8adeb4','e992676b-23e6-49e1-a2f6-81f90876b7da']) }}</h5></td>
                            </tr>
                            <tr>
                              <td class="text-muted">Fakultas FIB</td>
                              <td class="w-100 px-0">
                                <div class="progress progress-md mx-4">
                                  <div class="progress-bar bg-info" role="progressbar" style="width: {{ \App\Helpers\AkademikHelpers::getLulusPersen(['1daad851-b93f-4860-b37d-ddae33f1b860']) }}" aria-valuenow="60" aria-valuemin="0" aria-valuemax="250"></div>
                                </div>
                              </td>
                              <td><h5 class="font-weight-bold mb-0">{{ \App\Helpers\AkademikHelpers::getLulusFakultas(['1daad851-b93f-4860-b37d-ddae33f1b860']) }}</h5></td>
                            </tr>
                            <tr>
                              <td class="text-muted">Fakultas FTI</td>
                              <td class="w-100 px-0">
                                <div class="progress progress-md mx-4">
                                  <div class="progress-bar bg-primary" role="progressbar" style="width: {{ \App\Helpers\AkademikHelpers::getLulusPersen(['aaf15037-cd57-4743-a5f8-fd30840f221e','a74fffa1-43f1-4ab5-baca-dfbd08b22d20']) }}" aria-valuenow="40" aria-valuemin="0" aria-valuemax="250"></div>
                                </div>
                              </td>
                              <td><h5 class="font-weight-bold mb-0">{{ \App\Helpers\AkademikHelpers::getLulusFakultas(['aaf15037-cd57-4743-a5f8-fd30840f221e','a74fffa1-43f1-4ab5-baca-dfbd08b22d20']) }}</h5></td>
                            </tr>
                            <tr>
                              <td class="text-muted">Fakultas FIKES</td>
                              <td class="w-100 px-0">
                                <div class="progress progress-md mx-4">
                                  <div class="progress-bar bg-danger" role="progressbar" style="width: {{ \App\Helpers\AkademikHelpers::getLulusPersen(['303e6a30-c87a-4f70-8431-8ccc03b058f4','b41b8150-b1e6-4c63-9455-26f91174933c','93366fa0-45df-457c-a723-01b78226ad34']) }}" aria-valuenow="125" aria-valuemin="0" aria-valuemax="250"></div>
                                </div>
                              </td>
                              <td><h5 class="font-weight-bold mb-0">{{ \App\Helpers\AkademikHelpers::getLulusFakultas(['303e6a30-c87a-4f70-8431-8ccc03b058f4','b41b8150-b1e6-4c63-9455-26f91174933c','93366fa0-45df-457c-a723-01b78226ad34']) }}</h5></td>
                            </tr>
                            <tr>
                              <td class="text-muted">STAI SAS</td>
                              <td class="w-100 px-0">
                                <div class="progress progress-md mx-4">
                                  <div class="progress-bar bg-info" role="progressbar" style="width: {{ \App\Helpers\AkademikHelpers::getLulusPersen(['1','2']) }}" aria-valuenow="125" aria-valuemin="0" aria-valuemax="250"></div>
                                </div>
                              </td>
                              <td><h5 class="font-weight-bold mb-0">{{ \App\Helpers\AkademikHelpers::getLulusFakultas(['1','2']) }}</h5></td>
                            </tr>
                          </tbody></table>
                        </div>
                      </div>
                      <div class="col-md-6 mt-3"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                        <canvas id="south-america-chart" height="330" width="660" class="chartjs-render-monitor" style="display: block; height: 165px; width: 330px;"></canvas>
                        <div id="south-america-legend"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </div>

  





@endsection

@push('page-stylesheet')
@endpush

@push('page-script')
<script>
    const month = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
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
  if ($("#south-america-chart").length) {
      var areaData = {
        labels: ["Pendaftar Offline", "Pendaftar Online"],
        datasets: [{
            data: [{{number_format($totalPendaftarOffline,0)}}, {{number_format($totalPendaftarOnline,0)}}],
            backgroundColor: [
              "#FFC100", "#248AFD",
            ],
            borderColor: "rgba(0,0,0,0)"
          }
        ]
      };
      var areaOptions = {
        responsive: true,
        maintainAspectRatio: true,
        segmentShowStroke: false,
        cutoutPercentage: 78,
        elements: {
          arc: {
              borderWidth: 4
          }
        },      
        legend: {
          display: false
        },
        tooltips: {
          enabled: true
        },
        legendCallback: function(chart) { 
          var text = [];
          text.push('<div class="report-chart">');
            text.push('<div class="d-flex justify-content-between mx-4 mx-xl-5 mt-3"><div class="d-flex align-items-center"><div class="mr-3" style="width:20px; height:20px; border-radius: 50%; background-color: ' + chart.data.datasets[0].backgroundColor[0] + '"></div><p class="mb-0">Pendaftar Offline</p></div>');
            text.push('<p class="mb-0">{{number_format($totalPendaftarOffline,0)}}</p>');
            text.push('</div>');
            text.push('<div class="d-flex justify-content-between mx-4 mx-xl-5 mt-3"><div class="d-flex align-items-center"><div class="mr-3" style="width:20px; height:20px; border-radius: 50%; background-color: ' + chart.data.datasets[0].backgroundColor[1] + '"></div><p class="mb-0">Pendaftar Online</p></div>');
            text.push('<p class="mb-0">{{number_format($totalPendaftarOnline,0)}}</p>');
            text.push('</div>');
            
          text.push('</div>');
          return text.join("");
        },
      }
      var southAmericaChartPlugins = {
        beforeDraw: function(chart) {
          var width = chart.chart.width,
              height = chart.chart.height,
              ctx = chart.chart.ctx;
      
          ctx.restore();
          var fontSize = 3.125;
          ctx.font = "600 " + fontSize + "em sans-serif";
          ctx.textBaseline = "middle";
          ctx.fillStyle = "#000";
      
          var text = "{{ number_format($totalPendaftar,0)}}",
              textX = Math.round((width - ctx.measureText(text).width) / 2),
              textY = height / 2;
      
          ctx.fillText(text, textX, textY);
          ctx.save();
        }
      }
      var southAmericaChartCanvas = $("#south-america-chart").get(0).getContext("2d");
      var southAmericaChart = new Chart(southAmericaChartCanvas, {
        type: 'doughnut',
        data: areaData,
        options: areaOptions,
        plugins: southAmericaChartPlugins
      });
      document.getElementById('south-america-legend').innerHTML = southAmericaChart.generateLegend();
    }
</script>
@endpush
