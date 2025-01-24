<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SecondDatabaseController extends Controller
{
    public function index()
    {
        $tanggal=Carbon::parse(Carbon::now()->subYear())->format('Y-m-d');
        $data=DB::connection('mysql2')->table('pe3_formulir_pendaftaran')->select(DB::raw('nama_prodi,nama_jenjang, nama_mhs,ket_lulus'))
        ->join('pe3_prodi', 'pe3_prodi.id', '=', 'pe3_formulir_pendaftaran.prodi_id')
        ->join('pe3_nilai_ujian_pmb', 'pe3_nilai_ujian_pmb.user_id', '=', 'pe3_formulir_pendaftaran.user_id')
        ->whereDate('pe3_formulir_pendaftaran.created_at','<=',$tanggal)
        ->get();
        return Response()->json([
            'error_code'=>0,
            'error_desc'=>'',
            'data'=>$data,
            'message'=>'fetch data berhasil'
        ], 200);
    }
}
