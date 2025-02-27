<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.head')
    @stack('page-stylesheet')
</head>

<body>
    <div class="container-scroller">
        @include('layouts.navbar')
        <div class="container-fluid page-body-wrapper">
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('fakultas/dashboard') }}">
                            <i class="fa-solid fa-house fa-fw mr-2"></i>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('fakultas/pendaftaran') }}">
                            <i class="fa-solid fa-circle-user fa-fw mr-2"></i>
                            <span class="menu-title">Pendaftaran</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('fakultas/camaba') }}">
                            <i class="fa-solid fa-users fa-fw mr-2"></i>
                            <span class="menu-title">CAMABA</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('fakultas/rekomendasi') }}">
                            <i class="fa-solid fa-building fa-fw mr-2"></i>
                            <span class="menu-title">Rekomendasi Sekolah</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('fakultas/hasil-kelulusan') }}">
                            <i class="fa-solid fa-user-check fa-fw mr-2"></i>
                            <span class="menu-title">Hasil Kelulusan MABA</span>
                        </a>
                    </li>

            
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('keluar_fakultas') }}">
                           <i class="fa-solid fa-sign-out-alt fa-fw mr-2"></i>
                            <span class="menu-title">Keluar</span>
                        </a>
                    </li>
            
            
                    
                    
                </ul>
            </nav>
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row mb-4">
                        <div class="col-12">
                           {{--  <div class="alert alert-danger" role="alert">
                                <h4 class="text-center font-weight-bold m-0">MODE DEVELOPMENT</h4>
                                
                            </div> --}}
                        </div>
                    </div>
                  
                    @yield('content')
                </div>
                {{-- @include('layouts.footer') --}}
            </div>
        </div>
    </div>

    @include('layouts.script')
    @stack('page-script')
</body>

</html>
