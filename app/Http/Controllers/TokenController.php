<?php
 
namespace App\Http\Controllers; 
use Illuminate\Http\Request; 
use App\Models\UjianUser;

class TokenController extends Controller
{
    public function __construct()
    {
        
    }
 
    public function index()
    {
        $data = [];
        return view('user.token', $data);
    }
    public function process(Request $request)
    {

        
        $cekUjianUser   = UjianUser::select('*')
        ->where('token',$request->token)
        ->limit(1)
        ->first();

        if($cekUjianUser ){            
            return redirect('ujian-info'.'/'.$cekUjianUser->id);
        }
        else{
            return redirect('token')->with('success', 'Pastikan Token Anda Benar !');
        }
    }
}