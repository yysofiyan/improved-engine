@extends('layouts.master-dashboard')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="row">
            <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                <h3 class="font-weight-bold">Halo, Admin {{ session('nama') }}</h3>
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
    <div class="col-xl-4 grid-margin stretch-card d-none d-xl-block">
        <div class="card tale-bg">
            <div class="card-people mt-auto">
                <img src="{{ url('images/student.svg') }}" alt="people">
            </div>
        </div>
    </div>
    <div class="col-xl-8 grid-margin transparent">
        <div class="row">
            <div class="col-md-12 mb-4 stretch-card transparent">
                <div class="card card-tale">
                    <div class="card-body">
                        <p class="mb-4">Profil </p>
                        <dl class="row">
                           
                            
                        </dl>
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
