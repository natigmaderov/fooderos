<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagCollection;
use App\Models\Rest;
use App\Models\Tag;
use App\Models\TagLocales;
use App\Models\TagTypes;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TagController extends Controller
{
    public function show($lang ,$rest){
       
        // $start = $_GET['start'];
        // $page = $_GET['page'];
        
        
        // return DB::table('tags')->skip($start)->take($page*10)->get();
        // $tag = Tag::select(['id','status','image','type_id' ,'store_count'])->with(['tag_locals'=>function($query){$query->where('lang','Az');}])->get();
        

        $data = DB::table("tags")
        ->leftJoin("tag_locales", function($join){
            $join->on("tags.id", "=", "tag_locales.tag_id");
        })
        ->leftJoin("tag_types", function($join){
            $join->on("tags.type_id", "=", "tag_types.id");
        })
        ->leftJoin("rests",function($join){
            $join->on("tags.rest_id" , "=" , "rests.id");
        })
        ->select("tags.id", "tag_locales.name", "tags.store_count", "tags.image", "tag_types.name as type" , "tags.status")
        ->where("tag_locales.lang", "=", $lang)
        ->where("rests.name", "=", $rest)
        ->get();

        return $data;

        // $tags= TagCollection::collection($tag);
        // return response()->json([
        //     "Tags"=>$tags
        // ],200);
    }



    public function create(Request $request){

        $request ->validate([
            'name'=>'required',
        ]);
        $type_id = TagTypes::where('name',$request->tag_name)->first()->id;
        $rest_id = Rest::where('name' , $request->rest)->first()->id;

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
            'rest_id'=>$rest_id
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
            'id' => 'required',
            'name'=>'required',
            'rest'=>'required'
        ]);

        $tag = Tag::find($request->id);
        
        $rest = Rest::where('name' ,$request->rest)->first(); 
        $type_id = TagTypes::where('name' ,$request->tag_name)->first()->id;  
        
        $image = $request->hasFile('image');
       
        $tag->name = $request->name;
        if($image){
            
            $dest_path1 = 'storage/tags/images/'.$tag->image;
            if(File::exists($dest_path1)) {
                File::delete($dest_path1);
            }

            $dest_path = 'public/tags/images';
            $image = $request->file('image');
            $image_name = $image->getClientOriginalName();
            $path = $request->file('image')->storeAs($dest_path,$tag->id.$tag->name);
            $tag->image = $tag->id.$tag->name;
            
        }
        $tag->type_id = $type_id;
        $tag->rest_id = $rest->id;
 
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
    
    
    public function showID($id){

        // $request->validate([
        //     'id'=>'required'
        // ]);
        $tag = Tag::with('tag_locals')->where('id',$id)->first();
        $tag_name = TagTypes::find($tag->type_id)->name;
        return response([
            'tags'=>[
                'tag'=>$tag,
                'type_name'=>$tag_name
            ]
            ],201);

    }
    public function showAll($rest){

        $rest_id = Rest::where('name' ,$rest)->first()->id;
        return Tag::with('tag_locals')->where('rest_id',$rest_id)->get();
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
            'status'=>1,
        ]);
        return response([
            'message'=>'Tag created Successfully'
        ],201);
    }

    public function showTypes(){

        return TagTypes::all();

    }

    public function TypeStatus(Request $request){

        $request->validate([
            'id'=>'required',
            'status'=>'required',
        ]);

        $tag = TagTypes::where('id', $request->id)->first();
        $tag->status = $request->status;
        $tag->save();

        return response([
            'Message'=>'status changed'
        ],201);

    }
    
}
