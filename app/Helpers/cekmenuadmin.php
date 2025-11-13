<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use App\Models\MenuAdmin;

use DB;

class cekmenuadmin


{
    public static function hasilcek($id_user,$menu){

        $check           = DB::table('menu_admins')->select('*')
        ->where('id_user','=',$id_user)
        ->where('menu','=',$menu)
        ->first();
        
        if($check){
            return true;
        }
        else{
            return false;
        }
    }

    public static function hasilAbsen($status){

       
        if($status == 'P'){
            return "Hadir";
        }
        else{
            return "Alpa";
        }
    }
}
