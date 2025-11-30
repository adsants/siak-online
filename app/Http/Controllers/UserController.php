<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Models\Pelatihan;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        $data['user'] = Auth::user();

        $ujians           = DB::select("
            select
                distinct(ujians.id) as idUjian,
                ujians.name as ujian_name,
                ujians.token
            from
                ujians,
                ujian_users,
                ujian_user_details,
                ujian_details
            where
                ujians.id = ujian_users.ujian_id
                and ujian_user_details.id_ujian_user = ujian_users.id
                and ujian_details.id_ujian = ujians.id
                and ujian_users.user_id = '".$data['user']->id."'
                and ujian_user_details.nilai is null
                and ujians.status = 1
                and ujians.tgl_ujian like '".date('Y-m-d')."%'
            order by
                idUjian desc
        ");

        $tableHtml  = "";
        foreach($ujians as $ujian){



            $tableHtml  .= "
            <tr>
                <td colspan='5'>
                    <b>".$ujian->ujian_name."</b>
                </td>
            </tr>
            ";

            $ujianDetails           = DB::select("
            select

                distinct(jenis_soals.id) as idJenisSoal,
                jenis_soals.jenis_soal,
                ujian_details.jumlah_soal,
                ujian_details.id,
                ujian_details.waktu_pengerjaan
            from
                ujian_users,
                ujian_user_details,
                ujian_details,
                jenis_soals
            where
                ujian_user_details.id_ujian_user = ujian_users.id
                and ujian_details.id_ujian = '".$ujian->idUjian."'
                and ujian_users.user_id = '".$data['user']->id."'
                and jenis_soals.id = ujian_details.id_jenis_soal
            order by
                ujian_details.id
            ");
            //dd( $ujianDetails);





            $jenisSoalSudahDikerjakanUrutan = 1;

            $jenisSoalSudahDikerjakanUrutanKeDua = 0;

            $jenisSoalSudahDikerjakan = 1;

            foreach($ujianDetails as $ujianDetail){



                $getUjianDetails           = DB::select("
                select
                    *
                from
                    ujian_details
                where
                    id_jenis_soal = '".$ujianDetail->idJenisSoal."'
                    and id_ujian = '".$ujian->idUjian."'
                ");


                $getIdUjianUser           = DB::select("
                select
                    *
                from
                    ujian_users
                where
                    ujian_id= '".$ujian->idUjian."'
                    and user_id = '".$data['user']->id."'
                ");

                $ujianUserDetails           = DB::select("
                select
                    *
                from
                    ujian_user_details
                where
                    id_ujian_detail = '".$getUjianDetails[0]->id."'
                    and id_ujian_user = '".$getIdUjianUser[0]->id."'
                ");

                if($ujianUserDetails){

                    $nameUjianPopUp = $ujian->ujian_name." - ".$ujianDetail->jenis_soal;
                    $buttonKerjakan = '
                    <button type="button" class="btn btn-success" onclick="popUpToken(\''.$nameUjianPopUp.'\',\''.$ujian->token.'\',\''.$getUjianDetails[0]->id.'\',\''.$getIdUjianUser[0]->id.'\')">Kerjakan Ujian</button>
                    ';

                    $sqkCekSebelumnya ="";



                    if($jenisSoalSudahDikerjakanUrutan == 1)
                    {
                        if($ujianUserDetails[0]->nilai != ''){
                            $button = "sudah dikerjakan";
                        }else{
                            $button = $buttonKerjakan;
                        }
                    }
                    else{
                        $sqkCekSebelumnya ="
                        select
                            *
                        from
                            ujian_user_details
                        where
                            id_ujian_user = '".$getIdUjianUser[0]->id."'
                        order by id_ujian_detail
                            limit 1 offset ".$jenisSoalSudahDikerjakanUrutanKeDua;
                        $ujianUserDetailsSebelumnya           = DB::select($sqkCekSebelumnya);


                        if($ujianUserDetailsSebelumnya){
                            if($ujianUserDetailsSebelumnya[0]->nilai == ''){
                                $button = "Dikerjakan secara Berurutan";

                            }else{
                                if($ujianUserDetails[0]->nilai != ''){
                                    $button = "sudah dikerjakan";
                                }else{
                                    $button = $buttonKerjakan;
                                }

                            }

                        }else{
                            $button = "Dikerjakan secara Berurutan";
                        }

                        $jenisSoalSudahDikerjakanUrutanKeDua++;



                    }


                    //echo $jenisSoalSudahDikerjakan;


                    $tableHtml  .= "
                    <tr>
                        <td colspan='2' align='right'>
                            ".$ujianDetail->jenis_soal."
                        </td>
                        <td >
                            ".$ujianDetail->jumlah_soal."
                        </td>
                        <td >
                            ".$ujianDetail->waktu_pengerjaan." Menit
                        </td>
                        <td align='center'>
                            ".$button ."
                        </td>
                    </tr>
                    ";



                    $jenisSoalSudahDikerjakanUrutan++;

                }

            }

        }



        if(!$ujians){
                $tableHtml  .= "
            <tr>
                <td colspan='5'>
                Belum ada Ujian yang dapat Anda kerjakan.
                </td>
            </tr>";
        }

        $data['tableHtml']    = $tableHtml;

        return view('user.home', $data);
    }

    public function riwayat()
    {
        $data['user'] = Auth::user();

        $ujians           = DB::select("
            select
                distinct(ujians.id) as idUjian,
                ujians.name as ujian_name,
                ujians.token
            from
                ujians,
                ujian_users,
                ujian_user_details,
                ujian_details
            where
                ujians.id = ujian_users.ujian_id
                and ujian_user_details.id_ujian_user = ujian_users.id
                and ujian_details.id_ujian = ujians.id
                and ujian_users.user_id = '".$data['user']->id."'
                and ujian_user_details.nilai is not null
                and ujians.status = 1
            order by
                idUjian desc
        ");

        $tableHtml  = "";
        foreach($ujians as $ujian){



            $tableHtml  .= "
            <tr>
                <td colspan='5'>
                    <b>".$ujian->ujian_name."</b>
                </td>
            </tr>
            ";

            $ujianDetails           = DB::select("
            select

                distinct(jenis_soals.id) as idJenisSoal,
                jenis_soals.jenis_soal,
                jenis_soals.kategori,
                ujian_details.jumlah_soal,
                ujian_details.id,
                ujian_details.waktu_pengerjaan
            from
                ujian_users,
                ujian_user_details,
                ujian_details,
                jenis_soals
            where
                ujian_user_details.id_ujian_user = ujian_users.id
                and ujian_details.id_ujian = '".$ujian->idUjian."'
                and ujian_users.user_id = '".$data['user']->id."'
                and jenis_soals.id = ujian_details.id_jenis_soal
            order by
                ujian_details.id
            ");


            $jenisSoalSudahDikerjakanUrutan = 1;

            $jenisSoalSudahDikerjakanUrutanKeDua = 0;

            $jenisSoalSudahDikerjakan = 1;

            foreach($ujianDetails as $ujianDetail){

                $getUjianDetails           = DB::select("
                select
                    *
                from
                    ujian_details
                where
                    id_jenis_soal = '".$ujianDetail->idJenisSoal."'
                    and id_ujian = '".$ujian->idUjian."'
                ");


                $getIdUjianUser           = DB::select("
                select
                    *
                from
                    ujian_users
                where
                    ujian_id= '".$ujian->idUjian."'
                    and user_id = '".$data['user']->id."'
                ");

                $ujianUserDetails           = DB::select("
                select
                    *
                from
                    ujian_user_details
                where
                    id_ujian_detail = '".$getUjianDetails[0]->id."'
                    and id_ujian_user = '".$getIdUjianUser[0]->id."'
                ");



                if($ujianUserDetails[0]->nilai != ''){
                    if($ujianDetail->kategori ==2){
                            $button = "<a href='".url("ujian-selesai-kecermatan")."/".$getUjianDetails[0]->id."/".$getIdUjianUser[0]->id."'>Detail Hasil Ujian</a>";
                    }
                    else{
                        $button = "<a href='".url("ujian-selesai")."/".$getUjianDetails[0]->id."/".$getIdUjianUser[0]->id."'>Detail Hasil Ujian</a>";

                    }
                        }else{
                    $button = "Belum Dikerjakan";
                }


                $tableHtml  .= "
                <tr>
                    <td colspan='2' align='right'>
                        ".$ujianDetail->jenis_soal."
                    </td>
                    <td >
                        ".$ujianDetail->jumlah_soal."
                    </td>
                    <td >
                        ".$ujianDetail->waktu_pengerjaan." Menit
                    </td>
                    <td align='center'>
                        ".$button ."
                    </td>
                </tr>
                ";

                $jenisSoalSudahDikerjakanUrutan++;

            }
        }


        $data['tableHtml']    = $tableHtml;

        return view('user.home', $data);
    }



    public function riwayatPelatihan()
    {
        $data['user'] = Auth::user();

        $post   = DB::table('pelatihan_users')
            ->select('pelatihans.id as pelatihan_id', 'pelatihans.name as pelatihanName', 'pelatihans.deskripsi', 'users.name', 'users.id as user_id', 'pelatihan_users.id')
            ->selectRaw('DATE_FORMAT(pelatihans.start_date, "%Y-%m-%d %H:%i") as start_date')
            ->join('pelatihans', 'pelatihan_users.pelatihan_id', '=', 'pelatihans.id')
            ->join('users', 'pelatihan_users.user_id', '=', 'users.id')
            ->where('users.role', '=', 'user')
            ->where('users.id', '=', $data['user']->id)
            ->orderBy('pelatihans.id', 'desc');

        $data['rows'] = $post->paginate(50);

        return view('user.riwayat_pelatihan', $data);
    }


     public function riwayatPelatihanDetail(Request $request, $pelatihanId)
    {
        $data['user'] = Auth::user();

        $dataPelatihan           = DB::table('pelatihan_users')
            ->select('pelatihans.id as pelatihan_id', 'pelatihans.name as pelatihanName', 'pelatihans.deskripsi', 'users.name', 'users.id as user_id', 'pelatihan_users.id')
            ->selectRaw('DATE_FORMAT(pelatihans.start_date, "%Y-%m-%d %H:%i") as start_date')
            ->join('pelatihans', 'pelatihan_users.pelatihan_id', '=', 'pelatihans.id')
            ->join('users', 'pelatihan_users.user_id', '=', 'users.id')
            ->where('users.role', '=', 'user')
            ->where('users.id', '=', $data['user']->id)
            ->where('pelatihans.id', '=', $pelatihanId)
            ->orderBy('pelatihans.id', 'desc')
            ->first();
        $data['data_pelatihan']  = $dataPelatihan;

        if(! $dataPelatihan){
            return redirect()->route('riwayatPelatihan');
        }

        $pelatihanModules = DB::table('pelatihan_modules')
        ->select('pelatihan_modules.module_name','pelatihan_modules.module_deskripsi', 'users.name', 'users.id as user_id', 'pelatihan_modules.id')
            ->selectRaw('DATE_FORMAT(pelatihan_modules.created_at, "%d-%m-%Y") AS tgl')
        ->join('users', 'pelatihan_modules.user_id', '=', 'users.id')
        ->where('pelatihan_modules.pelatihan_id', $pelatihanId)
        ->get();

        $tableAbsensi = "";
        foreach($pelatihanModules as $pelatihanModule){
            $tableAbsensi .= "<tr>";
            $tableAbsensi .= "<td>".$pelatihanModule->tgl."</td>";
            $tableAbsensi .= "<td>".$pelatihanModule->name."</td>";
            $tableAbsensi .= "<td>".$pelatihanModule->module_name;
            if($pelatihanModule->module_deskripsi != ''){
                $tableAbsensi .= "<br>".$pelatihanModule->module_deskripsi;
            }
            $tableAbsensi .= "</td>";

                $tglYmd = Carbon::createFromFormat('d-m-Y', $pelatihanModule->tgl)->toDateString();
                $dataPresensi           = DB::table('pelatihan_presensis')->select('jenis_presensi', 'keterangan_presensi')
                ->where('pelatihan_id', '=', $pelatihanId)
                ->where('user_id', '=', $data['user']->id)
                ->whereDate('created_at', $tglYmd)
                ->first();

                //dd( $dataPresensi);

                if($dataPresensi){
                    $cekAbsen = \cekmenuadmin::hasilAbsen($dataPresensi->jenis_presensi);

                    if($dataPresensi->jenis_presensi == 'P'){
                        $hasil = '<span class="text-success fw-bold"><b>'.$cekAbsen.'</b></span>';
                    }
                    else{
                        $hasil = '<span class="text-danger fw-bold"><b>'.$cekAbsen.'</b></span>';
                        if($dataPresensi->keterangan_presensi != ''){
                            $hasil .= '<br><span>'.$dataPresensi->keterangan_presensi.'</span>';
                        }
                    }
                }
                else{
                    $hasil = "-";
                }

                 //$hasil = $pelatihanId."-".$pelatihanModule->user_id."-".$tglYmd;

            $tableAbsensi .= "<td>".$hasil."</td>";
        }
        if(count($pelatihanModules) == 0){
            $tableAbsensi .= "<tr><td colspan='5'>Tidak ada data Absensi.</td></tr>";
        }

        $data['tableAbsensi']    = $tableAbsensi;



        $ujians           = DB::select("
            select
                distinct(ujians.id) as idUjian,
                ujians.name as ujian_name,
                ujians.token
            from
                ujians,
                ujian_users,
                ujian_user_details,
                ujian_details
            where
                ujians.id = ujian_users.ujian_id
                and ujian_user_details.id_ujian_user = ujian_users.id
                and ujian_details.id_ujian = ujians.id
                and ujian_users.user_id = '".$data['user']->id."'
                and ujians.pelatihan_id = '".$pelatihanId."'
                and ujian_user_details.nilai is not null
                and ujians.status = 1
            order by
                idUjian asc
        ");

        $tableHtml  = "";
        foreach($ujians as $ujian){



            $tableHtml  .= "
            <tr>
                <td colspan='5'>
                    <b>".$ujian->ujian_name."</b>
                </td>
            </tr>
            ";

            $ujianDetails           = DB::select("
            select

                distinct(jenis_soals.id) as idJenisSoal,
                jenis_soals.jenis_soal,
                jenis_soals.kategori,
                ujian_details.jumlah_soal,
                ujian_details.id,
                ujian_details.waktu_pengerjaan
            from
                ujian_users,
                ujian_user_details,
                ujian_details,
                jenis_soals
            where
                ujian_user_details.id_ujian_user = ujian_users.id
                and ujian_details.id_ujian = '".$ujian->idUjian."'
                and ujian_users.user_id = '".$data['user']->id."'
                and jenis_soals.id = ujian_details.id_jenis_soal
            order by
                ujian_details.id
            ");


            $jenisSoalSudahDikerjakanUrutan = 1;

            $jenisSoalSudahDikerjakanUrutanKeDua = 0;

            $jenisSoalSudahDikerjakan = 1;

            foreach($ujianDetails as $ujianDetail){

                $getUjianDetails           = DB::select("
                select
                    *
                from
                    ujian_details
                where
                    id_jenis_soal = '".$ujianDetail->idJenisSoal."'
                    and id_ujian = '".$ujian->idUjian."'
                ");


                $getIdUjianUser           = DB::select("
                select
                    *
                from
                    ujian_users
                where
                    ujian_id= '".$ujian->idUjian."'
                    and user_id = '".$data['user']->id."'
                ");

                $ujianUserDetails           = DB::select("
                select
                    *
                from
                    ujian_user_details
                where
                    id_ujian_detail = '".$getUjianDetails[0]->id."'
                    and id_ujian_user = '".$getIdUjianUser[0]->id."'
                ");


                if($ujianUserDetails){

                    if($ujianUserDetails[0]->nilai != ''){
                        if($ujianDetail->kategori ==2){
                                $button = "<a href='".url("ujian-selesai-kecermatan")."/".$getUjianDetails[0]->id."/".$getIdUjianUser[0]->id."/user'>Detail Hasil Ujian</a>";
                        }
                        else{
                            $button = "<a href='".url("ujian-selesai")."/".$getUjianDetails[0]->id."/".$getIdUjianUser[0]->id."/user'>Detail Hasil Ujian</a>";

                        }
                    }else{
                        $button = "Belum Dikerjakan";
                    }


                    $tableHtml  .= "
                    <tr>
                        <td colspan='2' align=''>
                            ".$ujianDetail->jenis_soal."
                        </td>
                        <td >
                            ".$ujianDetail->jumlah_soal."
                        </td>
                        <td >
                            ".$ujianDetail->waktu_pengerjaan." Menit
                        </td>
                        <td align='center'>
                            ".$button ."
                        </td>
                    </tr>
                    ";

                    $jenisSoalSudahDikerjakanUrutan++;
                }

            }
        }


        if(count($ujians) == 0){
            $tableHtml .= "<tr><td colspan='5'>Tidak ada data Ujian.</td></tr>";
        }


        $data['tableUjian']    = $tableHtml;

        return view('user.riwayat_pelatihan_detail', $data);
    }


}
