<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShowCollection;
use App\Http\Resources\StoreCollection;
use App\Models\Manager;
use App\Models\Rest;
use App\Models\Store;
use App\Models\StoreLocals;
use App\Models\StoreTags;
use Illuminate\Support\Facades\File;
use App\Models\StoreType;
use App\Models\Tag;
use Illuminate\Http\Request;

use function PHPSTORM_META\map;

class StoreController extends Controller
{
    public function show($lang ,$type){
        $type_id = Rest::where('name' ,$type)->first()->id;
        $store = StoreCollection::collection(Store::where('store_type' , $type_id)->with(["tags.tag" => function ($query) use ($lang) {$query->where('lang' ,$lang );}])->get());
        
        

        return response([
            'Message'=>"Success",
             $type => $store
        ],201);
    }

    public function manager(){

        return Manager::all();
    }


    public function store(Request $request){

        $request->validate([
            'name'=>'required',
            'type'=>'required',
            'manager'=>'required',
            'commission'=>'required',
            'price'=>'required',
            'image'=>'required'
        ]);


        $myArray = explode(' ', $request->tags);
        

        $manager = Manager::where('name' , $request->manager)->first()->id;
        $type = Rest::where('name' , $request->type)->first()->id;
        $store = Store::create([
            'name'=>$request->name,
            'manager_id' =>$manager,
            'store_type'=>$type,
            'price'=>$request->price,
            'commission'=>$request->commission,
            'image'=>'default',
            'status'=>1

        ]);


        if($request->hasFile('image')){
            $dest_path = 'public/stores/images';
            $image = $request->file('image');
            $image_name = $image->getClientOriginalName();
            $path = $request->file('image')->storeAs($dest_path,$store->id.$store->name);
            $store->image = $store->id.$store->name;
        }

       
        foreach($myArray as $key => $value){
            $store_tags = StoreTags::create([
                'store_id' => $store->id,
                'tag_id'=> Tag::where('name',$myArray[$key])->first()->id,
                'status' => 1

            ]);

        }
        
        $store->save();

        $Locals = new StoreLocalsController();
        return $Locals->store($store ,$request);

    }

    public function showId($id){
        $store  = Store::select('id','name','image','price','manager_id','commission')->find($id);
        $manager = Manager::find($store->manager_id)->name;

        $tags = ShowCollection::collection(StoreTags::with('tag')->where('store_id' , $id)->get());
        $store_locales = StoreLocals::select('name','lang')->where('store_id',$id)->get();

        return response([
            'Message'=>'Success',
            'store_data'=>$store,
            'manager'=>$manager,
            'store_locales'=> $store_locales,
            'tags'=>$tags
        ],201);

    }


    public function edit(Request $request){
        $request->validate([
            'id'=>'required',
            'name'=>'required',
        ]);

        $store = Store::find($request->id);
        $myArray = explode(' ', $request->tags);
        $store->name = $request->name ;
        $store->commission =$request->commission;
        $store->price = $request->price;
        $store->manager_id = Manager::where('name',$request->manager)->first()->id;

        if($request->hasFile('image')){
            $dest_path1 = 'storage/stores/images/'.$store->image;
            if(File::exists($dest_path1)) {
                File::delete($dest_path1);
            }

            $dest_path = 'public/stores/images';
            $image = $request->file('image');
            $image_name = $image->getClientOriginalName();
            $path = $request->file('image')->storeAs($dest_path,$store->id.$store->name);
            $store->image = $store->id.$store->name;
        }
        $store->save();
      

        StoreTags::where('store_id',$store->id)->delete();

        foreach($myArray as $key => $value){
            $store_tags = StoreTags::create([
                'store_id' => $store->id,
                'tag_id'=> Tag::where('name',$myArray[$key])->first()->id,
                'status' => 1

            ]);

        }
        $Locals = new StoreLocalsController();
        return $Locals->edit($store ,$request);

    }


    public function delete(Request $request){

        $request->validate([
            'id'=>'required'
        ]);

        Store::find($request->id)->delete();
        StoreLocals::where('store_id',$request->id)->delete();
        StoreTags::where('store_id',$request->id)->delete();

        return response([
            'message'=>'store deleted'
        ],201);

    }   

    
}
