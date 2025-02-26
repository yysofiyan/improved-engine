@extends('layouts.without-sidebar')

@section('title', 'Statistik Pendaftaran')

@section('content')
<div class="container-fluid">
   
    <h1 class="text-center mb-4 text-white forced-color-adjust-auto">Statistik Pendaftaran Mahasiswa Baru Universitas Sebelas April<br> Tahun Akademik 2025/2026</h1>
     <!-- Informasi Waktu Realtime -->
     <p class="text-center mb-4 text-gray-600 dark:text-gray-300">Data diambil pada <span id="realtime" class="font-medium text-gray-800 dark:text-gray-100"></span> WIB</p>

    <!-- Card Section -->
    <div class="row mb-4">
        <!-- Card Total Pendaftar -->
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Pendaftar</h5>
                    <h2 class="card-text">{{ number_format($totalPendaftar, 0) }}</h2>
                </div>
            </div>
        </div>

        <!-- Card Pendaftar Hari Ini -->
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Pendaftar Hari Ini</h5>
                    <h2 class="card-text">{{ number_format($pendaftarHariIni, 0) }}</h2>
                </div>
            </div>
        </div>

        <!-- Card Pendaftar Bulan Ini -->
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Pendaftar Bulan Ini</h5>
                    <h2 class="card-text">{{ number_format($pendaftarBulanIni, 0) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Section -->
    <div class="row">
        <!-- Grafik Pendaftaran Harian -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Pendaftaran Harian (7 Hari Terakhir)</h5>
                    <div class="chart-container">
                        <canvas id="dailyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik Pendaftaran Berdasarkan Prodi -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Pendaftaran Berdasarkan Prodi</h5>
                    <div class="chart-container">
                        <canvas id="prodiChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Perbandingan Pendaftaran -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Grafik Pendaftaran Berdasarkan Prodi -->
        <div class="bg-white rounded-lg shadow-md h-96">
            <div class="p-4">
                <h5 class="text-lg font-semibold mb-2">Perbandingan Pendaftaran 2024 & 2025</h5>
                <div class="h-72">
                    {{-- <canvas id="pendaftaranChart"></canvas> --}}
                    <div id="pendaftaranChart" class="chart-container"></div>
                </div>
            </div>
        </div>

        <!-- Grafik Distribusi Fakultas -->
        <div class="bg-white rounded-lg shadow-md h-96">
            <div class="p-4">
                <h5 class="text-lg font-semibold mb-2">Distribusi Pendaftar Per Fakultas 2025</h5>
                <div class="h-72">
                    {{-- <canvas id="topFakultasChart"></canvas> --}}
                    <div id="topFakultasChart" class="chart-container"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-6"></div>
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card mb-6">
                <div class="card-body">
                    <h5 class="card-title">Data Pendaftar Berdasarkan Asal Sekolah</h5>
                    
                    <!-- Search Form -->
                    <div class="mb-4">
                        <form action="{{ route('statistik.index') }}" method="GET">
                            <div class="input-group">
                                <input type="text" 
                                       name="search" 
                                       class="form-control" 
                                       placeholder="Cari nama sekolah..." 
                                       value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                @if(request('search'))
                                    <a href="{{ route('statistik.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i> Reset
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Nama Sekolah</th>
                                    <th class="text-center">Jumlah Pendaftar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($asalSekolahData->count() > 0)
                                    @foreach($asalSekolahData as $index => $sekolah)
                                    <tr>
                                        <td class="text-center">{{ ($asalSekolahData->currentPage() - 1) * $asalSekolahData->perPage() + $index + 0 }}</td>
                                        <td>{{ $sekolah->asal_sekolah }}</td>
                                        <td class="text-center">{{ number_format($sekolah->total, 0) }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" class="text-center">Data tidak ditemukan</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $asalSekolahData->appends(['search' => request('search')])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-20"></div> <!-- Div kosong dengan margin bottom 20 -->


<style>
    /* Pastikan container memiliki padding yang cukup */
    .container-fluid {
        padding: 20px;
    }

    /* Style untuk card */
    .card {
        margin-bottom: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .card-body {
        padding: 20px;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .card-text {
        font-size: 2rem;
        font-weight: 700;
    }

    /* Style untuk grafik container */
    .chart-container {
        position: relative;
        height: 400px; /* Sesuaikan tinggi grafik */
        width: 100%;
    }

    /* Style untuk judul halaman */
    h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .chartjs-legend {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 1rem;
    }
    #prodiChart {
        height: 300px; /* Sesuaikan tinggi grafik */
        width: 100%; /* Sesuaikan lebar grafik */
    }

    .table-responsive {
        overflow-x: auto;
    }

    .table-bordered {
        border: 1px solid #dee2e6;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6;
    }
</style>
<script>
    // Grafik Pendaftaran Harian
    const dailyCtx = document.getElementById('dailyChart').getContext('2d');
    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dailyLabels) !!},
            datasets: [{
                label: 'Jumlah Pendaftar',
                data: {!! json_encode($dailyData) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Grafik Pendaftaran Berdasarkan Prodi
    const prodiChart = document.getElementById('prodiChart');
    if (prodiChart) {
        const ctx = prodiChart.getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($prodiLabels),
                datasets: [{
                    label: 'Jumlah Pendaftar',
                    data: @json($prodiData),
                    backgroundColor: '#36A2EB',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.label}: ${context.formattedValue} pendaftar`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true
                    },
                    y: {
                        ticks: {
                            autoSkip: false,
                            font: {
                                size: 12
                            },
                            padding: 20
                        }
                    }
                }
            }
        });
    }

    // Chart Statistik Pendaftaran 2024 dan 2025
    const pendaftaranChart = document.getElementById('pendaftaranChart');
    if (pendaftaranChart) {
        const ctx = pendaftaranChart.getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(\App\Helpers\AkademikHelpers::getBulanLabels()) !!},
                datasets: [
                    {
                        label: 'Pendaftar 2024',
                        data: {!! json_encode($data2024) !!},
                        backgroundColor: 'rgba(255, 192, 203, 0.7)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Pendaftar 2025',
                        data: {!! json_encode($data2025) !!},
                        backgroundColor: 'rgba(75, 192, 192, 0.7)',
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
    const topFakultasChart = document.getElementById('topFakultasChart');
    if (topFakultasChart) {
        const ctx2 = topFakultasChart.getContext('2d');
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
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 12
                            },
                            generateLabels: function(chart) {
                                const data = chart.data;
                                if (data.labels.length && data.datasets.length) {
                                    return data.labels.map(function(label, i) {
                                        return {
                                            text: label,
                                            fillStyle: data.datasets[0].backgroundColor[i],
                                            hidden: false,
                                            index: i
                                        };
                                    });
                                }
                                return [];
                            }
                        }
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

    console.log(@json(\App\Helpers\AkademikHelpers::getNamaFakultas2025())); // Debugging: cek data nama fakultas
    console.log(@json(\App\Helpers\AkademikHelpers::getDistribusiFakultas2025())); // Debugging: cek data distribusi fakultas

    function updateRealtime() {
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
        const formattedDate = new Intl.DateTimeFormat('id-ID', options).format(now);
        document.getElementById('realtime').textContent = formattedDate;
    }

    // Update waktu setiap detik
    setInterval(updateRealtime, 1000);
    updateRealtime(); // Panggil fungsi pertama kali
</script>
@endsection