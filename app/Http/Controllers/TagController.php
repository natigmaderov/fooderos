<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagCollection;
use App\Models\Tag;
use App\Models\TagTypes;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function show(Request $request){
       
        // $start = $_GET['start'];
        // $page = $_GET['page'];
        
        
        // return DB::table('tags')->skip($start)->take($page*10)->get();
        
        $tags= TagCollection::collection(Tag::with('tagtypes')->get());
        return response()->json([
            "Tags"=>$tags
        ],200);
    }



    public function create(Request $request){

        $request ->validate([
            'name'=>'required',
        ]);
        $type_id = TagTypes::where('name',$request->tag_name)->first()->id;

  
            $tag = Tag::create([
            'name'=>$request->name,
            'type_id'=>$type_id,
            'status'=>1,
            'store_count'=>1,
            'image' => '',
        ]);

        if($request->hasFile('image')){
            $dest_path = 'public/tags/images';
            $image = $request->file('image');
            $image_name = $image->getClientOriginalName();
            $path = $request->file('image')->storeAs($dest_path,$tag->name);
        }
        $tag->image = $tag->name;
        $tag->save();
    }
    
    public function status(Request $request){
        $request -> validate([
            'id'=>$request->id,
            'status'=>$request->status
        ]);

        $tag = Tag::where('id', $request->id)->first();
        $tag->status = $request->status;
        $tag->save();

        return response([
            'Message'=>'status changed'
        ],201);
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
