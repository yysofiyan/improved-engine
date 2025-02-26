@extends('layouts.without-sidebar')

@section('title', 'Informasi Pendaftaran')

@section('content')
<div class="container">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Pendaftaran Mahasiswa Baru</h6>
        </div>
        <div class="card-body">
            @php
                $now = \Carbon\Carbon::now();
                $startDate = \Carbon\Carbon::parse(env('REG_START'));
                $endDate = \Carbon\Carbon::parse(env('REG_END'));
                $registrationOpen = $now->between($startDate, $endDate);
            @endphp

            @if($registrationOpen)
                <div class="alert alert-success">
                    <h4 class="alert-heading">Pendaftaran Sedang Dibuka!</h4>
                    <p>Pendaftaran mahasiswa baru sedang berlangsung hingga {{ $endDate->translatedFormat('l, j F Y H:i') }}</p>
                </div>
            @else
                <div class="alert alert-info">
                    <h4 class="alert-heading">Pendaftaran Belum Dibuka</h4>
                    <p>Pendaftaran akan dimulai pada {{ $startDate->translatedFormat('l, j F Y H:i') }}</p>
                    
                    <div class="mt-4">
                        <h5>Countdown Pendaftaran:</h5>
                        <div class="countdown-timer">
                            <div class="d-flex justify-content-center">
                                <div class="mx-2 text-center">
                                    <div class="display-4">{{ $startDate->diffInDays($now) }}</div>
                                    <div>Hari</div>
                                </div>
                                <div class="mx-2 text-center">
                                    <div class="display-4">{{ $startDate->diffInHours($now) % 24 }}</div>
                                    <div>Jam</div>
                                </div>
                                <div class="mx-2 text-center">
                                    <div class="display-4">{{ $startDate->diffInMinutes($now) % 60 }}</div>
                                    <div>Menit</div>
                                </div>
                                <div class="mx-2 text-center">
                                    <div class="display-4">{{ $startDate->diffInSeconds($now) % 60 }}</div>
                                    <div>Detik</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mt-4">
                <h4>Jadwal Pendaftaran:</h4>
                <ul>
                    <li>Mulai: {{ $startDate->translatedFormat('l, j F Y H:i') }}</li>
                    <li>Berakhir: {{ $endDate->translatedFormat('l, j F Y H:i') }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- @if(!$registrationOpen)
    @push('scripts')
    <script>
        // Auto refresh countdown setiap 1 detik
        setInterval(function() {
            location.reload();
        }, 1000);
    </script>
    @endpush
@endif --}}
@endsection 