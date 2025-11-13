<?php

namespace App\Http\Controllers;

use App\Models\SoalChoice;
use App\Models\SoalChoiceJawaban;
use App\Models\JenisSoal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use File;
Use Response;
use DB;

class SoalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $data['jenisSoal']   = JenisSoal::select('*')
        ->orderBy('jenis_soal')
        ->where('kategori','=','1')
        ->get();

        $data['idJenisSoal'] = $request->id_jenis_soal;

        $data['title']  = 'Data Bank Soal ';
        $data['q']      = $request->q;
        $data['rows']   = SoalChoice::select('*')
        ->where('soal_choice', 'like', '%'.$request->q.'%')
        ->where('id_jenis_soal', '=', $request->id_jenis_soal)
        ->orderBy('is_contoh','desc')
        ->paginate(50);    
        $data['rows']->withPath('soal?id_jenis_soal='.$request->id_jenis_soal);

            $data['htmlTable']  = "";
            $no = 1;
            foreach($data['rows'] as $row22){
                if( $row22->is_contoh == 1)
                   $statusContoh = '<h4>Digunakan Contoh Soal</h4>';
                else
                    $statusContoh = '';

                $data['htmlTable']  .= "
                <tr>
                    <td>".$statusContoh."". $row22['soal_choice']."</td>
                    <td>
                    <ul>
                ";
                    
                $data['rowsJawaban']   = SoalChoiceJawaban::select('*')
                ->where('id_soal_choice',$row22['id'])
                ->orderBy('id','asc')
                ->paginate(10);
                
                foreach($data['rowsJawaban'] as $row23){
                    if($row23['status_jawaban'] == 1){
                        $data['htmlTable']  .= "<li style='color:green;font-weight:bold'>".$row23['jawaban']."</li>";
                    }else{
                        $data['htmlTable']  .= "<li>".$row23['jawaban']."</li>";
                    }
                }

                    
                if( $row22->status_active == 1)
                   $statusActive = 'Aktif';
                else
                    $statusActive = 'Tidak Aktif';
                
                $data['htmlTable']  .= "
                    </ul>
                    </td>
                    <td>
                        ".$statusActive."
                    </td>
                    <td>
                        <a class='btn btn-sm btn-warning' href='". url('soal/edit', $row22['id'] ) ."'>Ubah</a>
                        <a class='btn btn-sm btn-danger' href='".url('soal/delete', $row22['id'])."'>Hapus</a>
                    </td>
                </tr>
                ";
            }

        return view('admin.soal_choice.index', $data);
    }

   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data['jenisSoal']   = JenisSoal::select('*')
        ->orderBy('jenis_soal')
        ->where('id','!=','1')
        ->get();

        $data['listPeserta']   = User::select('*')
        ->orderBy('name')
        ->where('role','=','1')
        ->get();

        $data['id_jenis_soal'] = $request->id_jenis_soal;
        $data['title'] = 'Tambah Bank Soal ';
        
        return view('admin.soal_choice.create', $data);
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
            'soal'          => 'required',
            'jawaban_1'    => 'required',
            'jawaban_2'     => 'required',
            'status_jawaban'     => 'required',
            'is_contoh'     => 'required',
            'id_jenis_soal'     => 'required',
        ]);
        

        if($request->is_contoh == '0'){
            $statusActive = "1";
        }
        else{
            $statusActive = "0";

        }

        $soalInsert = SoalChoice::create([
            'soal_choice' => $request->soal,
            'is_contoh' => $request->is_contoh,
            'status_active' => $statusActive,
            'id_jenis_soal' => $request->id_jenis_soal
        ]);
        

        $newIdSoal = $soalInsert->id;

        if($request->jawaban_1 != ''){

            if($request->status_jawaban == 1){
                $statusJawab = 1;
            }
            else{
                $statusJawab = 0;
            }

            $soalInsert = SoalChoiceJawaban::create([
                'id_soal_choice'    => $newIdSoal,
                'jawaban'           => $request->jawaban_1,
                'status_jawaban'    => $statusJawab
            ]);
        }

        if($request->jawaban_2 != ''){

            if($request->status_jawaban == 2){
                $statusJawab = 1;
            }
            else{
                $statusJawab = 0;
            }

            $soalInsert = SoalChoiceJawaban::create([
                'id_soal_choice'    => $newIdSoal,
                'jawaban'           => $request->jawaban_2,
                'status_jawaban'    => $statusJawab
            ]);
        }

        if($request->jawaban_3!= ''){

            if($request->status_jawaban == 3){
                $statusJawab = 1;
            }
            else{
                $statusJawab = 0;
            }

            $soalInsert = SoalChoiceJawaban::create([
                'id_soal_choice'    => $newIdSoal,
                'jawaban'           => $request->jawaban_3,
                'status_jawaban'    => $statusJawab
            ]);
        }

        if($request->jawaban_4!= ''){

            if($request->status_jawaban == 4){
                $statusJawab = 1;
            }
            else{
                $statusJawab = 0;
            }

            $soalInsert = SoalChoiceJawaban::create([
                'id_soal_choice'    => $newIdSoal,
                'jawaban'           => $request->jawaban_4,
                'status_jawaban'    => $statusJawab
            ]);
        }

        
        if($request->jawaban_5!= ''){

            if($request->status_jawaban == 5){
                $statusJawab = 1;
            }
            else{
                $statusJawab = 0;
            }

            $soalInsert = SoalChoiceJawaban::create([
                'id_soal_choice'    => $newIdSoal,
                'jawaban'           => $request->jawaban_5,
                'status_jawaban'    => $statusJawab
            ]);
        }

        return redirect('soal?id_jenis_soal='.$request->id_jenis_soal)->with('success', 'Tambah Data Berhasil');
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
   // public function edit(SoalChoice $soal)
    //{
    //    $data['title'] = 'Ubah Bank Soal ';
    public function edit($id)
    {
        $post           = SoalChoice::findOrFail($id);
        $data['row']    = $post;
        $data['status_active'] = ['1' => 'Aktif', '0' => 'Tidak Aktif'];
        $jawaban1s   = SoalChoiceJawaban::select('*')
        ->where('id_soal_choice',$id)
        ->orderBy('id')
        ->offset(0)
        ->limit(1)
        ->first();
        $data['jenisSoal']   = JenisSoal::select('*')
        ->orderBy('jenis_soal')
        ->where('id','!=','1')
        ->get();


        if($jawaban1s){
            $data['jawaban1']       = $jawaban1s['jawaban'];
            $data['jawaban1Benar']  = $jawaban1s['status_jawaban'];
        }
        else{
            $data['jawaban1']       = "";
            $data['jawaban1Benar']  = 0;
        }

        $jawaban2s   = SoalChoiceJawaban::select('*')
        ->where('id_soal_choice',$id)
        ->orderBy('id')        
        ->offset(1)
        ->limit(1)
        ->first();
        if($jawaban2s){
            $data['jawaban2']       =   $jawaban2s['jawaban'];
            $data['jawaban2Benar']  =   $jawaban2s['status_jawaban'];
        }
        else{
            $data['jawaban3']       = "";
            $data['jawaban2Benar']  = 0;
        }


        $jawaban3s   = SoalChoiceJawaban::select('*')
        ->where('id_soal_choice',$id)
        ->orderBy('id')   
        ->offset(2)
        ->limit(1)
        ->first();
        if($jawaban3s){
            $data['jawaban3']       =   $jawaban3s['jawaban'];
            $data['jawaban3Benar']  =   $jawaban3s['status_jawaban'];
        }
        else{
            $data['jawaban3']       = "";
            $data['jawaban3Benar']  = 0;
        }

        $jawaban4s   = SoalChoiceJawaban::select('*')
        ->where('id_soal_choice',$id)
        ->orderBy('id')
        ->offset(3)
        ->limit(1)
        ->first();
        if($jawaban4s){
            $data['jawaban4']       =   $jawaban4s['jawaban'];
            $data['jawaban4Benar']  =   $jawaban4s['status_jawaban'];
        }
        else{
            $data['jawaban4']       = "";
            $data['jawaban4Benar']  = 0;
        }

        $jawaban5s   = SoalChoiceJawaban::select('*')
        ->where('id_soal_choice',$id)
        ->orderBy('id')
        ->offset(4)
        ->limit(1)
        ->first();

        if($jawaban5s){
            $data['jawaban5']       =   $jawaban5s['jawaban'];
            $data['jawaban5Benar']  =   $jawaban5s['status_jawaban'];
        }
        else{
            $data['jawaban5']       = "";
            $data['jawaban5Benar']  = "23424";
        }
        
        //dd( $data['jawaban5Benar']);

        return view('admin.soal_choice.edit', $data);
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
        if($request->is_contoh == '0'){           
            $statusActive = $request->status_active;
        }
        else{
            $statusActive = "0";
        }
        
        DB::table('soal_choices')->where('id', $id)->update([
            'soal_choice' => $request->soal,
            'id_jenis_soal' => $request->id_jenis_soal,
            'is_contoh' => $request->is_contoh,
            'status_active' => $statusActive,
        ]);

        DB::table('soal_choice_jawabans')->where('id_soal_choice', $id)->delete();
        
        if($request->jawaban_1 != ''){

            if($request->status_jawaban == 1){
                $statusJawab = 1;
            }
            else{
                $statusJawab = 0;
            }

            $soalInsert = SoalChoiceJawaban::create([
                'id_soal_choice'    => $id,
                'jawaban'           => $request->jawaban_1,
                'status_jawaban'    => $statusJawab
            ]);
        }

        if($request->jawaban_2 != ''){

            if($request->status_jawaban == 2){
                $statusJawab = 1;
            }
            else{
                $statusJawab = 0;
            }

            $soalInsert = SoalChoiceJawaban::create([
                'id_soal_choice'    => $id,
                'jawaban'           => $request->jawaban_2,
                'status_jawaban'    => $statusJawab
            ]);
        }

        if($request->jawaban_3!= ''){

            if($request->status_jawaban == 3){
                $statusJawab = 1;
            }
            else{
                $statusJawab = 0;
            }

            $soalInsert = SoalChoiceJawaban::create([
                'id_soal_choice'    => $id,
                'jawaban'           => $request->jawaban_3,
                'status_jawaban'    => $statusJawab
            ]);
        }

        if($request->jawaban_4!= ''){

            if($request->status_jawaban == 4){
                $statusJawab = 1;
            }
            else{
                $statusJawab = 0;
            }

            $soalInsert = SoalChoiceJawaban::create([
                'id_soal_choice'    => $id,
                'jawaban'           => $request->jawaban_4,
                'status_jawaban'    => $statusJawab
            ]);
        }

        
        if($request->jawaban_5!= ''){

            if($request->status_jawaban == 5){
                $statusJawab = 1;
            }
            else{
                $statusJawab = 0;
            }

            $soalInsert = SoalChoiceJawaban::create([
                'id_soal_choice'    => $id,
                'jawaban'           => $request->jawaban_5,
                'status_jawaban'    => $statusJawab
            ]);
        }

        return redirect('soal?id_jenis_soal='.$request->id_jenis_soal)->with('success', 'Ubah Data Berhasil');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(SoalChoice $id)
    {
        $id->delete();

        
        DB::table('soal_choice_jawabans')->where('id_soal_choice', $id)->delete();
        
        return redirect('soal')->with('success', 'Hapus Data Berhasil');
    }
    public function jsonFileDownload(Request $request, $id)
    {
        $jenisSoal           =  JenisSoal::select('*')
        ->where('id',$id)
        ->offset(0)
        ->limit(1)
        ->first();

        $dataSoals           = DB::table('soal_choices')->select('id','id_jenis_soal','status_active','soal_choice')
        ->where('id_jenis_soal',$id)
        ->get();

        $result = array();
        $i = 0;
        foreach($dataSoals as $dataSoal){
            
            $result[] = array(
                'id' 		        => $dataSoal->id ,
                'id_jenis_soal'     => $dataSoal->id_jenis_soal,
                'status_active'     => $dataSoal->status_active,
                'soal_choice' 		=> $dataSoal->soal_choice,
                'data_jawaban' => array()
            );

            $dataJawabans           = DB::table('soal_choice_jawabans')->select('id_soal_choice','jawaban','status_jawaban')
            ->where('id_soal_choice',$dataSoal->id)
            ->get();
            foreach($dataJawabans as $dataJawaban){    
              
                $result[$i]['data_jawaban'][] = array(
                    'jawaban' 			    => $dataJawaban->jawaban,
                    'id_soal_choice' 		=> $dataJawaban->id_soal_choice,
                    'status_jawaban' 		=> $dataJawaban->status_jawaban
                );
                
                $result = array_values($result); 
            }

			$i++;
        }

		$formattedData = json_encode($result);
        
        $fileName = $jenisSoal->jenis_soal.'.json';
        File::put(public_path('/upload/json/'.$fileName),$formattedData);
        return Response::download(public_path('/upload/json/'.$fileName));
	}

    public function importView(Request $request, $id)
    {

        $data['idJenisSoal']      =   $id;
        $data['jenisSoal']           =  JenisSoal::select('*')
        ->get();
       
       return view('admin.soal_choice.import', $data);
    }
    
    public function importProcess(Request $request, $id)
    {
        $destinationPath = "upload/"; 
        $file = $request->file('file'); 
        if($file->isValid()){ 
            $file->move($destinationPath, $file->getClientOriginalName()); 
            $path = $destinationPath.$file->getClientOriginalName(); // ie: /var/www/laravel/app/storage/json/filename.json

            $dataJson = json_decode(file_get_contents($path), true); 
          //dd($dataJson);
            if (is_array($dataJson) || is_object($dataJson)){
				foreach($dataJson as $dataSoalInsert){
					
					if(isset($dataSoalInsert)){
						
                        $input = new SoalChoice();
                        $input->id_jenis_soal   = $dataSoalInsert['id_jenis_soal'];
                        $input->soal_choice     = $dataSoalInsert['soal_choice'];
                        $input->status_active     = $dataSoalInsert['status_active'];
                
                        $input->save();
                        $soalIdNew = $input->id;
					//dd($dataSoalInsert['data_jawaban']);
						foreach($dataSoalInsert['data_jawaban'] as $dataJawabanInsert){
							

                            $soalInsert = SoalChoiceJawaban::create([
                                'id_soal_choice'    => $soalIdNew,
                                'jawaban'           => $dataJawabanInsert['jawaban'],
                                'status_jawaban'    => $dataJawabanInsert['status_jawaban']
                            ]);
							
						}
					}
				}				
            }
        }
        
        return redirect('soal')->with('success', 'Import Data Berhasil');
    }
    
    
}