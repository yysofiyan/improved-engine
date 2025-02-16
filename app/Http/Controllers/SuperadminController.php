<?php

namespace App\Http\Controllers;

use DateTime;
use Exception;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\Transaksi;
use App\Models\Persyaratan;
use App\Models\Neomahasiswa;
use Illuminate\Http\Request;
use App\Helpers\AkademikHelpers;
use Illuminate\Support\Facades\DB;
use Spatie\PdfToImage\Pdf as Imgt;
use App\Models\KonfirmasiPembayaran;
use Barryvdh\DomPDF\Facade\Pdf as Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;

class SuperadminController extends Controller
{
    public function index()
    {
        $prodi = DB::table('pe3_prodi')
            ->join('pe3_fakultas', 'pe3_prodi.kode_fakultas', '=', 'pe3_fakultas.kode_fakultas')
            ->select('pe3_prodi.*', 'pe3_fakultas.nama_fakultas')
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
            $totalPendaftarOnline=Neomahasiswa::WhereNull('id_operator')->count();
            $totalPendaftarOffline=Neomahasiswa::WhereNotNull('id_operator')->count();
        return view('admin/dashboard',[ 
            'prodi' => $prodi,
            'fakultas' => $fakultas,
            'pendaftarHariIni'=>$pendaftarHariIni,
            'totalPendaftar'=>$totalPendaftar,
            'totalLulus'=>$totalLulus,
            'totalPin'=>$totalPin,
            'totalPendaftarOnline'=>$totalPendaftarOnline,
            'totalPendaftarOffline'=>$totalPendaftarOffline,
            'tanggal'=>AkademikHelpers::tanggal('d F Y'),
            'tanggal1'=>AkademikHelpers::tanggal1('d F Y'),
            'tanggal2'=>AkademikHelpers::tanggal2('d F Y')
        ]);
    }

    public function camaba(Request $request)
    {
        if($request->ajax()) {
            $getData=Neomahasiswa::select(DB::raw('neomahasiswas.id,pin, nama_mahasiswa,neomahasiswas.is_aktif,neomahasiswas.handphone,nama_prodi,nama_jenjang,nomor_pendaftaran,status,id_operator,asal_sekolah,neomahasiswas.created_at'))
                ->join('pe3_prodi','neomahasiswas.kodeprodi_satu','=','pe3_prodi.config')
                ->join('quiz_murid','neomahasiswas.id','=','quiz_murid.murid_id')
                //->where('neomahasiswas.tahun_masuk','2025')
                ->orderby('neomahasiswas.created_at','asc')
                ->get();
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
                        'sma'=>$item['asal_sekolah'],
                        'handphone'=> $item['handphone'],
                        'operator'=> AkademikHelpers::getUser($item['id_operator']),
                        'nama_prodi'=> $item['nama_prodi'].' ( '.$item['nama_jenjang'].' ) ',
                        'tanggal_daftar'=> $item['created_at'],
                    ];
            }

            return Response()->json([
                'error_code'=>0,
                'error_desc'=>'',
                'data'=>$data,
                'message'=>'fetch data berhasil'
            ], 200);
        }

        return view('admin/camaba');
    }

    public function lihatPin(Request $request,$id)
    {
        $cekPIN=Neomahasiswa::select(DB::raw('*'))
        ->where('pin',$id);

        try {
            if($cekPIN->count()=='1')
            {
                $getData=$cekPIN->first();
                $request->session()->put([
                    'id' => $getData->id,
                    'pin' => $getData->pin,
                    'nomor_pendaftaran' => $getData->nomor_pendaftaran,
                    'nik' => $getData->nik,
                    'nama_mahasiswa' => $getData->nama_mahasiswa,
                    'handphone' => $getData->handphone,
                    'is_aktif' => $getData->is_aktif,
                ]);

                return redirect('/admin/form-maba')->with('success', 'PIN Sesuai ..');

            }else
            {
                return redirect('/admin/camaba')->with('error', 'PIN Tidak Dikenali');
            }

        } catch (\Throwable $th) {
            return redirect('/admin/camaba')->with('error', 'Terdapat Kesalahan');
        }
        

    }

    public function lihatpdf($file)
    {
        try {
            // Cari berdasarkan nomor pendaftaran dengan relasi prodi
            $formulir = Neomahasiswa::with(['prodiRelation'])
                ->where('nomor_pendaftaran', $file)
                ->firstOrFail();

            // Validasi data prodi
            if(!$formulir->kodeprodi_satu || !$formulir->prodiRelation) {
                throw new Exception('Data prodi tidak valid atau belum terdaftar');
            }

            // Siapkan header
            $headers = [
                'HEADER_LOGO' => AkademikHelpers::public_path("images/header_pmb2025.png"),
                'TANDATANGAN' => AkademikHelpers::public_path("images/tanda_tangan2025.png")
            ];

            $viewName = ($formulir->prodiRelation->nama_jenjang == 'S-2') 
                ? 'report.surat_s2' 
                : 'report.surat_s1';

            $pdf = Pdf::loadView($viewName, [
                'headers' => $headers,
                'formulir' => $formulir,
                'prodi' => $formulir->prodiRelation,
                'tanggal' => AkademikHelpers::tanggal('d F Y')
            ]);

            return $pdf->stream("Surat_Kelulusan_{$file}.pdf");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal generate PDF: '.$e->getMessage());
        }
    }

    public function formmaba()
    {
        $pendaftar=Neomahasiswa::select(DB::raw('*'))
        ->where('id',session('id'))
        ->first();

        $soal=DB::table('quiz_murid')
        ->where('murid_id',session('id'))
        ->count();

        $persyaratan=Persyaratan::select(DB::raw('*'))
        ->where('id_user',session('id'))
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
        ->where('pin','=',session('pin'))
        ->first();

        $konfirmasi=DB::table('konfirmasi_pembayarans')
        ->where('transaksi_id','=',$transaksi->id)
        ->first();

        
        return view('admin/form-maba',[
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
            'soal'=>$soal

        ]);
    }

    public function updateMhs(Request $request)
    {

        $request->validate([
            'nisn' => 'required|min:10|max:10',
            'nik' => 'required|min:16|max:16',
            'nama_mahasiswa' => 'required',
            'nama_ibu_kandung' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'handphone' => 'required',
            'agama' => 'required',
            'jenis_daftar' => 'required',
            'alamat_rumah' => 'required',
            'kelurahan' => 'required',
            'asal_sekolah' => 'required',
            'tahun_lulus' => 'required',
            'kode_pt_asal' => '',
            'kode_prodi_asal' => '',
            'catatan' => '',
            'kodeprodi_satu' => 'required',
            'kodeprodi_dua' => 'required',
            'jenis_daftar' => 'required',
            'kodewilayah'=>'',
            'kewarganegaraan'=>'required',
            'konfirmasi' => 'required',
        ]);



        try {
            Neomahasiswa::updateOrCreate([
                'id'=>$request->id,
            ],[
                'id_operator'=>auth()->user()->id,
                'nisn' => $request->nisn,
                'nik' => $request->nik,
                'nama_mahasiswa' => $request->nama_mahasiswa,
                'tempat_lahir'=>$request->tempat_lahir,
                'tanggal_lahir'=>$request->tanggal_lahir,
                'jenis_kelamin'=>$request->jenis_kelamin,
                'handphone'=>$request->handphone,
                'agama'=>$request->agama,
                'kelurahan'=>$request->kelurahan,
                'kodewilayah'=>$request->kodewilayah,
                'alamat_rumah'=>$request->alamat_rumah,
                'nama_ibu_kandung'=>$request->nama_ibu_kandung,
                'kodeprodi_satu'=>$request->kodeprodi_satu,
                'kodeprodi_dua'=>$request->kodeprodi_dua,
                'jenis_daftar'=>$request->jenis_daftar,
                'asal_sekolah'=>$request->asal_sekolah,
                'tahun_lulus'=>$request->tahun_lulus,
                'kode_pt_asal'=>$request->kode_pt_asal,
                'kode_prodi_asal'=>$request->kode_prodi_asal,
                'negara'=>$request->negara,
                'provinsi'=>$request->provinsi,
                'kota'=>$request->kota,
                'kecamatan'=>$request->kecamatan,
                'tahun_masuk'=>'2025',
                'kewarganegaraan'=>$request->kewarganegaraan,
                'konfirmasi'=>$request->konfirmasi,
                'catatan'=>$request->catatan,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ]);


            return response()->json(['status'=>'200','success'=>'Data Sukses di Simpan']);
        } catch (Exception $e) {
            return response()->json(['status'=>'200','success'=>$e->getMessage()]);
        }







    }

    public function uploadSyarat(Request $request)
    {
        $pendaftar=Neomahasiswa::select(DB::raw('*'))
        ->where('pin',session('pin'))
        ->first();

        if ($pendaftar->jenis_daftar=='1')
        {
            if (AkademikHelpers::getFakultas($pendaftar->kodeprodi_satu)=='13')
            {
                
            }else
            {
               
            }
            
        }else if ($pendaftar->jenis_daftar=='2') {
            

           
        }else
        {
            

            
        }

        try {

            if ($request->id_persyaratan=='')
            { 
                $id_persyaratan=Uuid::uuid4()->toString();
            }else{
                $id_persyaratan=$request->id_persyaratan;
            }

            

            if ($pendaftar->jenis_daftar=='1')
            {
                
    
                
                if (AkademikHelpers::getFakultas($pendaftar->kodeprodi_satu)=='13')
            {
                
                $exc=Persyaratan::updateOrCreate(
                    ['id'=>$id_persyaratan],
                    [
                    'id_user'=>session('id'),
                    'ijasah'=>'no_image.png',
                    'is_ijasah'=>$request->is_ijasah,
                    'id_operator'=>auth()->user()->id,
                    'ktp_kk'=>'no_image.png',
                    'is_ktp'=>$request->is_ktp,
                    'foto'=>'no_image.png',
                    'is_foto'=>$request->is_foto,
                    'ket_sehat'=>'no_image.png',
                    'is_ket_sehat'=>$request->is_ket_sehat,
                    'khs'=>'',
                    'is_khs'=>'0',
                    'ktm'=>'',
                    'is_ktm'=>'0',
                    'surat_pindah'=>'',
                    'is_surat_pindah'=>'0',
                    'screen_pddikti'=>'',
                    'is_screen_pddikti'=>'0',
                    'ijasah_lanjutan'=>'',
                    'is_ijasah_lanjutan'=>'0',
                    'transkrip_nilai'=>'',
                    'is_transkrip_nilai'=>'0',
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ]);
            }else
            {
                $exc=Persyaratan::updateOrCreate(
                    ['id'=>$id_persyaratan],
                    [
                    'id_user'=>session('id'),
                    'ijasah'=>'no_image.png',
                    'is_ijasah'=>$request->is_ijasah,
                    'id_operator'=>auth()->user()->id,
                    'ktp_kk'=>'no_image.png',
                    'is_ktp'=>$request->is_ktp,
                    'foto'=>'no_image.png',
                    'is_foto'=>$request->is_foto,
                    'ket_sehat'=>'',
                    'is_ket_sehat'=>'0',
                    'khs'=>'',
                    'is_khs'=>'0',
                    'ktm'=>'',
                    'is_ktm'=>'0',
                    'surat_pindah'=>'',
                    'is_surat_pindah'=>'0',
                    'screen_pddikti'=>'',
                    'is_screen_pddikti'=>'0',
                    'ijasah_lanjutan'=>'',
                    'is_ijasah_lanjutan'=>'0',
                    'transkrip_nilai'=>'',
                    'is_transkrip_nilai'=>'0',
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ]);
            }

            

            }else if ($pendaftar->jenis_daftar=='2') 
            {
                

                $exc=Persyaratan::updateOrCreate(
                    ['id'=>$id_persyaratan],
                    [
                    'id_user'=>session('id'),
                    'ijasah'=>'no_image.png',
                    'is_ijasah'=>$request->is_ijasah,
                    'id_operator'=>auth()->user()->id,
                    'ktp_kk'=>'no_image.png',
                    'is_ktp'=>$request->is_ktp,
                    'foto'=>'no_image.png',
                    'is_foto'=>$request->is_foto,
                    'ket_sehat'=>'',
                    'is_ket_sehat'=>'1',
                    'khs'=>'no_image.png',
                    'is_khs'=>$request->is_khs,
                    'ktm'=>'no_image.png',
                    'is_ktm'=>$request->is_ktm,
                    'surat_pindah'=>'no_image.png',
                    'is_surat_pindah'=>$request->is_surat_pindah,
                    'screen_pddikti'=>'',
                    'is_screen_pddikti'=>'0',
                    'ijasah_lanjutan'=>'',
                    'is_ijasah_lanjutan'=>'0',
                    'transkrip_nilai'=>'',
                    'is_transkrip_nilai'=>'0',
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ]);
    
            }else
            {
               

                $exc=Persyaratan::updateOrCreate(
                    ['id'=>$id_persyaratan],
                    [
                    'id_user'=>session('id'),
                    'ijasah'=>'no_image.png',
                    'is_ijasah'=>$request->is_ijasah,
                    'id_operator'=>auth()->user()->id,
                    'ktp_kk'=>'no_image.png',
                    'is_ktp'=>$request->is_ktp,
                    'foto'=>'no_image.png',
                    'is_foto'=>$request->is_foto,
                    'ket_sehat'=>'',
                    'is_ket_sehat'=>'0',
                    'khs'=>'',
                    'is_khs'=>'0',
                    'ktm'=>'',
                    'is_ktm'=>'0',
                    'surat_pindah'=>'',
                    'is_surat_pindah'=>'0',
                    'screen_pddikti'=>'no_image.png',
                    'is_screen_pddikti'=>$request->is_screen_pddikti,
                    'ijasah_lanjutan'=>'no_image.png',
                    'is_ijasah_lanjutan'=>$request->is_ijasah_lanjutan,
                    'transkrip_nilai'=>'no_image.png',
                    'is_transkrip_nilai'=>$request->is_transkrip_nilai,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ]);

                
            }

       
        return response()->json(['status'=>'200','success'=> 'Upload Persyaratan Berhasil ...']);
        } catch (Exception $e) {
            return response()->json(['status'=>'200','success'=>$e->getMessage()]);
        }
    
    
        
    }

    public function transaksi(Request $request)
    {
        if($request->ajax()) {
            $getData=Transaksi::select(DB::raw('transaksis.id,transaksis.pin, no_transaksi,nama_mahasiswa,transaksis.status,handphone,nama_prodi,nama_jenjang,total'))
        ->join('neomahasiswas','neomahasiswas.pin','=','transaksis.pin')
        ->join('pe3_prodi','neomahasiswas.kodeprodi_satu','=','pe3_prodi.config')->get();
                $data=[];
                foreach ($getData as $item)
                {
                    $data[]=[
                        'id'=>$item['id'],
                        'pin'=>$item['pin'],
                        'no_transaksi'=>$item['no_transaksi'],
                        'nama_mahasiswa'=>$item['nama_mahasiswa'],
                        'status'=>$item['status'],
                        'total'=>'Rp. '.number_format($item['total'],0),
                        'handphone'=> $item['handphone'],
                        'nama_prodi'=> $item['nama_prodi'].' - '.$item['nama_jenjang'],
                    ];
            }

            return Response()->json([
                'error_code'=>0,
                'error_desc'=>'',
                'data'=>$data,
                'message'=>'fetch data berhasil'
            ], 200);
            
        }
        
        return view('admin/transaksi');
    }
    public function konfirmasi(Request $request)
    {
        if($request->ajax()) {
            $getData=KonfirmasiPembayaran::select(DB::raw('konfirmasi_pembayarans.transaksi_id,transaksis.pin,nomor_pendaftaran,nama_mahasiswa,id_channel,total_bayar,bukti_bayar,nomor_pendaftaran,verified,tanggal_bayar,nama_rekening_pengirim'))
        ->join('transaksis','transaksis.id','=','konfirmasi_pembayarans.transaksi_id')
        ->join('neomahasiswas','neomahasiswas.pin','=','transaksis.pin')
        ->get();
                $data=[];
                foreach ($getData as $item)
                {
                    if (!isset($item['id_channel']))
                    {
                        $channelid='0';
                    }else
                    {
                        $channelid=$item['id_channel'];
                    }
                    $data[]=[
                        'id'=>$item['transaksi_id'],
                        'pin'=>$item['pin'],
                        'nomor_pendaftaran'=>$item['nomor_pendaftaran'],
                        'nama_mahasiswa'=>$item['nama_mahasiswa'],
                        'id_channel'=>$channelid,
                        'tanggal_bayar'=>$item['tanggal_bayar'],
                        'nama_rekening_pengirim'=>$item['nama_rekening_pengirim'],
                        'total_bayar'=>'Rp. '.number_format($item['total_bayar'],0),
                        'lunas'=> $item['verified'],
                        'bukti_bayar'=> $item['bukti_bayar'],
                    ];
            }

            return Response()->json([
                'error_code'=>0,
                'error_desc'=>'',
                'data'=>$data,
                'message'=>'fetch data berhasil'
            ], 200);
            
        }
        
        
        return view('admin/konfirmasi');
    }

    public function buatnim(Request $request)
    {
        if($request->ajax()) {
            $getData=Neomahasiswa::select(DB::raw('neomahasiswas.id,pin,nim, nomor_pendaftaran,nama_mahasiswa,is_aktif,handphone,nama_prodi,nama_jenjang'))
                ->join('pe3_prodi','neomahasiswas.kodeprodi_satu','=','pe3_prodi.config')
                ->where('nim','<>','')
                ->get();
                $data=[];
                foreach ($getData as $item)
                {
                    $data[]=[
                        'id'=>$item['id'],
                        'pin'=>$item['pin'],
                        'nim'=>$item['nim'],
                        'nama_mahasiswa'=>$item['nama_mahasiswa'],
                        'nomor_pendaftaran'=>$item['nomor_pendaftaran'],
                        'is_aktif'=>$item['is_aktif'],
                        'handphone'=> $item['handphone'],
                        'nama_prodi'=> $item['nama_prodi'].' - '.$item['nama_jenjang'],
                    ];
            }

            return Response()->json([
                'error_code'=>0,
                'error_desc'=>'',
                'data'=>$data,
                'message'=>'fetch data berhasil'
            ], 200);
        }
        return view('admin/listnim');
    }

    public function online(Request $request)
    {
       
        if($request->ajax()) {
            if (session('tanggal1') !='')
            {
                $getData=KonfirmasiPembayaran::select(DB::raw('konfirmasi_pembayarans.transaksi_id,transaksis.pin,nomor_pendaftaran,nama_mahasiswa,id_channel,total_bayar,bukti_bayar,nomor_pendaftaran,verified,tanggal_bayar,nama_rekening_pengirim,id_operator'))
        ->join('transaksis','transaksis.id','=','konfirmasi_pembayarans.transaksi_id')
        ->join('neomahasiswas','neomahasiswas.pin','=','transaksis.pin')
        ->whereNull('id_operator')
        ->whereBetween('konfirmasi_pembayarans.tanggal_bayar', [session('tanggal1'), session('tanggal2')])
        ->where('status','=','11')
        ->orderby('tanggal_bayar')
        ->get();
            }else
            {
                $getData=KonfirmasiPembayaran::select(DB::raw('konfirmasi_pembayarans.transaksi_id,transaksis.pin,nomor_pendaftaran,nama_mahasiswa,id_channel,total_bayar,bukti_bayar,nomor_pendaftaran,verified,tanggal_bayar,nama_rekening_pengirim,id_operator'))
        ->join('transaksis','transaksis.id','=','konfirmasi_pembayarans.transaksi_id')
        ->join('neomahasiswas','neomahasiswas.pin','=','transaksis.pin')
        ->whereNull('id_operator')
        ->where('status','=','11')
        ->orderby('tanggal_bayar')
        ->get();
            }
            
                $data=[];
                foreach ($getData as $item)
                {
                    if (!isset($item['id_channel']))
                    {
                        $channelid='0';
                    }else
                    {
                        $channelid=$item['id_channel'];
                    }
                    $data[]=[
                        'id'=>$item['transaksi_id'],
                        'pin'=>$item['pin'],
                        'nomor_pendaftaran'=>$item['nomor_pendaftaran'],
                        'nama_mahasiswa'=>$item['nama_mahasiswa'],
                        'id_channel'=>$channelid,
                        'tanggal_bayar'=>$item['tanggal_bayar'],
                        'nama_rekening_pengirim'=>$item['nama_rekening_pengirim'],
                        'total_bayar'=>'Rp. '.number_format($item['total_bayar'],0),
                        'lunas'=> $item['verified'],
                        'bukti_bayar'=> $item['bukti_bayar'],
                    ];
            }

            return Response()->json([
                'error_code'=>0,
                'error_desc'=>'',
                'data'=>$data,
                'message'=>'fetch data berhasil'
            ], 200);
            
        }
        return view('admin/online');
    }

    public function pos_online(Request $request)
    {
        $request->session()->put([
            'tanggal1' => $request->periode1,
            'tanggal2' => $request->periode2,
        ]);
        return Redirect::back()->with('success', 'Berhasil Menampilkan Data');
    }

    public function resetOnline(Request $request)
    {
        $request->session()->forget([
            'tanggal1',
            'tanggal2',
        ]);
        return Redirect::back()->with('success', 'Sukses Reset Filter');
    }

    public function pos_offline(Request $request)
    {
        $request->session()->put([
            'tanggal1' => $request->periode1,
            'tanggal2' => $request->periode2,
        ]);
        return Redirect::back()->with('success', 'Berhasil Menampilkan Data');
    }

    public function resetOffline(Request $request)
    {
        $request->session()->forget([
            'tanggal1',
            'tanggal2',
        ]);
        return Redirect::back()->with('success', 'Sukses Reset Filter');
    }

    public function offline(Request $request)
    {
        
        if($request->ajax()) {
            if (session('tanggal1')!='')
            {
                $getData=KonfirmasiPembayaran::select(DB::raw('konfirmasi_pembayarans.transaksi_id,transaksis.pin,nomor_pendaftaran,nama_mahasiswa,id_channel,total_bayar,bukti_bayar,nomor_pendaftaran,verified,tanggal_bayar,nama_rekening_pengirim,id_operator'))
        ->join('transaksis','transaksis.id','=','konfirmasi_pembayarans.transaksi_id')
        ->join('neomahasiswas','neomahasiswas.pin','=','transaksis.pin')
        ->whereNotNull('id_operator')
        ->whereBetween('konfirmasi_pembayarans.tanggal_bayar', [session('tanggal1'), session('tanggal2')])
        ->where('status','=','11')
        ->orderby('tanggal_bayar')
        ->get();
            }else
            {
                $getData=KonfirmasiPembayaran::select(DB::raw('konfirmasi_pembayarans.transaksi_id,transaksis.pin,nomor_pendaftaran,nama_mahasiswa,id_channel,total_bayar,bukti_bayar,nomor_pendaftaran,verified,tanggal_bayar,nama_rekening_pengirim,id_operator'))
        ->join('transaksis','transaksis.id','=','konfirmasi_pembayarans.transaksi_id')
        ->join('neomahasiswas','neomahasiswas.pin','=','transaksis.pin')
        ->whereNotNull('id_operator')
        ->where('status','=','11')
        ->orderby('tanggal_bayar')
        ->get();
            }
            
                $data=[];
                foreach ($getData as $item)
                {
                    if (!isset($item['id_channel']))
                    {
                        $channelid='0';
                    }else
                    {
                        $channelid=$item['id_channel'];
                    }
                    $data[]=[
                        'id'=>$item['transaksi_id'],
                        'pin'=>$item['pin'],
                        'nomor_pendaftaran'=>$item['nomor_pendaftaran'],
                        'nama_mahasiswa'=>$item['nama_mahasiswa'],
                        'id_channel'=>$channelid,
                        'tanggal_bayar'=>$item['tanggal_bayar'],
                        'nama_rekening_pengirim'=>$item['nama_rekening_pengirim'],
                        'total_bayar'=>'Rp. '.number_format($item['total_bayar'],0),
                        'lunas'=> $item['verified'],
                        'bukti_bayar'=> $item['bukti_bayar'],
                    ];
            }

            return Response()->json([
                'error_code'=>0,
                'error_desc'=>'',
                'data'=>$data,
                'message'=>'fetch data berhasil'
            ], 200);
            
        }
        return view('admin/offline');
    }

    public static function autonim($barang,$primary,$prefix,$prodi_id){
        $q=DB::table($barang)->select(DB::raw('MAX(RIGHT('.$primary.',3)) as kd_max'))->where('kodeprodi_satu','=',$prodi_id);
        $prx=$prefix;
        if($q->count()>0)
        {
            foreach($q->get() as $k)
            {
                $tmp = ((int)$k->kd_max)+1;
                $kd = $prx.sprintf("%03s", $tmp);
            }
        }
        else
        {
            $kd = $prx."001";
        }

        return $kd;
    }



    public function simpannim(Request $request)
    {
        $request->validate([
            'pin' => 'required',
        ]);

        try {
            
            $mahasiswa = DB::table('neomahasiswas')
            ->where('pin','=',$request->pin)
            ->first();
            $tahun='2025';
            $idkelas='R';
            $prodi_id=$mahasiswa->kodeprodi_satu;
            $idsmt='1';
            $prodi = DB::table('pe3_prodi')
            ->where('config','=',$mahasiswa->kodeprodi_satu)
            ->first();
            $fakultas = DB::table('pe3_fakultas')
            ->where('kode_fakultas','=',$prodi->kode_fakultas)
            ->first();

            $th=substr($tahun,-2);
            $kode_fakultas=$fakultas->nim_kode;
            $kode_urut_prodi=$prodi->nim_kode;
            $kode_jenjang=$prodi->nim_jenjang;
            $kode_mhs='';
            if ($idkelas=='R')
            {
                $kode_mhs='1';
            }else if ($idkelas=='P')
            {
                $kode_mhs='2';
            }else if ($idkelas=='L')
            {
                $kode_mhs='3';
            }
            $kode_status_mhs=$kode_mhs;

            $table="neomahasiswas";
			$primary="nim";
			$prefix=$th.''.$kode_fakultas.''.$kode_urut_prodi.''.$kode_jenjang.''.$kode_status_mhs;
            
            $nimMHS=$this->autonim($table,$primary,$prefix,$prodi_id);
        
            Neomahasiswa::updateOrCreate(
                [
                    'id' => $mahasiswa->id,
                ],
                [
                    'nim' => $nimMHS,
                ]
            );



            return response()->json(['status'=>'200','success'=>'Data Sukses di Simpan NIM Mahasiswa : '.$nimMHS]);
        } catch (Exception $e) {
            return response()->json(['status'=>'200','success'=>$e->getMessage()]);
        }
    }

    public function verifikasi($id)
    {
        try {
            $transaksi= DB::table('transaksis')
            ->where('id','=',$id)
            ->first();
            $mhs=DB::table('neomahasiswas')
            ->where('pin','=',$transaksi->pin)
            ->first();

            Neomahasiswa::updateOrCreate(
                [ 'pin'=>$transaksi->pin],
                [
                    'is_aktif'=>'1',
                ]
                );

            Transaksi::updateOrCreate(
                [
                    'id' => $id,
                ],
                [
                    'status' => '11',
                ]
            );
    
            KonfirmasiPembayaran::updateOrCreate(
                [
                    'transaksi_id' => $id,
                ],
                [
                    'verified' => '11',
                ]
            );
            
            $message='Hai, Kak *'.$mhs->nama_mahasiswa.'* , *Selamat PIN Pendaftaran kamu sudah aktif*. %0a%0a%0aLangkah selanjutnya Silahkan Login di alamat : *https://admission.unsap.ac.id/login-maba* %0adengan menggunakan Nomor Handphone dan Pin (*'.$transaksi->pin.'*) lalu isi formulir biodata, upload persyaratan dan Ujian Seleksi Mahasiswa Baru. %0a%0aTerima Kasih :) ';
           
            AkademikHelpers::kirimWA('62'.$mhs->handphone,$message);
            //$res=AkademikHelpers::kirimWAButton('6285220717928');
            return response()->json(['success'=>'Data Konfirmasi dengan pin='.$transaksi->pin.' Sukses Dikonfirmasi !']);
        } catch (Exception $e) {
            return response()->json(['status'=>'200','success'=>$e->getMessage()]);
        }
        
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


    public function migrasi(Request $request)
    {
        return view('admin/migrasi');
    }

    public function postMigrasi(Request $request)
    {
        $request->validate([
            'pin' => 'required',
        ]);

        try {
            $mhs=DB::table('neomahasiswas')
            ->where('pin','=',$request->pin)
            ->first();
        $transaksi= DB::table('transaksis')
            ->where('pin','=',$request->pin)
            ->first();
            $now = \Carbon\Carbon::now()->toDateTimeString(); 
        Transaksi::updateOrCreate([
            'id'=>$transaksi->id, 
        ],[
            'status'=>'11',
            'total'=>'350000',
            'nomor_rekening'=>'102',
            'tanggal'=>$now,
            'desc'=>'Pendaftaran Online Menjadi Offline',
            'created_at'=>$now,
        ]);

        KonfirmasiPembayaran::updateOrCreate([
            'transaksi_id'=>$transaksi->id, 
        ],[
            'total_bayar'=>'350000', 
            'tanggal'=>$now,
            'verified'=>'11',
            'desc'=>'Pendaftaran Online Ke Offline',
            'created_at'=>$now,
        ]);
        return Redirect::back()->with('success', 'Sukses Migrasi Online ke Offline');
        } catch (\Throwable $th) {
            return Redirect::back()->with('error', 'Terdapat Kesalahan');
        }
        
    }

    public function printpdf()
	{
       
        $tanggal1=session('tanggal1');
        $tanggal2=session('tanggal2');
$getData=KonfirmasiPembayaran::select(DB::raw('konfirmasi_pembayarans.transaksi_id,transaksis.pin,nomor_pendaftaran,nama_mahasiswa,id_channel,total_bayar,bukti_bayar,nomor_pendaftaran,verified,tanggal_bayar,nama_rekening_pengirim,id_operator'))
        ->join('transaksis','transaksis.id','=','konfirmasi_pembayarans.transaksi_id')
        ->join('neomahasiswas','neomahasiswas.pin','=','transaksis.pin')
        ->whereNull('id_operator')
        ->where('status','=','11')
        ->whereBetween('tanggal_bayar', [$tanggal1, $tanggal2])
        ->orderby('tanggal_bayar')
        ->get();
                $data=[];
                foreach ($getData as $item)
                {
                    if (!isset($item['id_channel']))
                    {
                        $channelid='0';
                    }else
                    {
                        $channelid=$item['id_channel'];
                    }
                    $data[]=[
                        'id'=>$item['transaksi_id'],
                        'pin'=>$item['pin'],
                        'nomor_pendaftaran'=>$item['nomor_pendaftaran'],
                        'nama_mahasiswa'=>$item['nama_mahasiswa'],
                        'id_channel'=>$channelid,
                        'tanggal_bayar'=>$item['tanggal_bayar'],
                        'nama_rekening_pengirim'=>$item['nama_rekening_pengirim'],
                        'total_bayar'=>'Rp. '.number_format($item['total_bayar'],0),
                        'jumlah'=>$item['total_bayar'],
                        'lunas'=> $item['verified'],
                        'bukti_bayar'=> $item['bukti_bayar'],
                    ];
            }

        $pdf = Pdf::loadView('report.pembayaran_online',['pembayaran'=>$data,'tanggal'=>AkademikHelpers::tanggal('d F Y')]);
        return $pdf->stream('Laporan-Rekap-PMB.pdf');
        
    }

    public function printofflinepdf()
	{
       
        $tanggal1=session('tanggal1');
        $tanggal2=session('tanggal2');
$getData=KonfirmasiPembayaran::select(DB::raw('konfirmasi_pembayarans.transaksi_id,transaksis.pin,nomor_pendaftaran,nama_mahasiswa,id_channel,total_bayar,bukti_bayar,nomor_pendaftaran,verified,tanggal_bayar,nama_rekening_pengirim,id_operator'))
        ->join('transaksis','transaksis.id','=','konfirmasi_pembayarans.transaksi_id')
        ->join('neomahasiswas','neomahasiswas.pin','=','transaksis.pin')
        ->whereNotNull('id_operator')
        ->where('status','=','11')
        ->whereBetween('tanggal_bayar', [$tanggal1, $tanggal2])
        ->orderby('tanggal_bayar')
        ->get();
                $data=[];
                foreach ($getData as $item)
                {
                    if (!isset($item['id_channel']))
                    {
                        $channelid='0';
                    }else
                    {
                        $channelid=$item['id_channel'];
                    }
                    $data[]=[
                        'id'=>$item['transaksi_id'],
                        'pin'=>$item['pin'],
                        'nomor_pendaftaran'=>$item['nomor_pendaftaran'],
                        'nama_mahasiswa'=>$item['nama_mahasiswa'],
                        'id_channel'=>$channelid,
                        'tanggal_bayar'=>$item['tanggal_bayar'],
                        'nama_rekening_pengirim'=>$item['nama_rekening_pengirim'],
                        'total_bayar'=>'Rp. '.number_format($item['total_bayar'],0),
                        'jumlah'=>$item['total_bayar'],
                        'lunas'=> $item['verified'],
                        'bukti_bayar'=> $item['bukti_bayar'],
                    ];
            }

        $pdf = Pdf::loadView('report.pembayaran_offline',['pembayaran'=>$data,'tanggal'=>AkademikHelpers::tanggal('d F Y')]);
        return $pdf->stream('Laporan-Rekap-PMB.pdf');
        
    }


    public function sendpin($id)
    
    {
        try {
            $data=Neomahasiswa::where('pin',$id)->first();
            $message='Hai, Kak *'.$data->nama_mahasiswa.'* , Silahkan Login di alamat : *https://admission.unsap.ac.id/login-maba* menggunakan Nomor Handphone *'.$data->handphone.'*  dan Pin *'.$data->pin.'* untuk melengkapi data kelengkapan PMB .Terima Kasih :) ';AkademikHelpers::kirimWA('62'.$data->handphone,$message);

        return response()->json(['status'=>'200','success'=>'Notifikasi PIN Berhasil '.$data->nama_mahasiswa]);
        } catch (Exception $e) {
            return response()->json(['status'=>'501','success'=>$e->getMessage()]);
        }
        

    }

    public function sendbroadcast()
    
    {
        try {
            //$data=Neomahasiswa::where('pin',$id)->first();
            //$message='Selamat untukmu yang telah lulus SMA/SMK. Semoga ke depan Kamu bisa bersinar meraih impianmu. Raih Impianmu selanjutnya ada di website https://pmb.unsap.ac.id. Test Web';
            //AkademikHelpers::kirimWAImage('6285220717928',$message);

        return response()->json(['status'=>'200','success'=>Hash::make('19101985')]);
        } catch (Exception $e) {
            return response()->json(['status'=>'501','success'=>$e->getMessage()]);
        }
        

    }


    public function gantiPasswordPanitia(Request $request,$id)
    {
        try 
        {
            $data=User::find($id);
            $pin="19101985";
            
            User::updateOrCreate(
                    ['id'=>$request->id],
                    [
                    'password'=>Hash::make($pin)

                ]);
            
            
            $message='Password Untuk sudah diganti ke '.$pin.' di Aplikasi PMB UNSAP. Silahkan Login di alamat : *https://admission.unsap.ac.id/login* menggunakan Email dan Password tersebut Terima Kasih :) ';
        
            return response()->json(['success'=>$message]);

        } catch (Exception $e) {
            return redirect('/admin/panitia')->with('error', $e->getMessage());
            
        }

    }

    

}
