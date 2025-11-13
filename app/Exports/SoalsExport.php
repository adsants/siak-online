<?php

namespace App\Exports;

use App\Models\SoalAngkaHilang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class SoalsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //return SoalAngkaHilang::all();
        return DB::table('soal_angka_hilangs')
        ->select('jenis_soal','data_soal','jawaban_benar')
        ->orderBy('id','desc')
        ->get();

    }
    public function headings(): array
    {
        return ["Jenis Soal","Data Soal","Jawaban Benar"];
    }
}
