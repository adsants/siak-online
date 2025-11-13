<?php
 
namespace App\Http\Controllers;
 
use App\Models\SoalUser;
use App\Models\UjianUser;
use App\Models\SoalAngkaHilang;
use App\Models\UjianUserDetail;
use App\Models\TextInfo;
use Illuminate\Http\Request;
use Auth;
use DB;
use DateTime;
use PDF;
use QrCode;

class UserUjianNewController extends Controller
{
    public function info($idUjianDetails,$idUjianUser)
    {

        $query           = DB::select("
        select 
            ujian_details.id as idUjianDetails,
            ujian_details.id_jenis_soal,
            ujian_details.jumlah_soal,
            ujian_details.waktu_pengerjaan,
            ujian_details.nilai_max,
            ujians.name as ujianName,
            ujians.id as ujianId,
            ujian_user_details.jawaban_benar,
            ujian_user_details.jawaban_salah,
            jenis_soals.jenis_soal,
            jenis_soals.kategori,
            ujian_users.id as idUjianUser,
            ujian_user_details.nilai,
            ujian_user_details.start_date,
            ujian_user_details.finish_date,
            ujian_user_details.kurang_waktu_pengerjaan,
            DATE_FORMAT(ujians.tgl_ujian, '%Y-%m-%d') as tgl_ujian,            
            DATE_FORMAT(ujians.tgl_ujian, '%d-%m-%Y') as tgl_ujian_indo
        from
            ujian_users,
            ujians,
            ujian_user_details,
            ujian_details,
            jenis_soals
        where 
            ujian_users.ujian_id = ujians.id 
            and ujian_details.id_ujian = ujians.id 
            and jenis_soals.id = ujian_details.id_jenis_soal 
            and ujian_user_details.id_ujian_user = ujian_users.id 
            and ujian_user_details.id_ujian_detail = ujian_details.id 
            and ujian_details.id = '".$idUjianDetails."'
            and ujian_users.id = '".$idUjianUser."'
            and ujian_user_details.nilai is null
        ");

        $data['textInfo']  = TextInfo::select('*')
        ->offset(0)
        ->limit(1)
        ->first();



        if (!$query) {
            return redirect('home')->with('success', 'Maaf, Ujian tidak tersedia');
        }
        
        $data['row']    = $query[0];
        return view('user.info', $data);
                
    }
    public function simulasiKecermatan($idUjianDetails,$idUjianUser)
    {

        $query           = DB::select("
        select 
            ujian_details.id as idUjianDetails,
            ujian_details.id_jenis_soal,
            ujian_details.jumlah_soal,
            ujian_details.waktu_pengerjaan,
            ujian_details.nilai_max,
            ujians.name as ujianName,
            ujians.id as ujianId,
            ujian_user_details.jawaban_benar,
            ujian_user_details.jawaban_salah,
            jenis_soals.jenis_soal,
            ujian_users.id as idUjianUser,
            ujian_user_details.nilai,
            ujian_user_details.start_date,
            ujian_user_details.finish_date,
            ujian_user_details.kurang_waktu_pengerjaan,
            DATE_FORMAT(ujians.tgl_ujian, '%Y-%m-%d') as tgl_ujian,            
            DATE_FORMAT(ujians.tgl_ujian, '%d-%m-%Y') as tgl_ujian_indo
        from
            ujian_users,
            ujians,
            ujian_user_details,
            ujian_details,
            jenis_soals
        where 
            ujian_users.ujian_id = ujians.id 
            and ujian_details.id_ujian = ujians.id 
            and jenis_soals.id = ujian_details.id_jenis_soal 
            and ujian_user_details.id_ujian_user = ujian_users.id 
            and ujian_user_details.id_ujian_detail = ujian_details.id 
            and ujian_details.id = '".$idUjianDetails."'
            and ujian_users.id = '".$idUjianUser."'
            and ujian_user_details.nilai is null
        ");

        if (!$query) {
            return redirect('user')->with('success', 'Ujian tidak tersedia');
        }



        $queryTampilSoals           = DB::select("
            select distinct(id_soal) as id_soal from soal_gambars where is_contoh = 1  and id_jenis_soal = '".$query[0]->id_jenis_soal."'
        ");


        $soalHtml = "";
        $soalHtml .= '
        
        <div class="row " >
            
            
            <div class="col-12">
            ';

            $idPlusPlus = 1;
            foreach($queryTampilSoals as $soal){
                
                if($idPlusPlus > 1){
                    $display = "none";
                }
                else{
                    $display = "";
                }

                
                $nilaiAwalKolom = $idPlusPlus - 1;
                $nilaiKolom = $nilaiAwalKolom / 50;
                $nilaiKolomTampil = ceil( $nilaiKolom);
                
                if($nilaiKolomTampil == 0){
                    $nilaiKolomTampil = 1;
                }

                
                $soalHtml .= '
                
                <div class="row " id="tampil_soal_'.$idPlusPlus.'" style="display:'.$display.'">
            
                    <table class="table table-bordered mb-0">
                        
                        <tr>
                            <td align="center">a</td>
                            <td align="center">b</td>
                            <td align="center">c</td>
                            <td align="center">d</td>
                            <td align="center">e</td>
                        </tr>
                        <tr>
                    ';

                    $queryJawabans           = DB::table('soal_gambars')
                    ->select(
                    'soal_gambars.*'
                    )
                    ->where('soal_gambars.id_soal','=', $soal->id_soal)
                    ->inRandomOrder()
                    ->get();

                    foreach($queryJawabans as $queryJawaban){
                        $soalHtml .= '  <td align="center">'.$queryJawaban->soal.'</td>';
                    }
                

                   $soalHtml .= '
                        </tr>
                    </table>
                    
                    <table class="table table-bordered mb-0 mt-3">
                        <tr>
                    ';


                   
                   $querySoals           = DB::table('soal_gambars')
                   ->select(
                   'soal_gambars.*'
                   )
                   ->where('soal_gambars.id_soal','=', $soal->id_soal)
                   ->where('soal_gambars.status','=', 'S')
                   ->inRandomOrder()
                   ->get();
                   //dd( $soal->id_soal);

                   foreach($querySoals as $acakSoal){
                       $soalHtml .= '<td align="center">'.$acakSoal->soal.'</td>';
                   }



                $soalHtml .= '
                        </tr>
                    </table>
                
                ';

                        $soalHtml .= '
                        <table class="table table-bordered mb-0 mt-3">
                            <tr>
                        ';

                        $iii = 1;
                        foreach($queryJawabans as $acakJawaban){

                            $Huruf = $this->huruf($iii);
                            
                            $soalHtml .= ' 
                                <td> 
                                <div class="col-2 text-center"><b><span onclick="klikJawaban(\''.$idPlusPlus.'\', \''.$soal->id_soal.'\',\''.$acakJawaban->id.'\',\''.$Huruf.'\')" class="btn btn-info">'.$Huruf.'</span></b></div>

                                </td>
                            ';
                        $iii++;
                        }
                    $soalHtml .= '      
                        </table>
                    ';
                    

                $soalHtml .= ' <input type="hidden" id="soal_'.$soal->id_soal.'" name="soal_'.$soal->id_soal.'" placeholder="soal_'.$soal->id_soal.'">
                </div>
                ';
                $idPlusPlus++;
                }
       
                DB::table('ujian_user_details')->where('id_ujian_user', $idUjianUser)->where('id_ujian_detail', $idUjianDetails)->update([
                    'start_date' => date('Y-m-d H:i:s')
                ]);
        

        $data['tampilSoals']    = $soalHtml;
        $data['row'] = $query[0];
        $data['totalSoalSimulasi'] =count($queryTampilSoals);

        $data['textInfo']  = TextInfo::select('*')
        ->offset(0)
        ->limit(1)
        ->first();

        
        return view('user.simulasi_kecermatan', $data);
    }    
    public function simulasi($idUjianDetails,$idUjianUser)
    {


        $query           = DB::select("
        select 
            ujian_details.id as idUjianDetails,
            ujian_details.id_jenis_soal,
            ujian_details.jumlah_soal,
            ujian_details.waktu_pengerjaan,
            ujian_details.nilai_max,
            ujians.name as ujianName,
            ujians.id as ujianId,
            ujian_user_details.jawaban_benar,
            ujian_user_details.jawaban_salah,
            jenis_soals.jenis_soal,
            ujian_users.id as idUjianUser,
            ujian_user_details.nilai,
            ujian_user_details.start_date,
            ujian_user_details.finish_date,
            ujian_user_details.kurang_waktu_pengerjaan,
            DATE_FORMAT(ujians.tgl_ujian, '%Y-%m-%d') as tgl_ujian,            
            DATE_FORMAT(ujians.tgl_ujian, '%d-%m-%Y') as tgl_ujian_indo
        from
            ujian_users,
            ujians,
            ujian_user_details,
            ujian_details,
            jenis_soals
        where 
            ujian_users.ujian_id = ujians.id 
            and ujian_details.id_ujian = ujians.id 
            and jenis_soals.id = ujian_details.id_jenis_soal 
            and ujian_user_details.id_ujian_user = ujian_users.id 
            and ujian_user_details.id_ujian_detail = ujian_details.id 
            and ujian_details.id = '".$idUjianDetails."'
            and ujian_users.id = '".$idUjianUser."'
            and ujian_user_details.nilai is null
        ");

        //dd($query );
        $data['textInfo']  = TextInfo::select('*')
        ->offset(0)
        ->limit(1)
        ->first();

        if ($query === null) {
            return redirect('home')->with('success', 'Maaf, Ujian tidak tersedia.');
        }
        if (date('Y-m-d') != $query[0]->tgl_ujian) {
            return redirect('home')->with('success', 'Ujian hanya bisa diakses pada Tanggal : '.$query->tgl_ujian_indo);
        }


        DB::table('ujian_user_details')->where('id_ujian_user', $idUjianUser)->where('id_ujian_detail', $idUjianDetails)->update([
            'start_date' => date('Y-m-d H:i:s')
        ]);

        $queryTampilSoals           = DB::table('soal_choices')
        ->select(
        'soal_choices.soal_choice',
        'soal_choices.id as id_soal')
        ->where('soal_choices.is_contoh','=', 1)
        ->where('soal_choices.id_jenis_soal','=', $query[0]->id_jenis_soal)
        ->orderBy('soal_choices.id','asc')
        ->get();

          

        $data['jumlahSoalSimulasi'] = count($queryTampilSoals);


        $soalHtml = "";
            $soalHtml .= '
            
            <div class="row " >
                
                
                
            ';

            $ii = 1;
            $tampilPaging  =    "";
            foreach($queryTampilSoals as $queryTampilSoal){
                if($ii ==1){

                    $tampilSoal = "";
                }
                else{
                    $tampilSoal = "none";
                }

                $soalHtml .= '
                <div class="col-12" id="soalNomor_'.$ii.'" style="display:'.$tampilSoal.'">
                    <b>'.$queryTampilSoal->soal_choice.'</b>
                ';

         

                $queryTampilJawabans           = DB::table('soal_choice_jawabans')
                ->where('id_soal_choice','=', $queryTampilSoal->id_soal)
                ->get();
                    foreach($queryTampilJawabans as $queryTampilJawaban){

                        

                        $soalHtml .= '
                        <div class="form-check">
                            <input class="form-check-input" type="radio"  id="flexjawaban_'.$queryTampilSoal->id_soal.'_'.$queryTampilJawaban->id.'" value="'.$queryTampilJawaban->id.'" name="'.$queryTampilSoal->id_soal.'" onclick="jawabSoalNomor('.$ii.','.$queryTampilJawaban->status_jawaban.','. $queryTampilJawaban->id.')">
                            <label class="form-check-label" for="flexjawaban_'.$queryTampilSoal->id_soal.'_'.$queryTampilJawaban->id.'">
                            '. $queryTampilJawaban->jawaban.'
                            </label>
                        </div>
                        ';
                    }
                $soalHtml .= '
                </div>
                ';

                        $activePaging  =   "";
                    $tampilPaging .= '
                    <li id="nomorSoalBawah_'.$ii.'" class="page-item '.$activePaging.'"><a class="page-link" href="#" onclick="tampilSoalFromPaging('.$ii.')">'.$ii.'</a></li>';

            $ii++;
            }

            $soalHtml .= '
                <input id="inputSoalNomor" type="hidden" value="1">
            
            </div>
            ';
        
        
        
        
        $data['tampilSoals']    = $soalHtml;
        $data['tampilPaging']    = $tampilPaging;
        $data['row']            = $query[0];
        return view('user.simulasi', $data);
        
    }


    public function mulaiKecermatan($idUjianDetails,$idUjianUser)
    {

        $query           = DB::select("
        select 
            ujian_details.id as idUjianDetails,
            ujian_details.id_jenis_soal,
            ujian_details.jumlah_soal,
            ujian_details.waktu_pengerjaan,
            ujian_details.nilai_max,
            ujians.name as ujianName,
            ujians.id as ujianId,
            ujian_user_details.jawaban_benar,
            ujian_user_details.jawaban_salah,
            jenis_soals.jenis_soal,
            ujian_users.id as idUjianUser,
            ujian_user_details.nilai,
            ujian_user_details.start_date,
            ujian_user_details.finish_date,
            ujian_user_details.kurang_waktu_pengerjaan,
            DATE_FORMAT(ujians.tgl_ujian, '%Y-%m-%d') as tgl_ujian,            
            DATE_FORMAT(ujians.tgl_ujian, '%d-%m-%Y') as tgl_ujian_indo
        from
            ujian_users,
            ujians,
            ujian_user_details,
            ujian_details,
            jenis_soals
        where 
            ujian_users.ujian_id = ujians.id 
            and ujian_details.id_ujian = ujians.id 
            and jenis_soals.id = ujian_details.id_jenis_soal 
            and ujian_user_details.id_ujian_user = ujian_users.id 
            and ujian_user_details.id_ujian_detail = ujian_details.id 
            and ujian_details.id = '".$idUjianDetails."'
            and ujian_users.id = '".$idUjianUser."'
            and ujian_user_details.nilai is null
        ");

        if (!$query) {
            return redirect('user')->with('success', 'Ujian tidak tersedia');
        }


        $queryTampilSoals           = DB::table('soal_users')
        ->select(
        'soal_users.id as soal_users_id', 
        'soal_users.id_soal', 
        'soal_users.jawaban', 
        'soal_users.benar_salah')
        ->where('soal_users.id_ujian_user','=', $idUjianUser)
        ->where('soal_users.id_ujian_detail','=', $idUjianDetails)
        ->orderBy('soal_users.id','asc')
        ->get();

        $soalHtml = "";
        $soalHtml .= '
        
        <div class="row " >
            
            
            <div class="col-12">
            ';

            $idPlusPlus = 1;
            foreach($queryTampilSoals as $soal){
                
                if($idPlusPlus > 1){
                    $display = "none";
                }
                else{
                    $display = "";
                }

                
                $nilaiAwalKolom = $idPlusPlus - 1;
                $nilaiKolom = $nilaiAwalKolom / 50;
                $nilaiKolomTampil = ceil( $nilaiKolom);
                
                if($nilaiKolomTampil == 0){
                    $nilaiKolomTampil = 1;
                }

                
                $soalHtml .= '
                
                <div class="row " id="tampil_soal_'.$idPlusPlus.'" style="display:'.$display.'">
            
                    <table class="table table-bordered mb-0">
                        
                        <tr>
                            <td align="center">a</td>
                            <td align="center">b</td>
                            <td align="center">c</td>
                            <td align="center">d</td>
                            <td align="center">e</td>
                        </tr>
                        <tr>
                    ';

                    $queryJawabans           = DB::table('soal_gambars')
                    ->select(
                    'soal_gambars.*'
                    )
                    ->where('soal_gambars.id_soal','=', $soal->id_soal)
                    ->inRandomOrder()
                    ->get();

                    foreach($queryJawabans as $queryJawaban){
                        $soalHtml .= '  <td align="center">'.$queryJawaban->soal.'</td>';
                    }
                

                   $soalHtml .= '
                        </tr>
                    </table>
                    
                    <table class="table table-bordered mb-0 mt-3">
                        <tr>
                    ';


                   
                   $querySoals           = DB::table('soal_gambars')
                   ->select(
                   'soal_gambars.*'
                   )
                   ->where('soal_gambars.id_soal','=', $soal->id_soal)
                   ->where('soal_gambars.status','=', 'S')
                   ->inRandomOrder()
                   ->get();
                   //dd( $soal->id_soal);

                   foreach($querySoals as $acakSoal){
                       $soalHtml .= '<td align="center">'.$acakSoal->soal.'</td>';
                   }



                $soalHtml .= '
                        </tr>
                    </table>
                
                ';

                        $soalHtml .= '
                        <table class="table table-bordered mb-0 mt-3">
                            <tr>
                        ';

                        $iii = 1;
                        foreach($queryJawabans as $acakJawaban){

                            $Huruf = $this->huruf($iii);
                            
                            $soalHtml .= ' 
                                <td> 
                                <div class="col-2 text-center"><b><span onclick="klikJawaban(\''.$idPlusPlus.'\', \''.$soal->id_soal.'\',\''.$acakJawaban->id.'\',\''.$Huruf.'\')" class="btn btn-info">'.$Huruf.'</span></b></div>

                                </td>
                            ';
                        $iii++;
                        }
                    $soalHtml .= '      
                        </table>
                    ';
                    

                $soalHtml .= ' <input type="hidden" id="soal_'.$soal->id_soal.'" name="soal_'.$soal->id_soal.'" placeholder="soal_'.$soal->id_soal.'">
                </div>
                ';
                $idPlusPlus++;
                }
       
                DB::table('ujian_user_details')->where('id_ujian_user', $idUjianUser)->where('id_ujian_detail', $idUjianDetails)->update([
                    'start_date' => date('Y-m-d H:i:s')
                ]);
        

        $data['tampilSoals']    = $soalHtml;
        $data['row'] = $query[0];

        $data['textInfo']  = TextInfo::select('*')
        ->offset(0)
        ->limit(1)
        ->first();

        
        return view('user.mulai_kecermatan', $data);
    } 
    public function mulai($idUjianDetails,$idUjianUser)
    {

        $query           = DB::select("
        select 
            ujian_details.id as idUjianDetails,
            ujian_details.id_jenis_soal,
            ujian_details.jumlah_soal,
            ujian_details.waktu_pengerjaan,
            ujian_details.nilai_max,
            ujians.name as ujianName,
            ujians.id as ujianId,
            ujian_user_details.jawaban_benar,
            ujian_user_details.jawaban_salah,
            jenis_soals.jenis_soal,
            ujian_users.id as idUjianUser,
            ujian_user_details.nilai,
            ujian_user_details.start_date,
            ujian_user_details.finish_date,
            ujian_user_details.kurang_waktu_pengerjaan,
            DATE_FORMAT(ujians.tgl_ujian, '%Y-%m-%d') as tgl_ujian,            
            DATE_FORMAT(ujians.tgl_ujian, '%d-%m-%Y') as tgl_ujian_indo
        from
            ujian_users,
            ujians,
            ujian_user_details,
            ujian_details,
            jenis_soals
        where 
            ujian_users.ujian_id = ujians.id 
            and ujian_details.id_ujian = ujians.id 
            and jenis_soals.id = ujian_details.id_jenis_soal 
            and ujian_user_details.id_ujian_user = ujian_users.id 
            and ujian_user_details.id_ujian_detail = ujian_details.id 
            and ujian_details.id = '".$idUjianDetails."'
            and ujian_users.id = '".$idUjianUser."'
            and ujian_user_details.nilai is null
        ");

        if ($query === null) {
            return redirect('home')->with('success', 'Maaf, Ujian tidak tersedia.');
        }
        if (date('Y-m-d') != $query[0]->tgl_ujian) {
            return redirect('home')->with('success', 'Ujian hanya bisa diakses pada Tanggal : '.$query[0]->tgl_ujian_indo);
        }

        DB::table('ujian_user_details')->where('id_ujian_user', $idUjianUser)->where('id_ujian_detail', $idUjianDetails)->update([
            'start_date' => date('Y-m-d H:i:s')
        ]);

        $queryTampilSoals           = DB::table('soal_users')
        ->select('soal_users.id as soal_users_id', 
        'soal_users.id_jawaban', 
        'soal_users.benar_salah',
        'soal_choices.soal_choice',
        'soal_choices.id as id_soal')
        ->join('soal_choices', 'soal_users.id_soal', '=', 'soal_choices.id')
        ->where('soal_users.id_ujian_user','=', $idUjianUser)
        ->where('soal_users.id_ujian_detail','=', $idUjianDetails)
        ->orderBy('soal_users.id','asc')
        ->get();


        $data['jumlahSoal'] = count($queryTampilSoals);

        $soalHtml = "";
            $soalHtml .= '
            
            <div class="row " >
                
                
                
            ';

            $ii = 1;
            $tampilPaging  =    "";
            foreach($queryTampilSoals as $queryTampilSoal){
                if($ii ==1){

                    $tampilSoal = "";
                }
                else{
                    $tampilSoal = "none";
                }

                $soalHtml .= '
                <div class="col-12" id="soalNomor_'.$ii.'" style="display:'.$tampilSoal.'">
                    <b>'.$queryTampilSoal->soal_choice.'</b>
                ';

                $cekJawabanUser          = DB::table('soal_users')
                ->select('id_jawaban')
                ->where('soal_users.id_ujian_user','=', $idUjianUser)
                ->where('soal_users.id_ujian_detail','=', $idUjianDetails)
                ->where('id_soal','=', $queryTampilSoal->id_soal)
                ->first();

                $queryTampilJawabans           = DB::table('soal_choice_jawabans')
                ->where('id_soal_choice','=', $queryTampilSoal->id_soal)
                ->get();
                    foreach($queryTampilJawabans as $queryTampilJawaban){

                        

                        if(!$cekJawabanUser){
                            $idJawabanDariUser  =   0;
                        }
                        else{
                            $idJawabanDariUser  =   $cekJawabanUser->id_jawaban;
                        }

                        if($idJawabanDariUser ==  $queryTampilJawaban->id){

                            $checked = "checked";
                        }
                        else{
                            $checked = "";
                        }

                        $soalHtml .= '
                        <div class="form-check">
                            <input class="form-check-input" type="radio" '. $checked .' name="jawaban_'.$queryTampilSoal->id_soal.'" id="flexjawaban_'.$queryTampilSoal->id_soal.'_'.$queryTampilJawaban->id.'" value="'.$queryTampilJawaban->id.'" onclick="jawabSoalNomor('.$ii.','. $queryTampilSoal->soal_users_id.','. $queryTampilJawaban->id.')">
                            <label class="form-check-label" for="flexjawaban_'.$queryTampilSoal->id_soal.'_'.$queryTampilJawaban->id.'">
                            '. $queryTampilJawaban->jawaban.'
                            </label>
                        </div>
                        ';
                    }
                $soalHtml .= '
                </div>
                ';

                    if($cekJawabanUser->id_jawaban == ''){
                        $activePaging  =   "";
                    }
                    else{
                        $activePaging  =   "active";
                    }

                    $tampilPaging .= '
                    <li id="nomorSoalBawah_'.$ii.'" class="page-item '.$activePaging.'"><a class="page-link" href="#" onclick="tampilSoalFromPaging('.$ii.')">'.$ii.'</a></li>';

            $ii++;
            }

            $soalHtml .= '
                <input id="inputSoalNomor" type="hidden" value="1">
            
            </div>
            ';
        
        
        
        
        $data['tampilSoals']    = $soalHtml;
        $data['tampilPaging']    = $tampilPaging;
        $data['row']            = $query[0];
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



    public function submitJawaban(Request $request)
    {
        // $id = ujian_user.id
        $queryUjianBySoalUser          = DB::table('ujian_users')
        ->select('ujian_users.id','soal_users.id_soal')
        ->join('soal_users', 'ujian_users.id', '=', 'soal_users.id_ujian_user')
        ->where('soal_users.id','=', $request->soal_user_id)
        ->first();
        //dd($queryUjianBySoalUser);

        $queryUjian          = DB::table('ujian_users')
        ->select('ujians.id as ujian_id', 'ujians.name as ujian_name', 'ujians.jumlah_soal','ujians.waktu_pengerjaan','ujians.status','ujians.jenis_soal','users.name', 'users.id as user_id','ujian_users.id', 'ujian_users.token','ujian_users.jawaban_benar','ujian_users.jawaban_salah','ujian_users.nilai','ujian_users.kurang_waktu_pengerjaan')
        ->selectRaw('DATE_FORMAT(ujian_users.start_date, "%Y-%m-%d %H:%i:%s") as start_date')
        ->selectRaw('DATE_FORMAT(ujian_users.finish_date, "%Y-%m-%d %H:%i:%s") as finish_date')
        ->selectRaw('DATE_FORMAT(ujians.tgl_ujian, "%Y-%m-%d") as tgl_ujian')

        ->join('ujians', 'ujian_users.ujian_id', '=', 'ujians.id')
        ->join('users', 'ujian_users.user_id', '=', 'users.id')

        ->where('ujian_users.id','=', $queryUjianBySoalUser->id)
        ->whereNull('ujian_users.nilai')
        ->first();

        //dd($queryUjian);

        if (!$queryUjian) {
            return response()->json([
                'message' => 'ujian tidak tersedia',
                'status' => 'error',
            ]);
        }
        else{
            $queryJawabanBenarSalah          = DB::table('soal_choice_jawabans')
            ->select('status_jawaban')
            ->where('id_soal_choice','=', $queryUjianBySoalUser->id_soal)
            ->where('id','=', $request->id_jawaban)
            ->first();

            DB::table('soal_users')->where('id', $request->soal_user_id)->update([
                'id_jawaban'    => $request->id_jawaban,
                'benar_salah'   => $queryJawabanBenarSalah->status_jawaban,
                'updated_at'     => date('Y-m-d H:i:s')
            ]);

            $waktu_awal     =   strtotime($queryUjian->start_date);
            $waktu_akhir    =   strtotime(now()); // bisa juga waktu sekarang now()
            
            $start_date = new DateTime($queryUjian->start_date);
            $since_start = $start_date->diff(new DateTime(now()));
            $minutes = $since_start->days * 24 * 60;
            $minutes += $since_start->h * 60;
            $minutes += $since_start->i;
            $sisaMenit = $queryUjian->kurang_waktu_pengerjaan - $minutes; 
            //dd($sisaMenit);
            DB::table('ujian_users')->where('id', $queryUjianBySoalUser->id)->update([
                'kurang_waktu_pengerjaan'    => $sisaMenit
            ]);


            return response()->json([
                'status' => 'success',
            ]);
        }

    }

    public function selesai($idUjianDetails,$idUjianUser, $fromApp)
    {
        //dd($request);

        $query           = DB::select("
        select 
            ujian_details.id as idUjianDetails,
            ujian_details.id_jenis_soal,
            ujian_details.jumlah_soal,
            ujian_details.waktu_pengerjaan,
            ujian_details.nilai_max,
            ujians.name as ujianName,
            ujians.id as ujianId,
            ujians.pelatihan_id as pelatihanId,
            ujian_user_details.jawaban_benar,
            ujian_user_details.jawaban_salah,
            jenis_soals.jenis_soal,
            ujian_users.id as idUjianUser,
            ujian_user_details.nilai,
            ujian_user_details.start_date,
            ujian_user_details.finish_date,
            ujian_user_details.kurang_waktu_pengerjaan,
            DATE_FORMAT(ujians.tgl_ujian, '%Y-%m-%d') as tgl_ujian,            
            DATE_FORMAT(ujians.tgl_ujian, '%d-%m-%Y') as tgl_ujian_indo
        from
            ujian_users,
            ujians,
            ujian_user_details,
            ujian_details,
            jenis_soals
        where 
            ujian_users.ujian_id = ujians.id 
            and ujian_details.id_ujian = ujians.id 
            and jenis_soals.id = ujian_details.id_jenis_soal 
            and ujian_user_details.id_ujian_user = ujian_users.id 
            and ujian_user_details.id_ujian_detail = ujian_details.id 
            and ujian_details.id = '".$idUjianDetails."'
            and ujian_users.id = '".$idUjianUser."'
            and ujian_user_details.nilai is not null
        ");

        if ($query === null) {
            return redirect('user')->with('success', 'Ujian tidak tersedia');
        }

        $dataSoals           = 
        DB::table('soal_users')
        ->select('soal_users.id_soal','soal_choices.soal_choice','soal_choice_jawabans.jawaban','soal_users.benar_salah')
        ->leftJoin('soal_choices', 'soal_choices.id', '=', 'soal_users.id_soal')
        ->leftJoin('soal_choice_jawabans', 'soal_choice_jawabans.id', '=', 'soal_users.id_jawaban')
        ->where('soal_users.id_ujian_user','=', $idUjianUser)
        ->where('soal_users.id_ujian_detail','=', $idUjianDetails)
        ->orderBy('soal_users.id')
        ->get();
        
        $data['hasilPengerjaan'] = "";

        $iii =1;
        foreach($dataSoals as $dataSoal){
            $data['hasilPengerjaan'] .= '
            <div class="row g-3 mt-4">    
                <div class="col-md-1">
                   '.$iii.'.
                </div>
                <div class="col-md-11">
                <a class="d-block text-decoration-none" href="#"> <strong class="d-block h5 mb-0">'.$dataSoal->soal_choice.'</strong>
                </a>
                </div>
            </div>
           
            ';

            if($dataSoal->benar_salah == '0'){
                $dataJawabanBenar           = DB::table('soal_choice_jawabans')
                ->select('jawaban')
                ->where('id_soal_choice','=', $dataSoal->id_soal)
                ->where('status_jawaban','=',  1)
                ->first();

                if($dataSoal->jawaban){
                    $jawaban22 = $dataSoal->jawaban;
                }
                else{
                    $jawaban22 = "<p>Tidak dijawab.</p>";
                }
                
                $data['hasilPengerjaan'] .= '
                    <div class="row ">    
                    <div class="col-md-1">
                    </div>
                    <div class="col-md-11  text-danger">
                    Jawaban Peserta (Salah)
                    </div>
                    <div class="col-md-1">
                    </div>
                    <div class="col-md-11">
                    '.$jawaban22.'
                    </div>
                </div>
                <div class="row ">    
                    <div class="col-md-1">
                    </div>
                    <div class="col-md-11">
                    Jawaban Benar
                    </div>
                    <div class="col-md-1">
                    </div>
                    <div class="col-md-11">
                    '.$dataJawabanBenar->jawaban.'
                    </div>
                </div>
                ';
            }
            else{
                $data['hasilPengerjaan'] .= '
                        <div class="row ">    
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-11 text-success">
                        Jawaban Peserta (Benar)
                        </div>
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-11">
                        '.$dataSoal->jawaban.'
                        </div>
                    </div>';
            }

            $iii++;
        }
        
       // dd( $data['hasilPengerjaan']);
        $data['row']    = $query[0];
        $data['fromApp']    = $fromApp;

        return view('user.selesai', $data);
    }

   
    public function selesaiKecermatan($idUjianDetails,$idUjianUser)
    {
        //dd($request);

        $query           = DB::select("
        select 
            ujian_details.id as idUjianDetails,
            ujian_details.id_jenis_soal,
            ujian_details.jumlah_soal,
            ujian_details.waktu_pengerjaan,
            ujian_details.nilai_max,
            ujians.name as ujianName,
            ujians.id as ujianId,
            ujian_user_details.jawaban_benar,
            ujian_user_details.jawaban_salah,
            jenis_soals.jenis_soal,
            ujian_users.id as idUjianUser,
            ujian_user_details.nilai,
            ujian_user_details.start_date,
            ujian_user_details.finish_date,
            ujian_user_details.kurang_waktu_pengerjaan,
            DATE_FORMAT(ujians.tgl_ujian, '%Y-%m-%d') as tgl_ujian,            
            DATE_FORMAT(ujians.tgl_ujian, '%d-%m-%Y') as tgl_ujian_indo
        from
            ujian_users,
            ujians,
            ujian_user_details,
            ujian_details,
            jenis_soals
        where 
            ujian_users.ujian_id = ujians.id 
            and ujian_details.id_ujian = ujians.id 
            and jenis_soals.id = ujian_details.id_jenis_soal 
            and ujian_user_details.id_ujian_user = ujian_users.id 
            and ujian_user_details.id_ujian_detail = ujian_details.id 
            and ujian_details.id = '".$idUjianDetails."'
            and ujian_users.id = '".$idUjianUser."'
            and ujian_user_details.nilai is not null
        ");

        if ($query === null) {
            return redirect('user')->with('success', 'Ujian tidak tersedia');
        }

  
        
        $data['hasilPengerjaan'] = "";

        $iii =1;
        $queryTampilSoals           = DB::table('soal_users')
        ->select(
        'soal_users.id as soal_users_id', 
        'soal_users.id_soal', 
        'soal_users.jawaban', 
        'soal_users.benar_salah')
        ->where('soal_users.id_ujian_user','=', $idUjianUser)
        ->where('soal_users.id_ujian_detail','=', $idUjianDetails)
        ->orderBy('soal_users.id','asc')
        ->get();

        $soalHtml = "";
        $soalHtml .= '
        
        <div class="row " >
            
            
            <div class="col-12">
            ';

            $idPlusPlus = 1;
            foreach($queryTampilSoals as $soal){
                
                if($idPlusPlus > 1){
                    $display = "none";
                }
                else{
                    $display = "";
                }

                
                $nilaiAwalKolom = $idPlusPlus - 1;
                $nilaiKolom = $nilaiAwalKolom / 50;
                $nilaiKolomTampil = ceil( $nilaiKolom);
                
                if($nilaiKolomTampil == 0){
                    $nilaiKolomTampil = 1;
                }

                
                $soalHtml .= '
                
                <div class="row mt-1 mr-1 ml-1 mb-4" id="tampil_soal_'.$idPlusPlus.'" style="background:#F5F5F5;:">
            
                    <table class="table table-bordered mb-0">
                        
                        <tr>
                            <td align="center">a</td>
                            <td align="center">b</td>
                            <td align="center">c</td>
                            <td align="center">d</td>
                            <td align="center">e</td>
                        </tr>
                        <tr>
                    ';

                    $queryJawabans           = DB::table('soal_gambars')
                    ->select(
                    'soal_gambars.*'
                    )
                    ->where('soal_gambars.id_soal','=', $soal->id_soal)
                    ->inRandomOrder()
                    ->get();

                    foreach($queryJawabans as $queryJawaban){
                        $soalHtml .= '  <td align="center" >'.$queryJawaban->soal.'</td>';
                    }
                

                   $soalHtml .= '
                        </tr>
                    </table>
                    
                    <table class="table table-bordered mb-0 mt-3">
                        <tr>
                    ';


                   
                   $querySoals           = DB::table('soal_gambars')
                   ->select(
                   'soal_gambars.*'
                   )
                   ->where('soal_gambars.id_soal','=', $soal->id_soal)
                   ->where('soal_gambars.status','=', 'S')
                   ->inRandomOrder()
                   ->get();
                   //dd( $soal->id_soal);

                   foreach($querySoals as $acakSoal){
                       $soalHtml .= '<td align="center">'.$acakSoal->soal.'</td>';
                   }



                $soalHtml .= '
                        </tr>
                    </table>
                
                ';

                $soalHtml .= '
                <table class="table table-bordered mb-0 mt-3">
                   
                ';


                $dataJawabanBenar           = DB::table('soal_gambars')
                ->select('*')
                ->where('id_soal','=', $soal->id_soal)
                ->where('status','=',  'B')
                ->first();

                $dataJawabanPeserta          = DB::table('soal_gambars')
                ->select('*')
                ->where('id','=', $soal->jawaban)
                ->first();

                    if($soal->benar_salah == '0'){

                       
        
                        if($soal->jawaban){
                            $jawaban22 = $soal->jawaban;
                            
                            $jawaban22 = "
                            <tr>
                                <td width='35%'><p class=' text-danger'>Jawaban Peserta (Salah)</p></td>
                                <td>".$dataJawabanPeserta->soal."</td>
                            </tr>
                            <tr>
                                <td width='35%'><p>Jawaban Benar</p></td>
                                <td>".$dataJawabanBenar->soal."</td>
                            </tr>
                            ";
                        }
                        else{
                            $jawaban22 = "
                            <tr>
                                <td width='35%'><p>Tidak dijawab.</p></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td width='35%'><p>Jawaban Benar</p></td>
                                <td>".$dataJawabanBenar->soal."</td>
                            </tr>
                            ";
                        }

                        $soalHtml .= $jawaban22;
                    }
                    else{
                        $soalHtml .= "
                        <tr>
                            <td width='35%' ><p class='text-success'>Jawaban Peserta (Benar)</p></td>
                            <td>".$dataJawabanPeserta->soal."</td>
                        </tr>";

                        
                    }

                    $soalHtml .= '
                        </table>
                    </div>
                    ';

                $idPlusPlus++;
                }
       
                DB::table('ujian_user_details')->where('id_ujian_user', $idUjianUser)->where('id_ujian_detail', $idUjianDetails)->update([
                    'start_date' => date('Y-m-d H:i:s')
                ]);
        

        $data['hasilPengerjaan']    = $soalHtml;
        
       // dd( $data['hasilPengerjaan']);
        $data['row']    = $query[0];

        return view('user.selesai', $data);
    }

    public function submitUjian(Request $request,  $idUjianDetails,$idUjianUser)
    {
        //dd($request);

        $query           = DB::select("
        select 
            ujian_details.id as idUjianDetails,
            ujian_details.id_jenis_soal,
            ujian_details.jumlah_soal,
            ujian_details.waktu_pengerjaan,
            ujian_details.nilai_max,
            ujians.name as ujianName,
            ujians.id as ujianId,
            ujian_user_details.jawaban_benar,
            ujian_user_details.jawaban_salah,
            jenis_soals.jenis_soal,
            ujian_users.id as idUjianUser,
            ujian_user_details.nilai,
            ujian_user_details.start_date,
            ujian_user_details.finish_date,
            ujian_user_details.kurang_waktu_pengerjaan,
            DATE_FORMAT(ujians.tgl_ujian, '%Y-%m-%d') as tgl_ujian,            
            DATE_FORMAT(ujians.tgl_ujian, '%d-%m-%Y') as tgl_ujian_indo
        from
            ujian_users,
            ujians,
            ujian_user_details,
            ujian_details,
            jenis_soals
        where 
            ujian_users.ujian_id = ujians.id 
            and ujian_details.id_ujian = ujians.id 
            and jenis_soals.id = ujian_details.id_jenis_soal 
            and ujian_user_details.id_ujian_user = ujian_users.id 
            and ujian_user_details.id_ujian_detail = ujian_details.id 
            and ujian_details.id = '".$idUjianDetails."'
            and ujian_users.id = '".$idUjianUser."'
            and ujian_user_details.nilai is null
        ");
        
        //dd($query);
        if ($query[0] === null) {
            return redirect('home')->with('success', 'Ujian tidak tersedia');
        }
        else{

            
            $queryTampilSoal           = DB::table('soal_users')
            ->select('soal_users.id as soal_users_id', 
            'soal_users.id_jawaban', 
            'soal_users.benar_salah',
            'soal_users.id_ujian_user',
            'soal_choices.soal_choice',
            'soal_choices.id as id_soal',)
            ->join('soal_choices', 'soal_users.id_soal', '=', 'soal_choices.id')
            ->where('soal_users.id_ujian_user','=', $idUjianUser)
            ->where('soal_users.id_ujian_detail','=', $idUjianDetails)
            ->orderBy('soal_users.id','asc')
            ->get();
            
            //dd($request);
            
            $totalJawabanSalah = 0;
            $totalJawabanBenar = 0;

            foreach($queryTampilSoal as $soal){

                $soalId             =   $soal->id_soal;
                if(isset( $_POST['jawaban_'."".$soalId])){                   
                    $ambilValue         =   $_POST['jawaban_'."".$soalId];
                }
                else{

                    $ambilValue         =   0;
                }

                $cekJawaban         =   DB::table('soal_choice_jawabans')
                ->select('id as id_jawaban')
                ->where('id_soal_choice','=', $soal->id_soal)
                ->where('status_jawaban','=', 1)
                ->first();

                if($ambilValue == $cekJawaban->id_jawaban){
                    $jawabanBenarSalah = '1';
                    $totalJawabanBenar++;
                }
                else{
                    $jawabanBenarSalah = '0';
                    $totalJawabanSalah++;
                }

                //dd( "ambilValue".$ambilValue." cekid_jawaban".$cekJawaban->id_jawaban." ".$_POST['jawaban_'."".$soalId]." --- ".$soalId);


                //dd($jawabanBenarSalah);

                $updateJawaban              = SoalUser::find($soal->soal_users_id);
                $updateJawaban->id_jawaban     = $ambilValue;
                $updateJawaban->benar_salah = $jawabanBenarSalah;
                $updateJawaban->save(); 
               // dd( $query->nilai_max   );

                $nilaiPerSoal   =   $query[0]->nilai_max / $query[0]->jumlah_soal;
                $totalNilai     =   $totalJawabanBenar * $nilaiPerSoal;
              
          

                DB::table('ujian_user_details')
                ->where('id_ujian_user', $idUjianUser)
                ->where('id_ujian_detail', $idUjianDetails)
                ->update([
                    'jawaban_benar'     => $totalJawabanBenar,
                    'jawaban_salah'     => $totalJawabanSalah,
                    'nilai'             => $totalNilai,
                    'finish_date'       => date('Y-m-d H:i:s')
                ]);

            }

            

        }
        return redirect('ujian-selesai'.'/'.$idUjianDetails.'/'.$idUjianUser.'/userApp');
    }


    public function submitUjianKecermatan(Request $request,  $idUjianDetails,$idUjianUser)
    {
        //dd($request);

        $query           = DB::select("
        select 
            ujian_details.id as idUjianDetails,
            ujian_details.id_jenis_soal,
            ujian_details.jumlah_soal,
            ujian_details.waktu_pengerjaan,
            ujian_details.nilai_max,
            ujians.name as ujianName,
            ujians.id as ujianId,
            ujian_user_details.jawaban_benar,
            ujian_user_details.jawaban_salah,
            jenis_soals.jenis_soal,
            ujian_users.id as idUjianUser,
            ujian_user_details.nilai,
            ujian_user_details.start_date,
            ujian_user_details.finish_date,
            ujian_user_details.kurang_waktu_pengerjaan,
            DATE_FORMAT(ujians.tgl_ujian, '%Y-%m-%d') as tgl_ujian,            
            DATE_FORMAT(ujians.tgl_ujian, '%d-%m-%Y') as tgl_ujian_indo
        from
            ujian_users,
            ujians,
            ujian_user_details,
            ujian_details,
            jenis_soals
        where 
            ujian_users.ujian_id = ujians.id 
            and ujian_details.id_ujian = ujians.id 
            and jenis_soals.id = ujian_details.id_jenis_soal 
            and ujian_user_details.id_ujian_user = ujian_users.id 
            and ujian_user_details.id_ujian_detail = ujian_details.id 
            and ujian_details.id = '".$idUjianDetails."'
            and ujian_users.id = '".$idUjianUser."'
            and ujian_user_details.nilai is null
        ");
        
        //dd($query);
        if (!$query) {
            return redirect('home')->with('success', 'Ujian tidak tersedia');
        }
        else{

            
            $queryTampilSoal           = DB::table('soal_users')
            ->select('soal_users.id as soal_users_id', 
            'soal_users.id_soal', 
            'soal_users.jawaban', 
            'soal_users.benar_salah',
            'soal_users.id_ujian_user')
            ->where('soal_users.id_ujian_user','=', $idUjianUser)
            ->where('soal_users.id_ujian_detail','=', $idUjianDetails)
            ->orderBy('soal_users.id','asc')
            ->get();
            
            //dd($request);
            
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

                $nilaiPerSoal   =   $query[0]->nilai_max / $query[0]->jumlah_soal;

                $totalNilai     =   $totalJawabanBenar * $nilaiPerSoal;
              
          

                DB::table('ujian_user_details')
                ->where('id_ujian_user', $idUjianUser)
                ->where('id_ujian_detail', $idUjianDetails)
                ->update([
                    'jawaban_benar'     => $totalJawabanBenar,
                    'jawaban_salah'     => $totalJawabanSalah,
                    'nilai'             => $totalNilai,
                    'finish_date'       => date('Y-m-d H:i:s')
                ]);

            }

            

        }
        return redirect('ujian-selesai-kecermatan'.'/'.$idUjianDetails.'/'.$idUjianUser.'/userApp');
    }

    public function sertifikat(Request $request,$id)
    {
        
//dd( $request->getSchemeAndHttpHost());
        $query           =      DB::table('ujian_users')
        ->select('ujians.id as ujian_id', 'ujians.name', 'ujians.nilai_max', 'ujians.jumlah_soal','ujians.min_penuhi_syarat','ujians.waktu_pengerjaan','ujians.status','users.name', 'users.id as user_id','ujian_users.id', 'ujian_users.token','ujian_users.jawaban_benar','ujian_users.jawaban_salah','ujian_users.nilai',
        'users.name as userName',
        'users.alamat',
        'users.pekerjaan',
        'users.umur',
        
        )
        ->selectRaw('DATE_FORMAT(ujian_users.start_date, "%Y-%m-%d %H:%i:%s") as start_date')
        ->selectRaw('DATE_FORMAT(ujian_users.finish_date, "%Y-%m-%d %H:%i:%s") as finish_date')
        ->selectRaw('DATE_FORMAT(ujians.tgl_ujian, "%Y-%m-%d") as tgl_ujian')

        ->join('ujians', 'ujian_users.ujian_id', '=', 'ujians.id')
        ->join('users', 'ujian_users.user_id', '=', 'users.id')

        ->where('ujian_users.id','=', $id)
        ->whereNotNull('ujian_users.nilai')
        ->first();  

        if($query->nilai < $query->min_penuhi_syarat){
            $data['lulus'] = false;
            ///dd('sini');
        }
        else{
            $data['lulus'] = true;
        }
        
        $data['row']    =   $query;
        
        $string = url('/')."/show-sertifikat/".$query->id;
        $data['qrcode'] = base64_encode(QrCode::format('svg')->size(100)->errorCorrection('H')->generate($string));
  

        //dd( $data);
        $pdf = PDF::loadView('user.sertifikat', ['data' => $data]);
        
        
        return $pdf->download('sertifikat-smartpsi.pdf');
        
        //return view('user.sertifikat', $data);

    }

    public function showSertifikat(Request $request,$id)
    {
        
//dd( $request->getSchemeAndHttpHost());
        $query           =      DB::table('ujian_users')
        ->select('ujians.id as ujian_id', 'ujians.name', 'ujians.nilai_max', 'ujians.jumlah_soal','ujians.min_penuhi_syarat','ujians.waktu_pengerjaan','ujians.status','users.name', 'users.id as user_id','ujian_users.id', 'ujian_users.token','ujian_users.jawaban_benar','ujian_users.jawaban_salah','ujian_users.nilai',
        'users.name as userName',
        'users.alamat',
        'users.pekerjaan',
        'users.umur',
        
        )
        ->selectRaw('DATE_FORMAT(ujian_users.start_date, "%Y-%m-%d %H:%i:%s") as start_date')
        ->selectRaw('DATE_FORMAT(ujian_users.finish_date, "%Y-%m-%d %H:%i:%s") as finish_date')
        ->selectRaw('DATE_FORMAT(ujians.tgl_ujian, "%Y-%m-%d") as tgl_ujian')

        ->join('ujians', 'ujian_users.ujian_id', '=', 'ujians.id')
        ->join('users', 'ujian_users.user_id', '=', 'users.id')

        ->where('ujian_users.id','=', $id)
        ->whereNotNull('ujian_users.nilai')
        ->first();  

        if($query->nilai < $query->min_penuhi_syarat){
            $data['lulus'] = false;
            ///dd('sini');
        }
        else{
            $data['lulus'] = true;
        }
        
        $data['row']    =   $query;

        
        return view('user.showSertifikat', $data);

        

    }
}