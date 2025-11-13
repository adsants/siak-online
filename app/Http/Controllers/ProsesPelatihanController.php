<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\MenuAdmin;
use App\Models\PelatihanModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;
use Auth;
use Carbon\Carbon;

class ProsesPelatihanController extends Controller
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

        $query = Pelatihan::leftJoin('pelatihan_users', 'pelatihan_users.pelatihan_id', '=', 'pelatihans.id')
            ->select('pelatihans.*')
            ->where('pelatihans.name', 'like', '%' . $request->q . '%')
            ->orderBy('pelatihans.start_date', 'desc')
            ->orderBy('pelatihans.name', 'asc')
            ->distinct(); // supaya pelatihan tidak dobel kalau punya banyak user

        if (Auth::user()->role != 'admin') {
            $query->where('pelatihan_users.user_id', Auth::user()->id);
        }

        $data['rows'] = $query->paginate(50);

        return view('admin.proses_pelatihan.index', $data);
    }


    public function presensi(Request $request, $pelatihanId)
    {
        // Jika sudah ada presensi hari ini, tolak
        $already = DB::table('pelatihan_presensis')
            ->where('pelatihan_id', $pelatihanId)
            ->whereDate('created_at', Carbon::today())
            ->exists();

        $data['sudahAbsen']           = $already;
    



        $post           = DB::table('pelatihan_users')
            ->select('pelatihans.id as pelatihan_id', 'pelatihans.name as pelatihanName', 'users.name', 'users.id as user_id', 'pelatihan_users.id')
            ->selectRaw('DATE_FORMAT(pelatihans.start_date, "%Y-%m-%d %H:%i") as start_date')

            ->join('pelatihans', 'pelatihan_users.pelatihan_id', '=', 'pelatihans.id')
            ->join('users', 'pelatihan_users.user_id', '=', 'users.id')

            ->where('pelatihans.id', '=', $pelatihanId)
            ->where('users.role', '=', 'user')
            ->get();
        //dd($post);
        $data['rows']           = $post;

        $dataPelatihan           = DB::table('pelatihans')->select('id', 'name')
            ->selectRaw('DATE_FORMAT(start_date, "%Y-%m-%d") as start_date')
            ->where('id', '=', $pelatihanId)
            ->first();
        $data['data_pelatihan']  = $dataPelatihan;

        $data['title']  = 'Data Peserta Pelatihan : ' . $dataPelatihan->name;
        $data['q']      = $request->q;

        return view('admin.proses_pelatihan.presensi', $data);
    }

    public function presensi_proses(Request $request, $pelatihanId)
    {
        $validated = $request->validate([
            'presensi' => ['required', 'array', 'min:1'],
            'presensi.*.jenis' => ['required', 'in:P,A'],
            'module_name' => ['required'],
            'presensi.*.keterangan' => ['nullable', 'string', 'max:150'],
        ]);


        $now   = Carbon::now('Asia/Jakarta');
        $start = $now->copy()->startOfDay();
        $end   = $now->copy()->endOfDay();

        // Jika sudah ada presensi hari ini, tolak
        $already = DB::table('pelatihan_presensis')
            ->where('pelatihan_id', $pelatihanId)
            ->whereDate('created_at', Carbon::today())
            ->exists();

        if ($already) {
            return redirect()
                ->route('pelatihan.presensi', $pelatihanId)
                ->with('info', 'Presensi hari ini sudah direkam. Tidak dapat menginput ulang.');
        }

        $presensi  = $validated['presensi'];

        DB::beginTransaction();
        try {
            
            $createdBy = Auth::id() ?? 0;

            $pelatihanModule                    = new PelatihanModule();
            $pelatihanModule->pelatihan_id      = $pelatihanId;
            $pelatihanModule->user_id           = $createdBy;
            $pelatihanModule->module_name       = $request->module_name;
            $pelatihanModule->module_deskripsi  = $request->module_deskripsi;
            $pelatihanModule->save();



            $rowsToInsert = [];
            foreach ($presensi as $userId => $data) {
                $rowsToInsert[] = [
                    'pelatihan_id'        => (int)$pelatihanId,
                    'user_id'             => (int)$userId,
                    'jenis_presensi'      => $data['jenis'],              // 'P' atau 'A'
                    'keterangan_presensi' => $data['keterangan'] ?? null,
                    'created_at'          => $now,
                    'created_by'          => $createdBy,
                ];
            }

            if ($rowsToInsert) {
                DB::table('pelatihan_presensis')->insert($rowsToInsert);
            }

            DB::commit();
            return redirect()
                ->route('pelatihan.hasil_presensi', $pelatihanId)
                ->with('success', 'Presensi berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan presensi: ' . $e->getMessage());
        }
    }



    public function hasilPresensi(Request $request, $pelatihanId)
    {

        $post           = DB::table('pelatihan_users')
            ->select('pelatihans.id as pelatihan_id', 'pelatihans.name as pelatihanName', 'users.name', 'users.id as user_id', 'pelatihan_users.id')
            ->selectRaw('DATE_FORMAT(pelatihans.start_date, "%Y-%m-%d %H:%i") as start_date')

            ->join('pelatihans', 'pelatihan_users.pelatihan_id', '=', 'pelatihans.id')
            ->join('users', 'pelatihan_users.user_id', '=', 'users.id')

            ->where('pelatihans.id', '=', $pelatihanId)
            ->where('users.role', '=', 'user')
            ->get();
        //dd($post);
        $data['rows']           = $post;

        $dataPelatihan           = DB::table('pelatihans')->select('id', 'name')
            ->selectRaw('DATE_FORMAT(start_date, "%Y-%m-%d") as start_date')
            ->where('id', '=', $pelatihanId)
            ->first();
        $data['data_pelatihan']  = $dataPelatihan;

        $data['title']  = 'Data Peserta Pelatihan : ' . $dataPelatihan->name;
        $data['q']      = $request->q;


        $hasilPresensi = DB::table('pelatihan_modules')
            ->select('users.name','pelatihan_modules.module_name','pelatihan_modules.module_deskripsi' )
            ->selectRaw('DATE_FORMAT(pelatihan_modules.created_at, "%d-%m-%Y") AS tgl')
            ->join('users', 'pelatihan_modules.user_id', '=', 'users.id')
            ->where('pelatihan_id', $pelatihanId)
            ->orderBy('tgl', 'asc')
            ->get();


        $tableHasil = "
        <thead class='table-light'>
        <tr>
            <th rowspan='2'>No</th>
            <th rowspan='2'>Nama</th>";
        foreach ($hasilPresensi as $row) {

            

                $tableHasil .= "<th>".$row->module_name."</th>";
        }
        $tableHasil .= "</tr><tr>";
         foreach ($hasilPresensi as $row) {

            

                $tableHasil .= "<th>". $row->tgl  ."</th>";
        }

        $tableHasil .= "</tr>
        </thead>
        <tbody>";
        $i = 1;
        foreach ($post as $row) {




            $tableHasil .= "<tr>
            <td>" . $i++ . "</td>
            <td>" . $row->name . "</td>";


            foreach ($hasilPresensi as $row2) {
                $tglYmd = Carbon::createFromFormat('d-m-Y', $row2->tgl)->toDateString(); // Y-m-d


                $dataPresensi           = DB::table('pelatihan_presensis')->select('jenis_presensi', 'keterangan_presensi')
                ->where('pelatihan_id', '=', $pelatihanId)
                ->where('user_id', '=', $row->user_id)
                ->whereDate('created_at', $tglYmd)
                ->first();


                if($dataPresensi){
                    
                    $cekAbsen = \cekmenuadmin::hasilAbsen($dataPresensi->jenis_presensi);

                    if($dataPresensi->jenis_presensi == 'P'){
                        $hasil = '<span class="text-success">'.$cekAbsen.'</span>';
                    }
                    else{

                        $hasil = '<span class="text-danger">'.$cekAbsen.'</span>';
                        if($dataPresensi->keterangan_presensi != ''){

                        $hasil .= '<br><span>'.$dataPresensi->keterangan_presensi.'</span>';
                        }
                    }
                    
                }
                else{
                    $hasil = "-";
                }

               

                $tableHasil .= "<td>". $hasil  ."</td>";
            }

            $tableHasil .= " </tr>";
        }

        $tableHasil .= " </tbody>";
        $data['tableHasil']  = $tableHasil;


        //dd();

        return view('admin.proses_pelatihan.hasil_presensi', $data);
    }
}
