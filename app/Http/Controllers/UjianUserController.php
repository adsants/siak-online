<?php

namespace App\Http\Controllers;

use App\Models\UjianUser;
use App\Models\UjianUserDetail;
use App\Models\UjianDetail;
use App\Models\SoalUser;
use App\Exports\TokenUserExport;
use App\Exports\HasilUjianExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;

class UjianUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function show(Request $request, $id)
    {


        $post           = DB::table('ujian_users')
        ->select('ujians.id as ujian_id', 'ujians.name as ujianName','ujians.status','users.name', 'users.id as user_id','ujian_users.id')
        ->selectRaw('DATE_FORMAT(ujians.tgl_ujian, "%Y-%m-%d %H:%i") as tgl_ujian')

        ->join('ujians', 'ujian_users.ujian_id', '=', 'ujians.id')
        ->join('users', 'ujian_users.user_id', '=', 'users.id')

        ->where('ujians.id','=',$id)
        ->get();
        //dd($post);
        $data['rows']    = $post;

        $dataUjian           = DB::table('ujians')->select('id', 'name','status')
        ->selectRaw('DATE_FORMAT(tgl_ujian, "%Y-%m-%d") as tgl_ujian')
        ->where('id','=',$id)
        ->first();

        $data['data_ujian']  = $dataUjian;

        $data['title']  = 'Data Peserta Ujian : '.$dataUjian->name;

        $data['q']      = $request->q;

        return view('admin.ujian_user.show', $data);
    }

    public function create(Request $request, $id)
    {

        $dataUjian           = DB::table('ujians')->select('id', 'name', 'jumlah_soal','waktu_pengerjaan','status')
        ->selectRaw('DATE_FORMAT(tgl_ujian, "%Y-%m-%d") as tgl_ujian')
        ->where('id','=',$id)
        ->first();
        $data['data_ujian']     = $dataUjian;


        return view('admin.ujian_user.create', $data);
    }
    public function store(Request $request, $id)
    {

        $dataUjian           = DB::table('ujians')
        ->select('id', 'name', 'jumlah_soal','waktu_pengerjaan','status')
        ->selectRaw('DATE_FORMAT(tgl_ujian, "%Y-%m-%d") as tgl_ujian')
        ->where('id','=',$id)
        ->first();

        $request->validate([
            'ujian_id' => 'required',
            'user_id' => 'required'
        ]);


        $variable   =   $id."-".$request->user_id;
        $encrypted  =   Crypt::encryptString($variable);


        $input              = new UjianUser();
        $input->ujian_id    = $request->ujian_id;
        $input->user_id     = $request->user_id;
        $input->token       = $encrypted;
        $input->save();
        $ujianUserIdNew     = $input->id;


        $dataUjian           = DB::table('ujians')
        ->select('id','jenis_soal','jumlah_soal')
        ->where('id','=',$request->ujian_id)
        ->first();

        $dataSoals           = DB::table('soal_choices')
        ->select('id')
        ->inRandomOrder()
        ->limit($dataUjian->jumlah_soal)
        ->get();



        foreach($dataSoals as $dataSoal){
            $inputSoalUjianUser                 = new SoalUser();
            $inputSoalUjianUser->id_soal        = $dataSoal->id;
            $inputSoalUjianUser->id_ujian_user  = $ujianUserIdNew;
            $inputSoalUjianUser->save();
        }


        return redirect('ujian-user/show/'. $dataUjian->id)->with('success', 'Tambah Data Berhasil');
    }
    public function destroy($id){

       // dd($id);

        $deleteSoalUsers         = DB::table('soal_users')
        ->where('id_ujian_user','=',$id)
        ->delete();
        $deleteUserDetail         = DB::table('ujian_user_details')
        ->where('id_ujian_user','=',$id)
        ->delete();

        $ujianUser = UjianUser::find($id);
        $ujianUser ->delete();

        return redirect('ujian-user/show/'.$ujianUser->ujian_id)->with('success', 'Hapus Data Berhasil');
    }

    public function exportToken($id)
    {
        $data = DB::table('ujian_users')
        ->select('ujians.id as ujian_id', 'ujians.name as ujian_name', 'ujians.jumlah_soal','ujians.waktu_pengerjaan','ujians.status','users.name', 'users.id as user_id','ujian_users.id', 'ujian_users.token','ujian_users.jawaban_benar','ujian_users.jawaban_salah','ujian_users.nilai')
        ->selectRaw('DATE_FORMAT(ujian_users.start_date, "%Y-%m-%d %H:%i") as start_date')
        ->selectRaw('DATE_FORMAT(ujian_users.finish_date, "%Y-%m-%d %H:%i") as finish_date')
        ->selectRaw('DATE_FORMAT(ujians.tgl_ujian, "%Y-%m-%d") as tgl_ujian')

        ->join('ujians', 'ujian_users.ujian_id', '=', 'ujians.id')
        ->join('users', 'ujian_users.user_id', '=', 'users.id')

        ->where('ujians.id','=',$id)
        ->first();

        return Excel::download(new TokenUserExport($id), 'Token User - Ujian - '.$data->ujian_name.'.xlsx');
    }

    public function exportHasilUjian($id)
    {
        $data = DB::table('ujian_users')
        ->select('ujians.id as ujian_id', 'ujians.name as ujian_name', 'ujians.jumlah_soal','ujians.waktu_pengerjaan','ujians.status','users.name', 'users.id as user_id','ujian_users.id', 'ujian_users.token','ujian_users.jawaban_benar','ujian_users.jawaban_salah','ujian_users.nilai')
        ->selectRaw('DATE_FORMAT(ujian_users.start_date, "%Y-%m-%d %H:%i") as start_date')
        ->selectRaw('DATE_FORMAT(ujian_users.finish_date, "%Y-%m-%d %H:%i") as finish_date')
        ->selectRaw('DATE_FORMAT(ujians.tgl_ujian, "%Y-%m-%d") as tgl_ujian')

        ->join('ujians', 'ujian_users.ujian_id', '=', 'ujians.id')
        ->join('users', 'ujian_users.user_id', '=', 'users.id')

        ->where('ujians.id','=',$id)
        ->first();

        return Excel::download(new HasilUjianExport($id), 'Hasil Ujian - '.$data->ujian_name.'.xlsx');
    }



    public function ujianSoalShow(Request $request, $idUjian)
    {


        $post           = DB::table('ujian_details')
        ->select('ujian_details.id','ujian_details.jumlah_soal','ujian_details.waktu_pengerjaan','ujian_details.nilai_max','ujians.name','jenis_soals.jenis_soal')
        ->selectRaw('DATE_FORMAT(ujians.tgl_ujian, "%Y-%m-%d %H:%i") as tgl_ujian')

        ->join('ujians', 'ujian_details.id_ujian', '=', 'ujians.id')
        ->join('jenis_soals', 'ujian_details.id_jenis_soal', '=', 'jenis_soals.id')

        ->orderBy('ujian_details.id')
        ->where('ujians.id','=',$idUjian)
        ->get();
        //dd($post);
        $data['rows']    = $post;

        $data['jenisSoal']   = DB::table('jenis_soals')
        ->select('*')
        ->whereNotIn('id', DB::table('ujian_details')->select('id_jenis_soal')->where('id_ujian','=',$idUjian))

        ->orderBy('jenis_soal')
        ->get();
        //dd($data['jenisSoal'] );



        $dataUjian           = DB::table('ujians')->select('id', 'name','status','pelatihan_id')
        ->selectRaw('DATE_FORMAT(tgl_ujian, "%Y-%m-%d") as tgl_ujian')
        ->where('id','=',$idUjian)
        ->first();

        $data['data_ujian']  = $dataUjian;

        $data['title']  = 'Data Peserta Ujian : '.$dataUjian->name;

        $data['q']      = $request->q;

        return view('admin.ujian_soal.show', $data);
    }

    public function ujianSoalStore(Request $request, $id)
    {
        //dd($request->tgl_ujian);

        $dataJenisSoal           = DB::table('jenis_soals')
        ->select('*')
        ->where('id','=',$request->id_jenis_soal)
        ->first();

        //dd( $dataJenisSoal);

        $request->validate([
            'jumlah_soal' => 'required',
            'waktu_pengerjaan' => 'required',
            'nilai_max' => 'required',
            'id_jenis_soal' => 'required'
        ]);


         if($dataJenisSoal->kategori == 1){
            $dataSoals           = DB::table('soal_choices')
            ->select('id')
            ->inRandomOrder()
            ->limit($request->jumlah_soal)
            ->where('id_jenis_soal','=',$request->id_jenis_soal)
            ->where('status_active','=','1')
            ->where('is_contoh','=','0')
            ->get();

        }
        else{


            $dataSoals              = DB::table('soal_gambars')
            ->select('id_soal')
            ->groupBy('id_soal')
            ->where('id_jenis_soal','=',$request->id_jenis_soal)
            ->where('aktif','=','1')
            ->inRandomOrder()
            ->limit($request->jumlah_soal)
            ->get();
        }


        if( $request->jumlah_soal > count($dataSoals)){

            return redirect('ujian-soal/show/'. $id)->with('alert', 'Proses Tambah Data Soal Ujian Gagal, Maksimal Soal yang tersedia adalah '.count($dataSoals));
        }

        //dd( $request->jumlah_soal." ".count($dataSoals));

        $variable   =   $id."-".$request->user_id;
        $encrypted  =   Crypt::encryptString($variable);


        $input                      = new UjianDetail();
        $input->id_ujian            = $id;
        $input->id_jenis_soal       = $request->id_jenis_soal;
        $input->jumlah_soal         = $request->jumlah_soal;
        $input->waktu_pengerjaan    = $request->waktu_pengerjaan;
        $input->nilai_max           = $request->nilai_max;
        $input->save();

        $newDetailUjian     = $input->id;



        $dataUjianUsers           = DB::table('ujian_users')
        ->select('id')
        ->where('ujian_id','=',$id)
        ->get();

        foreach($dataUjianUsers as $dataUjianUser){

            $ujianUserDetail                            = new UjianUserDetail();
            $ujianUserDetail->id_ujian_user             = $dataUjianUser->id;
            $ujianUserDetail->id_ujian_detail           = $newDetailUjian;
            $ujianUserDetail->kurang_waktu_pengerjaan   = $request->waktu_pengerjaan;
            $ujianUserDetail->save();


        //dd($ujianUserDetail);
            if($dataJenisSoal->kategori == 1){


                foreach($dataSoals as $dataSoal){
                    $inputSoalUjianUser                 = new SoalUser();
                    $inputSoalUjianUser->id_soal        = $dataSoal->id;
                    $inputSoalUjianUser->id_ujian_user  = $dataUjianUser->id;
                    $inputSoalUjianUser->id_ujian_detail  = $newDetailUjian;
                    $inputSoalUjianUser->save();


                }
            }
            else{


                foreach($dataSoals as $dataSoal){
                    $inputSoalUjianUser                 = new SoalUser();
                    $inputSoalUjianUser->id_soal        = $dataSoal->id_soal;
                    $inputSoalUjianUser->id_ujian_user  = $dataUjianUser->id;
                    $inputSoalUjianUser->id_ujian_detail  = $newDetailUjian;
                    $inputSoalUjianUser->save();
                }

            }
        }


        //dd($inputSoalUjianUser);
        return redirect('ujian-soal/show/'. $id)->with('success', 'Tambah Data Berhasil');
    }

    public function ujianSoalDestroy($idUjianDetail){

        $deleteSoalUsers         = DB::table('soal_users')
        ->where('id_ujian_detail','=',$idUjianDetail)
        ->delete();
        $deleteSoalUsers         = DB::table('ujian_user_details')
        ->where('id_ujian_detail','=',$idUjianDetail)
        ->delete();

        $dataUjianDetail           = DB::table('ujian_details')->select('id','id_ujian')
        ->where('id','=',$idUjianDetail)
        ->first();

        $idUjian = $dataUjianDetail->id_ujian;

        $ujianUser = UjianDetail::find($idUjianDetail);
        $ujianUser ->delete();

        return redirect('ujian-soal/show/'.$idUjian)->with('success', 'Hapus Data Berhasil');
    }
}
