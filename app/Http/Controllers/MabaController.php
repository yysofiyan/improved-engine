<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Helpers\Helper;
use App\Models\Transaksi;
use App\Models\Persyaratan;
use Illuminate\Support\Str;
use App\Models\Neomahasiswa;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\AkademikHelpers;
use Mews\Captcha\Facades\Captcha;
use Illuminate\Support\Facades\DB;
use App\Models\KonfirmasiPembayaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class MabaController extends Controller
{
    public function keluar(Request $request)
    {
        if (session('waktu_awal')<>'')
        {
            Session::flush();
            Auth::logout();
            return redirect('/ujian')->with('success', 'Anda Berhasil Logout ..');
        }else
        {
            Session::flush();
            Auth::logout();
            return redirect('/')->with('success', 'Anda Berhasil Logout ..');
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

    public function home()
    {
        $pendaftar = Neomahasiswa::with(['prodi'])
            ->where('id', session('id'))
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


        return view('maba/dashboard',[
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

    public function sendWa()
    {
        $respon=AkademikHelpers::kirimWA('6285220717928','Hello World');
        dd($respon);
    }

    public function sendWas()
    {
        $respon=AkademikHelpers::kirimWA('6285220717928','Hello World');
        return $respon;
    }

    public function cekPin(Request $request)
    {
        $request->validate([
            'handphone' => 'required',
            'pin' => 'required'
        ]);
        $cekPIN=Neomahasiswa::select(DB::raw('*'))
        ->where('handphone',$request->handphone)
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

                return redirect('/maba/dashboard')->with('success', 'Login Berhasil Masuk ..');

            }else
            {
                return redirect('/login-maba')->with('error', 'Handphone dan PIN Tidak Dikenali');
            }

        } catch (\Throwable $th) {
            return redirect('login.maba')->with('error', 'Terdapat Kesalahan');
        }



    }

    
    public function cekUjian(Request $request)
    {
        $request->validate([
            'pin' => 'required'
        ]);
        $cekPIN=Neomahasiswa::select(DB::raw('*'))
        ->where('is_aktif','=','1')
        ->where('pin',$request->pin);

        $soal=DB::table('quiz_murid')
        ->where('murid_id',session('id'))
        ->count();

        try {
            if($cekPIN->count()>'0')
            {
                $getData=$cekPIN->first();

                if ($soal>0)
                {
                    return redirect('/ujian')->with('success', 'Calon Mahasiswa Sudah Melakukan Ujian ..');
                }else
                {
                    $currentDateTime = Carbon::now();
                    $newDateTime = Carbon::now()->addMinute(15);
                    $request->session()->put([
                        'id' => $getData->id,
                        'pin' => $getData->pin,
                        'nomor_pendaftaran' => $getData->nomor_pendaftaran,
                        'nik' => $getData->nik,
                        'nama_mahasiswa' => $getData->nama_mahasiswa,
                        'handphone' => $getData->handphone,
                        'is_aktif' => $getData->is_aktif,
                        'waktu_awal'=>$currentDateTime,
                        'waktu_akhir'=>$newDateTime
                    ]);

                    return redirect('/soal')->with('success', 'Silahkan menjawab soal yang tampil ..');
                }



            }else
            {
                return redirect('/ujian')->with('error', 'PIN Tidak Dikenali/Belum Aktif');
            }

        } catch (\Throwable $th) {
            return redirect('ujian.maba')->with('error', 'Terdapat Kesalahan');
        }



    }

    public function cekUjianOnline(Request $request)
    {

        $cekPIN=Neomahasiswa::select(DB::raw('*'))
        ->where('is_aktif','=','1')
        ->where('pin',session('pin'));

        $soal=DB::table('quiz_murid')
        ->where('murid_id',session('id'))
        ->count();

        try {
            if($cekPIN->count()>'0')
            {
                $getData=$cekPIN->first();

                if ($soal>0)
                {
                    return redirect('home.maba')->with('success', 'Calon Mahasiswa Sudah Melakukan Ujian ..');
                }else
                {
                    $currentDateTime = Carbon::now();
                    $newDateTime = Carbon::now()->addMinute(15);
                    $request->session()->put([
                        'id' => $getData->id,
                        'pin' => $getData->pin,
                        'nomor_pendaftaran' => $getData->nomor_pendaftaran,
                        'nik' => $getData->nik,
                        'nama_mahasiswa' => $getData->nama_mahasiswa,
                        'handphone' => $getData->handphone,
                        'is_aktif' => $getData->is_aktif,
                        'waktu_awal'=>$currentDateTime,
                        'waktu_akhir'=>$newDateTime,
                        'online'=>1
                    ]);

                    return redirect('/soal')->with('success', 'Silahkan menjawab soal yang tampil ..');
                }



            }else
            {
                return redirect('home.maba')->with('error', 'PIN Tidak Dikenali/Belum Aktif');
            }

        } catch (\Throwable $th) {
            return redirect('home.maba')->with('error', 'Terdapat Kesalahan');
        }



    }



    public function index()
    {
        $prodi = DB::table('pe3_prodi')
            ->join('pe3_fakultas', 'pe3_prodi.kode_fakultas', '=', 'pe3_fakultas.kode_fakultas')
            ->select('pe3_prodi.*', 'pe3_fakultas.nama_fakultas')
            ->orderBy('pe3_fakultas.urut')
            ->get();

        return view('maba/register',[
            'prodi'=>$prodi
        ]);
    }

    public function simpandaftar(Request $request)
    {
        $request->validate([
            'nik' => 'required|unique:neomahasiswas|min:16|max:16',
            'nama_mahasiswa' => 'required',
            'captcha' => 'required|captcha',
            'jenis_kelamin' => 'required',
            'handphone' => 'required|starts_with:8|min:10',
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
                $tipe='1';
			    $no_daftar=$this->autonumber($table,$primary,$prefix,$tipe);

                Neomahasiswa::Create([
                'id' => $id,
                'nik' => $request->nik,
                'nama_mahasiswa' => $request->nama_mahasiswa,
                'jenis_kelamin' => $request->jenis_kelamin,'handphone'=>$request->handphone,'kodeprodi_satu'=>$request->kodeprodi,'pin'=>$pin,'nomor_pendaftaran'=>$no_daftar
            ]);

            $request->session()->put([
                'id' => $id,
                'pin' => $pin,
                'nomor_pendaftaran' => $no_daftar,
                'nik' => $request->nik,
                'nama_mahasiswa' => $request->nama_mahasiswa,
                'handphone' => $request->handphone,
                'is_aktif' => '12',
            ]);
            $now = \Carbon\Carbon::now()->toDateTimeString();
            $no_transaksi='101'.date('YmdHms');
            Transaksi::create([
				'id'=>$id_transaksi,
				'pin'=>$pin,
				'no_transaksi'=>$no_transaksi,
				'nomor_rekening'=>'101',
				'status'=>'10',
				'total'=>'200000',
				'tanggal'=>$now,
				'desc'=>'Pendaftaran Online',
				'created_at'=>$now,
			]);

            KonfirmasiPembayaran::create([
				'transaksi_id'=>$id_transaksi,
				'total_bayar'=>'200000',
                'id_channel'=>'4',
                'tanggal_bayar'=>$now,
                'nama_rekening_pengirim'=>'',
                'bukti_bayar'=>'no_image.png',
                'tanggal'=>$now,
				'desc'=>'Pendaftaran Online',
                'verified'=>'10',
                'created_at'=>$now,
			]);

            /* Transaksi::create([
				'id'=>$id_transaksi,
				'pin'=>$pin,
				'no_transaksi'=>$no_transaksi,
				'nomor_rekening'=>'101',
				'status'=>'11',
				'total'=>'0',
				'tanggal'=>$now,
				'desc'=>'Pendaftaran Online Gratis',
				'created_at'=>$now,
			]);

            KonfirmasiPembayaran::create([
				'transaksi_id'=>$id_transaksi,
				'total_bayar'=>'0',
                'id_channel'=>'4',
                'tanggal_bayar'=>$now,
                'nama_rekening_pengirim'=>'Pendaftaran Gratis',
                'bukti_bayar'=>'no_image.png',
                'tanggal'=>$now,
				'desc'=>'Pendaftaran Online Gratis',
                'verified'=>'11',
                'created_at'=>$now,
			]);
            Neomahasiswa::updateOrCreate(
                [ 'pin'=>$pin],
                [
                    'is_aktif'=>'1',
                ]
                );  */

            $message='Hai, Kak *'.$request->nama_mahasiswa.'* , Setelah melakukan pendaftaran segera upload bukti pembayaran dengan Login di alamat : *https://admission.unsap.ac.id/login-maba* %0amenggunakan Nomor Handphone *'.$request->handphone.'*  dan Pin *'.$pin.'* agar Mimin segera mengaktifkan pin setelah upload bukti bayar dan selangkah lagi kaka menjadi Calon Mahasiswa Baru UNSAP.  %0a%0aTerima Kasih :) ';
            //$message='Hai, Kak *'.$request->nama_mahasiswa.'* , Selamat Anda Mendapatkan Gratis Biaya Pendaftaran. Silahkan login di alamat : *https://admission.unsap.ac.id/login-maba* %0amenggunakan Nomor Handphone *'.$request->handphone.'*  dan Pin *'.$pin.'* Setelah berhasil masuk, isi formulir dan upload persyaratan.  %0a%0aTerima Kasih :) ';

            AkademikHelpers::kirimWA('62'.$request->handphone,$message);

            return response()->json(['status'=>'200','success'=>'Data Sukses di Simpan'.$pin]);

        } catch (Exception $e) {
            return response()->json(['status'=>'200','success'=>$e->getMessage()]);
        }


    }

    public function loginmaba()
    {
        return view('maba/login');
    }

    public function ujian()
    {
        return view('maba/ujian');
    }

    public function reloadCaptcha()
    {
        return response()->json(['captcha'=> Captcha::img()]);
    }

    public function wilayahProvinsi()
    {
        $provinsi=DB::table('wilayah')
        ->where('id_negara','=','ID')
        ->where('nama_wilayah','like','Prov.%')
        ->orderBy('nama_wilayah')
        ->get();

        /* $getWilayahProvinsi = new NeoFeeder([
            'act' => 'GetWilayah',
            'filter' => "id_negara = 'ID'",
            'filter' => "nama_wilayah like 'Prov.%'",
            'order' => "nama_wilayah"
        ]); */

        return Response()->json([
            'error_code'=>0,
            'error_desc'=>'',
            'data'=>$provinsi,
            'message'=>'fetch data berhasil'
        ], 200);

    }

    public function wilayahKota(Request $request)
    {
        $idProvinsi = Str::substr($request->provinsi, 0, 2);
        $provinsi=DB::table('wilayah')
        ->where('id_wilayah','like',$idProvinsi.'%')
        ->Where(function ($query) {
            $query->where('nama_wilayah', 'like', 'Kota%')
                  ->orwhere('nama_wilayah', 'like', 'Kab.%');
        })
        ->orderBy('nama_wilayah')
        ->get();


        return Response()->json([
            'error_code'=>0,
            'error_desc'=>'',
            'data'=>$provinsi,
            'message'=>'fetch data berhasil'
        ], 200);

       /*  $getWilayahKota = new NeoFeeder([
            'act' => 'GetWilayah',
            'filter' => "id_wilayah like '$idProvinsi%' and (nama_wilayah like 'Kota%' or nama_wilayah like 'Kab.%')",
            'order' => "nama_wilayah"
        ]); */

    }

    public function wilayahKecamatan(Request $request)
    {
        $idKota = Str::substr($request->kota, 0, 4);

        $provinsi=DB::table('wilayah')
        ->where('id_wilayah','like',$idKota.'%')
        ->where('nama_wilayah', 'like', 'Kec.%')
        ->orderBy('nama_wilayah')
        ->get();


        return Response()->json([
            'error_code'=>0,
            'error_desc'=>'',
            'data'=>$provinsi,
            'message'=>'fetch data berhasil'
        ], 200);


        /* $getWilayahKota = new NeoFeeder([
            'act' => 'GetWilayah',
            'filter' => "id_wilayah like '$idKota%' and nama_wilayah like 'Kec.%'",
            'order' => "nama_wilayah"
        ]); */

    }

    public function updateMhs(Request $request)
    {
        $request->validate([
            'nisn' => 'sometimes|nullable|min:10|max:10',
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
            'instansi' => 'sometimes|nullable|max:255',
            'catatan' => '',
            'kodeprodi_satu' => 'required',
            'kodeprodi_dua' => 'required',
            'kewarganegaraan' => 'required',
            'konfirmasi' => 'required',
        ]);

        try {
            Neomahasiswa::updateOrCreate([
                'id'=>$request->id,
            ],[
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
                'instansi'=>$request->instansi,
                'negara'=>$request->negara,
                'provinsi'=>$request->provinsi,
                'kota'=>$request->kota,
                'kecamatan'=>$request->kecamatan,
                'tahun_masuk'=>'2025', // Mengubah tahun masuk menjadi 2025 untuk menyesuaikan dengan tahun akademik 2025/2026. Perubahan ini perlu dilakukan jika proses pendaftaran diperuntukkan untuk tahun akademik berikutnya
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

    public function uploadBukti (Request $request)
    {
        $request->validate([
            'id_channel'=>'required',
            'nama_rekening_pengirim'=>'required',
            'bukti_bayar' => 'required|file|mimes:jpg,jpeg,bmp,png,pdf',
        ]);

        try {

            $fileName = time().'.'.$request->bukti_bayar->extension();

        $request->bukti_bayar->move(public_path('images/pmb'), $fileName);
        $transaksi= DB::table('transaksis')
        ->where('pin','=',session('pin'))
        ->first();

        KonfirmasiPembayaran::updateOrCreate([
            'transaksi_id'=>$transaksi->id,
        ],[
            'id_channel'=>$request->id_channel,
            'tanggal_bayar'=>$request->tanggal_bayar,
            'nama_rekening_pengirim'=>$request->nama_rekening_pengirim,
            'bukti_bayar'=>$fileName,
        ]);

        return response()->json(['status'=>'200','success'=>'Upload Bukti Pembayaran Sukses di Upload']);
        } catch (Exception $e) {
            return response()->json(['status'=>'200','success'=>$e->getMessage()]);
        }






    }


    public function uploadSyarat(Request $request)
    {
        $pendaftar=Neomahasiswa::select(DB::raw('*'))
        ->where('pin',session('pin'))
        ->first();

        if ($pendaftar->jenis_daftar == 2 || $pendaftar->jenis_daftar == 6)
        {
            if (AkademikHelpers::getFakultas($pendaftar->kodeprodi_satu)=='13')
            {
                $request->validate([
                    'ijasah'=>'file|mimes:jpg,jpeg,bmp,png,pdf',
                    'ktp_kk'=>'required|file|mimes:jpg,jpeg,bmp,png,pdf',
                    'foto'=>'required|file|mimes:jpg,jpeg,bmp,png,pdf',
                    'ket_sehat'=>'file|mimes:jpg,jpeg,bmp,png,pdf',
                ]);
            }else
            {
                $request->validate([
                    'ijasah'=>'file|mimes:jpg,jpeg,bmp,png,pdf',
                    'ktp_kk'=>'required|file|mimes:jpg,jpeg,bmp,png,pdf',
                    'foto'=>'required|file|mimes:jpg,jpeg,bmp,png,pdf',
                ]);
            }

        }else if ($pendaftar->jenis_daftar=='2') {
            // Validasi untuk pendaftar pindahan/transfer
            $request->validate([
                'ijasah'=>'file|mimes:jpg,jpeg,bmp,png,pdf', // Ijazah opsional karena mungkin belum lulus
                'ktp_kk'=>'required|file|mimes:jpg,jpeg,bmp,png,pdf', // Wajib untuk verifikasi identitas
                'foto'=>'required|file|mimes:jpg,jpeg,bmp,png,pdf', // Wajib untuk keperluan administrasi
                'ket_sehat'=>'file|mimes:jpg,jpeg,bmp,png,pdf', // Opsional tergantung kebijakan prodi
                'khs'=>'required|file|mimes:jpg,jpeg,bmp,png,pdf', // Wajib untuk melihat progress studi
                'ktm'=>'required|file|mimes:jpg,jpeg,bmp,png,pdf', // Wajib sebagai bukti status mahasiswa
                'surat_pindah'=>'file|mimes:jpg,jpeg,bmp,png,pdf', // Wajib untuk proses transfer
                'screen_pddikti'=>'file|mimes:jpg,jpeg,bmp,png,pdf', // Wajib untuk verifikasi data PDDikti
                'ijasah_lanjutan'=>'file|mimes:jpg,jpeg,bmp,png,pdf', // Opsional jika ada ijazah tambahan
                'transkrip_nilai'=>'file|mimes:jpg,jpeg,bmp,png,pdf', // Opsional untuk melihat nilai lengkap
            ]);


        }else
        {
            $request->validate([
                'ijasah'=>'file|mimes:jpg,jpeg,bmp,png,pdf', // Ijazah opsional karena mungkin belum lulus
                'ktp_kk'=>'required|file|mimes:jpg,jpeg,bmp,png,pdf', // Wajib untuk verifikasi identitas
                'foto'=>'required|file|mimes:jpg,jpeg,bmp,png,pdf', // Wajib untuk keperluan administrasi
                'ket_sehat'=>'file|mimes:jpg,jpeg,bmp,png,pdf', // Opsional tergantung kebijakan prodi
                'khs'=>'file|mimes:jpg,jpeg,bmp,png,pdf', // Opsional untuk melihat progress studi
                'ktm'=>'file|mimes:jpg,jpeg,bmp,png,pdf', // Opsional sebagai bukti status mahasiswa
                'screen_pddikti'=>'file|mimes:jpg,jpeg,bmp,png,pdf', // Opsional untuk verifikasi data PDDikti
                'surat_pindah'=>'file|mimes:jpg,jpeg,bmp,png,pdf', // Opsional untuk proses transfer
                'ijasah_lanjutan'=>'file|mimes:jpg,jpeg,bmp,png,pdf', // Opsional jika ada ijazah tambahan
                'transkrip_nilai'=>'file|mimes:jpg,jpeg,bmp,png,pdf', // Wajib untuk melihat nilai lengkap
            ]);


        }

        try {


            if ($request->id_persyaratan=='')
            {
                $id_persyaratan=Uuid::uuid4()->toString();
            }else{
                $id_persyaratan=$request->id_persyaratan;
            }

            // Cek apakah file ijazah diupload
            if ($request->hasFile('ijasah')) {
                // Generate nama file unik dengan format: Ijasah_timestamp.extension
                $fileIjasah = 'Ijasah_'.time().'.'.$request->ijasah->extension();
                
                // Pindahkan file ijazah ke folder public/images/persyaratan
                $request->ijasah->move(public_path('images/persyaratan'), $fileIjasah);
            } else {
                // Jika tidak ada file ijazah diupload, set nilai null
                $fileIjasah = null;
            }

            $fileKtp = 'Ktp_'.time().'.'.$request->ktp_kk->extension();
            $request->ktp_kk->move(public_path('images/persyaratan'), $fileKtp);

            $fileFoto = 'Foto_'.time().'.'.$request->foto->extension();
            $request->foto->move(public_path('images/persyaratan'), $fileFoto);

            if ($pendaftar->jenis_daftar=='1')
            {



                if (AkademikHelpers::getFakultas($pendaftar->kodeprodi_satu)=='13')
            {
                $fileKetSehat = 'KetSehat_'.time().'.'.$request->ket_sehat->extension();
                $request->ket_sehat->move(public_path('images/persyaratan'), $fileKetSehat);
                $exc=Persyaratan::updateOrCreate(
                    ['id'=>$id_persyaratan],
                    [
                    'id_user'=>session('id'),
                    'ijasah'=>$fileIjasah,
                    'is_ijasah'=>'1',
                    'id_operator'=>'Sendiri',
                    'ktp_kk'=>$fileKtp,
                    'is_ktp'=>'1',
                    'foto'=>$fileFoto,
                    'is_foto'=>'1',
                    'ket_sehat'=>$fileKetSehat,
                    'is_ket_sehat'=>'1',
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
                    'ijasah'=>$fileIjasah,
                    'is_ijasah'=>'1',
                    'id_operator'=>'Sendiri',
                    'ktp_kk'=>$fileKtp,
                    'is_ktp'=>'1',
                    'foto'=>$fileFoto,
                    'is_foto'=>'1',
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
                $fileKhs = 'Khs_'.time().'.'.$request->khs->extension();
                $request->khs->move(public_path('images/persyaratan'), $fileKhs);

                $fileKtm = 'Ktm_'.time().'.'.$request->ktm->extension();
                $request->ktm->move(public_path('images/persyaratan'), $fileKtm);

                $fileSuratPindah = 'SuratPindah_'.time().'.'.$request->surat_pindah->extension();
                $request->surat_pindah->move(public_path('images/persyaratan'), $fileSuratPindah);

                $exc=Persyaratan::updateOrCreate(
                    ['id'=>$id_persyaratan],
                    [
                    'id_user'=>session('id'),
                    'ijasah'=>$fileIjasah,
                    'is_ijasah'=>'1',
                    'id_operator'=>'Sendiri',
                    'ktp_kk'=>$fileKtp,
                    'is_ktp'=>'1',
                    'foto'=>$fileFoto,
                    'is_foto'=>'1',
                    'ket_sehat'=>'',
                    'is_ket_sehat'=>'1',
                    'khs'=>$fileKhs,
                    'is_khs'=>'0',
                    'ktm'=>$fileKtm,
                    'is_ktm'=>'0',
                    'surat_pindah'=>$fileSuratPindah,
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
               /*  $fileScreenPddikti = 'Pddikti_'.time().'.'.$request->screen_pddikti->extension();
                $request->screen_pddikti->move(public_path('images/persyaratan'), $fileScreenPddikti); */

                $fileIjasahLanjutan = 'IjasahLanjutan_'.time().'.'.$request->ijasah_lanjutan->extension();
                $request->ijasah_lanjutan->move(public_path('images/persyaratan'), $fileIjasahLanjutan);

                $fileTranskripNilai = 'TranskripNilai_'.time().'.'.$request->transkrip_nilai->extension();
                $request->transkrip_nilai->move(public_path('images/persyaratan'), $fileTranskripNilai);

                $exc=Persyaratan::updateOrCreate(
                    ['id'=>$id_persyaratan],
                    [
                    'id_user'=>session('id'),
                    'ijasah'=>$fileIjasah,
                    'is_ijasah'=>'1',
                    'id_operator'=>'Sendiri',
                    'ktp_kk'=>$fileKtp,
                    'is_ktp'=>'1',
                    'foto'=>$fileFoto,
                    'is_foto'=>'1',
                    'ket_sehat'=>'',
                    'is_ket_sehat'=>'1',
                    'khs'=>'',
                    'is_khs'=>'0',
                    'ktm'=>'',
                    'is_ktm'=>'0',
                    'surat_pindah'=>'',
                    'is_surat_pindah'=>'0',
                    'screen_pddikti'=>'',
                    'is_screen_pddikti'=>'0',
                    'ijasah_lanjutan'=>$fileIjasahLanjutan,
                    'is_ijasah_lanjutan'=>'0',
                    'transkrip_nilai'=>$fileTranskripNilai,
                    'is_transkrip_nilai'=>'0',
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now()
                ]);


            }

            /* $soal=DB::table('quiz_murid')
        ->where('murid_id',session('id'))
        ->count();
        if ($soal==0)
        {
            $tanpa_test=DB::table('quiz_murid')->insert([
                'quiz_id' => '1',
                'murid_id' => session('id'),
                'status' => '1',
                'benar' => '75',
                'nilai' => '0',
                'ket' => 'Lulus - Promo Tanpa Tes',
            ]);

        } */
        return response()->json(['status'=>'200','success'=> 'Upload Persyaratan Berhasil']);
        } catch (Exception $e) {
            return response()->json(['status'=>'200','success'=>$e->getMessage()]);
        }



    }


    // public function printpdf2()
	// {

	// 	$formulir = Neomahasiswa::find('03b5d645-5191-4c4a-a2d5-21723cee8060');

    //     $headers=[
	// 				'HEADER_LOGO'=>AkademikHelpers::public_path("images/header_pmb.png"),
	// 				'TANDATANGAN'=>AkademikHelpers::public_path("images/tanda_pmb.png")
	// 			];
    //             //dd($headers);
	// 	$pdf = \Meneses\LaravelMpdf\Facades\LaravelMpdf::loadView('report.surat',
	// 																	[
	// 																		'headers'=>$headers,
	// 																	],
	// 																	[],
	// 																	[
	// 																		'title' => 'Surat Kelulusan Calon Mahasiswa Baru UNSAP 2025',
	// 																	]);
	// 			$file_pdf=AkademikHelpers::public_path("exported/pdf/luluspmb_123.pdf");
	// 			$pdf->save($file_pdf);

	// 			$pdf_file='public/exported/pdf/luluspmb_123.pdf';

	// 			return Response()->json([
	// 									'status'=>1,
	// 									'pid'=>'fetchdata',
	// 									'formulir'=>$formulir,
	// 									'pdf_file'=>$pdf_file
	// 								], 200);
	// }

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