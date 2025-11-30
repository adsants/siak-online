<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\MenuAdmin;
use App\Models\PelatihanModule;
use App\Models\Ujian;
use App\Models\UjianUser;
use App\Models\JenisSoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;
use Auth;
use Carbon\Carbon;

class ProsesPelatihanUjianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $pelatihanId)
    {

        $dataPelatihan           = DB::table('pelatihans')->select('id', 'name')
            ->selectRaw('DATE_FORMAT(start_date, "%Y-%m-%d") as start_date')
            ->where('id', '=', $pelatihanId)
            ->first();
        $data['data_pelatihan']  = $dataPelatihan;

        if(! $dataPelatihan){
              return redirect()
                ->route('prosesPelatihanView');
        }





        $query = Pelatihan::leftJoin('ujians', 'ujians.pelatihan_id', '=', 'pelatihans.id')
            ->select('ujians.id', 'ujians.name','token','ujians.status')
            ->selectRaw('DATE_FORMAT(tgl_ujian, "%d-%m-%Y") as tgl_ujian')
            ->where('pelatihans.name', 'like', '%' . $request->q . '%')
            ->where('ujians.pelatihan_id', '=',  $pelatihanId)
            ->orderBy('ujians.id', 'asc');

        $data['rows'] = $query->paginate(50);

        return view('admin.pelatihan.ujian_view', $data);
    }

     public function create(Request $request, $pelatihanId)
    {

        $dataPelatihan           = DB::table('pelatihans')->select('id', 'name')
            ->selectRaw('DATE_FORMAT(start_date, "%Y-%m-%d") as start_date')
            ->where('id', '=', $pelatihanId)
            ->first();
        $data['data_pelatihan']  = $dataPelatihan;

        if(! $dataPelatihan){
              return redirect()
                ->route('prosesPelatihanView');
        }

        $data['title'] = 'Tambah Ujian ';

        $data['jenisSoal']   = JenisSoal::select('*')
        ->orderBy('jenis_soal')
        ->get();

        $data['status'] = ['1' => 'Aktif', '0' => 'Tidak Aktif'];

        return view('admin.pelatihan.ujian_create', $data);
    }



    public function edit($id)
    {
        //$post           = DB::table('ujians')->where('id','=',$id)->first();



        $post           = DB::table('ujians')
        ->select('*')
        ->selectRaw('DATE_FORMAT(tgl_ujian, "%Y-%m-%d %H:%i:%s") as tgl_ujian')
        ->where('id','=',$id)
        ->first();

        $data_pelatihan           = DB::table('pelatihans')
        ->select('*')
        ->where('id','=',$post->pelatihan_id)
        ->first();
        $data['data_pelatihan']    = $data_pelatihan;


        $data['jenisSoal']   = JenisSoal::select('*')
        ->orderBy('jenis_soal')
        ->get();
        //dd($post->tgl_ujian);

        $data['row']    = $post;
        $data['status'] = ['1' => 'Aktif', '0' => 'Tidak Aktif'];
        return view('admin.pelatihan.ujian_edit', $data);
    }

    public function update(Request $request, $id)
    {

//dd($request->name);
        $post           = DB::table('ujians')
        ->select('*')
        ->selectRaw('DATE_FORMAT(tgl_ujian, "%Y-%m-%d %H:%i:%s") as tgl_ujian')
        ->where('id','=',$id)
        ->first();

       // dd($post);
        $model          = Ujian::find($id);
        $model->name    = $request->name;
        $model->tgl_ujian   = $request->tgl_ujian;
        $model->status     = $request->status;
        $model->save();
        return redirect('proses-pelatihan/ujian/'.$post->pelatihan_id)->with('success', 'Ubah Data Ujian Berhasil');

    }

    public function save(Request $request, $pelatihanId)
    {
        $stringToken = rand(1111, 9999);


        $input = new Ujian();
        $input->name                = $request->name;
        $input->token               = $stringToken;
        $input->tgl_ujian           = $request->tgl_ujian;
        $input->status              = $request->status;
        $input->pelatihan_id   = $pelatihanId;
        $input->save();
        $ujianIdNew = $input->id;


         $userPelatihans           = DB::table('pelatihan_users')
        ->select('users.id as user_id')
        ->selectRaw('DATE_FORMAT(pelatihans.start_date, "%Y-%m-%d %H:%i") as start_date')

        ->join('pelatihans', 'pelatihan_users.pelatihan_id', '=', 'pelatihans.id')
        ->join('users', 'pelatihan_users.user_id', '=', 'users.id')

        ->where('pelatihans.id','=',$pelatihanId)
        ->where('users.role','=','user')
        ->get();

        foreach($userPelatihans as $user){

            $inputUjian             = new UjianUser();
            $inputUjian->ujian_id   = $ujianIdNew;
            $inputUjian->user_id    = $user->user_id;
            $inputUjian->save();

        }

        return redirect('proses-pelatihan/ujian/'.$pelatihanId)->with('success', 'Tambah Data Ujian Berhasil');
    }



    public function showNilai(Request $request, $ujianId)
    {


        $post           = DB::table('ujian_users')
        ->select('ujians.id as ujian_id','ujians.pelatihan_id', 'ujians.name as ujianName','ujians.status','users.name', 'users.id as user_id','ujian_users.id')
        ->selectRaw('DATE_FORMAT(ujians.tgl_ujian, "%Y-%m-%d %H:%i") as tgl_ujian')

        ->join('ujians', 'ujian_users.ujian_id', '=', 'ujians.id')
        ->join('users', 'ujian_users.user_id', '=', 'users.id')

        ->where('ujians.id','=',$ujianId)
        ->get();
        //dd($post);
        $data['rows']    = $post;

        $dataUjian           = DB::table('ujians')->select('id', 'name','status')
        ->selectRaw('DATE_FORMAT(tgl_ujian, "%Y-%m-%d") as tgl_ujian')
        ->where('id','=',$ujianId)
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
                and id_ujian = '".$ujianId."'
            ");

            $cekUjianUsers           = DB::select("
            select
                *
            from
                ujian_users
            where
                user_id = '".$row->user_id."'
                and ujian_id = '".$ujianId."'
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
