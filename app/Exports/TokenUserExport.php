<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class TokenUserExport implements FromCollection, WithHeadings
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
        ->select('users.name','ujian_users.token')

        ->join('ujians', 'ujian_users.ujian_id', '=', 'ujians.id')
        ->join('users', 'ujian_users.user_id', '=', 'users.id')

        ->where('ujians.id','=',$this->id)
        ->orderBy('users.name')
        ->get();
    }
    public function headings(): array
    {
        return ["Nama", "Token"];
    }
}
