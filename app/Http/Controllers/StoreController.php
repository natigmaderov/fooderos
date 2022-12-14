<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShowCollection;
use App\Http\Resources\StoreCollection;
use App\Models\Branch;
use App\Models\Manager;
use App\Models\Rest;
use App\Models\Store;
use App\Models\StoreLocals;
use App\Models\StoreTags;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use App\Models\StoreType;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Pagination\Factory;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session as FacadesSession;

use function PHPSTORM_META\map;

class StoreController extends Controller
{
    public function show($lang ,$type){
        $type_id = Rest::where('name' ,$type)->first()->id;
        $store = StoreCollection::collection(Store::where('store_type' , $type_id)->with(["tags.tag" => function ($query) use ($lang) {
            $query->where('lang' ,$lang )->select('tag_id','name','lang');}])->with(["store_locals" =>function($query) use ($lang) {
                $query->where('lang' ,$lang)->select('store_id','store_locals.name','lang');
            }])->get());
        
        
        
        //     $storelocals =Store::where('store_type' , $type_id)->with(["store_locals" =>function($query){
        //     $query->select('store_id','store_locals.name','lang');
        // }])->select('id')->get();
        

        return response([
            'Message'=>"Success",
             $type => $store,
            //  'storeLocals'=>$storelocals
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
            $tag = Tag::where('name',$myArray[$key])->first();
            $store_tags = StoreTags::create([
                'store_id' => $store->id,
                'tag_id'=> $tag->id,
                'status' => 1

            ]);
            // $tag->store_count +=1;
            // $tag->save();

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
       
        $branch = new BranchController();
        return $branch->destroy($request->id);

        return response([
            'message'=>'store deleted'
        ],201);

    }  

    public function status(Request $request){
        $request->validate([
            'id'=>'required',
            'status'=>'required',
        ]);

        $store = Store::where('id', $request->id)->first();
        $store->status = $request->status;
        $store->save();
        
        $branch = Branch::where('store_id' , $store->id)->get();
        foreach ($branch as $key =>$value){

            $branch[$key]->status = $request->status;
            $branch[$key]->save();
        }

        return response([
            'Message'=>'status changed'
        ],201);
    }


    public function StoreListClient(){

        
    }


    public function StoreFilterClient(Request $request){

        $request->validate([
            'lat'=>'required',
            'long'=>'required',

        ]);
        $tag_id = 0;
        $stores = "";
        $branchs = Branch::all();
        $store_ids = [];
        if($tag_id = Tag::where('name',$request->tag)->first())
        {
            $stores = StoreTags::where('tag_id',$tag_id->id)->get();
            foreach($stores as $key => $value){
                $store_id = $stores[$key]->store_id;
                array_push($store_ids , $store_id);
            }
            $branchs = Branch::whereIn('store_id' ,$store_ids)->get();
            
    

        }   

 
        $array = array();
        foreach($branchs as $key => $value){
            
            $branch = $branchs[$key];
            $lat = $branch->lat;
            $long = $branch->long;
        
    
            $theta = $long - $request->long; 
            $dist = sin(deg2rad($lat)) * sin(deg2rad($request->lat)) +  cos(deg2rad($lat)) * cos(deg2rad($request->lat)) * cos(deg2rad($theta)); 
            $dist = acos($dist); 
            $dist = rad2deg($dist); 
            $miles = $dist * 60 * 1.1515 * 1.609344;
              if($branch->max_distance >= $miles){
                $array = Arr::add($array, $branch->id, ['name'=>$branch->name ,'profile' => $branch->profile , 'cover'=>$branch->cover]);
            }

        }
        return $array;





    }
    
    
}
