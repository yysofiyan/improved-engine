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
            ->whereBetween('created_at', ['2024-03-01', '2024-12-31'])
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

    /**
     * Mendapatkan jumlah mahasiswa yang lulus seleksi PMB
     */
    public static function getLulusFakultas($kode)
    {
        $pendaftar = Neomahasiswa::select(DB::raw('*'))
            ->join('quiz_murid', 'quiz_murid.murid_id', '=', 'neomahasiswas.id')
            ->whereIn('kodeprodi_satu', $kode)
            ->whereYear('neomahasiswas.created_at', 2025) // Filter tahun 2025
            ->where('quiz_murid.status', '=', '1')
            ->count();
            
        return $pendaftar;        
    }

    public static function getLulusPersen($kode)
    {
        if(!is_array($kode) || empty($kode)) {
            return '0.00%';
        }

        // Hitung total kapasitas dari config
        $totalKapasitas = collect($kode)->sum(function ($prodiId) {
            return config("pmb.kapasitas_prodi.$prodiId", 0);
        });

        // Hitung jumlah pendaftar TAHUN 2025
        $pendaftar = Neomahasiswa::query()
            ->join('quiz_murid', 'quiz_murid.murid_id', '=', 'neomahasiswas.id')
            ->whereIn('kodeprodi_satu', $kode)
            ->whereYear('created_at', 2025) // Filter tahun 2025
            ->where('quiz_murid.status', '1')
            ->count();

        $totalKapasitas = $totalKapasitas > 0 ? $totalKapasitas : 1;
        
        $persen = ($pendaftar / $totalKapasitas) * 100;
        
        return number_format($persen, 2) . '%';
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
            ->whereDate('created_at', '>=', $tanggalAwal) // Mulai 3 Marer 2024
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
    /**
     * Mendapatkan jumlah pendaftar hari ini untuk tahun 2025
     * 
     * @return int Jumlah pendaftar hari ini
     */
    public static function getDaftarHariIni() {
        return Neomahasiswa::whereDate('created_at', today())
            ->whereYear('created_at', 2025)
            ->count();
    }

    /**
     * Mendapatkan statistik pendaftaran per bulan untuk tahun 2025
     * 
     * @return array Array berisi jumlah pendaftar per bulan (Jan-Des)
     */
    public static function getStatistikPendaftaran2025()
    {
        $data = [];
        for ($month = 1; $month <= 12; $month++) {
            $data[] = Neomahasiswa::whereYear('created_at', 2025)
                ->whereMonth('created_at', $month)
                ->count();
        }
        return $data;
    }

    /**
     * Mendapatkan distribusi pendaftar per fakultas untuk tahun 2025
     * 
     * @return array Array berisi jumlah pendaftar per fakultas
     */
    public static function getDistribusiFakultas2025()
    {
        return [
            self::getLulusFakultas(['5b3ff355-1e20-4c1d-8b47-e559b6991036','f48cbc83-b3c6-4e66-9e68-209b52a275e4','eca49026-745e-451c-8121-bfc81d4e9fe4','adc77657-6904-4aa3-bc6e-40565bdc27bf','5f69f4f0-ebbb-4638-8df1-4632b05326de','2cadf663-1d4c-4fd4-9457-6fc2b50bd1b3','8813','84102']), // FKIP
            self::getLulusFakultas(['1ff84166-cc64-48aa-a38e-3c6a952a8b90','08b181bc-1860-4c7e-8bda-ef4fbd59d869','8c56f8a8-8f27-4e2f-8376-a433b2862f36']), // FEB
            self::getLulusFakultas(['d778be91-7bc7-4757-bd22-9038fa8adeb4','e992676b-23e6-49e1-a2f6-81f90876b7da']), // FISIP
            self::getLulusFakultas(['1daad851-b93f-4860-b37d-ddae33f1b860']), // FIB
            self::getLulusFakultas(['aaf15037-cd57-4743-a5f8-fd30840f221e','a74fffa1-43f1-4ab5-baca-dfbd08b22d20']), // FTI
            self::getLulusFakultas(['303e6a30-c87a-4f70-8431-8ccc03b058f4','b41b8150-b1e6-4c63-9455-26f91174933c','93366fa0-45df-457c-a723-01b78226ad34']), // FIKES
            self::getLulusFakultas(['1','2']) // STAI
        ];
    }

    /**
     * Mendapatkan label bulan dalam format singkat (3 huruf)
     * 
     * @return array Array berisi nama bulan singkat (Jan-Des)
     */
    public static function getBulanLabels()
    {
        return ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
    }

    /**
     * Mendapatkan nama fakultas untuk tahun 2025
     * 
     * @return array Array berisi nama fakultas
     */
    public static function getNamaFakultas2025()
    {
        return [
            'FKIP', 'FEB', 'FISIP', 
            'FIB', 'FTI', 'FIKES', 'STAI SAS'
        ];
    }
public static function getPendaftarHarian2024() {
    return Neomahasiswa::whereDate('created_at', today())
        ->whereYear('created_at', 2024)
        ->count();
}
public static function getPendaftarHarian2025() {
    return Neomahasiswa::whereDate('created_at', today())
        ->whereYear('created_at', 2025)
        ->count();
}

    /**
     * Mendapatkan statistik pendaftaran tahun 2024 per bulan
     * 
     * @return array Array berisi jumlah pendaftar per bulan (Jan-Des)
     */
    public static function getStatistikPendaftaran2024()
    {
        $data = [];
        for ($month = 1; $month <= 12; $month++) {
            $data[] = Neomahasiswa::whereYear('created_at', 2024)
                ->whereMonth('created_at', $month)
                ->count();
        }
        return $data;
    }

}

