<?php

namespace App\Http\Controllers;

use App\Models\TextInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


use DB;

class TextInfolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['row']  = TextInfo::select('*')
        ->offset(0)
        ->limit(1)
        ->first();
        
        return view('admin.text_info.edit', $data);
    }

    public function update(Request $request)
    {
        DB::table('text_infos')->update([
            'info_awal_simulasi' => $request->info_awal_simulasi,
            'info_akhir_simulasi' => $request->info_akhir_simulasi,
            'info_jawaban_salah' => $request->info_jawaban_salah,
            'info_jawbaan_benar' =>  $request->info_jawbaan_benar,
        ]);

        return redirect('text-info')->with('success', 'Ubah Data Berhasil');
    }


    
}