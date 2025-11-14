<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MenuAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['title'] = 'Data User';
        $data['q'] = $request->q;
        $data['rows'] = User::where('name', 'like', '%' . $request->q . '%')->where('role','=','admin')->where('email','!=','root')->paginate(20);
        return view('admin.users.index', $data);
    }
    public function indexPengajar(Request $request)
    {
        $data['title'] = 'Data User';
        $data['q'] = $request->q;
        $data['rows'] = User::where('name', 'like', '%' . $request->q . '%')->where('email','!=','root')->where('role','=','pengajar')->paginate(20);
        return view('admin.users.index', $data);
    }
    public function indexSiswa(Request $request)
    {
        $data['title'] = 'Data User';
        $data['q'] = $request->q;
        $data['rows'] = User::where('name', 'like', '%' . $request->q . '%')->where('email','!=','root')->where('role','=','user')->paginate(20);
        return view('admin.users.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data['title'] = 'Tambah User';
        $data['levels'] = ['admin' => 'Admin', 'user' => 'Siswa', 'pengajar' => 'Pengajar'];
        $data['tampilMenu'] = ['user','jenis_soal','soal','ujian','hasil_ujian', 'text_info', 'master_data_pelatihan','proses_pelatihan'];
        return view('admin.users.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email_form' => 'required',
            'password_form' => 'required',
        ]);

        $cekUsername           = DB::table('users')->select('*')
        ->where('email','=', $request->email_form)
        ->first();

        if( $cekUsername){
            return back()->withErrors( 'Tambah Data Gagal, pastikan username berbeda');

        }

        $user = new User();
        $user->name     = $request->name;
        $user->email    = $request->email_form;
        $user->role     = $request->role;
        $user->alamat     = $request->alamat;
        $user->umur     = $request->umur;
        $user->pekerjaan     = $request->pekerjaan;
        $user->password = Hash::make($request->password_form);
        $user->save();
        $insertedId = $user->id;


        if( $request->role == 'admin'){
            foreach( $request->menu as $menu){

                $menuInsert = new MenuAdmin();
                $menuInsert->id_user     = $insertedId;
                $menuInsert->menu    = $menu;
                $menuInsert->save();
            }
        }


        if($request->role == 'admin'){
            return redirect('user-admin')->with('success', 'Tambah Data Berhasil');
        }
        elseif($request->role == 'pengajar'){
            return redirect('user-pengajar')->with('success', 'Tambah Data Berhasil');
        }
        else{
            return redirect('user-siswa')->with('success', 'Tambah Data Berhasil');
        }

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
    public function edit($id)
    {

        $post           = DB::table('users')->select('*')
        ->where('id','=',$id)
        ->first();

        //dd($post->tgl_ujian);

        $data['row']    = $post;

        $data['levels'] = ['admin' => 'Admin', 'user' => 'Siswa', 'pengajar' => 'Pengajar'];


        $data['tampilMenu'] = ['user','jenis_soal','soal','ujian','hasil_ujian', 'text_info', 'master_data_pelatihan','proses_pelatihan'];


        return view('admin.users.edit', $data);
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
        $model = User::find($id);
        $model->name = $request->name;
        $model->email = $request->email;
        $model->role = $request->role;
        $model->alamat     = $request->alamat;
        $model->umur     = $request->umur;
        $model->pekerjaan     = $request->pekerjaan;
        if($request->password){
            $model->password = Hash::make($request->password);
        }
        $model->save();

        if( $request->role == 'admin'){

            DB::table('menu_admins')->where('id_user', '=', $id)->delete();

            foreach( $request->menu as $menu){

                $menuInsert = new MenuAdmin();
                $menuInsert->id_user     = $id;
                $menuInsert->menu    = $menu;
                $menuInsert->save();
            }
        }


        if($request->role == 'admin'){
            return redirect('user-admin')->with('success', 'Ubah Data Berhasil');
        }
        elseif($request->role == 'pengajar'){
            return redirect('user-pengajar')->with('success', 'Ubah Data Berhasil');
        }
        else{
            return redirect('user-siswa')->with('success', 'Ubah Data Berhasil');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){

        $ujianUser = User::find($id);
        $ujianUser ->delete();

        return redirect('user-admin')->with('success', 'Hapus Data Berhasil');
    }
}
