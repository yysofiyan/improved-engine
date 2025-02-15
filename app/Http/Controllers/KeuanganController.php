<?php

namespace App\Http\Controllers;

use App\Helpers\AkademikHelpers;
use Exception;
use Ramsey\Uuid\Uuid;
use App\Models\Transaksi;
use App\Models\Neomahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\KonfirmasiPembayaran;

class KeuanganController extends Controller
{
    function __construct()
    {
        $this->middleware('authYPSA');
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
    
    public function index()
    {
        return view('keuangan/dashboard');
    }

    public function buatpin(Request $request)
    {
        if($request->ajax()) {
            $getData=Neomahasiswa::select(DB::raw('neomahasiswas.id,pin, nama_mahasiswa,is_aktif,handphone,nama_prodi,nama_jenjang'))
                ->join('pe3_prodi','neomahasiswas.kodeprodi_satu','=','pe3_prodi.config')->get();
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

        return view('keuangan/generate_pin');
    }

    public function tambahpin()
    {
        $prodi = DB::table('pe3_prodi')
            ->join('pe3_fakultas', 'pe3_prodi.kode_fakultas', '=', 'pe3_fakultas.kode_fakultas')
            ->select('pe3_prodi.*', 'pe3_fakultas.nama_fakultas')
            ->orderBy('pe3_fakultas.urut')
            ->get();
        return view('keuangan/f_generate_pin',[
            'prodi'=>$prodi
        ]);
    }

    public function simpanpin(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'nama_mahasiswa' => 'required',
            'captcha' => 'required|captcha',
            'jenis_kelamin' => 'required',
            'handphone' => 'required|unique:neomahasiswas',
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
            $now = \Carbon\Carbon::now()->toDateTimeString(); 
            $no_transaksi='101'.date('YmdHms');
            Transaksi::create([
				'id'=>$id_transaksi,
				'pin'=>$pin,  
				'no_transaksi'=>$no_transaksi,  
				'nomor_rekening'=>'103',
				'status'=>'10',
				'total'=>'350000',
				'tanggal'=>$now,
				'desc'=>'Pendaftaran Offline',
				'created_at'=>$now,
			]);

            KonfirmasiPembayaran::create([
				'transaksi_id'=>$id_transaksi,
				'total_bayar'=>'350000', 
                'tanggal'=>$now,
				'desc'=>'Pendaftaran Offline',
				'created_at'=>$now,
			]);
            
            return response()->json(['status'=>'200','success'=>'Data Sukses di Simpan']);
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
        
        return view('keuangan/transaksi');
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
        
        
        return view('keuangan/konfirmasi');
    }

    public function buatnim(Request $request)
    {
        if($request->ajax()) {
            $getData=Neomahasiswa::select(DB::raw('neomahasiswas.id,pin,nim, nomor_pendaftaran,nama_mahasiswa,is_aktif,handphone,nama_prodi,nama_jenjang'))
                ->join('pe3_prodi','neomahasiswas.kodeprodi_satu','=','pe3_prodi.config')->get();
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
        return view('keuangan/buatnim');
    }

    public function tambahnim()
    {
        return view('keuangan/f_buat_nim');
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
            $tahun=$mahasiswa->tahun_lulus;
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

            if ($mahasiswa->nim<>'')
            {
                return response()->json(['status'=>'201','success'=>'Mahasiswa Sudah Memiliki NIM']);
            }else
            {
                Neomahasiswa::updateOrCreate(
                    [
                        'id' => $mahasiswa->id,
                    ],
                    [
                        'nim' => $nimMHS,
                    ]
                );

                return response()->json(['status'=>'200','success'=>'Data Sukses di Simpan NIM Mahasiswa : '.$nimMHS]);

            }
        
            



            
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

}
