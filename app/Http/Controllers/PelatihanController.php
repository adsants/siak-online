<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\MenuAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;

class PelatihanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['title'] = 'Data Jenis Soal';
        $data['q'] = $request->q;
        $data['rows'] = Pelatihan::where('name', 'like', '%' . $request->q . '%')
        ->orderBy('start_date','desc')
        ->orderBy('name','asc')
        ->paginate(50);
        return view('admin.pelatihan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data['title'] = 'Tambah Pelatihan';
        return view('admin.pelatihan.create', $data);
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
        ]);

        $pelatihan = new Pelatihan();
        $pelatihan->name     = $request->name;
        $pelatihan->deskripsi    = $request->deskripsi;
        $pelatihan->start_date     = $request->start_date;
        $pelatihan->end_date     = $request->end_date;
        $pelatihan->save();
        $insertedId = $pelatihan->id;


        return redirect('pelatihan')->with('success', 'Tambah Data Berhasil');
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
       
        $post           = DB::table('pelatihans')->select('*')
        ->where('id','=',$id)
        ->first();

        //dd($post->tgl_ujian);
        
        $data['row']    = $post;


        return view('admin.pelatihan.edit', $data);
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
        $model = Pelatihan::find($id);
        $model->name = $request->name;
        $model->deskripsi = $request->deskripsi;
        $model->start_date = $request->start_date;
        $model->end_date     = $request->end_date;
        $model->save();

        return redirect('pelatihan')->with('success', 'Ubah Data Berhasil');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){

        $ujianUser = Pelatihan::find($id);
        $ujianUser ->delete();

        return redirect('pelatihan')->with('success', 'Hapus Data Berhasil');
    }
}