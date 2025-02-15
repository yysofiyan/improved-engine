<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use App\Models\Formulir;
use App\Models\Neomahasiswa;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Base PDF Generator
     */
    private function generateBasePDF($jenjang, $config = [])
    {
        try {
            $formulir = Formulir::where('jenjang', $jenjang)
                ->firstOrFail();
                
            $prodi = DB::table('pe3_prodi')
                ->where('config', $formulir->kodeprodi_satu)
                ->firstOrFail();

            $imageConfig = [
                'S1' => [
                    'header' => env('S1_HEADER_IMAGE', 'header_pmb2025.png'),
                    'ttd' => env('S1_TTD_IMAGE', 'tanda_tangan2025.png')
                ],
                'S2' => [
                    'header' => env('S2_HEADER_IMAGE', 'header_s2.png'),
                    'ttd' => env('S2_TTD_IMAGE', 'ttd_s2.png')
                ]
            ];

            $data = [
                'formulir' => $formulir,
                'prodi' => $prodi,
                'headers' => [
                    'HEADER_LOGO' => public_path("images/{$imageConfig[$jenjang]['header']}"),
                    'TANDATANGAN' => public_path("images/{$imageConfig[$jenjang]['ttd']}")
                ],
                'tanggal' => now()->format('d F Y')
            ];

            return PDF::loadView("report.surat_$jenjang", $data)
                ->setOptions([
                    'enable-php' => true,
                    'images' => true,
                    'image-quality' => $jenjang === 'S1' ? 60 : 50,
                    'margin-bottom' => $config['margin'] ?? 15,
                    'paper-size' => 'A4',
                    'orientation' => 'portrait'
                ]);

        } catch (ModelNotFoundException $e) {
            abort(404, 'Data formulir tidak ditemukan');
        } catch (\Exception $e) {
            abort(500, 'Terjadi kesalahan server: '.$e->getMessage());
        }
    }

    public function generateS1PDF()
    {
        return $this->generateBasePDF('S1', [
            'image_quality' => 60,
            'margin' => 15
        ])->stream('surat_s1.pdf');
    }

    public function generateS2PDF()
    {
        return $this->generateBasePDF('S2', [
            'image_quality' => 50,
            'margin' => 20
        ])->stream('surat_s2.pdf');
    }
} 