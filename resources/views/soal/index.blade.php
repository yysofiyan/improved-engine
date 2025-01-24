@extends('layouts.master-maba')

@section('title', 'Dashboard')
@section('content')
<div class="row" style="margin-top:50px;">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Halo, {{ session('nama_mahasiswa') }}</h3>
                <h3 class="font-weight-bold">Silahkan Lakukan Ujian dan Jawab Soal Dibawah Ini  </h3>
                
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
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold"><div>Waktu Ujian = <span style="color:red" id="timer"></span></div>
                   
                </h3>
                
            </div>
        </div> 
    </div>
</div>

<livewire:soal-ujian></livewire:soal-ujian>

@endsection

@push('page-stylesheet')
@endpush

@push('page-script')



@endpush
