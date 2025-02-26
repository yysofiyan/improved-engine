<?php

namespace App\Providers;

use Carbon\Carbon;
use App\View\Composers\ModeComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    /**
     * Metode boot untuk menginisialisasi konfigurasi aplikasi
     * 
     * Metode ini akan dijalankan saat aplikasi di-boot. Beberapa tugas yang dilakukan:
     * 1. Mengatur locale aplikasi ke bahasa Indonesia
     * 2. Mengatur locale Carbon (untuk format tanggal) ke bahasa Indonesia
     * 3. Menambahkan view composer untuk layout master-dashboard
     * 4. Menambahkan view composer khusus untuk halaman pendaftaran mahasiswa baru
     */
    public function boot()
    {
        // Mengatur locale aplikasi ke bahasa Indonesia
        config(['app.locale' => 'id']);
        
        // Mengatur locale Carbon untuk format tanggal bahasa Indonesia
        Carbon::setLocale('id');
        
        // Menambahkan view composer untuk layout master-dashboard
        View::composer('layouts.master-dashboard', ModeComposer::class);
        
        /**
         * View composer untuk halaman pendaftaran mahasiswa baru
         * 
         * Menambahkan variabel berikut ke view maba.register:
         * - registrationOpen: boolean apakah pendaftaran sedang dibuka
         * - countdown: array berisi jumlah hari, jam, menit, dan detik hingga pendaftaran dibuka
         * - startDate: tanggal mulai pendaftaran
         * - endDate: tanggal berakhirnya pendaftaran
         */
        // View::composer('maba.register', function ($view) {
        //     $start = Carbon::parse(env('REG_START'));
        //     $end = Carbon::parse(env('REG_END'));
            
        //     $now = Carbon::now();
            
        //     $diff = $start->diff($now);
            
        //     $view->with([
        //         'registrationOpen' => $now->between($start, $end),
        //         'countdown' => [
        //             'days' => $diff->days,
        //             'hours' => $diff->h,
        //             'minutes' => $diff->i,
        //             'seconds' => $diff->s,
        //             'total_seconds' => $now->diffInSeconds($start)
        //         ],
        //         'startDate' => $start,
        //         'endDate' => $end
        //     ]);
        // });
    }
}