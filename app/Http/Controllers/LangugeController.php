<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchLocals;
use App\Models\Language;
use App\Models\Store;
use App\Models\StoreLocals;
use App\Models\Tag;
use App\Models\TagLocales;
use App\Models\TagTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\DB;
use Mockery\Matcher\Type;

class LangugeController extends Controller
{
    public function show(){

        return Language::all();
    }

    public function store(Request $request){

        $request->validate([
            'name'=>'required'
        ]);
        
        if($l=Language::where('lang' , $request->name)->first()){
            return response([
                'message' =>'language is already exist'
            ],403);

        }
             $lang =Language::create([
            'lang'=>$request->name,
            'status'=>1
        ]);
        //Locales list.
        $tag_locals = Tag::all();
        $store_locals = Store::all();
        $branch_locals =Branch::all();

        //add lang to tags
        foreach ($tag_locals as $key => $value) {
            $tag_lang = TagLocales::create([
                'name'=>$tag_locals[$key]['name'],
                'tag_id'=>$tag_locals[$key]['id'],
                'description'=>'',
                'lang' => $request->name,
            ]
            );
            
        }

        //add lang to store
        foreach ($store_locals as $key => $value) {
            $store_lang = StoreLocals::create([
                'name'=>$store_locals[$key]['name'],
                'store_id'=>$store_locals[$key]['id'],
                'status'=>1,
                'lang' => $request->name,
            ]
            );
            
        }
        //add lang to branch
        foreach ($branch_locals as $key => $value) {
            $branch_locals = BranchLocals::create([
                'name'=>$store_locals[$key]['name'],
                'branch_id'=>$store_locals[$key]['id'],
                'address'=>'',
                'lang' => $request->name,
                'status'=>1
            ]
            );
            
        }


        return response([
            'message'=>'Language Added !'
        ],201);
    }


    public function delete(Request $request){
        
        $request->validate([
            'name'=>'required'
        ]);

        Language::where('lang' , $request->name)->delete();
        TagLocales::where('lang',$request->name)->delete();
        StoreLocals::where('lang',$request->name)->delete();
        BranchLocals::where('lang' , $request->name)->delete();

        return response([
            'message' => 'Language deleted'
        ],201);
    }


    
}
