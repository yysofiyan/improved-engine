<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\KelasKuliah;
use App\Models\Neomahasiswa;
use Illuminate\Support\Facades\DB;

class AkademikHelpers {
    public static function kirimWAS($phone,$message)
    {
        $token = "eHzE3NaxgfYTD3jgNFRzfSof9U2dsRWTLs6x4H2YMV1SRwHQkP";
                                
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://app.ruangwa.id/api/send_message',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'token='.$token.'&number='.$phone.'&message='.$message,
            ));
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
            
    }

    public static function kirimWA($phone,$message)
    {
       
        $datasend = [
            "api_key" => "OU6TII4TCY2IGPMD", 
            "number_key" => "ickSzuUJuSwtfFxb",
            "phone_no" => $phone,
            "message" => $message,
        ];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.watzap.id/v1/send_message',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>json_encode($datasend),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
            ));
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
            
    }


    public static function kirimWAImage($phone,$message)
    {
        $dataSending = Array();
        $dataSending["api_key"] = "OU6TII4TCY2IGPMD";
        $dataSending["number_key"] = "ickSzuUJuSwtfFxb";
        $dataSending["phone_no"] = $phone;
        $dataSending["message"] = $message;
        $dataSending["url"] = "https://yuyunhidayat.id/selamat_lulus.jpeg";
        $dataSending["separate_caption"]="0";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.watzap.id/v1/send_image_url',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>json_encode($dataSending),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: PHPSESSID=6b74sv2ov0iov2jhteeq28kdtd; X_URL_PATH=aHR0cHM6Ly9jb3JlLndhdHphcC5pZC98fHx8fHN1c3VrYWNhbmc%3D'
            ),
            ));
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
            
    }

    public static function kirimWAButton($phone)
    {
        $token = "eHzE3NaxgfYTD3jgNFRzfSof9U2dsRWTLs6x4H2YMV1SRwHQkP";
        $text= "Testing kirim button";
        $buttonlabel= "Google,Facebook";
        $buttonurl= "https://www.google.com,https://www.facebook.com";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://app.ruangwa.id/api/send_buttonurl',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'token='.$token.'&number='.$phone.'&text='.$text.'&buttonlabel='.$buttonlabel.'&buttonurl='.$buttonurl,
            CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                ),
            ));
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            $response = curl_exec($curl);
            curl_close($curl);
                                
        
            return $response;
            
    }
    public static function getSemester()
    {
        $variabel=substr(env('SMT'), -1);
        if ($variabel='1')
        {
        $semester='Ganjil';
        }else if ($variabel='2')
        {
        $semester='Genap';
        }else {
        $semester='Pendek';
        }
        $tahun=substr(env('SMT'),0,4);
        return 'Tahun '. $tahun . ' Semester : '. $semester;
    }

    public static function gotSemester($id)
    {
        $variabel=substr($id, -1);
        if ($variabel='1')
        {
        $semester='Ganjil';
        }else if ($variabel='2')
        {
        $semester='Genap';
        }else {
        $semester='Pendek';
        }
        $tahun=(int) substr($id,0,4);
        return $tahun . '/'. $tahun + 1 .' Semester - '. $semester;
    }

    public static function getFakultas($kode)
    {
        $fakultas=DB::table('pe3_prodi')
        ->where('config','=',$kode)
        ->first();
        if (is_null($fakultas))
        {
            return 'Fakultas tidak ditemukan';
        }else
        {
            return $fakultas->kode_fakultas;
        }
        
    }

    public static function getUser($kode)
    {
        $fakultas=DB::table('users')
        ->where('id','=',$kode)
        ->first();
        if (is_null($fakultas))
        {
            return 'ONLINE';
        }else
        {
            return $fakultas->name;
        }
        
    }
    public static function getDaftar($kode)
    {
        $tanggal=Carbon::parse(Carbon::now())->format('Y-m-d');
        $tanggal2=Carbon::createFromFormat('Y-m-d', '2025-02-01');
        $pendaftar=Neomahasiswa::select(DB::raw('*'))
        ->where('kodeprodi_satu','=',$kode)
        ->where('pin','<>','')
        ->whereDate('created_at','<=',$tanggal)
        ->whereDate('created_at','>=',$tanggal2)
        ->count();        
        return $pendaftar;        
    }

    public static function getLulus($kode)
    {
        $tanggal=Carbon::parse(Carbon::now())->format('Y-m-d');
        $tanggal2=Carbon::createFromFormat('Y-m-d', '2025-02-01');;
        $pendaftar=Neomahasiswa::select(DB::raw('*'))
        ->join('quiz_murid', 'quiz_murid.murid_id', '=', 'neomahasiswas.id')
        ->where('kodeprodi_satu','=',$kode)
        ->whereDate('created_at','<=',$tanggal)
        ->whereDate('created_at','>=',$tanggal2)
        ->where('quiz_murid.status','=','1')
        ->count();        

        return $pendaftar;        
    }

    public static function getDaftar2($kode)
    {
        return Neomahasiswa::where('kodeprodi_satu', $kode)
            ->where('pin', '<>', '')
            ->whereBetween('created_at', ['2024-03-01', '2025-02-28'])
            ->count();
    }

    public static function getLulus2($kode)
    {
        $tanggal = Carbon::parse(Carbon::now())->format('Y-m-d');
        $tanggal2 = Carbon::createFromFormat('Y-m-d', '2024-03-01');
        $pendaftar = Neomahasiswa::select(DB::raw('*'))
            ->join('quiz_murid', 'quiz_murid.murid_id', '=', 'neomahasiswas.id')
            ->where('kodeprodi_satu', '=', $kode)
            ->whereDate('created_at', '<=', $tanggal)
            ->whereDate('created_at', '>=', $tanggal2)
            ->where('quiz_murid.status', '=', '1')
            ->count();

        return $pendaftar;
    }

    public static function getPastDaftar($kode)
    {
        $tanggal=Carbon::parse(Carbon::now()->subYear())->format('Y-m-d');
        $pendaftar=DB::connection('mysql2')->table('pe3_formulir_pendaftaran')->select(DB::raw('*'))
        ->join('pe3_prodi', 'pe3_prodi.id', '=', 'pe3_formulir_pendaftaran.prodi_id')
        ->where('pe3_formulir_pendaftaran.prodi_id','=',$kode)
        ->whereDate('pe3_formulir_pendaftaran.created_at','<=',$tanggal)
        ->count();        
        return $pendaftar;        
    }

    public static function getPastLulus($kode)
    {
        $tanggal=Carbon::parse(Carbon::now()->subYear())->format('Y-m-d');
        $pendaftar=DB::connection('mysql2')->table('pe3_formulir_pendaftaran')->select(DB::raw('*'))
        ->join('pe3_prodi', 'pe3_prodi.id', '=', 'pe3_formulir_pendaftaran.prodi_id')
        ->join('pe3_nilai_ujian_pmb', 'pe3_nilai_ujian_pmb.user_id', '=', 'pe3_formulir_pendaftaran.user_id')
        ->where('pe3_formulir_pendaftaran.prodi_id','=',$kode)
        ->where('pe3_nilai_ujian_pmb.ket_lulus','=','1')
        ->whereDate('pe3_nilai_ujian_pmb.created_at','<=',$tanggal)
        ->count();        

        return $pendaftar;        
    }

    public static function getLulusFakultas($kode)
    {
        $pendaftar=Neomahasiswa::select(DB::raw('*'))
        ->join('quiz_murid', 'quiz_murid.murid_id', '=', 'neomahasiswas.id')
        ->whereIn('kodeprodi_satu',$kode)
        ->where('quiz_murid.status','=','1')
        ->count();        

        return $pendaftar;        
    }

    public static function getLulusPersen($kode)
    {
        $pendaftar=Neomahasiswa::select(DB::raw('*'))
        ->join('quiz_murid', 'quiz_murid.murid_id', '=', 'neomahasiswas.id')
        ->whereIn('kodeprodi_satu',$kode)
        ->where('quiz_murid.status','=','1')
        ->count();        
        $persen=($pendaftar/250)*100;

        return number_format($persen,0).'%';        
    }

    /**
     * Mendapatkan total jumlah pendaftar mahasiswa baru
     * 
     * Fungsi ini menghitung total pendaftar mahasiswa baru dengan kriteria:
     * - Memiliki PIN yang tidak kosong
     * - Tanggal pendaftaran antara 1 Februari 2025 hingga hari ini
     *
     * @return int Jumlah total pendaftar
     */
    public static function getTotalDaftar()
    {
        // Mendapatkan tanggal hari ini dalam format Y-m-d
        $tanggalSekarang = Carbon::now()->format('Y-m-d');
        
        // Menetapkan tanggal awal periode pendaftaran
        $tanggalAwal = Carbon::createFromFormat('Y-m-d', '2025-02-01');
        
        // Menghitung jumlah pendaftar dengan kriteria yang ditentukan
        $totalPendaftar = Neomahasiswa::query()
            ->whereNotNull('pin') // Hanya yang memiliki PIN
            ->whereDate('created_at', '<=', $tanggalSekarang) // Sampai hari ini
            ->whereDate('created_at', '>=', $tanggalAwal) // Mulai 1 Februari 2025
            ->count();
            
        return $totalPendaftar;
    }

    /**
     * Mendapatkan total jumlah pendaftar mahasiswa baru tahun 2024
     * 
     * Fungsi ini menghitung total pendaftar mahasiswa baru untuk data perbandingan tahun 2024 dengan kriteria:
     * - Memiliki PIN yang tidak kosong
     * - Tanggal pendaftaran antara 1 Februari 2024 hingga 31 Desember 2024
     *
     * @return int Jumlah total pendaftar tahun 2024
     */
    public static function getTotalDaftar2()
    {
        // Menetapkan tanggal akhir periode pendaftaran tahun 2024
        $tanggalAkhir = Carbon::createFromFormat('Y-m-d', '2024-12-31');
        
        // Menetapkan tanggal awal periode pendaftaran tahun 2024
        $tanggalAwal = Carbon::createFromFormat('Y-m-d', '2024-03-01');
        
        // Menghitung jumlah pendaftar dengan kriteria yang ditentukan
        $totalPendaftar = Neomahasiswa::query()
            ->whereNotNull('pin') // Hanya yang memiliki PIN
            ->whereDate('created_at', '<=', $tanggalAkhir) // Sampai 31 Desember 2024
            ->whereDate('created_at', '>=', $tanggalAwal) // Mulai 1 Februari 2024
            ->count();
            
        return $totalPendaftar;
    }

    public static function getTotalLulus()
    {
        $tanggal=Carbon::parse(Carbon::now())->format('Y-m-d');
        $tanggal2=Carbon::createFromFormat('Y-m-d', '2025-02-01');
        $pendaftar=Neomahasiswa::select(DB::raw('neomahasiswas.id,pin, nama_mahasiswa,is_aktif,handphone,nama_prodi,nama_jenjang,nomor_pendaftaran,nilai'))
        ->join('pe3_prodi','neomahasiswas.kodeprodi_satu','=','pe3_prodi.config')
        ->join('quiz_murid','neomahasiswas.id','=','quiz_murid.murid_id')
        ->whereDate('created_at','<=',$tanggal)
        ->whereDate('created_at','>=',$tanggal2)
        ->count();  
        
        return $pendaftar;        
    }

    public static function getTotalLulus2()
    {
        // Menetapkan tanggal awal dan akhir periode tahun 2024
        $tanggalAwal = Carbon::createFromFormat('Y-m-d', '2024-03-01');
        $tanggalAkhir = Carbon::createFromFormat('Y-m-d', '2024-12-31');
        
        // Menghitung jumlah mahasiswa yang lulus pada tahun 2024
        $pendaftar = Neomahasiswa::select(DB::raw('neomahasiswas.id,pin, nama_mahasiswa,is_aktif,handphone,nama_prodi,nama_jenjang,nomor_pendaftaran,nilai'))
            ->join('pe3_prodi', 'neomahasiswas.kodeprodi_satu', '=', 'pe3_prodi.config')
            ->join('quiz_murid', 'neomahasiswas.id', '=', 'quiz_murid.murid_id')
            ->whereDate('created_at', '>=', $tanggalAwal)
            ->whereDate('created_at', '<=', $tanggalAkhir)
            ->count();
            
        return $pendaftar;        
    }

    public static function getPastTotalDaftar()
    {
        $tanggal=Carbon::parse(Carbon::now()->subYear())->format('Y-m-d');
        $pendaftar=DB::connection('mysql2')->table('pe3_formulir_pendaftaran')->select(DB::raw('*'))
        ->join('pe3_prodi', 'pe3_prodi.id', '=', 'pe3_formulir_pendaftaran.prodi_id')
        ->where('pe3_formulir_pendaftaran.prodi_id','<>','24')
        ->whereDate('pe3_formulir_pendaftaran.created_at','<=',$tanggal)
        ->count();        
        return $pendaftar;        
    }

    public static function getPastTotalLulus()
    {
        $tanggal=Carbon::parse(Carbon::now()->subYear())->format('Y-m-d');
        $pendaftar=DB::connection('mysql2')->table('pe3_formulir_pendaftaran')->select(DB::raw('b.nama_prodi,b.nama_jenjang, nama_mhs,ket_lulus'))
        ->join('pe3_prodi', 'pe3_prodi.id', '=', 'pe3_formulir_pendaftaran.prodi_id')
        ->join('pe3_nilai_ujian_pmb', 'pe3_nilai_ujian_pmb.user_id', '=', 'pe3_formulir_pendaftaran.user_id')
        ->where('pe3_formulir_pendaftaran.prodi_id','<>','24')
        ->whereDate('pe3_nilai_ujian_pmb.created_at','<=',$tanggal)
        ->count();
        
        return $pendaftar;        
    }

     public static function getTotalPin($kode)
    {
        $tanggal=Carbon::parse(Carbon::now())->format('Y-m-d');
        $tanggal2=Carbon::createFromFormat('Y-m-d', '2025-02-01');
        $pendaftar=Neomahasiswa::select(DB::raw('*'))
        ->where('kodeprodi_satu','=',$kode)
        ->where('is_aktif','=','1')
        ->whereDate('created_at','<=',$tanggal)
        ->whereDate('created_at','>=',$tanggal2)
        ->count();        
        return $pendaftar;        
    }

    public static function getTotalPin2($kode)
    {
        $tanggalAwal = Carbon::createFromFormat('Y-m-d', '2024-03-01');
        $tanggalAkhir = Carbon::createFromFormat('Y-m-d', '2024-12-31');
        
        $pendaftar = Neomahasiswa::select(DB::raw('*'))
            ->where('kodeprodi_satu', '=', $kode)
            ->where('is_aktif', '=', '1')
            ->whereDate('created_at', '>=', $tanggalAwal)
            ->whereDate('created_at', '<=', $tanggalAkhir)
            ->count();
            
        return $pendaftar;        
    }

    public static function getJumlahPin()
    {
        $tanggal=Carbon::parse(Carbon::now())->format('Y-m-d');
        $tanggal2=Carbon::createFromFormat('Y-m-d', '2025-02-01');
        $pendaftar=Neomahasiswa::select(DB::raw('*'))
        ->where('is_aktif','=','1')
        ->whereDate('created_at','<=',$tanggal)
        ->whereDate('created_at','>=',$tanggal2)
        ->count();        
        return $pendaftar;        
    }

    public static function getJumlahPin2()
    {
        $tanggalAwal = Carbon::createFromFormat('Y-m-d', '2024-03-01');
        $tanggalAkhir = Carbon::createFromFormat('Y-m-d', '2024-12-31');
        $pendaftar = Neomahasiswa::select(DB::raw('*'))
            ->where('is_aktif', '=', '1')
            ->whereDate('created_at', '>=', $tanggalAwal)
            ->whereDate('created_at', '<=', $tanggalAkhir)
            ->count();
        return $pendaftar;
    }

    public static function getFakultasNama($kode)
    {
        $fakultas=DB::table('pe3_fakultas')
        ->where('kode_fakultas','=',$kode)
        ->first();
        if (is_null($fakultas))
        {
            return 'Tidak Ditemukan !';
        }else
        {
            return $fakultas->nama_fakultas;
        }
    }

    public static function getSekolahKab($kode)
    {
        $fakultas=DB::table('sekolah')
        ->where('sekolah','=',$kode)
        ->first();
        return $fakultas->kabupaten_kota;
    }

    public static function public_path($path = null)
    {
        return rtrim(app()->basePath('public/' . $path), '/');
    }
    public static function exported_path($folder='/')
    {
        return app()->basePath("public/exported$folder");
    }

    public static function tanggal($format, $date=null) {
        Carbon::setLocale(app()->getLocale());
        if ($date == null){
            $tanggal=Carbon::parse(Carbon::now())->format($format);
        }else{
            $tanggal = Carbon::parse($date)->format($format);
        }
        $result = str_replace([
                                'Sunday',
                                'Monday',
                                'Tuesday',
                                'Wednesday',
                                'Thursday',
                                'Friday',
                                'Saturday'
                            ],
                            [
                                'Minggu',
                                'Senin',
                                'Selasa',
                                'Rabu',
                                'Kamis',
                                'Jumat',
                                'Sabtu'
                            ],
                            $tanggal);

        return str_replace([
                            'January',
                            'February',
                            'March',
                            'April',
                            'May',
                            'June',
                            'July',
                            'August',
                            'September',
                            'October',
                            'November' ,
                            'December'
                        ],
                        [
                            'Januari',
                            'Februari',
                            'Maret',
                            'April',
                            'Mei',
                            'Juni',
                            'Juli',
                            'Agustus',
                            'September',
                            'Oktober',
                            'November',
                            'Desember'
                        ], $result);
    } 
    
    public static function tanggal1($format, $date=null) {
        Carbon::setLocale(app()->getLocale());
        if ($date == null){
            $tanggal=Carbon::parse(Carbon::now()->subyear())->format($format);
        }else{
            $tanggal = Carbon::parse($date)->format($format);
        }
        $result = str_replace([
                                'Sunday',
                                'Monday',
                                'Tuesday',
                                'Wednesday',
                                'Thursday',
                                'Friday',
                                'Saturday'
                            ],
                            [
                                'Minggu',
                                'Senin',
                                'Selasa',
                                'Rabu',
                                'Kamis',
                                'Jumat',
                                'Sabtu'
                            ],
                            $tanggal);

        return str_replace([
                            'January',
                            'February',
                            'March',
                            'April',
                            'May',
                            'June',
                            'July',
                            'August',
                            'September',
                            'October',
                            'November' ,
                            'December'
                        ],
                        [
                            'Januari',
                            'Februari',
                            'Maret',
                            'April',
                            'Mei',
                            'Juni',
                            'Juli',
                            'Agustus',
                            'September',
                            'Oktober',
                            'November',
                            'Desember'
                        ], $result);
    } 

    public static function tanggal2($format, $date=null) {
        Carbon::setLocale(app()->getLocale());
        if ($date == null){
            $tanggal=Carbon::parse(Carbon::now()->subyear(2))->format($format);
        }else{
            $tanggal = Carbon::parse($date)->format($format);
        }
        $result = str_replace([
                                'Sunday',
                                'Monday',
                                'Tuesday',
                                'Wednesday',
                                'Thursday',
                                'Friday',
                                'Saturday'
                            ],
                            [
                                'Minggu',
                                'Senin',
                                'Selasa',
                                'Rabu',
                                'Kamis',
                                'Jumat',
                                'Sabtu'
                            ],
                            $tanggal);

        return str_replace([
                            'January',
                            'February',
                            'March',
                            'April',
                            'May',
                            'June',
                            'July',
                            'August',
                            'September',
                            'October',
                            'November' ,
                            'December'
                        ],
                        [
                            'Januari',
                            'Februari',
                            'Maret',
                            'April',
                            'Mei',
                            'Juni',
                            'Juli',
                            'Agustus',
                            'September',
                            'Oktober',
                            'November',
                            'Desember'
                        ], $result);
    } 

    public static function getDaftarHariIni() {
        return Neomahasiswa::whereDate('created_at', today())
            ->whereYear('created_at', 2025)
            ->count();
    }

}
