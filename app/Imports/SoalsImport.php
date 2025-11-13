<?php

namespace App\Imports;
  
use App\Models\SoalChoice;
use App\Models\SoalChoiceJawaban;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;  
use Maatwebsite\Excel\Concerns\ToCollection;
use DB;

class SoalsImport implements ToCollection
{
    public function collection(Collection $rows)
    {

        $i=1;
        $oke= "";
        foreach ($rows as $row) 
        {
            if($i > 1){
                $newId = SoalChoice::insertGetId([
                    'soal_choice' => $row[0],
                ]);   
                //dd($oke );

                if($row[1] != ''){
                    SoalChoiceJawaban::insertGetId([
                        'jawaban' => $row[1],
                        'status_jawaban' => '1',
                        'id_soal_choice' => $newId,
                    ]);   
                }

                if($row[2] != ''){
                    SoalChoiceJawaban::insertGetId([
                        'jawaban' => $row[2],
                        'status_jawaban' => '0',
                        'id_soal_choice' => $newId,
                    ]);   
                }

                if($row[3] != ''){
                    SoalChoiceJawaban::insertGetId([
                        'jawaban' => $row[3],
                        'status_jawaban' => '0',
                        'id_soal_choice' => $newId,
                    ]);   
                }

                if($row[4] != ''){
                    SoalChoiceJawaban::insertGetId([
                        'jawaban' => $row[4],
                        'status_jawaban' => '0',
                        'id_soal_choice' => $newId,
                    ]);   
                }

                if($row[5] != ''){
                    SoalChoiceJawaban::insertGetId([
                        'jawaban' => $row[5],
                        'status_jawaban' => '0',
                        'id_soal_choice' => $newId,
                    ]);   
                }

            }
            
               
            $i++;
        }   

        
    }
}

