<?php
 
namespace App\Http\Controllers;
 
use App\Models\SoalUser;
use App\Models\UjianUser;
use App\Models\SoalAngkaHilang;
use Illuminate\Http\Request;
use Auth;
use DB;
 
class UserUjianController extends Controller
{
    public function info($id)
    {
        $data['user'] = Auth::user();

        $query           = DB::table('ujian_users')
        ->select('ujians.id as ujian_id', 'ujians.name as ujian_name', 'ujians.jumlah_soal','ujians.waktu_pengerjaan','ujians.status','users.name', 'users.id as user_id','ujian_users.id', 'ujian_users.token','ujian_users.jawaban_benar','ujian_users.jawaban_salah','ujian_users.nilai','jenis_soals.jenis_soal')
        ->selectRaw('DATE_FORMAT(ujian_users.start_date, "%Y-%m-%d %H:%i:%s") as start_date')
        ->selectRaw('DATE_FORMAT(ujian_users.finish_date, "%Y-%m-%d %H:%i:%s") as finish_date')
        ->selectRaw('DATE_FORMAT(ujians.tgl_ujian, "%Y-%m-%d") as tgl_ujian')

        ->join('ujians', 'ujian_users.ujian_id', '=', 'ujians.id')
        ->join('users', 'ujian_users.user_id', '=', 'users.id')
        ->join('jenis_soals', 'ujian_users.id_jenis_soal', '=', 'jenis_soals.id')

        ->where('ujians.id','=', $id)
        ->where('ujian_users.user_id','=', $data['user']->id)
        ->whereNull('ujian_users.nilai')
        ->first();
        
        
        if ($query === null) {
            return redirect('user')->with('success', 'Ujian tidak tersedia');
        }
        
        $data['row']    = $query;
        return view('user.info', $data);
                
    }

    public function token($id)
    {
        $data['user'] = Auth::user();

        $query           = DB::table('ujian_users')
        ->select('ujians.id as ujian_id', 'ujians.name', 'ujians.nilai_max','ujians.jumlah_soal','ujians.waktu_pengerjaan','ujians.status','users.name', 'users.id as user_id','ujian_users.id', 'ujian_users.token','ujian_users.jawaban_benar','ujian_users.jawaban_salah','ujian_users.nilai')
        ->selectRaw('DATE_FORMAT(ujian_users.start_date, "%Y-%m-%d %H:%i:%s") as start_date')
        ->selectRaw('DATE_FORMAT(ujian_users.finish_date, "%Y-%m-%d %H:%i:%s") as finish_date')
        ->selectRaw('DATE_FORMAT(ujians.tgl_ujian, "%Y-%m-%d") as tgl_ujian')

        ->join('ujians', 'ujian_users.ujian_id', '=', 'ujians.id')
        ->join('users', 'ujian_users.user_id', '=', 'users.id')

        ->where('ujians.id','=', $id)
        ->where('ujian_users.user_id','=', $data['user']->id)
        ->whereNull('ujian_users.nilai')
        ->first();
        
        //dd($query);
        if ($query === null) {
            return redirect('user')->with('success', 'Ujian tidak tersedia');
        }
        
        $data['row']    = $query;
        return view('user.token', $data);
        
    }

    public function token_check(Request $request, $ujian_id)
    {
        $data['user'] = Auth::user();

        $query           = DB::table('ujians')
        ->select('id','token')
        ->where('ujians.id','=', $ujian_id)
        ->first();        
        
        if($request->token == $query->token){
            return redirect('ujian/info'.'/'.$query->id);            
        }
        else{
            return redirect('ujian/token'.'/'.$ujian_id)->withErrors(['msg' => 'Token anda Salah, harap masukkan Token dengan Benar !']);
        }
    }

    public function mulai($id)
    {
        // $id = ujian_user.id
        $data['user'] = Auth::user();

        $query           = DB::table('ujian_users')
        ->select('ujians.id as ujian_id', 'ujians.name as ujian_name', 'ujians.jumlah_soal', 'ujians.token','ujians.waktu_pengerjaan','ujians.status','users.name', 'users.id as user_id','ujian_users.id','ujian_users.jawaban_benar','ujian_users.jawaban_salah','ujian_users.nilai','jenis_soals.jenis_soal')
        ->selectRaw('DATE_FORMAT(ujian_users.start_date, "%Y-%m-%d %H:%i:%s") as start_date')
        ->selectRaw('DATE_FORMAT(ujian_users.finish_date, "%Y-%m-%d %H:%i:%s") as finish_date')
        ->selectRaw('DATE_FORMAT(ujians.tgl_ujian, "%Y-%m-%d") as tgl_ujian')

        ->join('ujians', 'ujian_users.ujian_id', '=', 'ujians.id')
        ->join('jenis_soals', 'ujian_users.id_jenis_soal', '=', 'jenis_soals.id')
        ->join('users', 'ujian_users.user_id', '=', 'users.id')

        ->where('ujian_users.id','=', $id)
        ->where('ujian_users.user_id','=', $data['user']->id)
        ->whereNull('ujian_users.nilai')
        ->first();

        if ($query === null) {
            return redirect('user')->with('success', 'Ujian tidak tersedia');
        }
        
        if (date('Y-m-d') != $query->tgl_ujian) {
            return redirect('user')->with('success', 'Ujian hanya bisa diakses pada Tanggal : '.$query->tgl_ujian);
        }

        $updateStartDate    = UjianUser::find($id);
        $updateStartDate->start_date        = date('Y-m-d H:i:s');
        $updateStartDate->save(); 

        $queryTampilSoal           = DB::table('soal_users')
        ->select('soal_users.id as soal_users_id', 
        'soal_users.jawaban', 
        'soal_users.benar_salah',
        'soal_angka_hilangs.data_soal',
        'soal_angka_hilangs.id as id_soal')
        ->join('soal_angka_hilangs', 'soal_users.id_soal', '=', 'soal_angka_hilangs.id')
        ->where('soal_users.id_ujian_user','=', $id)
        ->orderBy('soal_users.id','asc')
        ->get();


        $soalHtml = "";
            $soalHtml .= '
            
            <div class="row " >
                <div class="col-5">
                    <div class="row">
                ';
                for ($x = 1; $x <= $query->jumlah_soal; $x++) {
                    $soalHtml .= '
                    <div class="col-2" style="margin-bottom:5px;padding-right: 0px;" >
                        <span style="padding:10px;font-weight:bold;display: inline-block;font-size: 0.9rem; background: #EEEBE9;" id="spanId'.$x.'">
                            <sup style="font-size: 55%;" >'.$x.'</sup> <span id="spanJawabanHurufId'.$x.'">-</span>
                        </span>
                    </div>
                    
                    ';
                }
                $soalHtml .= '
                    </div>
                </div>
                
                <div class="col-7">
                ';
        $idPlusPlus = 1;
        foreach($queryTampilSoal as $soal){
            
            if($idPlusPlus > 1){
                $display = "none";
            }
            else{
                $display = "";
            }

            $soalArray = [];
            $explodeSoal    =   explode(',',  $soal->data_soal);
            $soalArrays = array($explodeSoal[0],$explodeSoal[1],$explodeSoal[2],$explodeSoal[3]);

            $acakSoals = $this->moveElementSoal($soalArrays, 1, 3);
            
            
            $soalHtml .= '
            
            <div class="row " id="tampil_soal_'.$idPlusPlus.'" style="display:'.$display.'">
         
                <div class="col-5 text-center" >
                <h4>Soal</h4>
                <h1 STYLE="color:red;font-weigth:bold;">';
                
                foreach($acakSoals as $acakSoal){
                    $soalHtml .= $acakSoal."&nbsp;";
                }

            $soalHtml .= '
                </h1>
            
                </div>
            <div class="col-7 text-center">
            
            ';



            
                
            $explodeJawaban    =   explode(',',  $soal->data_soal);
            $acakJawabans = $this->moveElement($explodeJawaban, 1, 3);

                $soalHtml .= '
                    <div class="row">';
                    
                    foreach($acakJawabans as $acakJawaban){
                        $soalHtml .= '  
                        <div class="col-2 text-center"><h4>'. $acakJawaban.'</h4></div>
                        ';
                    }
                         
                $soalHtml .= '    
                        </div>
                    <div class="row">
                    ';

                    $iii = 1;
                    foreach($acakJawabans as $acakJawaban){

                        $Huruf = $this->huruf($iii);
                        
                        $soalHtml .= '  
                            <div class="col-2 text-center"><b><span onclick="klikJawaban(\''.$idPlusPlus.'\', \''.$soal->id_soal.'\',\''.$acakJawaban.'\',\''.$Huruf.'\')" class="btn btn-info"><h2>'.$Huruf.'</h2></span></b></div>
                        ';
                    $iii++;
                    }
                $soalHtml .= '      
                    </div>
                ';
                

            $soalHtml .= ' <input type="hidden" id="soal_'.$soal->id_soal.'" name="soal_'.$soal->id_soal.'" placeholder="soal_'.$soal->id_soal.'">
            </div>
            
            </div>
            ';
            $idPlusPlus++;
        }
        
        
        
        $data['tampilSoals']    = $soalHtml;
        $data['row']    = $query;
        return view('user.mulai', $data);
        
    }

    function moveElement($array) {


        $a = rand(1, 4);
        $b = rand(1, 4);

        $p1 = array_splice($array, $a, 2);
        $p2 = array_splice($array, 4, $b);
        $array = array_merge($p2,$p1,$array);

        return $array;
    }

    function moveElementSoal($array) {


        $a = rand(1, 3);
        $b = rand(1, 3);

        $p1 = array_splice($array, $a, 2);
        $p2 = array_splice($array,3, $b);
        $array = array_merge($p2,$p1,$array);

        return $array;
    }

    function huruf($hurf) {


        switch ($hurf) {
            case "1":
              $HuruF = "A";
              break;
            case "2":
                $HuruF = "B";
              break;
            case "3":
                $HuruF = "C";
              break;
            case "4":
                $HuruF = "D";
                break;
            case "5":
                $HuruF = "E";
                break;
            default:
                $HuruF = "...";
        }

        return $HuruF;
    }


    public function submit(Request $request, $id)
    {
        
        $data['user'] = Auth::user();

        $query           = DB::table('ujian_users')
        ->select('ujians.id as ujian_id', 'ujians.name', 'ujians.nilai_max', 'ujians.jumlah_soal','ujians.waktu_pengerjaan','ujians.status','users.name', 'users.id as user_id','ujian_users.id', 'ujian_users.token','ujian_users.jawaban_benar','ujian_users.jawaban_salah','ujian_users.nilai')
        ->selectRaw('DATE_FORMAT(ujian_users.start_date, "%Y-%m-%d %H:%i:%s") as start_date')
        ->selectRaw('DATE_FORMAT(ujian_users.finish_date, "%Y-%m-%d %H:%i:%s") as finish_date')
        ->selectRaw('DATE_FORMAT(ujians.tgl_ujian, "%Y-%m-%d") as tgl_ujian')

        ->join('ujians', 'ujian_users.ujian_id', '=', 'ujians.id')
        ->join('users', 'ujian_users.user_id', '=', 'users.id')

        ->where('ujian_users.id','=', $id)
        ->where('ujian_users.user_id','=', $data['user']->id)
        ->whereNull('ujian_users.nilai')
        ->first();     
        
        //dd($query);
        if ($query === null) {
            return redirect('user')->with('success', 'Ujian tidak tersedia');
        }
        else{

            
            $queryTampilSoal           = DB::table('soal_users')
            ->select('soal_users.id as soal_users_id', 
            'soal_users.jawaban', 
            'soal_users.benar_salah',
            'soal_users.id_ujian_user',
            'soal_angka_hilangs.data_soal',
            'soal_angka_hilangs.jawaban_benar',
            'soal_angka_hilangs.id as id_soal',)
            ->join('soal_angka_hilangs', 'soal_users.id_soal', '=', 'soal_angka_hilangs.id')
            ->where('soal_users.id_ujian_user','=', $id)
            ->orderBy('soal_users.id','asc')
            ->get();
            
            $totalJawabanSalah = 0;
            $totalJawabanBenar = 0;

            foreach($queryTampilSoal as $soal){
                $soalId         =   $soal->id_soal;
                $ambilValue         = $_POST['soal_'."".$soalId];

                $cekJawaban         =   DB::table('soal_angka_hilangs')
                ->select('jawaban_benar')
                ->where('id','=', $soal->id_soal)
                ->first();

                if($ambilValue == $cekJawaban->jawaban_benar){
                    $jawabanBenarSalah = '1';
                    $totalJawabanBenar++;
                }
                else{
                    $jawabanBenarSalah = '0';
                    $totalJawabanSalah++;
                }

                $updateJawaban              = SoalUser::find($soal->soal_users_id);
                $updateJawaban->jawaban     = $ambilValue;
                $updateJawaban->benar_salah = $jawabanBenarSalah;
                $updateJawaban->save(); 

                $nilaiPerSoal   =   $query->nilai_max / $query->jumlah_soal;

                $totalNilai     =   $totalJawabanBenar * $nilaiPerSoal;
              
                $updateUjianUser                    =   UjianUser::find($soal->id_ujian_user);
                $updateUjianUser->jawaban_benar     =   $totalJawabanBenar;
                $updateUjianUser->jawaban_salah     =   $totalJawabanSalah;
                $updateUjianUser->finish_date       =   date('Y-m-d H:i:s');
                $updateUjianUser->nilai             =   $totalNilai ;
                $updateUjianUser->save(); 

                

            }

            

        }
        return redirect('ujian/selesai'.'/'.$id);
    }


    public function submitGambar(Request $request, $id)
    {
        //dd($request);
        $data['user'] = Auth::user();

        $query           = DB::table('ujian_users')
        ->select('ujians.id as ujian_id', 'ujians.name', 'ujians.nilai_max', 'ujians.jumlah_soal','ujians.waktu_pengerjaan','ujians.status','users.name', 'users.id as user_id','ujian_users.id', 'ujian_users.token','ujian_users.jawaban_benar','ujian_users.jawaban_salah','ujian_users.nilai')
        ->selectRaw('DATE_FORMAT(ujian_users.start_date, "%Y-%m-%d %H:%i:%s") as start_date')
        ->selectRaw('DATE_FORMAT(ujian_users.finish_date, "%Y-%m-%d %H:%i:%s") as finish_date')
        ->selectRaw('DATE_FORMAT(ujians.tgl_ujian, "%Y-%m-%d") as tgl_ujian')

        ->join('ujians', 'ujian_users.ujian_id', '=', 'ujians.id')
        ->join('users', 'ujian_users.user_id', '=', 'users.id')

        ->where('ujian_users.id','=', $id)
        ->where('ujian_users.user_id','=', $data['user']->id)
        ->whereNull('ujian_users.nilai')
        ->first();     
        
        //dd($query);
        if ($query === null) {
            return redirect('user')->with('success', 'Ujian tidak tersedia');
        }
        else{

            
            $queryTampilSoal           = DB::table('soal_users')
            ->select('soal_users.id as soal_users_id', 
            'soal_users.id_soal', 
            'soal_users.jawaban', 
            'soal_users.benar_salah',
            'soal_users.id_ujian_user')
            ->where('soal_users.id_ujian_user','=', $id)
            ->orderBy('soal_users.id','asc')
            ->get();
            
            $totalJawabanSalah = 0;
            $totalJawabanBenar = 0;
            
            foreach($queryTampilSoal as $soal){
                $soalId             =   $soal->id_soal;

                $cekJawabanBenar         =   DB::table('soal_gambars')
                ->select('*')
                ->where('id_soal','=', $soalId)
                ->where('status','=', 'B')
                ->first();
                
                $ambilValue         = $_POST['soal_'."".$soalId];


                if($ambilValue == $cekJawabanBenar->id){
                    $jawabanBenarSalah = '1';
                    $totalJawabanBenar++;
                }
                else{
                    $jawabanBenarSalah = '0';
                    $totalJawabanSalah++;
                }

                $updateJawaban              = SoalUser::find($soal->soal_users_id);
                $updateJawaban->jawaban     = $ambilValue;
                $updateJawaban->benar_salah = $jawabanBenarSalah;
                $updateJawaban->save(); 

                $nilaiPerSoal   =   $query->nilai_max / $query->jumlah_soal;

                $totalNilai     =   $totalJawabanBenar * $nilaiPerSoal;
              
                $updateUjianUser                    =   UjianUser::find($soal->id_ujian_user);
                $updateUjianUser->jawaban_benar     =   $totalJawabanBenar;
                $updateUjianUser->jawaban_salah     =   $totalJawabanSalah;
                $updateUjianUser->finish_date       =   date('Y-m-d H:i:s');
                $updateUjianUser->nilai             =   $totalNilai ;
                $updateUjianUser->save(); 
                

            }
        }

        return redirect('ujian/selesai'.'/'.$id);
    }




    public function mulaiGambar($id)
    {
        // $id = ujian_user.id
        $data['user'] = Auth::user();

        $query           = DB::table('ujian_users')
        ->select('ujians.id as ujian_id', 'ujians.name as ujian_name', 'ujians.jumlah_soal', 'ujians.token','ujians.waktu_pengerjaan','ujians.status','users.name', 'users.id as user_id','ujian_users.id','ujian_users.jawaban_benar','ujian_users.jawaban_salah','ujian_users.nilai')
        ->selectRaw('DATE_FORMAT(ujian_users.start_date, "%Y-%m-%d %H:%i:%s") as start_date')
        ->selectRaw('DATE_FORMAT(ujian_users.finish_date, "%Y-%m-%d %H:%i:%s") as finish_date')
        ->selectRaw('DATE_FORMAT(ujians.tgl_ujian, "%Y-%m-%d") as tgl_ujian')

        ->join('ujians', 'ujian_users.ujian_id', '=', 'ujians.id')
        ->join('users', 'ujian_users.user_id', '=', 'users.id')

        ->where('ujian_users.id','=', $id)
        ->where('ujian_users.user_id','=', $data['user']->id)
        ->whereNull('ujian_users.nilai')
        ->first();

        if ($query === null) {
            return redirect('user')->with('success', 'Ujian tidak tersedia');
        }

        $updateStartDate                    = UjianUser::find($id);
        $updateStartDate->start_date        = date('Y-m-d H:i:s');
        $updateStartDate->save(); 

        $queryTampilSoals           = DB::table('soal_users')
        ->select(
        'soal_users.id as soal_users_id', 
        'soal_users.id_soal', 
        'soal_users.jawaban', 
        'soal_users.benar_salah')
        ->where('soal_users.id_ujian_user','=', $id)
        ->orderBy('soal_users.id','asc')
        ->get();



        $soalHtml = "";
        $soalHtml .= '
        
        <div class="row " >
            <div class="col-5">
                <div class="row">
            ';
            for ($x = 1; $x <= $query->jumlah_soal; $x++) {
                $soalHtml .= '
                <div class="col-2" style="margin-bottom:5px;padding-right: 0px;" >
                    <span style="padding:10px;font-weight:bold;display: inline-block;font-size: 0.9rem; background: #EEEBE9;" id="spanId'.$x.'">
                        <sup style="font-size: 55%;" >'.$x.'</sup> <span id="spanJawabanHurufId'.$x.'">-</span>
                    </span>
                </div>
                
                ';
            }
            $soalHtml .= '
                </div>
            </div>
            
            <div class="col-7">
            ';

            $idPlusPlus = 1;
            foreach($queryTampilSoals as $soal){
                
                if($idPlusPlus > 1){
                    $display = "none";
                }
                else{
                    $display = "";
                }

                
                
                $soalHtml .= '
                
                <div class="row " id="tampil_soal_'.$idPlusPlus.'" style="display:'.$display.'">
            
                    <div class="container mb-3">
                    <h4>Soal</h4>
                    </div>
                    
                    <div class="container ">
                    <div class="row ">
                   ';


                    $querySoals           = DB::table('soal_gambars')
                    ->select(
                    'soal_gambars.*'
                    )
                    ->where('soal_gambars.id_soal','=', $soal->id_soal)
                    ->where('soal_gambars.status','=', 'S')
                    ->inRandomOrder()
                    ->get();
                   // dd($soal->id_soal);

                    foreach($querySoals as $acakSoal){
                        $soalHtml .= ' <div class="col-2 text-center">'.$acakSoal->soal.'</div>';
                    }

                $soalHtml .= '
                    </div>
                    </div>
                    <div class="container pt-5" style="border-top:1px solid #EEEBE9;">
                    <h4>Pilih Jawaban</h4>
                    </div>

                    
                    <div class="container ">
                    <div class="row ">
                <div class="col-12 text-center">
                
                ';



                
                    
                $queryJawabans           = DB::table('soal_gambars')
                ->select(
                'soal_gambars.*'
                )
                ->where('soal_gambars.id_soal','=', $soal->id_soal)
                ->inRandomOrder()
                ->get();

                    $soalHtml .= '
                        <div class="row">';
                        
                        foreach($queryJawabans as $acakJawaban){
                            $soalHtml .= '  
                            <div class="col-2 text-center"><h4>'. $acakJawaban->soal.'</h4></div>
                            ';
                        }
                            
                    $soalHtml .= '    
                            </div>
                        <div class="row">
                        ';

                        $iii = 1;
                        foreach($queryJawabans as $acakJawaban){

                            $Huruf = $this->huruf($iii);
                            
                            $soalHtml .= '  
                                <div class="col-2 text-center"><b><span onclick="klikJawaban(\''.$idPlusPlus.'\', \''.$soal->id_soal.'\',\''.$acakJawaban->id.'\',\''.$Huruf.'\')" class="btn btn-info"><h2>'.$Huruf.'</h2></span></b></div>
                            ';
                        $iii++;
                        }
                    $soalHtml .= '      
                        </div>
                        </div>
                    ';
                    

                $soalHtml .= ' <input type="hidden" id="soal_'.$soal->id_soal.'" name="soal_'.$soal->id_soal.'" placeholder="soal_'.$soal->id_soal.'">
                </div>
                
                </div>
                </div>
                ';
                $idPlusPlus++;
                }
       


                $data['tampilSoals']    = $soalHtml;
        $data['row'] = $query;

        return view('user.mulai_gambar', $data);

    }


    public function selesai($id)
    {
        // $id = ujian_user.id
        $data['user'] = Auth::user();

        $query           = DB::table('ujian_users')
        ->select('ujians.id as ujian_id', 'ujians.name as ujian_name', 'ujians.jumlah_soal','ujians.waktu_pengerjaan','ujians.status','users.name', 'users.id as user_id','ujian_users.id', 'ujian_users.token','ujian_users.jawaban_benar','ujian_users.jawaban_salah','ujian_users.nilai')
        ->selectRaw('DATE_FORMAT(ujian_users.start_date, "%Y-%m-%d %H:%i:%s") as start_date')
        ->selectRaw('DATE_FORMAT(ujian_users.finish_date, "%Y-%m-%d %H:%i:%s") as finish_date')
        ->selectRaw('DATE_FORMAT(ujians.tgl_ujian, "%Y-%m-%d") as tgl_ujian')

        ->join('ujians', 'ujian_users.ujian_id', '=', 'ujians.id')
        ->join('users', 'ujian_users.user_id', '=', 'users.id')

        ->where('ujian_users.id','=', $id)
        ->where('ujian_users.user_id','=', $data['user']->id)
        ->whereNotNull('ujian_users.nilai')
        ->first();

        if ($query === null) {
            return redirect('user')->with('success', 'Ujian tidak tersedia');
        }

        
        $data['row']    = $query;

        return view('user.selesai', $data);
    }

    public function riwayat()
    {
        $data['user'] = Auth::user();

        $query           = DB::table('ujian_users')
        ->select('ujians.id as ujian_id', 'ujians.name as ujian_name', 'ujians.jumlah_soal','ujians.waktu_pengerjaan','ujians.status','users.name', 'users.id as user_id','ujian_users.id', 'ujian_users.token','ujian_users.jawaban_benar','ujian_users.jawaban_salah','ujian_users.nilai','jenis_soals.jenis_soal')
        ->selectRaw('DATE_FORMAT(ujian_users.start_date, "%d-%m-%Y %H:%i") as start_date')
        ->selectRaw('DATE_FORMAT(ujian_users.finish_date, "%d-%m-%Y %H:%i") as finish_date')
        ->selectRaw('DATE_FORMAT(ujians.tgl_ujian, "%Y-%m-%d") as tgl_ujian')

        ->join('ujians', 'ujian_users.ujian_id', '=', 'ujians.id')
        ->join('users', 'ujian_users.user_id', '=', 'users.id')
        ->join('jenis_soals', 'ujians.id_jenis_soal', '=', 'jenis_soals.id')

        ->where('ujian_users.user_id','=', $data['user']->id)
        ->whereNotNull('ujian_users.nilai')
        ->Paginate(100);
        //dd($post);
        //dd($query);
        //dd($data['user']->id);
        $data['rows']    = $query;

        return view('user.riwayat', $data);
    }

}