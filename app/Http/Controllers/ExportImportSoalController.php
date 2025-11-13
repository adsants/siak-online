<?php
     
namespace App\Http\Controllers;
    
use Illuminate\Http\Request;
use App\Exports\SoalsExport;
use App\Imports\SoalsImport;
use Maatwebsite\Excel\Facades\Excel;
    
class ExportImportSoalController extends Controller
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function importExportView()
    {
       return view('admin/soal_choice/import');
    }
     
    /**
    * @return \Illuminate\Support\Collection
    */
    public function export() 
    {
        return Excel::download(new SoalsExport, 'soal.xlsx');
    }
     
    /**
    * @return \Illuminate\Support\Collection
    */
    public function import(Request $request) 
    {
        Excel::import(new SoalsImport($request->jenis_soal),request()->file('file'));
             
        
        return redirect('soal')->with('success', 'Import Data Berhasil');
    }
}