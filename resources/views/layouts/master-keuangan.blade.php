<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.head')
    @stack('page-stylesheet')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
</head>

<body>
    <div class="container-scroller">
        @include('layouts.navbar')
        <div class="container-fluid page-body-wrapper">
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('keuangan/dashboard') }}">
                            <i class="fa-solid fa-house fa-fw mr-2"></i>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('keuangan/buatpin') }}">
                            <i class="fa-solid fa-window-restore fa-fw mr-2"></i>
                            <span class="menu-title">Buat PIN</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('keuangan/transaksi') }}">
                            <i class="fa-solid fa-wallet fa-fw mr-2"></i>
                            <span class="menu-title">Transaksi</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('keuangan/konfirmasi-bayar') }}">
                            <i class="fa-solid fa-user-check fa-fw mr-2"></i>
                            <span class="menu-title">Konfirmasi</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('keuangan/buatnim') }}">
                            <i class="fa-solid fa-university fa-fw mr-2"></i>
                            <span class="menu-title">Buat NIM</span>
                        </a>
                    </li>
            
            
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('keluar_operator') }}">
                           <i class="fa-solid fa-sign-out-alt fa-fw mr-2"></i>
                            <span class="menu-title">Keluar</span>
                        </a>
                    </li>
            
            
                    
                    
                </ul>
            </nav>
            <div class="main-panel">
                <div class="content-wrapper">
                    
                  
                    @yield('content')
                </div>
                {{-- @include('layouts.footer') --}}
            </div>
        </div>
    </div>

    @include('layouts.script')
    @stack('page-script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    @stack('scripts')
</body>

</html>
