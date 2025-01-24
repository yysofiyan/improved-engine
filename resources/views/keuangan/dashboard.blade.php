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
              <p class="fs-30 mb-2">4006</p>
            
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-4 stretch-card transparent">
          <div class="card card-dark-blue">
            <div class="card-body">
              <p class="mb-4">Total Pendaftar</p>
              <p class="fs-30 mb-2">61344</p>
              
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
          <div class="card card-light-blue">
            <div class="card-body">
              <p class="mb-4">Jumlah Calon MABA</p>
              <p class="fs-30 mb-2">34040</p>
             
            </div>
          </div>
        </div>
        <div class="col-md-6 stretch-card transparent">
          <div class="card card-light-danger">
            <div class="card-body">
              <p class="mb-4">Jumlah PIN Aktif</p>
              <p class="fs-30 mb-2">47033</p>
             
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
          <p class="card-title">Laporan Pendaftaran</p>
          <a href="#" class="text-info">Lihat</a>
         </div>
          <p class="font-weight-500">Jumlah Pendaftaran Calon Mahasiswa Baru UNSAP 2024</p>
          <div id="sales-legend" class="chartjs-legend mt-4 mb-2"><ul class="1-legend"><li><span style="background-color: rgb(152, 189, 255);"></span>Pendaftar Offline</li><li><span style="background-color: rgb(75, 73, 172);"></span>Pendaftar Online</li></ul></div>
          <canvas id="sales-chart" width="860" height="430" style="display: block; height: 215px; width: 430px;" class="chartjs-render-monitor"></canvas>
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
                      <h1 class="text-primary">1600 Maba</h1>
                      
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
                                  <div class="progress-bar bg-primary" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                              </td>
                              <td><h5 class="font-weight-bold mb-0">713</h5></td>
                            </tr>
                            <tr>
                              <td class="text-muted">Fakultas FEB</td>
                              <td class="w-100 px-0">
                                <div class="progress progress-md mx-4">
                                  <div class="progress-bar bg-warning" role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                              </td>
                              <td><h5 class="font-weight-bold mb-0">583</h5></td>
                            </tr>
                            <tr>
                              <td class="text-muted">Fakultas FISIP</td>
                              <td class="w-100 px-0">
                                <div class="progress progress-md mx-4">
                                  <div class="progress-bar bg-danger" role="progressbar" style="width: 95%" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                              </td>
                              <td><h5 class="font-weight-bold mb-0">924</h5></td>
                            </tr>
                            <tr>
                              <td class="text-muted">Fakultas FIB</td>
                              <td class="w-100 px-0">
                                <div class="progress progress-md mx-4">
                                  <div class="progress-bar bg-info" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                              </td>
                              <td><h5 class="font-weight-bold mb-0">664</h5></td>
                            </tr>
                            <tr>
                              <td class="text-muted">Fakultas FTI</td>
                              <td class="w-100 px-0">
                                <div class="progress progress-md mx-4">
                                  <div class="progress-bar bg-primary" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                              </td>
                              <td><h5 class="font-weight-bold mb-0">560</h5></td>
                            </tr>
                            <tr>
                              <td class="text-muted">Fakultas FIKES</td>
                              <td class="w-100 px-0">
                                <div class="progress progress-md mx-4">
                                  <div class="progress-bar bg-danger" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                              </td>
                              <td><h5 class="font-weight-bold mb-0">793</h5></td>
                            </tr>
                          </tbody></table>
                        </div>
                      </div>
                      <div class="col-md-6 mt-3"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                        <canvas id="south-america-chart" height="330" width="660" class="chartjs-render-monitor" style="display: block; height: 165px; width: 330px;"></canvas>
                        <div id="south-america-legend"><div class="report-chart"><div class="d-flex justify-content-between mx-4 mx-xl-5 mt-3"><div class="d-flex align-items-center"><div class="mr-3" style="width:20px; height:20px; border-radius: 50%; background-color: #4B49AC"></div><p class="mb-0">Pendaftar Offline</p></div><p class="mb-0">495343</p></div><div class="d-flex justify-content-between mx-4 mx-xl-5 mt-3"><div class="d-flex align-items-center"><div class="mr-3" style="width:20px; height:20px; border-radius: 50%; background-color: #FFC100"></div><p class="mb-0">Pendaftar Online</p></div><p class="mb-0">630983</p></div><div class="d-flex justify-content-between mx-4 mx-xl-5 mt-3"><div class="d-flex align-items-center"><div class="mr-3" style="width:20px; height:20px; border-radius: 50%; background-color: #248AFD"></div><p class="mb-0">Belum Daftar</p></div><p class="mb-0">290831</p></div></div></div>
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
@endpush
