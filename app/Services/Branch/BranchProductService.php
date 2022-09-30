<?php
namespace App\Services\Branch;

use App\Models\BranchProduct;
use App\Models\BranchProductCatalogs;
use App\Models\Product;
use Illuminate\Http\Request;


class  BranchProductService {

    public function createMainPage($product, $request){        
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
            'branch_id'=>'required'
            ]);
    
            $catalogs = $request->catalogs;
            $validated['product_id']= $product->id;
            $branchProduct = BranchProduct::create($validated);
            foreach($catalogs as $cat){
                BranchProductCatalogs::create([
                    'branch_product_id'=>$branchProduct->id,
                    'cat_id'=>$cat->catagory_id,
                    'catagory_id'=>$cat->parent_id,
                    'status'=>1
                ]);
    
            }
            return response([
                'message' => 'Main Branch Product Created !',
                'branch_porduct_id'=>$branchProduct->id
            ],201);
    }
    

}

