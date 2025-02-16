<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\PendaftaranExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class ExportController extends Controller
{
    public function export(Request $request)
    {
        $tahun = $request->tahun ?? date('Y');
        $filename = 'Rekap_Pendaftaran_PMB_'.$tahun.'.xlsx';
        
        return Excel::download(new PendaftaranExport($tahun), $filename);
    }
} 