<?php

namespace App\Http\Controllers;

use App\Models\UjianUser;
use App\Models\UjianUserDetail;
use App\Models\UjianDetail;
use App\Models\SoalUser;
use App\Models\User;
use App\Exports\TokenUserExport;
use App\Exports\HasilUjianExport;
use App\Models\PelatihanUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;

class PelatihanUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function show(Request $request, $id)
    {


        $post           = DB::table('pelatihan_users')
        ->select('pelatihans.id as pelatihan_id', 'pelatihans.name as pelatihanName','users.name', 'users.id as user_id','pelatihan_users.id')
        ->selectRaw('DATE_FORMAT(pelatihans.start_date, "%Y-%m-%d %H:%i") as start_date')

        ->join('pelatihans', 'pelatihan_users.pelatihan_id', '=', 'pelatihans.id')
        ->join('users', 'pelatihan_users.user_id', '=', 'users.id')

        ->where('pelatihans.id','=',$id)
        ->where('users.role','=','user')
        ->get();
        //dd($post);
        $data['rows']    = $post;

        $dataPelatihan           = DB::table('pelatihans')->select('id', 'name')
        ->selectRaw('DATE_FORMAT(start_date, "%Y-%m-%d") as start_date')
        ->where('id','=',$id)
        ->first();

        $data['listPeserta']   = User::select('*')
        ->orderBy('name')
        ->where('role','=','user')
        ->get();

        $data['data_pelatihan']  = $dataPelatihan;

        $data['title']  = 'Data Peserta Pelatihan : '.$dataPelatihan->name;

        $data['q']      = $request->q;

        return view('admin.pelatihan.user_show', $data);
    }

    public function showPengajar(Request $request, $id)
    {


        $post           = DB::table('pelatihan_users')
        ->select('pelatihans.id as pelatihan_id', 'pelatihans.name as pelatihanName','users.name', 'users.id as user_id','pelatihan_users.id')
        ->selectRaw('DATE_FORMAT(pelatihans.start_date, "%Y-%m-%d %H:%i") as start_date')

        ->join('pelatihans', 'pelatihan_users.pelatihan_id', '=', 'pelatihans.id')
        ->join('users', 'pelatihan_users.user_id', '=', 'users.id')

        ->where('pelatihans.id','=',$id)
        ->where('users.role','=','pengajar')
        ->get();
        //dd($post);
        $data['rows']    = $post;

        $dataPelatihan           = DB::table('pelatihans')->select('id', 'name')
        ->selectRaw('DATE_FORMAT(start_date, "%Y-%m-%d") as start_date')
        ->where('id','=',$id)
        ->first();

        $data['listPeserta']   = User::select('*')
        ->orderBy('name')
        ->where('role','=','pengajar')
        ->get();

        $data['data_pelatihan']  = $dataPelatihan;

        $data['title']  = 'Data Peserta Pelatihan : '.$dataPelatihan->name;

        $data['q']      = $request->q;

        return view('admin.pelatihan.pengajar_show', $data);
    }

    public function store(Request $request, $id)
    {

        $dataPesertaPelatihan           = DB::table('pelatihan_users')->select('id')
        ->where('pelatihan_id','=',$id)
        ->where('user_id','=',$request->user_id)
        ->first();

        if($dataPesertaPelatihan){
            return redirect('pelatihan/'.$request->jenis.'/'. $id)->with('error', 'Tambah Data Gagal, peserta sudah termasuk di Pelatihan.');
        }
        else{
            $input                  = new PelatihanUser();
            $input->pelatihan_id    = $id;
            $input->user_id         = $request->user_id;
            $input->save();
            return redirect('pelatihan/'.$request->jenis.'/'. $id)->with('success', 'Tambah Data Berhasil');
        }

    }
    public function destroy($id,$jenis){

       // dd($id);

        $pelatihanUser = PelatihanUser::find($id);
        $pelatihanUser ->delete();

        return redirect('pelatihan/'.$jenis.'/'.$pelatihanUser->pelatihan_id)->with('success', 'Hapus Data Berhasil');
    }


}
