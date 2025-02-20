@extends('layouts.master-keuangan')

@section('title', 'Dashboard')
@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Halo, {{ auth()->user()->name }}</h3>
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
  <div class="col-md-6 grid-margin stretch-card">
      <div class="card tale-bg">
          <div class="card-body">
              <div class="row">
                  <div class="col-12">
                      <h4 class="card-title">Statistik Pendaftaran</h4>
                      <canvas id="pendaftaranChart" height="150"></canvas>
                  </div>
                  <div class="col-12 mt-4">
                      <h4 class="card-title">Distribusi Pendaftar Per Fakultas Tahun {{ date('Y') }}</h4>
                      <canvas id="topFakultasChart" height="150"></canvas>
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
        <p class="fs-30 mb-2">
            <span id="pendaftar-hari-ini">{{ number_format($pendaftarHariIni,0) }}</span>
        </p>
      
      </div>
    </div>
  </div>
  <div class="col-md-6 mb-4 stretch-card transparent">
    <div class="card card-dark-blue">
      <div class="card-body">
        <p class="mb-4">Total Pendaftar</p>
        <p class="fs-30 mb-2">{{ number_format(\App\Helpers\AkademikHelpers::getTotalDaftar(),0)}}</p>
        
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
    <div class="card card-light-blue">
      <div class="card-body">
        <p class="mb-4">Jumlah Calon MABA</p>
        <p class="fs-30 mb-2">{{ number_format(\App\Helpers\AkademikHelpers::getTotalLulus(),0)}}</p>
       
      </div>
    </div>
  </div>
  <div class="col-md-6 stretch-card transparent">
    <div class="card card-light-danger">
      <div class="card-body">
        <p class="mb-4">Jumlah PIN Aktif</p>
        <p class="fs-30 mb-2">{{ number_format(\App\Helpers\AkademikHelpers::getJumlahPin(),0)}}</p>
       
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
    <p class="card-title">Rekap Pendaftaran PMB Tahun 2025</p>
    <a href="{{ route('export.pendaftaran') }}" class="btn btn-success btn-flat">
      <i class="mdi mdi-file-excel"></i> Export Excel
    </a>
   </div>
   <p class="font-weight-800">Per Tanggal : {{ $tanggal }}</p>
    <p class="font-weight-500">Jumlah Pendaftaran Calon Mahasiswa Baru UNSAP 2025</p>
    <div class="table-responsive">
      <table id="refMember2025" class="display expandable-table table-hover" style="width:100%">
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
              <td>Pendidikan Bahasa Indonesia (S2)</td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getDaftar('8813') }}</td>
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
              <td>Informatika (S1)</td>
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
<div class="card">
  <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
   <div class="d-flex justify-content-between">
    <p class="card-title">Rekap Pendaftaran PMB Tahun 2024</p>
    {{-- <a href="{{ route('export.pendaftaran.2024') }}" class="btn btn-success btn-flat">
      <i class="mdi mdi-file-excel"></i> Export 2024 --}}
    </a>
   </div>
   <p class="font-weight-800">Per Tanggal : {{ now()->day }} {{ now()->translatedFormat('F') }} 2024</p>
    <p class="font-weight-500">Jumlah Pendaftaran Calon Mahasiswa Baru UNSAP</p>
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
              <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar2('8813') }} </td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin2('8813') }}</td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus2('8813') }}</td>
              
            </tr> 

            <tr>
              <td></td>
              <td>Pendidikan Matematika (S2)</td>
              <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar2('84102') }} </td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin2('84102') }}</td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus2('84102') }}</td>
              
            </tr> 


            <tr>
              <td></td>
              <td>Pendidikan Bahasa dan Sastra Indonesia (S1)</td>
              <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar2('5b3ff355-1e20-4c1d-8b47-e559b6991036') }} </td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin2('5b3ff355-1e20-4c1d-8b47-e559b6991036') }}</td>
              
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus2('5b3ff355-1e20-4c1d-8b47-e559b6991036') }}</td>
              
            </tr> 

            <tr>
              <td></td>
              <td>Pendidikan Guru Pendidikan Anak Usia Dini (S1)</td>
              <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar2('f48cbc83-b3c6-4e66-9e68-209b52a275e4') }} </td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin2('f48cbc83-b3c6-4e66-9e68-209b52a275e4') }}</td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus2('f48cbc83-b3c6-4e66-9e68-209b52a275e4') }}</td>
            </tr> 

            <tr>
              <td></td>
              <td>Pendidikan Guru Sekolah Dasar (S1)</td>
              <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar2('eca49026-745e-451c-8121-bfc81d4e9fe4') }} </td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin2('eca49026-745e-451c-8121-bfc81d4e9fe4') }}</td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus2('eca49026-745e-451c-8121-bfc81d4e9fe4') }}</td>
            </tr> 

            <tr>
              <td></td>
              <td>Pendidikan Jasmani Kesehatan dan Rekreasi (S1)</td>
              <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar2('adc77657-6904-4aa3-bc6e-40565bdc27bf') }} </td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin2('adc77657-6904-4aa3-bc6e-40565bdc27bf') }}</td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus2('adc77657-6904-4aa3-bc6e-40565bdc27bf') }}</td>
            </tr> 

            <tr>
              <td></td>
              <td>Pendidikan Matematika (S1)</td>
              <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar2('5f69f4f0-ebbb-4638-8df1-4632b05326de') }} </td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin2('5f69f4f0-ebbb-4638-8df1-4632b05326de') }}</td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus2('5f69f4f0-ebbb-4638-8df1-4632b05326de') }}</td>
            </tr> 

            <tr>
              <td></td>
              <td>Pendidikan Teknik Mesin (S1)</td>
              <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar2('2cadf663-1d4c-4fd4-9457-6fc2b50bd1b3') }} </td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin2('2cadf663-1d4c-4fd4-9457-6fc2b50bd1b3') }}</td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus2('2cadf663-1d4c-4fd4-9457-6fc2b50bd1b3') }}</td>
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
              <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar2('1daad851-b93f-4860-b37d-ddae33f1b860') }} </td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin2('1daad851-b93f-4860-b37d-ddae33f1b860') }}</td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus2('1daad851-b93f-4860-b37d-ddae33f1b860') }}</td>
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
              <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar2('1ff84166-cc64-48aa-a38e-3c6a952a8b90') }} </td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin2('1ff84166-cc64-48aa-a38e-3c6a952a8b90') }}</td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus2('1ff84166-cc64-48aa-a38e-3c6a952a8b90') }}</td>
            </tr> 
            <tr>
              <td></td>
              <td>Akuntansi (S1)</td>
              <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar2('08b181bc-1860-4c7e-8bda-ef4fbd59d869') }} </td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin2('08b181bc-1860-4c7e-8bda-ef4fbd59d869') }}</td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus2('08b181bc-1860-4c7e-8bda-ef4fbd59d869') }}</td>
            </tr> 
            <tr>
              <td></td>
              <td>Manajemen (S1)</td>
              <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar2('8c56f8a8-8f27-4e2f-8376-a433b2862f36') }} </td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin2('8c56f8a8-8f27-4e2f-8376-a433b2862f36') }}</td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus2('8c56f8a8-8f27-4e2f-8376-a433b2862f36') }}</td>
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
              <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar2('d778be91-7bc7-4757-bd22-9038fa8adeb4') }} </td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin2('d778be91-7bc7-4757-bd22-9038fa8adeb4') }}</td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus2('d778be91-7bc7-4757-bd22-9038fa8adeb4') }}</td>
            </tr> 
            <tr>
              <td></td>
              <td>Administrasi Publik (S1)</td>
              <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar2('e992676b-23e6-49e1-a2f6-81f90876b7da') }} </td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin2('e992676b-23e6-49e1-a2f6-81f90876b7da') }}</td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus2('e992676b-23e6-49e1-a2f6-81f90876b7da') }}</td>
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
              <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar2('93366fa0-45df-457c-a723-01b78226ad34') }} </td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin2('93366fa0-45df-457c-a723-01b78226ad34') }}</td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus2('93366fa0-45df-457c-a723-01b78226ad34') }}</td>
            </tr> 
            <tr>
              <td></td>
              <td>Ilmu Keperawatan (S1)</td>
              <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar2('303e6a30-c87a-4f70-8431-8ccc03b058f4') }} </td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin2('303e6a30-c87a-4f70-8431-8ccc03b058f4') }}</td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus2('303e6a30-c87a-4f70-8431-8ccc03b058f4') }}</td>
            </tr> 
            <tr>
              <td></td>
              <td>Kesehatan Masyarakat (S1)</td>
              <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar2('b41b8150-b1e6-4c63-9455-26f91174933c') }} </td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin2('b41b8150-b1e6-4c63-9455-26f91174933c') }}</td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus2('b41b8150-b1e6-4c63-9455-26f91174933c') }}</td>
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
              <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar2('a74fffa1-43f1-4ab5-baca-dfbd08b22d20') }} </td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin2('a74fffa1-43f1-4ab5-baca-dfbd08b22d20') }}</td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus2('a74fffa1-43f1-4ab5-baca-dfbd08b22d20') }}</td>
            </tr> 
            <tr>
              <td></td>
              <td>Sistem Informasi (S1)</td>
              <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar2('aaf15037-cd57-4743-a5f8-fd30840f221e') }} </td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin2('aaf15037-cd57-4743-a5f8-fd30840f221e') }}</td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus2('aaf15037-cd57-4743-a5f8-fd30840f221e') }}</td>
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
              <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar2('1') }} </td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin2('1') }}</td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus2('1') }}</td>
            </tr> 
            <tr>
              <td></td>
              <td>Ekonomi Syariah (S1)</td>
              <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getDaftar2('2') }} </td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalPin2('2') }}</td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getLulus2('2') }}</td>
            </tr> 
            <tr>
              <td></td>
              <td><b>Total Keseluruhan</b></td>
              <td class="text-center"> {{ \App\Helpers\AkademikHelpers::getTotalDaftar2() }} </td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getJumlahPin2() }}</td>
              <td class="text-center">{{ \App\Helpers\AkademikHelpers::getTotalLulus2() }}</td>
            </tr>
            
            
           
           
          </tbody>
         
      </table>
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
<script>
// Chart Statistik Pendaftaran 2024 dan 2025
if ($("#pendaftaranChart").length) {
  const ctx = document.getElementById('pendaftaranChart').getContext('2d');
  new Chart(ctx, {
      type: 'bar',
      data: {
          labels: @json(\App\Helpers\AkademikHelpers::getBulanLabels()),
          datasets: [
              {
                  label: 'Pendaftar 2024',
                  data: @json($data2024),
                  backgroundColor: 'rgba(255, 192, 203, 0.7)', // Warna pink yang lebih lembut
                  borderColor: 'rgba(255, 99, 132, 1)',
                  borderWidth: 1
              },
              {
                  label: 'Pendaftar 2025',
                  data: @json($data2025),
                  backgroundColor: 'rgba(75, 192, 192, 0.7)', // Warna hijau yang lebih lembut
                  borderColor: 'rgba(54, 162, 235, 1)',
                  borderWidth: 1
              }
          ]
      },
      options: {
          responsive: true,
          scales: {
              y: {
                  beginAtZero: true,
                  ticks: {
                      callback: function(value) {
                          return value.toLocaleString('id-ID');
                      }
                  }
              }
          }
      }
  });
}

// Chart distribusi Fakultas 2025
if ($("#topFakultasChart").length) {
  const ctx2 = document.getElementById('topFakultasChart').getContext('2d');
  new Chart(ctx2, {
      type: 'doughnut',
      data: {
          labels: @json(\App\Helpers\AkademikHelpers::getNamaFakultas2025()),
          datasets: [{
              data: @json(\App\Helpers\AkademikHelpers::getDistribusiFakultas2025()),
              backgroundColor: [
                  '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                  '#9966FF', '#FF9F40', '#EB3B5A'
              ],
              borderWidth: 1
          }]
      },
      options: {
          responsive: true,
          plugins: {
              legend: {
                  position: 'bottom'
              },
              tooltip: {
                  callbacks: {
                      label: function(context) {
                          return `${context.label}: ${context.formattedValue} pendaftar`;
                      }
                  }
              }
          }
      }
  });
}
</script>
<script>
// Menangani klik tombol export
$('.btn-export').click(function(e) {
  e.preventDefault(); // Mencegah perilaku default
  
  // Mengirim permintaan AJAX untuk export data
  $.ajax({
      url: "{{ route('export.pendaftaran') }}", // URL endpoint export
      method: 'GET', // Metode HTTP GET
      data: {
          tahun: 2025 // Mengirim parameter tahun 2025
      },
      xhrFields: {
          responseType: 'blob' // Mengatur response sebagai blob
      },
      success: function(response) {
          // Membuat blob dari response
          var blob = new Blob([response]);
          // Membuat elemen link untuk download
          var link = document.createElement('a');
          link.href = window.URL.createObjectURL(blob);
          link.download = 'rekap_pendaftaran_2025.xlsx'; // Nama file download
          link.click(); // Memicu download
      },
      error: function(xhr) {
          // Menangani error dan menampilkan di console
          console.log(xhr.responseText);
      }
  });
});
</script>
<script>
function updatePendaftarHariIni() {
  $.get('/api/pendaftar-hari-ini', function(data) {
      $('#pendaftar-hari-ini').text(data.count.toLocaleString('id-ID'));
  });
}

// Jalankan fungsi update pertama kali saat halaman dimuat
updatePendaftarHariIni();

// Atur interval untuk memperbarui data setiap 30 detik (30000 milidetik)
setInterval(updatePendaftarHariIni, 30000);
</script>
@endpush
