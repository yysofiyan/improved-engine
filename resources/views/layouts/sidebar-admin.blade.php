<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/dashboard') }}">
                <i class="fa-solid fa-house fa-fw mr-2"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        
        <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/camaba') }}">
                <i class="fa-solid fa-users fa-fw mr-2"></i>
                <span class="menu-title">CAMABA</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/transaksi') }}">
                <i class="fa-solid fa-wallet fa-fw mr-2"></i>
                <span class="menu-title">Transaksi</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/konfirmasi-bayar') }}">
                <i class="fa-solid fa-user-check fa-fw mr-2"></i>
                <span class="menu-title">Konfirmasi</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/listnim') }}">
                <i class="fa-solid fa-university fa-fw mr-2"></i>
                <span class="menu-title">List NIM</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/migrasi') }}">
                <i class="fa-solid fa-repeat fa-fw mr-2"></i>
                <span class="menu-title">Migrasi Daftar</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#referensi" aria-expanded="false"
                aria-controls="referensi">
                <i class="fa-solid fa-file-lines fa-fw mr-2"></i>
                <span class="menu-title">Rekap</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="referensi">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item" style="list:none">
                        <a class="nav-link" href="{{ url('admin/online') }}">
                            <span class="menu-title">Pembayaran Online</span>
                        </a>
                    </li>

                    <li class="nav-item" style="list:none">
                        <a class="nav-link" href="{{ url('admin/offline') }}">
                            <span class="menu-title">Pembayaran Offline</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#pengguna" aria-expanded="false"
                aria-controls="pengguna">
                <i class="fa-solid fa-users fa-fw mr-2"></i>
                <span class="menu-title">Pengguna</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="pengguna">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item" style="list:none">
                        <a class="nav-link" href="{{ url('admin/panitia') }}">
                            <span class="menu-title">Panitia</span>
                        </a>
                    </li>

                    <li class="nav-item" style="list:none">
                        <a class="nav-link" href="{{ url('admin/fakultas') }}">
                            <span class="menu-title">Fakultas</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>


        <li class="nav-item">
            <a class="nav-link" href="{{ url('keluar_operator') }}">
               <i class="fa-solid fa-sign-out-alt fa-fw mr-2"></i>
                <span class="menu-title">Keluar</span>
            </a>
        </li>


        
        
    </ul>
</nav>
