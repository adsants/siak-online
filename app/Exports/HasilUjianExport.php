<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class HasilUjianExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $id;

    function __construct($id) {
        $this->id = $id;
    }
    
    public function collection()
    {
        return DB::table('ujian_users')
        ->select('users.name'
        ,'ujian_users.jawaban_benar'
        ,'ujian_users.jawaban_salah'
        ,'ujian_users.nilai'
        )
        ->selectRaw('DATE_FORMAT(ujian_users.start_date, "%d-%m-%Y %H:%i") as start_date')
        ->selectRaw('DATE_FORMAT(ujian_users.finish_date, "%d-%m-%Y %H:%i") as finish_date')

        ->join('ujians', 'ujian_users.ujian_id', '=', 'ujians.id')
        ->join('users', 'ujian_users.user_id', '=', 'users.id')

        ->where('ujians.id','=',$this->id)
        ->orderBy('users.name')
        ->get();
    }
    public function headings(): array
    {
        return ["Nama", "Jumlah Jawaban Benar", "Jumlah Jawaban Salah", "Nilai", "Waktu Mulai Ujian", "Waktu Selesai Ujian"];
    }
}
