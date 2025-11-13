<?php

namespace App\Http\Controllers;

use App\Models\SoalGambar;
use App\Models\JenisSoal;
use App\Models\JawabanGambar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


use Response;
use View;
use File;

use DB;

class SoalGambarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function jsonFileDownload()
    {
        $dataSoals           =  json_encode(DB::table('soal_gambars')->select('id_soal','soal','status')->get());
        
        $fileName = 'soal_gambar.json';
        
        
        File::put(public_path('/upload/json/'.$fileName),$dataSoals);
        return Response::download(public_path('/upload/json/'.$fileName));
	}

    public function importView()
    {
       return view('admin/soal_angka_hilang/import_gambar');
    }
     

    public function jsonFileUpload(Request $request)
    {
 
        //dd($request);

        $request->validate([
            'file' => 'required|mimes:json|max:99999999',
        ]);
  
        $fileName = time().'_upload_soal.'.$request->file->extension();  
   
        $request->file->move(public_path('upload/json/'), $fileName);
   
        $json = File::get(public_path('upload/json/'.$fileName));
        $datas = json_decode($json);

        foreach( $datas as  $data){

            $importSoalGambar = new SoalGambar();
            $importSoalGambar->id_soal  = $data->id_soal;
            $importSoalGambar->soal     = $data->soal;
            $importSoalGambar->status    = $data->status;
            
             $importSoalGambar->save();
            

        }
        
        return redirect('soal/kecermatan')->with('success', 'Import Data Berhasil');
    }


    public function index(Request $request)
    {
        
        $data['jenisSoal']   = JenisSoal::select('*')
        ->orderBy('jenis_soal')
        ->where('kategori','=','2')
        ->get();

        $data['idJenisSoal'] = $request->id_jenis_soal;
        
        $data['title']  = 'Data Bank Soal Bessrgambar';
        $data['q']      = $request->q;
        $data['rows']            = SoalGambar::select('*')
        ->where('id_jenis_soal', '=', $request->id_jenis_soal)
        ->groupBy('id_soal')
        ->orderBy('is_contoh','desc')
        ->paginate(10);
        
        $data['rows']->withPath('/soal/kecermatan?id_jenis_soal='.$request->id_jenis_soal);
        
        

        $html = "";
        foreach( $data['rows']  as $row){
           $html .= '<div class="row border-bottom-1  mt-2">'.$this->show_soal($row->id_soal).'</div>';
        }

        $data['tableData'] = $html;

        
        return view('admin.soal_angka_hilang.gambar_index', $data);
    }

    public function show_soal($id_soal)
    {
        
        $rows  = DB::table('soal_gambars')
        ->select('*')
        ->where('id_soal',$id_soal)
        ->orderBy('id')
        ->get();

        $row2  = DB::table('soal_gambars')
        ->select('*')
        ->where('id_soal',$id_soal)
        ->orderBy('id')
        ->first();

        $html = "";


        if($row2->is_contoh == 1 ){
            $html .= '
                
            <div class="col-sm-11">
                <div class="card p-2 bt-2">                
                    <h4>Dijadikan Soal Simulasi</h4>
                </div>
            </div>
        
        ';
        }

        foreach($rows as $row){
            $html .= '
                
                <div class="col-sm-2">
                    <div class="card p-2">                
                    '.$row->soal.'
                    </div>
                </div>
            
            ';
        }
            $html .= '
            <div class="col-sm-2 text-center ">
                <a class="btn btn-sm btn-warning m-2" href="'.url("soal/edit-kecermatan", $row->id_soal ).'">Ubah</a>
            
                </div>
            ';

        return $html;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data['title'] = 'Tambah Bank Soal ';
        $data['jenisSoal']   = JenisSoal::select('*')
        ->orderBy('jenis_soal')
        ->where('kategori','=','2')
        ->get();
        $data['idJenisSoal'] = $request->id_jenis_soal;
        
        return view('admin.soal_angka_hilang.gambar_create', $data);
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
            'jawaban_1' => 'required',
            'jawaban_2' => 'required',
            'jawaban_3' => 'required',
            'jawaban_4' => 'required',
            'jawaban_5' => 'required',
            'status' => 'required',
        ]);
        

        $newIdSoal = time();


        $inputJawaban1 = new SoalGambar();
        $inputJawaban1->id_soal = $newIdSoal;
        $inputJawaban1->soal = $request->jawaban_1;
        $inputJawaban1->id_jenis_soal = $request->id_jenis_soal;
        $inputJawaban1->is_contoh = $request->is_contoh;
        if($request->status == '1'){
            $inputJawaban1->status = 'B';
        }
        $inputJawaban1->save();

        $inputJawaban2 = new SoalGambar();
        $inputJawaban2->id_soal = $newIdSoal;
        $inputJawaban2->soal = $request->jawaban_2;
        $inputJawaban2->id_jenis_soal = $request->id_jenis_soal;
        $inputJawaban2->is_contoh = $request->is_contoh;
        if($request->status == '2'){
            $inputJawaban2->status = 'B';
        }
        $inputJawaban2->save();

        $inputJawaban3 = new SoalGambar();
        $inputJawaban3->id_soal = $newIdSoal;
        $inputJawaban3->soal = $request->jawaban_3;
        $inputJawaban3->id_jenis_soal = $request->id_jenis_soal;
        $inputJawaban3->is_contoh = $request->is_contoh;
        if($request->status == '3'){
            $inputJawaban3->status = 'B';
        }
        $inputJawaban3->save();

        $inputJawaban4 = new SoalGambar();
        $inputJawaban4->id_soal = $newIdSoal;
        $inputJawaban4->soal = $request->jawaban_4;
        $inputJawaban4->id_jenis_soal = $request->id_jenis_soal;
        $inputJawaban4->is_contoh = $request->is_contoh;
        if($request->status == '4'){
            $inputJawaban4->status = 'B';
        }
        $inputJawaban4->save();

        $inputJawaban5 = new SoalGambar();
        $inputJawaban5->id_soal = $newIdSoal;
        $inputJawaban5->soal = $request->jawaban_5;
        $inputJawaban5->id_jenis_soal = $request->id_jenis_soal;
        $inputJawaban5->is_contoh = $request->is_contoh;
        if($request->status == '5'){
            $inputJawaban5->status = 'B';
        }
        $inputJawaban5->save();



        return redirect('soal/kecermatan?id_jenis_soal='.$request->id_jenis_soal)->with('success', 'Tambah Data Berhasil');
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
   // public function edit(SoalGambar $soal)
    //{
    //    $data['title'] = 'Ubah Bank Soal ';
    public function edit($id)
    {
        $posts = DB::table('soal_gambars')
        ->select('*')
        ->where('id_soal','=',$id)
        ->orderBy('id')
        ->get();

        $data['jenisSoal']   = JenisSoal::select('*')
        ->orderBy('jenis_soal')
        ->where('kategori','=','2')
        ->get();

        $werwer = DB::table('soal_gambars')
        ->select('*')
        ->where('id_soal','=',$id)
        ->orderBy('id')
        ->first();
        //dd($post->id_jenis_Soal);
        
       // $data['idJenisSoal'] = $post->id_jenis_soal;

       // dd($post->id_jenis_soal);
      
        $data['rows']   = $posts;

        $data['row']    = $werwer;

        $data['idSoal'] = $id;
       // dd( $werwer->id_jenis_soal);
        
        $data['idJenisSoal'] =  $werwer->id_jenis_soal;
        $data['isContoh'] = $data['row']->is_contoh;

        return view('admin.soal_angka_hilang.gambar_edit', $data);
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
        $posts = DB::table('soal_gambars')
        ->select('*')
        ->where('id_soal','=',$id)
        ->orderBy('id')
        ->get();

        $arrayValidate = [];
        foreach($posts as $post){
            $request->validate([
                'soal_'.$post->id => 'required'
            ]);

        }
        $request->validate([
            'status' => 'required'
        ]);
        


        foreach($posts as $post){
            if($request->input('status') ==  $post->id){
                $status =   "B";
            }
            else{
                $status = 'S';
            }

            DB::table('soal_gambars')->where('id', $post->id)->update([
                'soal'          => $request->input('soal_'.$post->id),
                'is_contoh'     => $request->is_contoh,
                'id_jenis_soal'     => $request->id_jenis_soal,
                'status'            => $status
            ]);

        }
        
        
        return redirect('soal/kecermatan?id_jenis_soal='.$request->id_jenis_soal)->with('success', 'Ubah Data Berhasil');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(SoalGambar $id)
    {
        $id->delete();
        return redirect('soal/kecermatan')->with('success', 'Hapus Data Berhasil');
    }
    
}