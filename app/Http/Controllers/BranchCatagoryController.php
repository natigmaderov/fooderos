<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchCatalog;
use App\Models\BranchLocals;
use App\Models\CatagoryLocalsModel;
use Illuminate\Http\Request;

class BranchCatagoryController extends Controller
{
    // public function __construct()
    // {
    //     $productCount =CatagoryModel::all();
    //     foreach ($productCount as $key => $value){
    //             $productCount[$key]->branch_count = count(BranchCatalog::where('catagory_id' ,$productCount[$key]->id)->get());
    //             $productCount[$key]->save();
    //     }
    // }
    public function store(Request $request){
        $request->validate([
            'catagories' => 'required'
        ]);
        $cats = $request->catagories;
        foreach($cats as $cat){
            $data = BranchCatalog::create([
                'branch_id'=>$request->branch_id,
                'catagory_id'=>$cat->catagory_id,
                'status'=>1,
                'parent_id'=>$cat->parent_id,
                'isActive'=>$cat->isActive,
                'product_count'=>0
            ]);
        }

        return response([
            'message'=>'Branch Catalog added successfully !'
        ],201);
        
    }

    public function edit(Request $request){
        $request->validate([
            'branch_id'=>'required',
            'catagories'=>'required'
        ]);
        $cats = $request->catagories;
        foreach($cats as $cat) {
            $data = BranchCatalog::where('branch_id' , $request->branch_id)->where('catagory_id' , $request->catagory_id)->first();
            $data->isActive = $cat->isActive;
            $data->save();
        }

        return response([
            'message'=>'Records Updated'
        ],201);
    }

    public function status(Request $request){
        $request->validate([
            'id'=>'required',
            'status'=>'required'
        ]);

        $data = BranchCatalog::find($request->id);
        $data->status = $request->status;
        $data->save();

        return response([
            'message'=>'status changed !'
        ],201);

    }

    public function delete($id){
        $data = BranchCatalog::find($id);
        $data->isActive = 0;
        $data->save();
        return response([
            'message'=>'row removed !'
        ],201);
    }
    public function show(){

        $branchcatalogs = BranchCatalog::with(["sub"=>function($query) {
            $query->where('isActive' , '1');
        }, ])->with(['catalocales'=>function($query){
            $query->select('name' , 'catagory_id' , 'lang')->where('lang' , \request()->header('lang'));
        }])->where('parent_id' , 0)
        ->where('isActive' , 1)->select('id','branch_id' , 'catagory_id','product_count','status' ,'parent_id' , 'isActive' )->get();
       
        return $branchcatalogs;


    }
}
