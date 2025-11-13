<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ujian;
use App\Models\UjianUser;
use App\Models\JenisSoal;
use App\Models\UjianDetail;
use App\Models\SoalUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;
use Illuminate\Support\Facades\Crypt;

class UjianController extends Controller
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
        return view('admin.ujian.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data['title'] = 'Tambah Ujian ';

        
        $data['listPeserta']   = User::select('*')
        ->orderBy('name')
        ->where('role','=','user')
        ->get();

        $data['jenisSoal']   = JenisSoal::select('*')
        ->orderBy('jenis_soal')
        ->get();
        
        $data['status'] = ['1' => 'Aktif', '0' => 'Tidak Aktif'];
        return view('admin.ujian.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $stringToken = rand(1111, 9999);
        //dd($request);

        if($request->jenis_peserta == 'All'){
            $request->validate([
                'name' => 'required',
                'tgl_ujian' => 'required',
                'status' => 'required',
            ]);

        }
        else{
            $request->validate([
                'name' => 'required',
                'tgl_ujian' => 'required',
                'status' => 'required',
                "id_user"    => "required|array|min:1",
            ]);

        }

        $input = new Ujian();
        $input->name                = $request->name;
        $input->token               = $stringToken;
        $input->tgl_ujian           = $request->tgl_ujian;
        $input->status              = $request->status;

        $input->save();
        $ujianIdNew = $input->id;
        
        if($request->jenis_peserta == 'All'){

            $users   = User::select('*')
            ->orderBy('id')
            ->where('role','=','user')
            ->get();
    
            $i=1;
            
            foreach($users as $user){

                $inputUjian             = new UjianUser();
                $inputUjian->ujian_id   = $ujianIdNew;
                $inputUjian->user_id    = $user->id;
                $inputUjian->save();
    
                $ujianUserIdNew         = $inputUjian->id;            
        
                $i++;
            }
        }
        else{

            $users           = $request->id_user;
            $i=1;
            foreach($users as $user){
                $token                  =   time()."".$user;
    
                $inputUjian             = new UjianUser();
                $inputUjian->ujian_id   = $ujianIdNew;
                $inputUjian->user_id    = $user;
                $inputUjian->save();    
                
                $i++;
            }
        }
        
        return redirect('ujian')->with('success', 'Tambah Data Berhasil');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
   // public function edit(Ujian $soal)
    //{
    //    $data['title'] = 'Ubah Ujian ';
    public function edit($id)
    {
        //$post           = DB::table('ujians')->where('id','=',$id)->first();

        $post           = DB::table('ujians')
        ->select('id', 'token', 'name', 'status')
        ->selectRaw('DATE_FORMAT(tgl_ujian, "%Y-%m-%d %H:%i:%s") as tgl_ujian')
        ->where('id','=',$id)
        ->first();

        $data['jenisSoal']   = JenisSoal::select('*')
        ->orderBy('jenis_soal')
        ->get();
        //dd($post->tgl_ujian);
        
        $data['row']    = $post;
        $data['status'] = ['1' => 'Aktif', '0' => 'Tidak Aktif'];
        return view('admin.ujian.edit', $data);
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model          = Ujian::find($id);
        $model->name    = $request->name;
        $model->tgl_ujian   = $request->tgl_ujian;
        $model->status     = $request->status;
        $model->save();
        
        return redirect('ujian')->with('success', 'Ubah Data Berhasil');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ujian $id)
    {
     //dd();

        $ujianDetails   = UjianDetail::select('*')
        ->where('id_ujian','=',$id->id)
        ->get();
        foreach( $ujianDetails as  $ujianDetail){

            $deleteUjianUserDetails          = DB::table('ujian_user_details')
            ->where('id_ujian_detail','=',$ujianDetail->id)
            ->delete();

            $deleteSoalUsers       = DB::table('soal_users')
            ->where('id_ujian_detail','=',$ujianDetail->id)
            ->delete();

        }

        $deleteUjianDetails          = DB::table('ujian_details')
        ->where('id_ujian','=',$id->id)
        ->delete();

        $deleteUjianUsers          = DB::table('ujian_users')
        ->where('ujian_id','=',$id->id)
        ->delete();

        $deleteUjian          = DB::table('ujians')
        ->where('id','=',$id->id)
        ->delete();
        //dd($query->toSql(), $query->getBindings());
        //dd(DB::getQueryLog());


        return redirect('ujian')->with('success', 'Hapus Data Berhasil');
    }
    
}
