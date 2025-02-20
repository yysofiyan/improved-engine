<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Persyaratan;
use App\Models\Neomahasiswa;
use Illuminate\Http\Request;
use App\Helpers\AkademikHelpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Barryvdh\DomPDF\Facade\Pdf;

class FakultasController extends Controller
{
    public function login()
    {
        return view('auth/login_fakultas');
    }

    public function index()
    {
        $prodi = DB::table('pe3_prodi')
            ->join('pe3_fakultas', 'pe3_prodi.kode_fakultas', '=', 'pe3_fakultas.kode_fakultas')
            ->select('pe3_prodi.*', 'pe3_fakultas.nama_fakultas')
            ->orderBy('pe3_fakultas.urut')
            ->get();
        $prodi_list = DB::table('pe3_prodi')
            ->join('pe3_fakultas', 'pe3_prodi.kode_fakultas', '=', 'pe3_fakultas.kode_fakultas')
            ->select('pe3_prodi.*', 'pe3_fakultas.nama_fakultas')
            ->whereIn('config',session('l_prodi'))
            ->orderBy('pe3_fakultas.urut')
            ->get();

        $fakultas = DB::table('pe3_fakultas')
            ->select('pe3_fakultas.*')
            ->orderBy('pe3_fakultas.urut')
            ->get();
        
            $pendaftarHariIni=Neomahasiswa::where('created_at',Carbon::now())->count();
            $totalPendaftar=Neomahasiswa::count();
            $totalLulus=Neomahasiswa::select(DB::raw('neomahasiswas.id,pin, nama_mahasiswa,is_aktif,handphone,nama_prodi,nama_jenjang,nomor_pendaftaran,nilai'))
            ->join('pe3_prodi','neomahasiswas.kodeprodi_satu','=','pe3_prodi.config')
            ->join('quiz_murid','neomahasiswas.id','=','quiz_murid.murid_id')->count();
            $totalPin=Neomahasiswa::where('is_aktif','1')->count();
            // Menghitung total pendaftar online dan offline untuk tahun 2025
            // Mengambil total pendaftar online dan offline untuk tahun 2025
            $tahunSekarang = date('Y');

            // Update perhitungan dengan filter tahun 2025
            $totalPendaftarOnline = Neomahasiswa::whereYear('created_at', 2025)
                ->whereNull('id_operator')
                ->count();
            
            $totalPendaftarOffline = Neomahasiswa::whereYear('created_at', 2025)
                ->whereNotNull('id_operator')
                ->count();
                
            $tahun = 2024; // atau ambil dari request
            $data2024 = AkademikHelpers::getStatistikPendaftaran2024();
            $data2025 = AkademikHelpers::getStatistikPendaftaran2025();

            return view('fakultas/dashboard', [
            'prodi' => $prodi,
            'prodi_list' => $prodi_list,
            'fakultas' => $fakultas,
            'pendaftarHariIni'=>$pendaftarHariIni,
            'totalPendaftar'=>$totalPendaftar,
            'totalLulus'=>$totalLulus,
            'totalPin'=>$totalPin,
            'totalPendaftarOnline'=>$totalPendaftarOnline,
            'totalPendaftarOffline'=>$totalPendaftarOffline,
            'data2024' => $data2024,
            'data2025' => $data2025,
        ]);
    }

    public function camaba(Request $request)
    {
        if($request->ajax()) {
            if (session('pilihprodi')!='')
            {
                $getData=Neomahasiswa::select(DB::raw('neomahasiswas.id,nim,pin, nama_mahasiswa,neomahasiswas.is_aktif,neomahasiswas.handphone,nama_prodi,nama_jenjang,nomor_pendaftaran,status,id_operator,asal_sekolah'))
                ->join('pe3_prodi','neomahasiswas.kodeprodi_satu','=','pe3_prodi.config')
                ->join('quiz_murid','neomahasiswas.id','=','quiz_murid.murid_id')
                ->where('kodeprodi_satu',session('pilihprodi'))
                ->get();
            }else
            {
                $getData=Neomahasiswa::select(DB::raw('neomahasiswas.id,nim,pin, nama_mahasiswa,neomahasiswas.is_aktif,neomahasiswas.handphone,nama_prodi,nama_jenjang,nomor_pendaftaran,status,id_operator,asal_sekolah'))
                ->join('pe3_prodi','neomahasiswas.kodeprodi_satu','=','pe3_prodi.config')
                ->join('quiz_murid','neomahasiswas.id','=','quiz_murid.murid_id')
                ->whereIn('kodeprodi_satu',session('l_prodi'))
                ->get();
            }
            
                $data=[];
                foreach ($getData as $item)
                {
                    $data[]=[
                        'id'=>$item['id'],
                        'nomor_daftar'=>$item['nomor_pendaftaran'].'/'.$item['pin'],
                        'nomor_pendaftaran'=>$item['nomor_pendaftaran'],
                        'nim'=>$item['nim'],
                        'pin'=>$item['pin'],
                        'nama_mahasiswa'=>$item['nama_mahasiswa'],
                        'is_aktif'=>$item['is_aktif'],
                        'is_lulus'=>$item['status'],
                        'asal_sekolah'=>$item['asal_sekolah'],
                        'handphone'=> $item['handphone'],
                        'operator'=> AkademikHelpers::getUser($item['id_operator']),
                        'nama_prodi'=> $item['nama_prodi'],
                    ];
            }

            return Response()->json([
                'error_code'=>0,
                'error_desc'=>'',
                'data'=>$data,
                'message'=>'fetch data berhasil'
            ], 200);
        }

        return view('fakultas/camaba');
    }

    public function pendaftaran(Request $request)
    {
        if($request->ajax()) {
            if (session('pilihprodi')!='')
            {
                $getData=Neomahasiswa::select(DB::raw('neomahasiswas.id,pin, nama_mahasiswa,neomahasiswas.is_aktif,neomahasiswas.handphone,nama_prodi,nama_jenjang,nomor_pendaftaran'))
                ->join('pe3_prodi','neomahasiswas.kodeprodi_satu','=','pe3_prodi.config')
                ->where('kodeprodi_satu',session('pilihprodi'))
                ->get();
            }else
            {
                $getData=Neomahasiswa::select(DB::raw('neomahasiswas.id,pin, nama_mahasiswa,neomahasiswas.is_aktif,neomahasiswas.handphone,nama_prodi,nama_jenjang,nomor_pendaftaran'))
                ->join('pe3_prodi','neomahasiswas.kodeprodi_satu','=','pe3_prodi.config')
                ->whereIn('kodeprodi_satu',session('l_prodi'))
                ->get();
            }
            
                $data=[];
                foreach ($getData as $item)
                {
                    $data[]=[
                        'id'=>$item['id'],
                        'nomor_daftar'=>$item['nomor_pendaftaran'].'/'.$item['pin'],
                        'nomor_pendaftaran'=>$item['nomor_pendaftaran'],
                        'pin'=>$item['pin'],
                        'nama_mahasiswa'=>$item['nama_mahasiswa'],
                        'is_aktif'=>$item['is_aktif'],
                        'handphone'=> $item['handphone'],
                        'nama_prodi'=> $item['nama_prodi'].' ( '.$item['nama_jenjang'].' ) ',
                    ];
            }

            return Response()->json([
                'error_code'=>0,
                'error_desc'=>'',
                'data'=>$data,
                'message'=>'fetch data berhasil'
            ], 200);
        }

        return view('fakultas/pendaftaran');
    }

    public function rekomendasi(Request $request)
    {
        if($request->ajax()) {
            if (session('pilihprodi')!='')
            {
                $getData=Neomahasiswa::select(DB::raw('neomahasiswas.id,pin, nama_mahasiswa,neomahasiswas.is_aktif,neomahasiswas.handphone,nama_prodi,nama_jenjang,nomor_pendaftaran,status,id_operator,asal_sekolah'))
                ->join('pe3_prodi','neomahasiswas.kodeprodi_satu','=','pe3_prodi.config')
                ->join('quiz_murid','neomahasiswas.id','=','quiz_murid.murid_id')
                ->where('benar','=','75')
                ->where('kodeprodi_satu',session('pilihprodi'))
                ->get();
            }else
            {
                $getData=Neomahasiswa::select(DB::raw('neomahasiswas.id,pin, nama_mahasiswa,neomahasiswas.is_aktif,neomahasiswas.handphone,nama_prodi,nama_jenjang,nomor_pendaftaran,status,id_operator,asal_sekolah'))
                ->join('pe3_prodi','neomahasiswas.kodeprodi_satu','=','pe3_prodi.config')
                ->join('quiz_murid','neomahasiswas.id','=','quiz_murid.murid_id')
                ->where('benar','=','75')
                ->whereIn('kodeprodi_satu',session('l_prodi'))
                ->get();
            }
            
                $data=[];
                foreach ($getData as $item)
                {
                    $data[]=[
                        'id'=>$item['id'],
                        'nomor_daftar'=>$item['nomor_pendaftaran'].'/'.$item['pin'],
                        'nomor_pendaftaran'=>$item['nomor_pendaftaran'],
                        'pin'=>$item['pin'],
                        'nama_mahasiswa'=>$item['nama_mahasiswa'],
                        'asal_sekolah'=>$item['asal_sekolah'],
                        'is_aktif'=>$item['is_aktif'],
                        'is_lulus'=>$item['status'],
                        'handphone'=> $item['handphone'],
                        'operator'=> AkademikHelpers::getUser($item['id_operator']),
                        'nama_prodi'=> $item['nama_prodi'],
                    ];
            }

            return Response()->json([
                'error_code'=>0,
                'error_desc'=>'',
                'data'=>$data,
                'message'=>'fetch data berhasil'
            ], 200);
        }

        return view('fakultas/rekomendasi');
    }

    public function kelulusan(Request $request)
    {
        if($request->ajax()) {
            if (session('pilihprodi')!='')
            {
                $getData=Neomahasiswa::select(DB::raw('neomahasiswas.id,pin, nama_mahasiswa,is_aktif,handphone,nama_prodi,nama_jenjang,nomor_pendaftaran,nilai'))
                ->join('pe3_prodi','neomahasiswas.kodeprodi_satu','=','pe3_prodi.config')
                ->join('quiz_murid','neomahasiswas.id','=','quiz_murid.murid_id')
                ->where('kodeprodi_satu',session('pilihprodi'))
                ->get();
            }else
            {
                $getData=Neomahasiswa::select(DB::raw('neomahasiswas.id,pin, nama_mahasiswa,is_aktif,handphone,nama_prodi,nama_jenjang,nomor_pendaftaran,nilai'))
                ->join('pe3_prodi','neomahasiswas.kodeprodi_satu','=','pe3_prodi.config')
                ->join('quiz_murid','neomahasiswas.id','=','quiz_murid.murid_id')
                ->whereIn('kodeprodi_satu',session('l_prodi'))
                ->get();
            }
            
                $data=[];
                foreach ($getData as $item)
                {
                    $data[]=[
                        'id'=>$item['id'],
                        'nomor_pendaftaran'=>$item['nomor_pendaftaran'],
                        'pin'=>$item['pin'],
                        'nama_mahasiswa'=>$item['nama_mahasiswa'],
                        'is_aktif'=>$item['is_aktif'],
                        'handphone'=> $item['handphone'],
                        'nama_prodi'=> $item['nama_prodi'].' ( '.$item['nama_jenjang'].' ) ',
                        'nilai'=> $item['nilai'],
                        'lulus'=>'Lulus',
                        'daftar'=>$item['nomor_pendaftaran'],
                    ];
            }

            return Response()->json([
                'error_code'=>0,
                'error_desc'=>'',
                'data'=>$data,
                'message'=>'fetch data berhasil'
            ], 200);
        }
        return view('fakultas/kelulusan');
    }

    public function pilihprodi(Request $request)
    {
        $request->session()->put([
            'pilihprodi' => $request->pilihprodi,
        ]);
        return Redirect::back()->with('success', 'Berhasil Memilih Prodi');
    }

    public function resetProdi(Request $request)
    {
        $request->session()->forget([
            'pilihprodi',
        ]);
        return Redirect::back()->with('success', 'Sukses Reset Filter Prodi');
    }

    public function reminder($id)
    {
        try {
            $transaksi= DB::table('transaksis')
            ->where('id','=',$id)
            ->first();
            $mhs=DB::table('neomahasiswas')
            ->where('pin','=',$transaksi->pin)
            ->first();

            
            $message='Hai, Kak *'.$mhs->nama_mahasiswa.'* , Mimin mengingatkan nih untuk segera upload bukti pembayaran dengan Login di alamat : *https://admission.unsap.ac.id/login-maba* %0amenggunakan Nomor Handphone *'.$mhs->handphone.'*  dan Pin *'.$transaksi->pin.'* agar Mimin segera mengaktifkan pin setelah upload bukti bayar dan selangkah lagi kaka menjadi Calon Mahasiswa Baru UNSAP.  %0a%0aTerima Kasih :) ';
           
            AkademikHelpers::kirimWA('62'.$mhs->handphone,$message);
            //$res=AkademikHelpers::kirimWAButton('6285220717928');
            return response()->json(['success'=>'Calon Mahasiswa nama='.$mhs->nama_mahasiswa.' Sukses Dikirim Reminder Pembayaran!']);
        } catch (Exception $e) {
            return response()->json(['status'=>'200','success'=>$e->getMessage()]);
        }
        
    }

    public function lihatMhs(Request $request,$id)
    {
        $cekPIN=Neomahasiswa::select(DB::raw('*'))
        ->where('id',$id);
        $getData=$cekPIN->first();
        try {
            if($getData->is_aktif=='1')
            {
                
                $pendaftar=Neomahasiswa::select(DB::raw('*'))
        ->where('id',$getData->id)
        ->first();

        

        $persyaratan=Persyaratan::select(DB::raw('*'))
        ->where('id_user',$getData->id)
        ->first();
        //dd($persyaratan);

        if (is_null($persyaratan))
        {
            $persyaratan=new Persyaratan();
        }

        $id_channel=DB::table('pe3_channel_pembayaran')
        ->get();

        $agama = json_decode(file_get_contents(storage_path() . "/json/GetAgama.json"), true);
       
        $negara = json_decode(file_get_contents(storage_path() . "/json/GetWilayah.json"), true);

        $collect_negara = collect($negara['data']);
        $filter_negara = $collect_negara->where('id_negara','!=','ID');

        

        $prodi = DB::table('pe3_prodi')
            ->join('pe3_fakultas', 'pe3_prodi.kode_fakultas', '=', 'pe3_fakultas.kode_fakultas')
            ->select('pe3_prodi.*', 'pe3_fakultas.nama_fakultas')
            ->orderBy('pe3_fakultas.urut')
            ->get();

        $sekolah = DB::table('sekolah')
            ->whereIn('bentuk',['SMA','SMK','MA'])
            ->orderBy('sekolah')
            ->get();
        
        $provinsi=DB::table('wilayah')
            ->where('id_negara','=','ID')
            ->where('nama_wilayah','like','Prov.%')
            ->orderBy('nama_wilayah')
            ->get();
        $kota=DB::table('wilayah')
        ->Where(function ($query) {
            $query->where('nama_wilayah', 'like', 'Kota%')
                  ->orwhere('nama_wilayah', 'like', 'Kab.%');
        })
        ->orderBy('nama_wilayah')
        ->get();

        $kecamatan=DB::table('wilayah')
        ->where('nama_wilayah', 'like', 'Kec.%')
        ->orderBy('nama_wilayah')
        ->get();

        $transaksi= DB::table('transaksis')
        ->where('pin','=',$getData->pin)
        ->first();

        $konfirmasi=DB::table('konfirmasi_pembayarans')
        ->where('transaksi_id','=',$transaksi->id)
        ->first();

        
        return view('fakultas/form-maba',[
            'mhs' => $pendaftar,
            'pendaftar'=>$pendaftar,
            'id_channel'=>$id_channel,
            'agama'=>$agama,
            'prodi'=>$prodi,
            'prodi_dua'=>$prodi,
            'negara'=>$filter_negara,
            'provinsi'=>$provinsi,
            'kota'=>$kota,
            'kecamatan'=>$kecamatan,
            'sekolah'=>$sekolah,
            'konfirmasi'=>$konfirmasi,
            'persyaratan'=>$persyaratan,

        ]);

                

            }else
            {
                return redirect('/fakultas/pendaftaran')->with('error', 'PIN Belum Aktif');
            }

        } catch (\Throwable $th) {
            return redirect('/fakultas/pendaftaran')->with('error', 'Terdapat Kesalahan');
        }

    }

    public function lihatPdf($nomor_pendaftaran)
    {
        try {
            // 1. Ambil data mahasiswa beserta relasi prodi
            $mahasiswa = Neomahasiswa::with(['prodiRelation'])
                ->where('nomor_pendaftaran', $nomor_pendaftaran)
                ->firstOrFail();

            // 2. Validasi akses fakultas berdasarkan prodi yang diizinkan
            $allowedProdi = session('l_prodi') ?? [];
            if(!in_array($mahasiswa->kodeprodi_satu, $allowedProdi)) {
                throw new Exception('Anda tidak memiliki akses untuk melihat data prodi ini');
            }

            // 3. Pastikan data prodi tersedia
            if(!$mahasiswa->prodiRelation) {
                throw new Exception('Data program studi tidak ditemukan');
            }

            // 4. Tentukan template PDF berdasarkan jenjang studi
            $viewName = ($mahasiswa->prodiRelation->nama_jenjang == 'S-2') 
                ? 'report.surat_s2' 
                : 'report.surat_s1';

            // 5. Siapkan path untuk gambar header dan tanda tangan
            $headerPath = public_path('images/header_pmb2025.png');
            $ttdPath = public_path('images/tanda_tangan2025.png');

            // 6. Generate dan tampilkan PDF
            $pdf = PDF::loadView($viewName, [
                'formulir' => $mahasiswa,
                'prodi' => $mahasiswa->prodiRelation,
                'tanggal' => now()->format('d F Y'),
                'header_img' => $headerPath,
                'ttd_img' => $ttdPath
            ]);

            return $pdf->stream("Surat_Kelulusan_{$nomor_pendaftaran}.pdf");

        } catch (\Exception $e) {
            // Tangani error dan redirect dengan pesan error
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        } catch (\Throwable $th) {
            // Tangani error yang tidak terduga dan redirect dengan pesan error
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }
}