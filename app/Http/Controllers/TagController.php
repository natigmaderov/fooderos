<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\TagTypes;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function show(Request $request){
       
        $start = $_GET['start'];
        $page = $_GET['page'];
        
        
        

        return DB::table('tags')->skip($start)->take($page*10)->get();
    }



    public function create(Request $request){

        $request ->validate([
            'name'=>'required',
        ]);
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    //store the tag-types 


    public function store(Request $request){

        $request->validate([
            'name'=>'required'
        ]);

        if(TagTypes::where('name',$request->name)->first()){

            return response([
                'message'=>'Tag-Type is already exist!'
            ],403);
        }

        $tag = TagTypes::create([
            'name'=>$request->name,
            'status'=>1
        ]);
        return response([
            'message'=>'Tag created Successfully'
        ],201);
    }

    public function showTypes(){

        return TagTypes::all();

    }
    
}
