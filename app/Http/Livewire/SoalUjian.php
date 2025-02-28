<?php

namespace App\Http\Livewire;

use App\Models\Quiz;
use Livewire\Component;
use App\Models\Neomahasiswa;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\AkademikHelpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SoalUjian extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $pilih;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function paginationView()
    {
        return 'vendor.pagination.bootstrap-5';
    }

    public function mount()
    {
        $quiz = Quiz::find('1');
        $soal = $quiz->soal;
        foreach ($soal as $key => $value) {
            $this->pilih[$key] = '';
        }
    }

    public function pilih($pilihan, $page)
    {
        $this->pilih[$page - 1] = $pilihan;
    }

    public function selesai()
    {
        $quiz = Quiz::find('1');
        $soal = $quiz->soal;
        $jawaban = $soal->map(function ($item) {
            return $item->jawaban;
        });

        $benar = 0;
        for ($a = 0; $a < count($this->pilih); $a++) {
            for ($b = 0; $b < count($jawaban); $b++) {
                if ($this->pilih[$a] == $jawaban[$a]) {
                    $benar = $benar + 1;
                    break;
                } else {
                    $benar = $benar;
                    break;
                }
            }
        }
        $hasil = $benar / count($jawaban) * 100;
        
        DB::table('quiz_murid')->insert([
            'quiz_id' => '1',
            'murid_id' => session('id'),
            'status' => '0',
            'benar' => '0',
            'nilai' => '0'
        ]);

        DB::table('quiz_murid')->where('quiz_id', '1')->where('murid_id', session('id'))->update([
            'benar' => $benar,
            'nilai' => $hasil,
            'status' => '1',
            'ket' => 'Lulus - Sistem Online',
        ]);

        for ($a=0; $a < count($soal); $a++) {
            DB::table('quiz_jawaban_murid')->insert([
                'soal_id' => $soal[$a]->id,
                'murid_id' => session('id'),
                'jawaban_murid' => $this->pilih[$a]
            ]);
        }
        // Ambil data formulir berdasarkan ID session
        $formulir = Neomahasiswa::find(session('id'));
        
        // Siapkan header untuk PDF dengan path gambar terbaru
        $headers = [
            'HEADER_LOGO' => AkademikHelpers::public_path("images/header_pmb2025.png"),
            'TANDATANGAN' => AkademikHelpers::public_path("images/tanda_tangan2025.png"),
        ];

        // Cari data prodi berdasarkan kode prodi dari formulir
        $prodi = DB::table('pe3_prodi')
            ->where('config', $formulir->kodeprodi_satu)
            ->first();

        // Validasi data prodi
        if(!$prodi) {
            throw new Exception('Data prodi tidak ditemukan');
        }

        

        // Menentukan nama view berdasarkan jenjang program studi
        $viewName = ($prodi->nama_jenjang == 'S-2') ? 'report.surat_s2' : 'report.surat_s1';
        
        // Membuat PDF dengan view yang dipilih dan data yang diperlukan
        $pdf = Pdf::loadView($viewName, ['headers' => $headers, 'formulir' => $formulir, 'prodi' => $prodi, 'tanggal' => AkademikHelpers::tanggal('d F Y')]);
        
        // Mendapatkan konten PDF yang telah di-generate
        $content = $pdf->download()->getOriginalContent();
        
        // Menyimpan file PDF ke storage dengan nama berdasarkan nomor pendaftaran
        Storage::put('public/exported/pdf/'.$formulir->nomor_pendaftaran.'.pdf',$content);
        
        // Mengembalikan response JSON bahwa proses berhasil
        return response()->json(['status'=>'200','success'=>'Data Sukses di Simpan']);
    }

    public function render()
    {
        $soal=DB::table('quiz_murid')
        ->where('murid_id',session('id'))
        ->count();
        if ($soal>0)
        {
            return view('livewire.no-soal');
        }else
        {
            $quiz = Quiz::find('1');
            $soal = $quiz->soal()->paginate(1);
            return view('livewire.soal-ujian', compact('soal'));
        }
        
    }
}
