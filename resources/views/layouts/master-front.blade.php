<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.head')
    @stack('page-stylesheet')


</head>

<body>
    <div class="container-scroller">
    @hasSection('sesi')
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-danger" role="alert">
                <h4 class="text-center font-weight-bold m-0">Konfirmasi Identitas Mahasiswa Baru Universitas Sebelas April </h4>
                <p class="text-center font-weight-bold m-0">sampai tanggal 20 Oktober 2022</p>
            </div>
        </div>
    </div>
     @endif


        @yield('content')
    </div>

    @include('layouts.script')
    @stack('page-script')
</body>

</html>
