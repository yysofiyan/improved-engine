<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\Transaksi;
use App\Models\Persyaratan;
use App\Models\Neomahasiswa;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\AkademikHelpers;
use Illuminate\Support\Facades\DB;
use App\Models\KonfirmasiPembayaran;
use Illuminate\Support\Facades\Storage;

class RekomendasiController extends Controller
{
    public function selesai(Request $request)
    {
        $request->session()->put([
            'pin' =>'<span class="btn btn-danger">Belum Set PIN</span>',
            'is_aktif'=>'1',
            'id'=>''
        ]);
        return redirect('/operator/rekomendasi')->with('success', 'Input Data Calon Mahasiswa Rekomendasi Baru');
    }

    public function index(Request $request)
    {
        if (session('id')=='')
        {
            $request->session()->put([
                'pin' =>'<span class="btn btn-danger">Belum Set PIN</span>',
                'is_aktif'=>'1',
                'id'=>''
            ]);
        }

        $pendaftar=Neomahasiswa::select(DB::raw('*'))
        ->where('id',session('id'))
        ->first();
        if (is_null($pendaftar))
        {
            $pendaftar=new Neomahasiswa();
        }

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




        return view('operator/form-rekom',[
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
            'persyaratan'=>$persyaratan,
            'soal'=>$soal,
            'is_aktif'=>'1'

        ]);
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
            'nik' => 'required|unique:neomahasiswas|min:16|max:16',
            'nama_mahasiswa' => 'required',
            'nama_ibu_kandung' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'handphone' => 'required|starts_with:8|min:10',
            'agama' => 'required',
            'jenis_daftar' => 'required',
            'alamat_rumah' => 'required',
            'kelurahan' => 'required',
            'asal_sekolah' => 'required',
            'tahun_lulus' => 'required',
            'kode_pt_asal' => '',
            'instansi' => 'sometimes|nullable|max:255',
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
            $cekMhs=Neomahasiswa::select(DB::raw('*'))
                ->where('handphone',$request->handphone)
                ->where('nik',$request->nik);

                if ($cekMhs->count()==0)
                {
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
                'nik' => $request->nik,
                'nama_mahasiswa' => $request->nama_mahasiswa,
                'jenis_kelamin' => $request->jenis_kelamin,'handphone'=>$request->handphone,'kodeprodi_satu'=>$request->kodeprodi,'pin'=>$pin,'nomor_pendaftaran'=>$no_daftar
            ]);
            $request->session()->put([
                'pin' =>$pin,
                'is_aktif'=>'1',
                'id'=>$id
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
				'desc'=>'Rekomendasi Offline',
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
				'desc'=>'Rekomendasi Offline',
				'created_at'=>$now,
			]);

            DB::table('quiz_murid')->insert([
                'quiz_id' => '1',
                'murid_id' => $id,
                'status' => '1',
                'benar' => '75',
                'nilai' => '0',
                'ket' => 'Lulus - Rekomendasi',
            ]);



            Neomahasiswa::updateOrCreate([
                'id'=>$id,
            ],[
                'id_operator'=>auth()->user()->id,
                'nisn' => $request->nisn,
                'nik' => $request->nik,
                'is_aktif'=>'1',
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
                'instansi'=>$request->instansi,
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

            $formulir = Neomahasiswa::find($id);
            $headers=[
                'HEADER_LOGO'=>AkademikHelpers::public_path("images/header_pmb2025.png"),
                'TANDATANGAN'=>AkademikHelpers::public_path("images/tanda_tangan2025.png"),

            ];
            $prodi=DB::table('pe3_prodi')
            ->where('config','=',$formulir->kodeprodi_satu)
            ->first();



            $viewName = ($prodi->nama_jenjang == 'S-2') ? 'report.surat_s2' : 'report.surat_s1';
            $pdf = Pdf::loadView($viewName, [
                'headers' => $headers,
                'formulir' => $formulir,
                'prodi' => $prodi,
                'tanggal' => now()->format('d F Y')
            ]);
            $content = $pdf->download()->getOriginalContent();
            Storage::put('public/exported/pdf/'.$formulir->nomor_pendaftaran.'.pdf',$content);
                }else
                {
                    Neomahasiswa::updateOrCreate([
                        'id'=>$request->id,
                    ],[
                        'id_operator'=>auth()->user()->id,
                        'nisn' => $request->nisn,
                        'nik' => $request->nik,
                        'is_aktif'=>'1',
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
                        'instansi' => $request->instansi,
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
                }

            return response()->json(['status'=>'200','success'=>'Data Sukses di Simpan']);
        } catch (Exception $e) {
            return response()->json(['status'=>'400','error'=>$e->getMessage()]);
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

    public function injekKonfirmasi(Request $request,$pin)
    {
        $cekPIN=Neomahasiswa::select(DB::raw('*'))
        ->where('pin',$pin);

        try {
            if($cekPIN->count()=='1')
            {
                $now = \Carbon\Carbon::now()->toDateTimeString();
        $no_transaksi='103'.date('YmdHms');
        $id_transaksi=Uuid::uuid4()->toString();
            Transaksi::create([
				'id'=>$id_transaksi,
				'pin'=>$pin,
				'no_transaksi'=>$no_transaksi,
				'nomor_rekening'=>'103',
				'status'=>'11',
				'total'=>'350000',
				'tanggal'=>$now,
				'desc'=>'Rekomendasi Offline',
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
				'desc'=>'Rekomendasi Offline',
				'created_at'=>$now,
			]);
            return response()->json(['status'=>'200','success'=>'Injek Pembayaran Berhasil ']);
            }else
            {
                return response()->json(['status'=>'500','error'=>'Injek Error ']);
            }

        } catch (\Throwable $th) {
            return response()->json(['status'=>'500','error'=>'Terdapat Kesalahan']);
        }

    }

    public function printpdf($id)
    {
        try {
            // Ambil data formulir dengan kolom tertentu
            $formulir = Neomahasiswa::findOrFail($id)->select(
                'id',
                'nomor_pendaftaran',
                'nama_mahasiswa', 
                'asal_perguruan_tinggi',
                'jenis_daftar',
                'kode_pt_asal',
                'kodeprodi_satu'
            )->first();

            // Siapkan header untuk PDF
            $headers = [
                'HEADER_LOGO' => AkademikHelpers::public_path("images/header_pmb2025.png"),
                'TANDATANGAN' => AkademikHelpers::public_path("images/tanda_tangan2025.png"),
            ];

            // Cari data prodi berdasarkan kode prodi
            $prodi = DB::table('pe3_prodi')
                ->where('config', $formulir->kodeprodi_satu)
                ->first();

            // Validasi data prodi
            if(!$prodi) {
                throw new Exception('Data prodi tidak ditemukan');
            }

            // Tentukan view berdasarkan jenjang prodi
            $viewName = ($prodi->nama_jenjang == 'S-2') ? 'report.surat_s2' : 'report.surat_s1';

            // Generate PDF
            $pdf = Pdf::loadView($viewName, [
                'headers' => $headers,
                'formulir' => $formulir,
                'prodi' => $prodi,
                'tanggal' => now()->format('d F Y')
            ]);

            // Simpan PDF ke storage
            $content = $pdf->download()->getOriginalContent();
            Storage::put('public/exported/pdf/'.$formulir->nomor_pendaftaran.'.pdf', $content);

            // Stream PDF ke browser
            return $pdf->stream("Surat_Kelulusan_{$formulir->nomor_pendaftaran}.pdf");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal generate PDF: '.$e->getMessage());
        }
    }

    public function downloadpdf($file)
    {
        try {
            $formulir = Neomahasiswa::findOrFail(session('id'));
            $prodi = DB::table('pe3_prodi')
                ->where('config', $formulir->kodeprodi_satu)
                ->first();

            // Validasi data prodi
            if(!$prodi) {
                throw new Exception('Data prodi tidak ditemukan');
            }

            $viewName = ($prodi->nama_jenjang == 'S-2') ? 'report.surat_s2' : 'report.surat_s1';

            $pdf = Pdf::loadView($viewName, [
                'formulir' => $formulir,
                'prodi' => $prodi,
                'tanggal' => now()->format('d F Y')
            ]);

            return $pdf->stream("Surat_Kelulusan_{$formulir->nomor_pendaftaran}.pdf");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal generate PDF: '.$e->getMessage());
        }
    }

}