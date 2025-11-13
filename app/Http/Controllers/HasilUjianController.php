<?php

namespace App\Http\Controllers;

use App\Models\Ujian;
use App\Models\UjianUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;

class HasilUjianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index(Request $request)
    {
        $data['title']  = 'Data Ujian ';
        $data['q']      = $request->q;
        $data['rows']   = Ujian::select('ujians.id', 'name','token','status')
        ->selectRaw('DATE_FORMAT(tgl_ujian, "%d-%m-%Y %H:%i") as tgl_ujian')->where('name', 'like', '%' . $request->q . '%')
        ->orderBy('id','desc')
        ->paginate(10);
        return view('admin.hasil_ujian.index', $data);
    }
   
    public function show(Request $request, $id)
    {
        

        $post           = DB::table('ujian_users')
        ->select('ujians.id as ujian_id','ujians.pelatihan_id', 'ujians.name as ujianName','ujians.status','users.name', 'users.id as user_id','ujian_users.id')
        ->selectRaw('DATE_FORMAT(ujians.tgl_ujian, "%Y-%m-%d %H:%i") as tgl_ujian')

        ->join('ujians', 'ujian_users.ujian_id', '=', 'ujians.id')
        ->join('users', 'ujian_users.user_id', '=', 'users.id')

        ->where('ujians.id','=',$id)
        ->get();
        //dd($post);
        $data['rows']    = $post;

        $dataUjian           = DB::table('ujians')->select('id', 'name','status','ujians.pelatihan_id')
        ->selectRaw('DATE_FORMAT(tgl_ujian, "%Y-%m-%d") as tgl_ujian')
        ->where('id','=',$id)
        ->first();

        $data['data_ujian']  = $dataUjian;

        $data['title']  = 'Data Peserta Ujian : '.$dataUjian->name;
        
        $data['q']      = $request->q;

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
                and id_ujian = '".$id."'
            ");

            $cekUjianUsers           = DB::select("
            select 
                *
            from
                ujian_users
            where
                user_id = '".$row->user_id."'
                and ujian_id = '".$id."'
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


        $no++;
        }

        
        $data['textHtmlShow']  = $textHtml;
        
        return view('admin.hasil_ujian.show', $data);
    }
}