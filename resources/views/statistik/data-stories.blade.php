@extends('layouts.without-sidebar')

@section('title', 'Data Stories Pendaftaran')

@section('content')
    <div class="container-fluid">

        <h1 class="text-center mb-4 text-gray-800 dark:text-white forced-color-adjust-auto">
            Data Stories Pendaftaran Mahasiswa Baru Universitas Sebelas April<br> 
            Tahun Akademik 2025/2026
        </h1>
        <!-- Informasi Waktu Realtime -->
        <p class="text-center mb-4 text-gray-600 dark:text-gray-300 forced-color-adjust-auto">Data diperbarui secara realtime : <span id="realtime"
                class="font-medium text-gray-800 dark:text-gray-100"></span> WIB</p>

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
            <div class="col-12 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Pendaftaran Harian (7 Hari Terakhir)</h5>
                        <div id="dailyChart" class="chart-container" style="height: 300px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Pendaftaran Berdasarkan Prodi</h5>
                        <div id="prodiChart" class="chart-container" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Grafik Distribusi Fakultas -->
            <div class="col-12 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Distribusi Pendaftar Per Fakultas 2025</h5>
                        <div id="topFakultasChart" class="chart-container" style="height: 300px;"></div>
                    </div>
                </div>
            </div>

            <!-- Grafik Perbandingan Pendaftaran -->
            <div class="col-12 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Perbandingan Pendaftaran</h5>
                        <div id="pendaftaranChart" class="chart-container" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- data asal sekolah -->
    <div class="mb-4"></div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card mb-6">
                <div class="card-body">
                    <h5 class="card-title">Data Pendaftar Berdasarkan Asal Sekolah</h5>

                    <!-- Form Pilih Tahun -->
                    <div class="mb-4">
                        <form action="{{ route('data-stories.index') }}" method="GET">
                            <div class="input-group">
                                <select name="tahun" class="form-select" onchange="this.form.submit()">
                                    @for ($i = date('Y'); $i >= 2025; $i--)
                                        <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Nama Sekolah</th>
                                    {{-- <th class="text-center">PT Asal</th> --}}
                                    <th class="text-center">Jumlah Pendaftar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($asalSekolahData->count() > 0)
                                    @foreach ($asalSekolahData as $index => $sekolah)
                                        <tr>
                                            <td class="text-center">
                                                {{ ($asalSekolahData->currentPage() - 1) * $asalSekolahData->perPage() + $index + 1 }}
                                            </td>
                                            <td>{{ $sekolah->asal_sekolah }}</td>
                                            {{-- <td class="text-center">{{ $sekolah->kode_pt_asal }}</td> --}}
                                            <td class="text-center">{{ number_format($sekolah->total, 0) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center">Data tidak ditemukan</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $asalSekolahData->appends(['tahun' => $tahun, 'search' => request('search')])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-20"></div> <!-- Div kosong dengan margin bottom 20 -->

    <!-- Memuat library jQuery untuk manipulasi DOM dan AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Library Chart.js untuk membuat visualisasi grafik -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>

    <!-- Plugin Chart.js untuk menambahkan label data pada grafik -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0/chartjs-plugin-datalabels.min.js">
    </script>

    <!-- Plugin Chart.js untuk menambahkan anotasi pada grafik -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.0.2/dist/chartjs-plugin-annotation.min.js">
    </script>

    <!-- Library ApexCharts untuk membuat visualisasi grafik yang interaktif -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>

    <!-- Mulai bagian style untuk custom CSS -->
    <style>
        /* Pastikan container memiliki padding yang cukup */
        .container-fluid {
            padding: 20px;
        }

        /* Style untuk card */
        .card {
            margin-bottom: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 1.5rem;
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
            height: 300px;
            /* Sesuaikan tinggi grafik */
            width: 100%;
            /* Sesuaikan lebar grafik */
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

        .prodiChart {
            height: 400px;
            /* Sesuaikan tinggi grafik */
            width: 100px;
            /* Sesuaikan lebar grafik */
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
        // Grafik Pendaftaran Harian menggunakan ApexCharts
        const dailyChartElement = document.getElementById('dailyChart');
        if (dailyChartElement) {
            const options = {
                chart: {
                    type: 'line',
                    height: 400
                },
                series: [{
                    name: 'Jumlah Pendaftar',
                    data: {!! json_encode($dailyData) !!}
                }],
                xaxis: {
                    categories: {!! json_encode($dailyLabels) !!},
                    labels: {
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function(value) {
                            return value.toLocaleString('id-ID');
                        },
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                colors: ['#36A2EB'],
                stroke: {
                    width: 2
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        type: 'vertical',
                        shadeIntensity: 0.5,
                        gradientToColors: ['#36A2EB'],
                        inverseColors: true,
                        opacityFrom: 0.8,
                        opacityTo: 0.2,
                        stops: [0, 100]
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return value.toLocaleString('id-ID') + ' pendaftar';
                        }
                    }
                }
            };

            const chart = new ApexCharts(dailyChartElement, options);
            chart.render();
        } else {
            console.error('Elemen dailyChart tidak ditemukan');
        }

        // Grafik Pie Chart Pendaftaran Berdasarkan Prodi menggunakan ApexCharts
        const prodiChart = document.getElementById('prodiChart');
        if (prodiChart && typeof ApexCharts !== 'undefined') {
            const options = {
                series: {!! json_encode($prodiData) !!},
                chart: {
                    type: 'pie',
                    height: 400,
                    toolbar: { show: false }
                },
                labels: {!! json_encode($prodiLabels) !!},
                colors: ['#36A2EB', '#FF6384', '#4BC0C0', '#FFCE56', '#9966FF', '#FF9F40'],
                stroke: {
                    width: 1
                },
                plotOptions: {
                    pie: {
                        dataLabels: {
                            minAngleToShowLabel: 0,
                            formatter: function(val, opts) {
                                const actualValue = opts.w.config.series[opts.seriesIndex];
                                return actualValue;
                            }
                        }
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val, opts) {
                        const actualValue = opts.w.config.series[opts.seriesIndex];
                        return actualValue;
                    }
                },
                legend: {
                    position: 'bottom',
                    fontSize: '12px',
                    fontFamily: 'Inter, sans-serif'
                },
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return value.toLocaleString('id-ID') + ' pendaftar';
                        }
                    }
                },
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 300
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            const chart = new ApexCharts(prodiChart, options);
            chart.render();
        } else {
            console.error('Elemen prodiChart tidak ditemukan');
        }

        // Chart Statistik Pendaftaran 2024 dan 2025 menggunakan ApexCharts
        const pendaftaranChart = document.getElementById('pendaftaranChart');
        if (pendaftaranChart) {
            const options = {
                chart: {
                    type: 'bar',
                    height: 350
                },
                series: [{
                        name: 'Pendaftar 2024',
                        data: {!! json_encode($data2024) !!}
                    },
                    {
                        name: 'Pendaftar 2025',
                        data: {!! json_encode($data2025) !!}
                    }
                ],
                xaxis: {
                    categories: {!! json_encode(\App\Helpers\AkademikHelpers::getBulanLabels()) !!},
                    labels: {
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function(value) {
                            return value.toLocaleString('id-ID');
                        },
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                dataLabels: {
                    enabled: false
                },
                colors: ['#FF6384', '#36A2EB'],
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return value.toLocaleString('id-ID') + ' pendaftar';
                        }
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    }
                }
            };

            const chart = new ApexCharts(pendaftaranChart, options);
            chart.render();
        }

        // Chart distribusi Fakultas 2025
        document.addEventListener("DOMContentLoaded", function() {
            const topFakultasChart = document.getElementById('topFakultasChart');
            if (topFakultasChart) {
                const options = {
                    chart: {
                        type: 'donut',
                        height: 350
                    },
                    series: @json(\App\Helpers\AkademikHelpers::getDistribusiFakultas2025()),
                    labels: @json(\App\Helpers\AkademikHelpers::getNamaFakultas2025()),
                    colors: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#EB3B5A'],
                    legend: {
                        position: 'bottom',
                        fontSize: '12px',
                        formatter: function(seriesName, opts) {
                            return seriesName + ": " + opts.w.globals.series[opts.seriesIndex]
                                .toLocaleString('id-ID') + " pendaftar";
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function(value) {
                                return value.toLocaleString('id-ID') + " pendaftar";
                            }
                        }
                    },
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: {
                                width: 200
                            },
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }]
                };

                const chart = new ApexCharts(topFakultasChart, options);
                chart.render();
            }
        });

        // console.log(@json(\App\Helpers\AkademikHelpers::getNamaFakultas2025())); // Debugging: cek data nama fakultas
        // console.log(@json(\App\Helpers\AkademikHelpers::getDistribusiFakultas2025())); // Debugging: cek data distribusi fakultas

        function updateRealtime() {
            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };
            const formattedDate = new Intl.DateTimeFormat('id-ID', options).format(now);
            document.getElementById('realtime').textContent = formattedDate;
        }

        // Update waktu setiap detik
        setInterval(updateRealtime, 1000);
        updateRealtime(); // Panggil fungsi pertama kali

        // Auto-refresh setiap 1 menit
        // untuk refresh data secara realtime
        setInterval(() => {
            window.location.reload();
        }, 60000);
    </script>

   
@endsection
