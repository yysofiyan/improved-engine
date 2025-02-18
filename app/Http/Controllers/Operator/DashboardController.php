<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use App\Models\Rekomendasi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil bulan dan tahun saat ini
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        // Hitung total pendaftar online
        $pendaftarOnline = Pendaftar::where('jenis_daftar', 'online')->count();
        
        // Hitung total pendaftar offline
        $pendaftarOffline = Pendaftar::where('jenis_daftar', 'offline')->count();
        
        // Hitung total rekomendasi sekolah
        $rekomendasiSekolah = Rekomendasi::count();

        return view('operator.dashboard', [
            'pendaftarOnline' => $pendaftarOnline,
            'pendaftarOffline' => $pendaftarOffline,
            'rekomendasiSekolah' => $rekomendasiSekolah,
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear,
            'tanggal' => now()->toDateString()
        ]);
    }
} 