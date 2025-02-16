<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\Transaksi;
use App\Models\Persyaratan;
use App\Models\Neomahasiswa;
use Illuminate\Http\Request;
use App\Helpers\AkademikHelpers;
use Illuminate\Support\Facades\DB;
use App\Models\KonfirmasiPembayaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade\Pdf;

class OperatorController extends Controller
{
    public function generatePin($length = 4)
    {
    $random = "";
    srand((double) microtime() * 1000000);
    $data = "123456123456789071234567890890";
    // $data .= "aBCdefghijklmn123opq45rs67tuv89wxyz"; // if you need alphabatic also
    for ($i = 0; $i < $length; $i++) {
          $random .= substr($data, (rand() % (strlen($data))), 1);
    }
    return $random;
    }

    public static function autonumber($barang,$primary,$prefix,$tipe){
        $q=DB::table($barang)->select(DB::raw('MAX(RIGHT('.$primary.',5)) as kd_max'));

        if ($tipe=='1')
        {
            $prx=$prefix.'A';
        }else
        {
            $prx=$prefix.'B';
        }
        if($q->count()>0)
        {
            foreach($q->get() as $k)
            {
                $tmp = ((int)$k->kd_max)+1;
                $kd = $prx.sprintf("%06s", $tmp);
            }
        }
        else
        {
                $kd = $prx."000001";

        }

        return $kd;
    }
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
        return view('operator/dashboard',[
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

    public function riwayat(Request $request)
    {
        if($request->ajax()) {
            $getData=Neomahasiswa::select(DB::raw('neomahasiswas.id,pin, nama_mahasiswa,is_aktif,handphone,nama_prodi,nama_jenjang'))
                ->join('pe3_prodi','neomahasiswas.kodeprodi_satu','=','pe3_prodi.config')
                ->where('id_operator','=',auth()->user()->id)
				->where('tahun_masuk','2025')
				->get();
                $data=[];
                foreach ($getData as $item)
                {
                    $data[]=[
                        'id'=>$item['id'],
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

        return view('operator/riwayat');
    }

    public function kelulusan(Request $request)
    {
        if($request->ajax()) {
            $getData=Neomahasiswa::select(DB::raw('neomahasiswas.id,pin, nama_mahasiswa,is_aktif,handphone,nama_prodi,nama_jenjang,nomor_pendaftaran,nilai'))
                ->join('pe3_prodi','neomahasiswas.kodeprodi_satu','=','pe3_prodi.config')
                ->join('quiz_murid','neomahasiswas.id','=','quiz_murid.murid_id')
				->where('tahun_masuk','2025')
                ->orderby('quiz_murid.id','desc')->get();
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
        return view('operator/kelulusan');
    }

    public function datasekolah(Request $request)
    {
        if($request->ajax()) {
            $getData=DB::table('sekolah')
            ->whereIn('bentuk',['SMA','SMK','MA'])
            ->select(DB::raw('sekolah,id,kabupaten_kota,kecamatan,bentuk'))
            ->orderBy('sekolah')
            ->get();
                $data=[];
                foreach ($getData as $item)
                {
                    $data[]=[
                        'id'=>$item->id,
                        'sekolah'=>$item->sekolah,
                        'kabupaten_kota'=>$item->kabupaten_kota,
                        'kecamatan'=>$item->kecamatan,
                        'bentuk'=>$item->bentuk
                    ];
            }

            return Response()->json([
                'error_code'=>0,
                'error_desc'=>'',
                'data'=>$data,
                'message'=>'fetch data berhasil'
            ], 200);
        }
        return view('operator/datasekolah');
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


        return view('operator/form-maba',[
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

    public function getPin(Request $request)
    {
        $cekPIN=Neomahasiswa::select(DB::raw('*'))
        ->where('pin',$request->pin);

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

                return redirect('/operator/form-maba')->with('success', 'PIN Sesuai ..');

            }else
            {
                return redirect('/operator/dashboard')->with('error', 'PIN Tidak Dikenali');
            }

        } catch (\Throwable $th) {
            return redirect('/operator/dashboard')->with('error', 'Terdapat Kesalahan');
        }

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

                return redirect('/operator/form-maba')->with('success', 'PIN Sesuai ..');

            }else
            {
                return redirect('/operator/riwayat-daftar')->with('error', 'PIN Tidak Dikenali');
            }

        } catch (\Throwable $th) {
            return redirect('/operator/riwayat-daftar')->with('error', 'Terdapat Kesalahan');
        }

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
    public function lihatpdf($file)
    {
        try {
            // Cari data mahasiswa berdasarkan nomor pendaftaran
            $formulir = Neomahasiswa::with(['prodiRelation'])
                ->where('nomor_pendaftaran', $file)
                ->firstOrFail();

            // Validasi data prodi
            if(!$formulir->kodeprodi_satu || !$formulir->prodiRelation) {
                throw new Exception('Data prodi tidak valid');
            }

            // Tentukan template berdasarkan jenjang
            $viewName = ($formulir->prodiRelation->nama_jenjang == 'S-2') 
                ? 'report.surat_s2' 
                : 'report.surat_s1';

            // Membuat PDF dengan menggunakan view yang telah ditentukan
            $pdf = Pdf::loadView($viewName, [
                'formulir' => $formulir, // Data formulir mahasiswa
                'prodi' => $formulir->prodiRelation, // Data program studi terkait
                'tanggal' => now()->format('d F Y') // Tanggal saat ini dengan format 'd F Y'
            ]);

            // Mengembalikan PDF sebagai stream dengan nama file yang dihasilkan
            return $pdf->stream("Surat_Kelulusan_{$file}.pdf");

        } catch (\Exception $e) {
            // Jika terjadi error, redirect kembali dengan pesan error
            return redirect()->back()
                ->with('error', 'Gagal generate PDF: '.$e->getMessage());
        }
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();

        return redirect('/login')->with('success', 'Anda Berhasil Logout ..');
    }

    public function buatpin(Request $request)
    {
        if($request->ajax()) {
            $getData=Neomahasiswa::select(DB::raw('neomahasiswas.id,pin, nama_mahasiswa,is_aktif,handphone,nama_prodi,nama_jenjang'))
                ->join('pe3_prodi','neomahasiswas.kodeprodi_satu','=','pe3_prodi.config')
                ->where('id_operator',auth()->user()->id)
                ->get();
                $data=[];
                foreach ($getData as $item)
                {
                    $data[]=[
                        'id'=>$item['id'],
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

        return view('operator/generate_pin');
    }

    public function tambahpin()
    {
        $prodi = DB::table('pe3_prodi')
            ->join('pe3_fakultas', 'pe3_prodi.kode_fakultas', '=', 'pe3_fakultas.kode_fakultas')
            ->select('pe3_prodi.*', 'pe3_fakultas.nama_fakultas')
            ->orderBy('pe3_fakultas.urut')
            ->get();
        return view('operator/f_generate_pin',[
            'prodi'=>$prodi
        ]);
    }

    public function simpanpin(Request $request)
    {
        $request->validate([
            'nik' => 'required|unique:neomahasiswas',
            'nama_mahasiswa' => 'required',
            'captcha' => 'required|captcha',
            'jenis_kelamin' => 'required',
            'handphone' => 'required',
            'kodeprodi' => 'required',
        ]);

        try {
                $id=Uuid::uuid4()->toString();
                $id_transaksi=Uuid::uuid4()->toString();
                $gen_satu=$this->generatePin();
                $hp=$request->handphone;
                $pin=$gen_satu.''.substr($hp,-4);

                $table="neomahasiswas";
			    $primary="nomor_pendaftaran";
			    $prefix="2025";
                $tipe='2';
			    $no_daftar=$this->autonumber($table,$primary,$prefix,$tipe);

                Neomahasiswa::Create([
                'id' => $id,
                'id_operator'=>auth()->user()->id,
                'nik' => $request->nik,
                'nama_mahasiswa' => $request->nama_mahasiswa,
                'jenis_kelamin' => $request->jenis_kelamin,'handphone'=>$request->handphone,'kodeprodi_satu'=>$request->kodeprodi,'pin'=>$pin,'nomor_pendaftaran'=>$no_daftar,
                'is_aktif'=>'1',
            ]);
            $now = \Carbon\Carbon::now()->toDateTimeString();
            $no_transaksi='103'.date('YmdHms');
            Transaksi::create([
				'id'=>$id_transaksi,
				'pin'=>$pin,
				'no_transaksi'=>$no_transaksi,
				'nomor_rekening'=>'103',
				'status'=>'11',
				'total'=>'350000',
				'tanggal'=>$now,
				'desc'=>'Pendaftaran Offline',
				'created_at'=>$now,
			]);

            KonfirmasiPembayaran::create([
				'transaksi_id'=>$id_transaksi,
				'total_bayar'=>'350000',
                'tanggal_bayar'=>$now,
                'bukti_bayar'=>'no_image.png',
                'id_channel'=>'4',
                'nomor_rekening_pengirim'=>'01',
                'nama_rekening_pengirim'=>'YPSA',
                'verified'=>'11',
				'desc'=>'Pendaftaran Offline',
				'created_at'=>$now,
			]);
            $message='Hai, Kak *'.$request->nama_mahasiswa.'* , *Selamat PIN Pendaftaran kamu sudah aktif*. %0a%0a%0aLangkah selanjutnya Silahkan Login di alamat : *https://admission.unsap.ac.id/login-maba* %0adengan menggunakan Nomor Handphone dan Pin (*'.$pin.'*) lalu isi formulir biodata, upload persyaratan dan Ujian Seleksi Mahasiswa Baru. %0a%0aTerima Kasih :) ';

            AkademikHelpers::kirimWA('62'.$request->handphone,$message);

            return response()->json(['status'=>'200','success'=>'Data Sukses di Simpan']);
        } catch (Exception $e) {
            return response()->json(['status'=>'200','success'=>$e->getMessage()]);
        }

    }


    public function tambahsekolah()
    {
        return view('operator/f_sekolah');
    }

    public function simpansekolah(Request $request)
    {
        $request->validate([
            'sekolah' => 'required',
            'kabupaten_kota' => 'required',
            'kecamatan' => 'required',
            'bentuk' => 'required',
        ]);

        try {
                $id=Uuid::uuid4()->toString();
                DB::table('sekolah')->insert([
                    'kode_prop' => '020000',
                    'propinsi' => 'Prov. Jawa Barat',
                    'kode_kab_kota' => '021000',
                    'kode_kec' => '021005',
                    'id' => $id,
                    'npsn' => '69964370',
                    'sekolah' => $request->sekolah,
                    'kabupaten_kota' => $request->kabupaten_kota,
                    'kecamatan' => $request->kecamatan,
                    'bentuk' => $request->bentuk,
                    'status' => 'S',
                    'alamat_jalan' => 'Sumedang',
                    'lintang' => '-6.8623000',
                    'bujur' => '107.9203000',
                ]);
            return response()->json(['status'=>'200','success'=>'Data Sekolah Sukses di Simpan']);
        } catch (Exception $e) {
            return response()->json(['status'=>'200','success'=>$e->getMessage()]);
        }

    }

}
