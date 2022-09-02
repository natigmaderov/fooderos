<?php

namespace App\Http\Controllers;

use App\Models\CatagoryLocalsModel;
use App\Models\Language;
use Illuminate\Http\Request;

class CatagoryLocalsController extends Controller
{
    

    public function store($request, $catagory){
        $languages = Language::all();
        foreach ($languages as $key => $value) {
               
            $catagoryLocals = CatagoryLocalsModel::create([
                    'name' => $request->input($languages[$key]['lang'].'_name'),
                    'catagory_id'=>$catagory->id,
                    'lang'=>$languages[$key]['lang'],
                    'status'=>1
               ]);
            
            
        } 

        return response([
            'message'=>'Catagory Created !!!'
        ],201);

    }


    public function edit($request , $catagory){

        $languages = Language::all();
            // dd($request);   
        foreach ($languages as $key => $value) {
               
            $catagoryLocals = CatagoryLocalsModel::where('catagory_id' , $catagory->id)->where('lang',$languages[$key]['lang'])->update([
                    'name' => $request->input($languages[$key]['lang'].'_name'),
                    'catagory_id'=>$catagory->id,
                    'status'=>1,
                    'lang'=>$languages[$key]['lang']
               ]);
            
            
        } 

        return response([
            'message'=>'Catagory updated !!!'
        ],201);
    }
}
