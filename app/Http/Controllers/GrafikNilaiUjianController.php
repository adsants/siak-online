<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Models\UjianUser;
use Auth;
use DB;
 
class GrafikNilaiUjianController extends Controller
{
    public function index()
    {

        $satu           = UjianUser::all()->whereNotNull('finish_date')->whereBetween('nilai',array(0,10))->count();
        
        $dua           = UjianUser::all()->whereNotNull('finish_date')->whereBetween('nilai',array(11,20))->count();
        
        $tiga           = UjianUser::all()->whereNotNull('finish_date')->whereBetween('nilai',array(21,30))->count();
        $empat           = UjianUser::all()->whereNotNull('finish_date')->whereBetween('nilai',array(31,40))->count();
        $lima           = UjianUser::all()->whereNotNull('finish_date')->whereBetween('nilai',array(41,50))->count();
        $enam           = UjianUser::all()->whereNotNull('finish_date')->whereBetween('nilai',array(51,60))->count();
        $tujuh           = UjianUser::all()->whereNotNull('finish_date')->whereBetween('nilai',array(61,70))->count();
        $delapan           = UjianUser::all()->whereNotNull('finish_date')->whereBetween('nilai',array(71,80))->count();
        $sembilan           = UjianUser::all()->whereNotNull('finish_date')->whereBetween('nilai',array(81,90))->count();
        $sepuluh           = UjianUser::all()->whereNotNull('finish_date')->whereBetween('nilai',array(91,100))->count();

        $data['dataChart'] = "
            ['0 - 10', ".$satu."],
            ['11 - 20', ".$dua."],
            ['21 - 30', ".$tiga."],
            ['31 - 40', ".$empat."],
            ['41 - 50', ".$lima."],
            ['51 - 60', ".$enam."],
            ['61 - 70', ".$tujuh."],
            ['71 - 80', ".$delapan."],
            ['81 - 90', ".$sembilan."],
            ['91 - 100',".$sepuluh."]
        ";
       

        return view('admin.grafik_nilai_ujian', $data);
    }
}