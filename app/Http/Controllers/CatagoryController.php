<?php

namespace App\Http\Controllers;

use App\Models\CatagoryModel;
use App\Models\Rest;
use Illuminate\Http\Request;

use function PHPSTORM_META\map;

class CatagoryController extends Controller
{
    //section 1
    public function show(){
        
        $catagory = CatagoryModel::all();


    }

    //section 2
    public function store(Request $request){
        $request->validate([
            'image'=>'required',
            'catagory_sub'=>'required',
            'rest' =>'required'

        ]);
        $sub_catagory = CatagoryModel::where('name',$request->catagory_sub)->first();
        $rest = Rest::where('name' , $request->rest)->first();
        $catagory = CatagoryModel::create([
            'catagory_id'=>$sub_catagory->id,
            'branch_count'=>0,
            'image' => '',
            'status'=>1,
            'rest_id'=>$rest->id,
            'store_id'=>$request->store
        ]);
    
        if($request->hasFile('image')){
            $dest_path = 'public/catagory/images';
            $path = $request->file('profile')->storeAs($dest_path,$catagory->id."_image");
            $catagory->profile = $catagory->id."_image";
        }

        $catagory->save();

        $Locals = new CatagoryLocalsController();
        return $Locals->store($request , $catagory);
    }

    //section 3
    public function edit(){

        $catagory = CatagoryModel::update();


    }

    //section 4
    public function delete(){

        $catagory = CatagoryModel::delete();


    }




    
}
