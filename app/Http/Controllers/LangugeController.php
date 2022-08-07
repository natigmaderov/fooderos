<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Tag;
use App\Models\TagLocales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\DB;

class LangugeController extends Controller
{
    public function show(){

        return Language::all();
    }

    public function store(Request $request){

        $request->validate([
            'name'=>'required'
        ]);
        if(Language::where('name' , $request->name)->first()){
            return response([
                'message' =>'language is already exist'
            ],403);

        }
             Language::create([
            'name'=>$request->name,
            'status'=>1
        ]);

        return response([
            'message'=>'Language Added !'
        ],201);
    }


    public function delete(Request $request){
        
        $request->validate([
            'name'=>'required'
        ]);

        Language::where('name' , $request->name)->delete();

        return response([
            'message' => 'Language deleted'
        ],201);
    }


    public function languageVariable(Request $request){
        
        $languages = Language::all();
        //$languages[$key]['lang'];
        foreach ($languages as $key => $value) {
               
            $tagLocals = TagLocales::create([
                    'name' => $request->name,
                    'tag_id'=>Tag::where('name',$request->tag_name)->first()->id,
                    'description'=>$request->$languages[$key]['lang'].'_description',
                    'lang'=>$languages[$key]['lang']
                    
               ]); 
        } 


     }
}
