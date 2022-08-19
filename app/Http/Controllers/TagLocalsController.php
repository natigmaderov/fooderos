<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Tag;
use App\Models\TagLocales;
use App\Models\TagTypes;
use Illuminate\Http\Request;

class TagLocalsController extends Controller
{
    public function store(Request $request , $tag){

        $languages = Language::all();
        $tag_id = $tag->id;
            // dd($request);   
        foreach ($languages as $key => $value) {
               
            $tagLocals = TagLocales::create([
                    'name' => $request->input($languages[$key]['lang'].'_name'),
                    'tag_id'=>$tag_id,
                    'description'=>$request->input($languages[$key]['lang'].'_description'),
                    'lang'=>$languages[$key]['lang']
               ]);
            
            
        } 

        return response([
            'message'=>'tags added !!!'
        ],201);
    }


    public function edit($request , $tag){

        $languages = Language::all();
        $tag_id = $tag->id;
            // dd($request);   
        foreach ($languages as $key => $value) {
               
            $tagLocals = TagLocales::where('tag_id' , $tag_id)->where('lang',$languages[$key]['lang'])->update([
                    'name' => $request->input($languages[$key]['lang'].'_name'),
                    'tag_id'=>$tag_id,
                    'description'=>$request->input($languages[$key]['lang'].'_description'),
                    'lang'=>$languages[$key]['lang']
               ]);
            
            
        } 

        return response([
            'message'=>'tags updated !!!'
        ],201);
    }

    
}
