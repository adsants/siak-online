<?php

namespace App\Http\Controllers;

use App\Models\JenisSoal;
use App\Models\MenuAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;

class JenisSoalController extends Controller
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
        $data['rows'] = JenisSoal::where('jenis_soal', 'like', '%' . $request->q . '%')
        ->orderBy('kategori','asc')
        ->orderBy('jenis_soal','asc')
        ->paginate(50);
        return view('admin.jenis_soal.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data['title'] = 'Tambah Jenis Soal';
        $data['levels'] = ['admin' => 'Admin', 'user' => 'User'];
        $data['tampilMenu'] = ['user','soal','ujian','hasil_ujian', 'grafik'];
        return view('admin.jenis_soal.create', $data);
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
            'jenis_soal' => 'required',
            'kategori' => 'required',
        ]);

        $JenisSoal = new JenisSoal();
        $JenisSoal->jenis_soal     = $request->jenis_soal;
        $JenisSoal->kategori     = $request->kategori;
        $JenisSoal->save();
        $insertedId = $JenisSoal->id;


        return redirect('jenis-soal')->with('success', 'Tambah Data Berhasil');
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
       
        $post           = DB::table('jenis_soals')->select('*')
        ->where('id','=',$id)
        ->first();

        //dd($post->tgl_ujian);
        
        $data['row']    = $post;


        return view('admin.jenis_soal.edit', $data);
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
        $JenisSoal              = JenisSoal::find($id);
        $JenisSoal->jenis_soal  = $request->jenis_soal;
        $JenisSoal->kategori  = $request->kategori;
        $JenisSoal->save();

        return redirect('jenis-soal')->with('success', 'Ubah Data Berhasil');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){

        $ujianUser = JenisSoal::find($id);
        $ujianUser ->delete();

        return redirect('jenis-soal')->with('success', 'Hapus Data Berhasil');
    }
}