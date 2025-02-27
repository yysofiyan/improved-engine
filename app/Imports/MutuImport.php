<?php

namespace App\Imports;

use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class MutuImport implements
    ToCollection,
    WithHeadingRow,
    WithValidation,
    SkipsOnError,
    SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //
    }

    public function headingRow(): int
    {
        return 3;
    }

    public function rules(): array
    {
        return [
            '*.nim' => 'required',
            '*.nilai_kehadiran' => 'required|numeric|min:0|max:100',
            '*.nilai_tugas' => 'required|numeric|min:0|max:100',
            '*.nilai_uts' => 'required|numeric|min:0|max:100',
            '*.nilai_uas' => 'required|numeric|min:0|max:100',
            '*.nilai_akhir' => 'required|numeric|min:0|max:100',
        ];
    }
}
