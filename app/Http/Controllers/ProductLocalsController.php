<?php

namespace App\Http\Controllers;

use App\Models\AddonsLocals;
use App\Models\Language;
use App\Models\ProductLocals;
use App\Models\VariantLocals;
use Illuminate\Http\Request;

class ProductLocalsController extends Controller
{
    public function store($request , $product){

        $languages = Language::all();
        foreach ($languages as $key => $value) {

            $productLocals = ProductLocals::create([
                    'name' => $request->input($languages[$key]['lang'].'_name'),
                    'product_id'=>$product->id,
                    'description'=>$request->input($languages[$key]['lang'].'_description'),
                    'lang'=>$languages[$key]['lang'],
                    'status'=>1
               ]);


        }


    }

    public function edit($request , $product){

        $languages = Language::all();
        foreach ($languages as $key => $value) {

            $productLocals = ProductLocals::where('product_id' , $product->id)->where('lang',$languages[$key]['lang'])->update([
                    'name' => $request->input($languages[$key]['lang'].'_name'),
                    'product_id'=>$product->id,
                    'description'=>$request->input($languages[$key]['lang'].'description'),
                    'lang'=>$languages[$key]['lang'],
                    'status'=>1
               ]);


        }


    }



    public function storeAddons($data , $id){

        $languages = Language::all();
            foreach ($languages as $key => $value) {
                $addonsLocals = AddonsLocals::create([
                    'name' =>$data->$languages[$key]['lang'].'_name',
                    'addon_id'=>$id,
                    'status'=>1,
                    'lang'=>$languages[$key]['lang']

                ]);

            }
    }
}
