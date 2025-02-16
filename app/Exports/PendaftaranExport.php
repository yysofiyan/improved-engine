<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use App\Helpers\AkademikHelpers;

class PendaftaranExport implements FromView
{
    protected $tahun;

    public function __construct($tahun)
    {
        $this->tahun = $tahun;
    }

    public function view(): View
    {
        $data = [
            [
                'no' => 1,
                'fakultas' => 'Fakultas Keguruan dan Ilmu Pendidikan',
                'prodi' => [
                    ['nama' => 'Pendidikan Bahasa Indonesia(S2)', 'kode' => '8813'],
                    ['nama' => 'Pendidikan Matematika (S2)', 'kode' => '84102'],
                    ['nama' => 'Pendidikan Bahasa dan Sastra Indonesia (S1)', 'kode' => '5b3ff355-1e20-4c1d-8b47-e559b6991036'],
                    ['nama' => 'Pendidikan Guru Pendidikan Anak Usia Dini (S1)', 'kode' => 'f48cbc83-b3c6-4e66-9e68-209b52a275e4'],
                    ['nama' => 'Pendidikan Guru Sekolah Dasar (S1)', 'kode' => 'eca49026-745e-451c-8121-bfc81d4e9fe4'],
                    ['nama' => 'Pendidikan Jasmani Kesehatan dan Rekreasi (S1)', 'kode' => 'adc77657-6904-4aa3-bc6e-40565bdc27bf'],
                    ['nama' => 'Pendidikan Matematika (S1)', 'kode' => '5f69f4f0-ebbb-4638-8df1-4632b05326de'],
                    ['nama' => 'Pendidikan Teknik Mesin (S1)', 'kode' => '2cadf663-1d4c-4fd4-9457-6fc2b50bd1b3']
                ]
            ],
            [
                'no' => 2,
                'fakultas' => 'Fakultas Ilmu Budaya',
                'prodi' => [
                    ['nama' => 'Sastra Inggris (S1)', 'kode' => '1daad851-b93f-4860-b37d-ddae33f1b860']
                ]
            ],
            [
                'no' => 3,
                'fakultas' => 'Fakultas Ekonomi Bisnis',
                'prodi' => [
                    ['nama' => 'Manajemen (S2)', 'kode' => '1ff84166-cc64-48aa-a38e-3c6a952a8b90'],
                    ['nama' => 'Akuntansi (S1)', 'kode' => '08b181bc-1860-4c7e-8bda-ef4fbd59d869'],
                    ['nama' => 'Manajemen (S1)', 'kode' => '8c56f8a8-8f27-4e2f-8376-a433b2862f36']
                ]
            ],
            [
                'no' => 4,
                'fakultas' => 'Fakultas Ilmu Sosial dan Ilmu Politik',
                'prodi' => [
                    ['nama' => 'Administrasi Publik (S2)', 'kode' => 'd778be91-7bc7-4757-bd22-9038fa8adeb4'],
                    ['nama' => 'Administrasi Publik (S1)', 'kode' => 'e992676b-23e6-49e1-a2f6-81f90876b7da']
                ]
            ],
            [
                'no' => 5,
                'fakultas' => 'Fakultas Ilmu Kesehatan',
                'prodi' => [
                    ['nama' => 'Profesi Ners (Profesi)', 'kode' => '93366fa0-45df-457c-a723-01b78226ad34'],
                    ['nama' => 'Ilmu Keperawatan (S1)', 'kode' => '303e6a30-c87a-4f70-8431-8ccc03b058f4'],
                    ['nama' => 'Kesehatan Masyarakat (S1)', 'kode' => 'b41b8150-b1e6-4c63-9455-26f91174933c']
                ]
            ],
            [
                'no' => 6,
                'fakultas' => 'Fakultas Teknologi Informasi',
                'prodi' => [
                    ['nama' => 'Teknik Informatika (S1)', 'kode' => 'a74fffa1-43f1-4ab5-baca-dfbd08b22d20'],
                    ['nama' => 'Sistem Informasi (S1)', 'kode' => 'aaf15037-cd57-4743-a5f8-fd30840f221e']
                ]
            ],
            [
                'no' => 7,
                'fakultas' => 'Sekolah Tinggi Agama Islam (Dalam Proses Penyatuan)',
                'prodi' => [
                    ['nama' => 'Pendidikan Agama Islam (S1)', 'kode' => '1'],
                    ['nama' => 'Ekonomi Syariah (S1)', 'kode' => '2']
                ]
            ]
        ];

        // Hitung total
        $total = [
            'pendaftar' => AkademikHelpers::getTotalDaftar(),
            'pin' => AkademikHelpers::getJumlahPin(),
            'lulus' => AkademikHelpers::getTotalLulus()
        ];

        // Mapping data dengan nilai sebenarnya
        foreach($data as &$fakultas) {
            foreach($fakultas['prodi'] as &$prodi) {
                $prodi['pendaftar'] = AkademikHelpers::getDaftar($prodi['kode']);
                $prodi['pin_aktif'] = AkademikHelpers::getTotalPin($prodi['kode']);
                $prodi['lulus'] = AkademikHelpers::getLulus($prodi['kode']);
            }
        }

        $tanggal = now()->locale('id')->translatedFormat('j F Y');

        return view('exports.pendaftaran', [
            'data' => $data,
            'total' => $total,
            'tahun' => $this->tahun,
            'tanggal' => $tanggal
        ]);
    }
} 