<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.head')
    @stack('page-stylesheet')
    @livewireStyles
</head>

<body>
  <div class="container-scroller">
    <!-- partial:../../partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" href="../../index.html"><img src="{{ url('images/logo-importer-mini.png') }}" class="mr-2" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="../../index.html"><img src="{{ url('images/logo-importer-mini.png') }}" alt="logo"/></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">


        <ul class="navbar-nav navbar-nav-right">

          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
              <img src="{{url('images/student.png')}}" alt="profile"/>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  <i class="ti-power-off text-primary"></i>
                  Logout
                </a>
              </form>
            </div>
          </li>

        </ul>

      </div>
    </nav>
    <!-- partial -->
    <div class="content-wrapper">
        @yield('content')

    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <footer class="footer">
    <div class="d-sm-flex justify-content-center justify-content-sm-between">
      <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © {{ date('Y') }}.  Sistem Informasi Penerimaan Mahasiswa Baru</span>
      <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Universitas Sebelas April<i class="ti-heart text-danger ml-1"></i></span>
    </div>
  </footer>
  @include('layouts.script')
  @stack('page-script')
  @livewireScripts
</body>

</html>
