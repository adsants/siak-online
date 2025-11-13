<?php
 
namespace App\Http\Controllers;
use Redirect;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Response as FacadeResponse;

use View;
use File;
use DB;
 
class JsonController extends Controller
{
    public function download(Request $request)
    {
      
        $dataSoals           =  json_encode(DB::table('soal_gambars')->select('id_soal','soal','status')->get());
        
        $fileName = 'soal_gambar.json';
        File::put(public_path('/upload/json/'.$fileName),$dataSoals);
        return FacadeResponse::download(public_path('/upload/json/'.$fileName));
    }
 
}