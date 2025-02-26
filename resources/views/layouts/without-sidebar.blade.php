<!DOCTYPE html>
<html lang="id" class="dark:bg-gray-900">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Penerimaan Mahasiswa Baru UNSAP">
    <meta name="author" content="yysofiyan">

    <title>@yield('title') - PMB UNSAP</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    @stack('styles')
</head>

<body id="page-top" class="bg-gray-100 dark:bg-gray-900">
    <!-- Wrapper Halaman -->
    <div id="wrapper">
        <!-- Wrapper Konten -->
        <div id="content-wrapper" class="flex flex-col min-h-screen">
            <!-- Konten Utama -->
            <div id="content" class="flex-grow">
                <!-- Navigasi Atas -->
                <nav class="bg-white dark:bg-gray-800 shadow-md fixed w-full z-10">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between h-16">
                            <!-- Tombol Sidebar untuk Mobile -->
                            <div class="flex items-center md:hidden">
                                <button id="sidebarToggleTop" class="p-2 rounded-md text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none">
                                    <i class="fa fa-bars"></i>
                                </button>
                            </div>
                            
                            <!-- Menu Informasi -->
                            <div class="flex-1 flex items-center justify-center md:justify-start">
                                <a href="/informasi" class="bg-gray-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                                    <i class="fas fa-info-circle mr-2"></i>Informasi
                                </a>
                            </div>

                            <!-- Menu Pengguna -->
                            <div class="flex items-center">
                                <div class="relative">
                                    <button class="flex items-center focus:outline-none">
                                        <span class="hidden md:inline-block mr-2 text-gray-600 dark:text-gray-300">
                                            @auth
                                                {{ Auth::user()->name }}
                                            @else
                                                UNSAP
                                            @endauth
                                        </span>
                                        <img class="h-8 w-8 rounded-full" src="{{ asset('images/logo-importer-mini.png') }}" alt="Profil">
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
                
                <!-- Isi Halaman -->
                <div class="container mx-auto px-4 pt-20">
                    @yield('content')
                </div>
            </div>
            <!-- Akhir Konten Utama -->

            @include('partials.footer')
        </div>
        <!-- Akhir Wrapper Konten -->
    </div>
    <!-- Akhir Wrapper Halaman -->

    <!-- Tombol Scroll ke Atas -->
    <a class="scroll-to-top rounded-full p-3 bg-blue-500 dark:bg-blue-600 text-white fixed bottom-4 right-4 shadow-lg hover:bg-blue-600 dark:hover:bg-blue-700 transition-colors" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    @include('partials.modals')
    @include('partials.scripts')
    @stack('scripts')
</body>
</html>