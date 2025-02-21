@extends('layouts.error')

@section('content')
    {{-- 
        Halaman 404 Not Found dengan desain modern dan animasi
        Menggunakan Tailwind CSS untuk styling dan animasi
        Terdiri dari:
        1. Background gradient yang dinamis
        2. Card tengah dengan efek hover dan shadow
        3. Animasi pada teks dan gambar
        4. Tombol kembali ke beranda dengan transisi
    --}}
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-purple-500 via-pink-500 to-red-500">
        {{-- 
            Container utama dengan:
            - Background putih
            - Padding besar
            - Sudut melengkung
            - Shadow besar
            - Efek scale saat hover
            - Transisi halus
        --}}
        <div class="text-center bg-white p-12 rounded-2xl shadow-2xl transform hover:scale-105 transition-transform">
            {{-- 
                Teks error 404 dengan:
                - Ukuran sangat besar
                - Warna indigo
                - Animasi pulse
            --}}
            <h1 class="text-9xl font-bold text-indigo-600 animate-pulse">404</h1>
            
            {{-- Pesan error utama --}}
            <p class="text-2xl font-medium text-gray-600 mt-4">Halaman tidak ditemukan</p>
            
            {{-- Pesan tambahan yang lebih ringan --}}
            <p class="text-gray-500 mt-2">Sepertinya Anda Kelelahan Berkerja...</p>

            {{-- 
                Ilustrasi 404 dengan:
                - Lebar 64
                - Posisi tengah
                - Margin atas
                - Animasi float
            --}}
            <img src="{{ asset('images/404_human.svg') }}" alt="404 Illustration" class="w-64 mx-auto mt-8 animate-float">

            {{-- 
                Tombol kembali ke beranda dengan:
                - Padding yang cukup
                - Warna background indigo
                - Warna teks putih
                - Sudut melengkung
                - Efek hover
                - Transisi halus
                - Emoji roket
            --}}
            <a href="{{ url('/') }}"
                class="mt-6 inline-block px-6 py-3 bg-indigo-600 text-white rounded-lg 
                      hover:bg-indigo-700 transition duration-300">
                🚀 Kembali ke Beranda
            </a>
        </div>
    </div>
@endsection
