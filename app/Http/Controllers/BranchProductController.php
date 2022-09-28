<?php

namespace App\Http\Controllers;

use App\Models\BranchProduct;
use App\Models\BranchProductVariants;
use App\Models\Product;
use App\Models\ProductVariants;
use App\Models\TempVariants;
use Illuminate\Http\Request;

class BranchProductController extends Controller
{

    public function getProducts($id){
       return Product::with('locals')->with(['variants'=>function($query){
            $query->with('combination.localesOption')->with('combination.localesValue');
        }])
        ->with('addons.locales')->with('store')->with(['option'=>function($query){
            $query->with('locales')->with("values.values");
        }])->find(1);

        
    }

    public function createMain(Product $product , Request $request){
        $validated = $request->validate([
        'isPublish'=>'required',
        'status'=>'required',
        'sku'=>'required',
        'barcode'=>'required',
        'price'=>'required',
        'weight'=>'required',
        'vendor'=>'required',
        'preparation_time'=>'required',
        'working_hours'=>'required',
        ]);
        $validated['product_id']= $product->id;
        $branchProduct = BranchProduct::create($validated);

        return response([
            'message' => 'Main Branch Product Created !',
            'branch_porduct_id'=>$branchProduct->id
        ]);
    }


    public function createVariants(BranchProductVariants $variant , Request $request){  

        $request->validate([
            'variants'=>'required'
        ]);
        $variants = $request->variants;
        foreach($variants as $var){
            $variant->create([
                'image'=>$var->image,
                'branch_product_id'=>$request->branch_product_id,
                'sku'=>$var->sku,
                'barcode'
            ]);
    
        }
        
    }

}
