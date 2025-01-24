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
        $formulir = Neomahasiswa::find(session('id'));
        $headers=[
            'HEADER_LOGO'=>AkademikHelpers::public_path("images/header_pmb.png"),
            'TANDATANGAN'=>AkademikHelpers::public_path("images/tanda_tangan.png"),
            'qrunsap1'=>AkademikHelpers::public_path("images/qrunsap1.png")
        ];
        $prodi=DB::table('pe3_prodi')
        ->where('config','=',$formulir->kodeprodi_satu)
        ->first();

        

        $pdf = Pdf::loadView('report.surat',['headers' => $headers,'formulir'=>$formulir,'prodi'=>$prodi,'tanggal'=>AkademikHelpers::tanggal('d F Y')]);
        $content = $pdf->download()->getOriginalContent();
        Storage::put('public/exported/pdf/'.$formulir->nomor_pendaftaran.'.pdf',$content);
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
