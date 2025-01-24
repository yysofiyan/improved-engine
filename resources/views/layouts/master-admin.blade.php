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
            @include('layouts.sidebar-admin')
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
