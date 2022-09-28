<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductLocals;
use App\Models\ProductVariants;
use App\Models\ProductVariantsCombination;
use App\Models\VariantOptions;
use App\Models\VariantOptionsValues;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function showProducts(){

        return response ([
            'product'=>ProductLocals::where('lang', 'en')->get()
        ]);
    }
    public function showVariants($id){
        $options = VariantOptions::where('product_id', $id)->with(["values.values"=>function($query){
            $query->where('lang' , "En");
        }])->with(['locales'=>function($query){
            $query->where('lang' , 'En');
        }])->get();

        $product_variants = ProductVariants::where('product_id' , $id)->with(['combination'=>function($query){
            $query->locales();
        }])->get();
        
        return response([
            'product_id'=>$product_variants,
            'options'=>$options
        ]);
    }

    public function statusVariants(Request $request){
        $request->validate([
            'status'=>'required',
            'id'=>'required'
        ]);
        $variants = ProductVariants::find($request->id);
        $variants->status = $request->status;
        $variants->save();
        $data = ProductVariantsCombination::where('product_variant_id' , $request->id)->get();
        foreach($data as $dat){
            $dat->status = $request->status;
            $dat->save();
        } 
        return response([
            'variants'=>'changed'
        ],201);

    }

    public function statusOptions(Request $request){
        $request->validate([
            'id'=>'required',
            'status'=>'required'
        ]);

        $options = VariantOptionsValues::find($request->id);
        $options->status = $request->status;
        $options->save();
        $data = ProductVariantsCombination::where('variant_option_values_id' , $request->id)->get();
        foreach($data as $dat){
            $dat->status = $request->status;
            $dat->save();
            $pro = ProductVariantsCombination::where('product_variant_id' , $dat->product_variant_id)->whereNotIn('variant_option_values_id',[$request->id] )->get();
            $temp = true;
            if($request->status == 1){   
            foreach($pro as $p){
                if($p->status == 0 ){
                    $temp=false;
                }
            }
        }
          
            if($temp){
                $pro = ProductVariants::find($dat->product_variant_id);
                $pro ->status = $request->status;
                $pro->save();
            }
         
        }
        return response([
            'option'=>'changed'
        ],201);
    }


 

}
