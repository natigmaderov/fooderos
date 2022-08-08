<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\TagLocales;
use App\Models\TagTypes;
use Illuminate\Http\Request;

class TagLocalsController extends Controller
{
    public function store(Request $request){

        $languages = Language::all();
        $tag_id = TagTypes::where('name',$request->tag_name)->first()->id;
        foreach ($languages as $key => $value) {
               
            
            if(!TagLocales::where('tag_id',$tag_id)->where('lang' ,$languages[$key]['lang'])){

            
            $tagLocals = TagLocales::create([
                    'name' => $request->name,
                    'tag_id'=>$tag_id,
                    'description'=>$request->input($languages[$key]['lang'].'_description'),
                    'lang'=>$languages[$key]['lang']
               ]);
            
            
            } 
        } 
    }
}
