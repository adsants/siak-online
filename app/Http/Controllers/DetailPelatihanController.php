<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\MenuAdmin;
use App\Models\PelatihanModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;
use Auth;
use Carbon\Carbon;

class DetailPelatihanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index(Request $request, $pelatihanId)
    {
         $dataPelatihan           = DB::table('pelatihans')->select('id', 'name')
            ->where('id', '=', $pelatihanId)
            ->first();


        $data['data_pelatihan']  = $dataPelatihan;

        if (!$dataPelatihan) {
            return redirect()->route('pelatihanView');
        }


        $post  = DB::table('pelatihan_users')
            ->select('pelatihans.id as pelatihan_id', 'pelatihans.name as pelatihanName', 'users.name', 'users.id as user_id', 'pelatihan_users.id')
            ->selectRaw('DATE_FORMAT(pelatihans.start_date, "%Y-%m-%d %H:%i") as start_date')

            ->join('pelatihans', 'pelatihan_users.pelatihan_id', '=', 'pelatihans.id')
            ->join('users', 'pelatihan_users.user_id', '=', 'users.id')

            ->where('pelatihans.id', '=', $pelatihanId)
            ->where('users.role', '=', 'user')
            ->get();
        //dd($post);
        $data['rows']           = $post;
        $hasilPresensi = DB::table('pelatihan_modules')
            ->select('users.name','pelatihan_modules.module_name','pelatihan_modules.module_deskripsi' )
            ->selectRaw('DATE_FORMAT(pelatihan_modules.created_at, "%d-%m-%Y") AS tgl')
            ->join('users', 'pelatihan_modules.user_id', '=', 'users.id')
            ->where('pelatihan_id', $pelatihanId)
            ->orderBy('tgl', 'asc')
            ->get();


        $tableHasil = "
        <thead class='table-light'>
        <tr>
            <th rowspan='2'>No</th>
            <th rowspan='2'>Nama</th>";
        foreach ($hasilPresensi as $row) {



                $tableHasil .= "<th>".$row->module_name."</th>";
        }
        $tableHasil .= "</tr><tr>";
         foreach ($hasilPresensi as $row) {



                $tableHasil .= "<th>". $row->tgl  ."</th>";
        }

        $tableHasil .= "</tr>
        </thead>
        <tbody>";
        $i = 1;
        foreach ($post as $row) {




            $tableHasil .= "<tr>
            <td>" . $i++ . "</td>
            <td>" . $row->name . "</td>";


            foreach ($hasilPresensi as $row2) {
                $tglYmd = Carbon::createFromFormat('d-m-Y', $row2->tgl)->toDateString(); // Y-m-d


                $dataPresensi           = DB::table('pelatihan_presensis')->select('jenis_presensi', 'keterangan_presensi')
                ->where('pelatihan_id', '=', $pelatihanId)
                ->where('user_id', '=', $row->user_id)
                ->whereDate('created_at', $tglYmd)
                ->first();


                if($dataPresensi){

                    $cekAbsen = \cekmenuadmin::hasilAbsen($dataPresensi->jenis_presensi);

                    if($dataPresensi->jenis_presensi == 'P'){
                        $hasil = '<span class="text-success">'.$cekAbsen.'</span>';
                    }
                    else{

                        $hasil = '<span class="text-danger">'.$cekAbsen.'</span>';
                        if($dataPresensi->keterangan_presensi != ''){
                            // $hasil .= '<br><span>'.$dataPresensi->keterangan_presensi.'</span>';
                        }
                    }

                }
                else{
                    $hasil = "-";
                }



                $tableHasil .= "<td>". $hasil  ."</td>";
            }

            $tableHasil .= " </tr>";
        }

        $tableHasil .= " </tbody>";
        $data['tableHasil']  = $tableHasil;



        $post           = DB::table('ujian_users')
        ->select('ujians.id as ujian_id', 'ujians.name as ujianName','ujians.status','users.name', 'users.id as user_id','ujian_users.id')
        ->selectRaw('DATE_FORMAT(ujians.tgl_ujian, "%Y-%m-%d %H:%i") as tgl_ujian')

        ->join('ujians', 'ujian_users.ujian_id', '=', 'ujians.id')
        ->join('users', 'ujian_users.user_id', '=', 'users.id')

        ->where('ujians.pelatihan_id','=',$pelatihanId)
        ->get();
        //dd($post);
        $data['rows']    = $post;


        $textHtml = "";

        $no=1;
        foreach($post as $row){
            $textHtml .= "
            <tr>
                <td width='5%'>".$no."</td>
                <td colspan='5'><b>".$row->name."</b></td>
            </tr>";

            $dataUjianJenisSoals           = DB::select("
            select
                jenis_soals.jenis_soal,
                ujian_details.id
            from
                ujian_details,
                jenis_soals
            where
                ujian_details.id_jenis_soal = jenis_soals.id
                and id_ujian = '".$row->ujian_id."'
            ");

            $cekUjianUsers           = DB::select("
            select
                *
            from
                ujian_users
            where
                user_id = '".$row->user_id."'
                and ujian_id = '".$row->ujian_id."'
            ");

            foreach($dataUjianJenisSoals as $dataUjianJenisSoal){

                $cekUjianUserDetail           = DB::select("
                select
                    *,
                    DATE_FORMAT(start_date, '%d-%m-%Y %H:%i') as start_date_indo
                from
                    ujian_user_details
                where
                    id_ujian_user = '".$cekUjianUsers[0]->id."'
                    and id_ujian_detail = '".$dataUjianJenisSoal->id."'
                ");


                if($cekUjianUserDetail  ){

                    if($cekUjianUserDetail[0]->nilai != ''){
                        $textHtml .= "
                        <tr>
                            <td></td>
                            <td>".$dataUjianJenisSoal->jenis_soal."</td>
                            <td>".$cekUjianUserDetail[0]->start_date_indo."</td>
                            <td>".$cekUjianUserDetail[0]->jawaban_benar."</td>
                            <td>".$cekUjianUserDetail[0]->jawaban_salah."</td>
                            <td>".$cekUjianUserDetail[0]->nilai."</td>
                        </tr>";
                    }
                    else{
                        $textHtml .= "
                        <tr>
                            <td></td>
                            <td>".$dataUjianJenisSoal->jenis_soal."</td>
                            <td colspan=4> Belum dikerjakan</td>
                        </tr>";
                    }

                }
            }


        $no++;
        }


        $data['textHtmlShow']  = $textHtml;


        return view('admin.proses_pelatihan.detail', $data);
    }


}
