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
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
        $tahunMasuk = Transaksi::selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('keuangan.transaksi', [
            'tahunMasuk' => $tahunMasuk,
            'tahunAktif' => now()->year
        ]);
    }

    public function getDataTransaksi(Request $request)
    {
        $query = Transaksi::with(['neomahasiswa'])
            ->select('id', 'pin', 'no_transaksi', 'tanggal', 'total', 'status');

        // Filter tahun
        if ($request->tahun) {
            $query->whereYear('tanggal', $request->tahun);
        }

        // Hitung total records
        $totalRecords = Transaksi::count();
        $filteredRecords = $query->count();

        // Pagination
        $transaksis = $query->skip($request->start)
            ->take($request->length)
            ->get()
            ->map(function($item, $index) use ($request) {
                return [
                    'no' => $request->start + $index + 1,
                    'tanggal' => $item->tanggal->translatedFormat('d F Y'),
                    'pin' => $item->pin,
                    'no_transaksi' => $item->no_transaksi,
                    'nama_mahasiswa' => $item->neomahasiswa->nama_mahasiswa ?? 'N/A',
                    'total' => number_format($item->total, 0, ',', '.'),
                    'status_badge' => $item->status == 11 ? 
                        '<span class="badge badge-success">Lunas</span>' : 
                        '<span class="badge badge-warning">Belum Lunas</span>'
                ];
            });

        // Tambahkan logging
        Log::debug('Data Response:', [
            'count' => $transaksis->count(),
            'sample' => $transaksis->first()
        ]);

        return response()->json([
            'draw' => (int) $request->draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $transaksis
        ]);
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

    

    public function konfirmasi()
    {
        $tahunMasuk = Transaksi::selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->get()
            ->pluck('tahun');

        return view('keuangan.konfirmasi', [
            'tahunMasuk' => $tahunMasuk,
            'tahunAktif' => now()->year
        ]);
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
            $tahun=$mahasiswa->tahun_masuk; // Ganti dari tahun_lulus ke tahun_masuk
            $idkelas='R';
            $prodi_id=$mahasiswa->kodeprodi_satu;
            $idsmt='1';
            $prodi = DB::table('pe3_prodi')
            ->where('config','=',$mahasiswa->kodeprodi_satu)
            ->first();
            $fakultas = DB::table('pe3_fakultas')
            ->where('kode_fakultas','=',$prodi->kode_fakultas)
            ->first();

            $th=substr($tahun,-2); // Ambil 2 digit terakhir tahun masuk
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
            
            // Format: TTFFPPJS (T=Tahun, F=Fakultas, P=Prodi, J=Jenjang, S=Status)

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
            $konfirmasi = KonfirmasiPembayaran::findOrFail($id);
            $konfirmasi->update(['verified' => 11]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function reminder(Request $request, $id)
    {
        try {
            $konfirmasi = KonfirmasiPembayaran::with('transaksi.neomahasiswa')->findOrFail($id);
            $nomorWA = $konfirmasi->transaksi->neomahasiswa->no_wa;
            $pesan = urlencode($request->pesan);
            
            // Ganti dengan API WhatsApp Anda
            $waURL = "https://api.whatsapp.com/send?phone=$nomorWA&text=$pesan";
            
            return response()->json(['success' => true, 'redirect' => $waURL]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function dashboard()
    {
        $tanggal = now()->format('d F Y');
        $tanggal1 = now()->format('Y-m-d');
        
        return view('keuangan.dashboard', [
            'pendaftarHariIni' => Neomahasiswa::whereDate('created_at', today())->count(),
            'totalPendaftar' => Neomahasiswa::count(),
            'totalPendaftarOffline' => Neomahasiswa::whereNotNull('id_operator')->count(),
            'totalPendaftarOnline' => Neomahasiswa::whereNull('id_operator')->count(),
            'tanggal' => $tanggal,
            'tanggal1' => $tanggal1,
            'data2024' => $this->getDataTahun(2024),
            'data2025' => $this->getDataTahun(2025)
        ]);
    }

    private function getDataTahun($tahun)
    {
        return Neomahasiswa::whereYear('created_at', $tahun)
            ->selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->pluck('total');
    }

    public function indexKonfirmasi()
    {
        $tahunMasuk = Transaksi::selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('keuangan.konfirmasi', [
            'tahunMasuk' => $tahunMasuk,
            'tahunAktif' => now()->year
        ]);
    }

    public function getDataKonfirmasi(Request $request)
    {
        try {
            $query = KonfirmasiPembayaran::query()
                ->join('transaksis', 'transaksis.id', '=', 'konfirmasi_pembayarans.transaksi_id')
                ->join('neomahasiswas', 'neomahasiswas.pin', '=', 'transaksis.pin')
                ->select(
                    'konfirmasi_pembayarans.transaksi_id',
                    'transaksis.pin',
                    'neomahasiswas.nomor_pendaftaran',
                    'neomahasiswas.nama_mahasiswa',
                    'konfirmasi_pembayarans.tanggal_bayar',
                    'konfirmasi_pembayarans.nama_rekening_pengirim',
                    'konfirmasi_pembayarans.total_bayar',
                    'konfirmasi_pembayarans.verified',
                    'konfirmasi_pembayarans.bukti_bayar'
                );

            // Filter Tahun
            if ($request->tahun) {
                $query->whereYear('konfirmasi_pembayarans.tanggal_bayar', $request->tahun);
            }

            // Hitung total records
            $totalRecords = KonfirmasiPembayaran::count();
            $filteredRecords = $query->count();

            // Pagination
            $data = $query->skip($request->start)
                ->take($request->length)
                ->get()
                ->map(function($item, $index) use ($request) {
                    return [
                        'no' => $request->start + $index + 1,
                        'id' => $item->transaksi_id,
                        'pin' => $item->pin,
                        'nomor_pendaftaran' => $item->nomor_pendaftaran,
                        'nama_mahasiswa' => $item->nama_mahasiswa,
                        'tanggal_bayar' => \Carbon\Carbon::parse($item->tanggal_bayar)->format('d/m/Y'),
                        'nama_rekening_pengirim' => $item->nama_rekening_pengirim,
                        'total_bayar' => number_format($item->total_bayar, 0, ',', '.'),
                        'lunas' => $item->verified == 11 ? 'Verifikasi' : 'Belum Verifikasi',
                        'bukti_bayar' => asset('images/pmb/' . $item->bukti_bayar),
                    ];
                });

            return response()->json([
                'draw' => (int) $request->draw,
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            Log::error('Error getDataKonfirmasi: '.$e->getMessage());
            return response()->json([
                "draw" => (int) $request->draw,
                "error" => "Terjadi kesalahan server"
            ], 500);
        }
    }

}
