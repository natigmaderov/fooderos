<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchCatalog;
use App\Models\BranchLocals;
use App\Models\CatagoryLocalsModel;
use App\Models\CatagoryModel;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;

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
        // public function __construct(Request $request)
        // {
        //     $cats = CatagoryModel::all();

        //     foreach($cats as $cat ){

        //         if(!BranchCatalog::where('branch_id' ,$request->branch_id)->where('catagory_id' , $cat->id)->first()){
        //         BranchCatalog::create([
        //             'branch_id'=>$request->branch_id,
        //             'catagory_id'=>$cat->id,
        //             'status'=>1,
        //             'parent_id'=>$cat->catagory_id,
        //             'isActive'=>0,
        //             'product_count'=>1
        //         ]);

        //     }
        // }
        // }


    public function store(Request $request){
        $request->validate([
            'catagories' => 'required',
            'branch_id'=>'required'
        ]);
        $cats = $request->catagories;
        foreach($cats as $cat){
            $data = BranchCatalog::create([
                'branch_id'=>$request->branch_id,
                'catagory_id'=>$cat['catagory_id'],
                'status'=>1,
                'image'=>$cat['image'],
                'parent_id'=>$cat['parent_id'],
                'isActive'=>$cat['active'],
                'product_count'=>0
            ]);
        }

        return response([
            'message'=>'Branch Catalog added successfully !'
        ],201);
        
    }

    public function edit(Request $request){
        $cats = CatagoryModel::all();

        foreach($cats as $cat ){
            if(!BranchCatalog::where('branch_id' ,$request->branch_id)->where('catagory_id' , $cat->id)->first()){
            BranchCatalog::create([
                'branch_id'=>$request->branch_id,
                'catagory_id'=>$cat->id,
                'status'=>1,
                'parent_id'=>$cat->catagory_id,
                'isActive'=>0,
                'product_count'=>1
            ]);

        }
    }
        $request->validate([
            'branch_id'=>'required',
            'catagories'=>'required'
        ]);

        $cats = $request->catagories;
        foreach($cats as $cat) {
            $data = BranchCatalog::where('branch_id' , $request->branch_id)->where('catagory_id' , $cat['catagory_id'])->first();
            $data->isActive = $cat['isActive'];
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
    public function show($id , $lang ,Request $request){
        $branchcatalogs = BranchCatalog::where('branch_id' , $id)->with(["sub"=>function($query) use ($request){
            $query->where('isActive' , '1');
        }, ])->with(['catalocales'=>function($query) use ($request) {
            $query->select('name' , 'catagory_id' , 'lang')->where('lang' , $request->lang);
        }])->where('parent_id' , 0)
        ->where('isActive' , 1)->get();
       
        return $branchcatalogs;


    }
}
