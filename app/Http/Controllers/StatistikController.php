<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\AkademikHelpers;
use Carbon\Carbon;
use App\Models\Neomahasiswa;
use App\Models\Prodi;

class StatistikController extends Controller
{
    public function index(Request $request)
    {
        // Total Pendaftar
        $totalPendaftar = AkademikHelpers::getTotalDaftar();

        // Pendaftar Hari Ini
        $pendaftarHariIni = AkademikHelpers::getDaftarHariIni();

        // Pendaftar Bulan Ini
        $pendaftarBulanIni = Neomahasiswa::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // Data 7 Hari Terakhir
        $dailyData = [];
        $dailyLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dailyLabels[] = $date->format('d M');
            $dailyData[] = Neomahasiswa::whereDate('created_at', $date)->count();
        }

        // Data Berdasarkan Prodi
        $prodiData = Neomahasiswa::selectRaw('kodeprodi_satu, count(*) as total')
            ->groupBy('kodeprodi_satu')
            ->with('prodi')
            ->get();
        
        $prodiLabels = $prodiData->pluck('prodi.nama_prodi');
        $prodiData = $prodiData->pluck('total');

        // Data Statistik Pendaftaran 2024
        $data2024 = AkademikHelpers::getStatistikPendaftaran2024();

        // Data Statistik Pendaftaran 2025
        $data2025 = AkademikHelpers::getStatistikPendaftaran2025();

        // Data Distribusi Fakultas 2025
        $distribusiFakultas2025 = \App\Helpers\AkademikHelpers::getDistribusiFakultas2025();

        // Debugging: Cek data distribusi fakultas
        \Log::info('Distribusi Fakultas 2025:', $distribusiFakultas2025);

        $namaFakultas2025 = \App\Helpers\AkademikHelpers::getNamaFakultas2025();

        // Debugging: Cek data distribusi fakultas
        \Log::info('Nama Fakultas 2025:', \App\Helpers\AkademikHelpers::getNamaFakultas2025());
        \Log::info('Distribusi Fakultas 2025:', \App\Helpers\AkademikHelpers::getDistribusiFakultas2025());

        // Data Berdasarkan Prodi (Tahun 2025)
        $prodiData = Neomahasiswa::whereYear('created_at', 2025)
            ->with('prodiRelation')
            ->get()
            ->groupBy('prodiRelation.nama_prodi')
            ->map(function ($items) {
                return $items->count();
            });

        $prodiLabels = $prodiData->keys()->toArray();
        $prodiData = $prodiData->values()->toArray();

        // Data Asal Sekolah dengan filter tahun
        $tahun = $request->tahun ?? date('Y'); // Default tahun sekarang
        $asalSekolahData = Neomahasiswa::selectRaw('asal_sekolah, kode_pt_asal, count(*) as total')
            ->whereYear('created_at', $tahun) // Filter berdasarkan tahun
            ->whereNotNull('kode_pt_asal') // Hanya ambil data dengan kode_pt_asal yang valid
            ->when($request->search, function($query) use ($request) {
                return $query->where('asal_sekolah', 'like', '%'.$request->search.'%');
            })
            ->groupBy('asal_sekolah', 'kode_pt_asal') // Group by asal_sekolah dan kode_pt_asal
            ->orderByDesc('total')
            ->paginate(10); // Ubah 10 menjadi 50 untuk menampilkan lebih banyak data per halaman

        return view('statistik.data-stories', compact(
            'totalPendaftar',
            'pendaftarHariIni',
            'pendaftarBulanIni',
            'dailyLabels',
            'dailyData',
            'prodiLabels',
            'prodiData',
            'data2024',
            'data2025',
            'distribusiFakultas2025',
            'namaFakultas2025',
            'asalSekolahData',
            'tahun' // Kirim variabel tahun ke view
        ));
    }
} 