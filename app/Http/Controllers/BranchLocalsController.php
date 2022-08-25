<?php

namespace App\Http\Controllers;

use App\Models\BranchLocals;
use App\Models\Language;
use Illuminate\Http\Request;

class BranchLocalsController extends Controller
{
    
    public function store($request , $branch){

        $languages = Language::all();
        
            // dd($request);   
        foreach ($languages as $key => $value) {
               
            $branchLocals = BranchLocals::create([
                    'name' => $request->input($languages[$key]['lang'].'_name'),
                    'branch_id'=>$branch->id,
                    'address'=>$request->input($languages[$key]['lang'].'_address'),
                    'lang'=>$languages[$key]['lang'],
                    'status'=>1
               ]);
            
            
        } 

        return response([
            'message'=>'Branch added !!!'
        ],201);
    }


    public function edit($request , $branch){

        $languages = Language::all();
            // dd($request);   
        foreach ($languages as $key => $value) {
               
            $branchLocals = BranchLocals::where('branch_id' , $branch->id)->where('lang',$languages[$key]['lang'])->update([
                    'name' => $request->input($languages[$key]['lang'].'_name'),
                    'bramch_id'=>$branch->id,
                    'address'=>$request->input($languages[$key]['lang'].'_address'),
                    'lang'=>$languages[$key]['lang']
               ]);
            
            
        } 

        return response([
            'message'=>'Branch updated !!!'
        ],201);
    }

}
