<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;

class TypeaheadController extends Controller
{
 
    public function autocompleteSearch(Request $request)
    {
        
        $ujianId = $request->get('ujian_id');
        $query = $request->get('query');
        $filterResult = User::where('name', 'LIKE', '%'. $query. '%')
        ->where('role', '=', 'user')
        ->whereNotIn('id', function($q)  use ($ujianId){
            $q->select('user_id')->from('ujian_users')->where('ujian_id', '=',  $ujianId);
        })
        ->get();
        return response()->json($filterResult);
    } 
}