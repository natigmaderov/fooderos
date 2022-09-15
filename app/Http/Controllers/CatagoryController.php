<?php

namespace App\Http\Controllers;

use App\Models\CatagoryLocalsModel;
use App\Models\CatagoryModel;
use App\Models\Rest;
use App\Models\Store;
use App\Models\StoreLocals;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\PseudoTypes\True_;

use function PHPSTORM_META\map;

class CatagoryController extends Controller
{
    //section 1
    public function show($lang , $rest){
        // $a = CatagoryModel::select('id','catagory_id')->get();
        // $i = 0;
        // $tree = 0;
        // $temp = 0;
        // while(true){
          
        //     if($a[$i]->catogory_id != 0){
        //         $t = CatagoryModel::where('id' , $a[$i]->catagory_id)->first();
        //         $temp = 1;
        //         while(true){
        //             if($t->catagory_id ==0){
        //                $tree = $temp;
                        
        //             } 

        //             $t = CatagoryModel::where('id', $t->catagory_id)->first();
        //              $temp ++;
        //             if($tree<=$temp){
        //                 $tree = $temp;
        //             }
        //         }

        //         }
        //         $i++;
        //         if($i == count($a)){

        //             break;
        //         }
        //     }

         
        
        // return $tree;
        $rest_id =Rest::where('name' , $rest)->first()->id; 
        $test = array();
        $catagory = CatagoryModel::with(["sub"=>function($query) use ($lang) {
            $query
            ->select('id','catagory_id', 'branch_count' , 'store_id', 'image','status' )->with(["locals"=>function($query)use ($lang){
                $query->select('id','name','catagory_id','lang')->where('lang' ,$lang); 
            }])
            ->with(["store"=>function($query) use ($lang){
                $query->select('store_id' , 'name' , 'lang')->where('lang' , $lang);
            }])
            ->with(["sub"=>function($query) use ($lang){
                $query->select('id','catagory_id', 'branch_count' ,'status' ,'image' , 'store_id')->with(["locals"=>function($query)use ($lang){
                    $query->select('id','name','catagory_id','lang')->where('lang' ,$lang); 
                }])
                ->with(["store"=>function($query) use ($lang){
                    $query->select('store_id' , 'name' , 'lang')->where('lang' , $lang);
                }])->with(["sub"=>function($query) use ($lang){
                    $query->select('id','catagory_id', 'branch_count' , 'store_id','status' ,'image')->with(["locals"=>function($query)use ($lang){
                        $query->select('id','name','catagory_id','lang')->where('lang' ,$lang); 
                    }])->with(["store"=>function($query) use ($lang){
                        $query->select('store_id' , 'name' , 'lang')->where('lang' , $lang);
                    }]);
                }]);
            }]); 
        }])
        ->where('catagory_id' , 0)->where('rest_id' , $rest_id)
        ->with(["locals" => function($query) use ($lang){
            $query->select('id','name','catagory_id','lang')->where('lang',$lang); 
        }])
        ->with(["store"=>function($query)use ($lang){
            $query->select('store_id' , 'name' , 'lang')->where('lang',$lang);
        }])->select('id','catagory_id','branch_count' , 'image' , 'status' , 'rest_id' , 'store_id')->get();
        // array_push($test ,$catagory);
        return $catagory;


    }

    //section 2
    public function store(Request $request){
        $request->validate([
            'image'=>'required',
            'catagory_sub'=>'required',
            'rest' =>'required'

        ]);
        $store_id = StoreLocals::where('name' , $request->store)->first();
        $sub_catagory = CatagoryLocalsModel::where('name',$request->catagory_sub)->first();
        $rest = Rest::where('name' , $request->rest)->first();
        $catagory = CatagoryModel::create([
            'catagory_id'=>$sub_catagory->catagory_id??0,
            'branch_count'=>0,
            'image' => '',
            'status'=>1,
            'rest_id'=>$rest->id,
            'store_id'=>$store_id->store_id??0
        ]);
    
        if($request->hasFile('image')){
            $dest_path = 'public/catagory/images';
            $path = $request->file('image')->storeAs($dest_path,$catagory->id."_image");
            $catagory->image = $catagory->id."_image";
        }

        $catagory->save();

        $Locals = new CatagoryLocalsController();
        return $Locals->store($request , $catagory);
    }

    //section 3
    public function edit(Request $request){

        $request->validate([
            'id'=>'required'
        ]);
        $sub_catagory = CatagoryLocalsModel::where('name' , $request->catagory_sub)->first();
        $catagory = CatagoryModel::find($request->id);
       //update
        $catagory->catagory_id = $sub_catagory->catagory_id??0;
        $catagory->store_id = StoreLocals::where('name',$request->store)->first()->store_id??0;

        if($request->hasFile('image')){
            $dest_path1 = 'storage/catagory/images/'.$catagory->image;
            if(File::exists($dest_path1)) {
                File::delete($dest_path1);
            }
            $dest_path = 'public/catagory/images';
            $path = $request->file('profile')->storeAs($dest_path,$catagory->id."_image");
            $catagory->image = $catagory->id."_image";
        }
        $catagory->save();

        $Locals = new CatagoryLocalsController();
        return $Locals->edit($request , $catagory);
    }

    //section 4
    public function delete(Request $request){
        $request->validate([
            'id'=> 'required'
        ]);
        $catagory = CatagoryModel::find($request->id);

        $dest_path1 = 'storage/catagory/images/'.$catagory->image;
        if(File::exists($dest_path1)) 
        {
            File::delete($dest_path1);
        }
        CatagoryLocalsModel::where('catagory_id' , $catagory->id)->delete();

        $catagory->delete();

        return response([
            'message'=>'Catagory deleted'
        ]);

    }


    // public function status(Request $request){
      

    // }


    public function showID($id , $lang){
        
        $catagory = CatagoryModel::with(["locals"=>function($query){
            $query->select('catagory_id' , 'name' , 'lang');

        }])->select('id', 'catagory_id' , 'image' , 'store_id')->find($id);
        $Parent = CatagoryLocalsModel::where('catagory_id',$catagory->catagory_id)->where('lang' , 'En')->first()->name??0;
        $Store =StoreLocals::where('store_id', $catagory->store_id)->where('lang' ,$lang)->select('name')->get()??0;
        return response([
            'catagory'=>$catagory,
            'store'=>$Store,
            'ParentCatagory'=>$Parent
        ],201);

    }

    public function list($lang,$rest){
        $catagorylist =[];
        $storeList = [];
        $rest_id = Rest::where('name' ,$rest)->first()->id;
        $catgs =CatagoryModel::has('sub')->with(["sub" =>function($query){$query->has('sub')->with('sub');}])->get();
        $temp = [];
        $removeList = [];
        foreach($catgs  as $key ){
                foreach($key->sub as $key =>$value){
                    array_push($temp , $value->sub);
                }
        }
        foreach($temp as $a ){
            foreach($a as $key ){
                array_push($removeList , $key->id);
            }
        }

        $catagory=CatagoryModel::with(["locals"=>function($query)use ($lang){
            $query->where('lang' ,$lang);
        }])->where('rest_id' , $rest_id)->whereNotIn('id',$removeList)->where('status' ,1)->get();
       
        foreach($catagory as $key =>$value){
            array_push( $catagorylist,$catagory[$key]->locals[0]->name??'');
        }

        $Store = Store::where('store_type' ,$rest_id)->with(["store_locals"=>function($query)use ($lang){
            $query->where('lang' ,$lang);
        }])->where('status' ,1)->get();
        foreach($Store as $key =>$value){
            array_push( $storeList,$Store[$key]->store_locals[0]->name??'');
        }
        
        return response([
            'catagories'=>$catagorylist,
            'stores'=>$storeList
        ],201);
    }

    public function status(Request $request){

        $request->validate([
            'id'=>'required',
            'status'=>'required',
        ]);

       $catagory =  CatagoryModel::find($request->id);
       $catagory->status = $request->status;
       $catagory->save();  
        
       $Locals =CatagoryLocalsModel::select('id')->where('catagory_id' , $catagory->id)->get();

       CatagoryLocalsModel::whereIn('id',$Locals)->update([
        'status'=>$request->status
       ]);

       return response([
        'message'=>'status changed'
       ],201);

    }


    
}
