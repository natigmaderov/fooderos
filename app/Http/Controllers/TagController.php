<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagCollection;
use App\Models\Tag;
use App\Models\TagLocales;
use App\Models\TagTypes;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

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

        if(Tag::where('name',$request->name)->first()){
            return response([
                'message'=>'Tag Already Exist!!!'
            ],401);
        }
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
            $path = $request->file('image')->storeAs($dest_path,$tag->id.$tag->name);
        }
        $tag->image = $tag->id.$tag->name;
        $tag->save();

        $Locals = new TagLocalsController();
        return $Locals->store($request , $tag);
    }


    public function edit(Request $request){

        $request->validate([
            'id' => 'required'
        ]);

        $tag = Tag::find($request->id);
        
        $dest_path = 'storage/tags/images/'.$tag->image;
        
        if(File::exists($dest_path)) {
            File::delete($dest_path);
        }

        $tag->name = $request->name;
        if($request->hasFile('image')){
            $dest_path = 'public/tags/images';
            $image = $request->file('image');
            $image_name = $image->getClientOriginalName();
            $path = $request->file('image')->storeAs($dest_path,$tag->id.$tag->name);
        }
        $tag->image = $tag->id.$tag->name;
 
        $tag->save();

        $Locals = new TagLocalsController();
        return $Locals->edit($request , $tag);

    }
  
    public function delete(Request $request){
        
        $request->validate([
            'id'=>'required'
        ]);
        $id = $request->id;
        $tag = Tag::find($id)->delete();
        $tag_locales = TagLocales::where('tag_id',$id)->delete();

        return response([
            'message'=>'Record deleted !!!'
        ],201);

    }

    public function status(Request $request){
        $request->validate([
            'id'=>'required',
            'status'=>'required',
        ]);

        $tag = Tag::where('id', $request->id)->first();
        $tag->status = $request->status;
        $tag->save();

        return response([
            'Message'=>'status changed'
        ],201);
    }
    
    
    public function showID(Request $request){

        $request->validate([
            'id'=>'required'
        ]);
        $tag = Tag::with('tag_locals')->where('id',$request->id)->get();
        
        return $tag;

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
