<?php

namespace App\Http\Controllers;

use App\Http\Traits\BranchProductTrait;
use App\Models\Branch;
use App\Models\BranchProduct;
use App\Models\BranchProductAddons;
use App\Models\BranchProductCatalogs;
use App\Models\BranchProductVariants;
use App\Models\Product;
use App\Models\ProductVariants;
use App\Models\TempVariants;
use App\Services\Branch\BranchProductService;
use Illuminate\Http\Request;

class BranchProductController extends Controller
{
    use BranchProductTrait;
    protected $service ;
    public function __construct(BranchProductService $services)
    {
        $this->service = $services;
    }

    public function showID($id){
      
        return $this->showProductsById($id);
    }

    public function show($id , $lang){
       
        return  $this->showProducts($lang , $id);
        
    }

    public function getProducts($id){

       return $this->getProductsById($id);
        
    }

    public function createMain(Product $product  , Request $request ){
        return $this->service->createMainPage( $product,$request);
    }


    public function createVariants(BranchProductVariants $variant , Request $request){  

        $request->validate([
            'variants'=>'required',
            'branch_product_id'=>'required'
        ]);
        $i = 0; 
        $variants = $request->variants;
        foreach($variants as $var){
            $variant->create([
                'image'=>$var->image,
                'branch_product_id'=>$request->branch_product_id,
                'sku'=>$var->sku,
                'barcode'=>$var->barcode,
                'variant_id'=>$var->variant_id,
                'price'=>$var->price,
                'weight'=>$var->weigth,
                'status'=>$var->status   
            ]);  
            if($request->hasFile($i)){
                $dest_path = 'public/product/variants/images';
                $path = $request->file($i)->storeAs($dest_path, $variant->id . "_image");
                $variant->image = $variant->id . "_image_branch";
                $i++;    
            }
            $variant->save();
        }

        return response([
            'message'=>'Branch product variants created !',
            'branch_product_id'=> $request->branch_product_id
        ],201);
        
    }

    public function createAddons( BranchProductAddons $addon, Request $request){
        
        $request->validate([
            'branch_product_id'=>'required',
            'addons'=>'required'
        ]);

        $addons =  $request->addons;
        foreach($addons as $key => $value){
            $addon->create([
                'sku'=>$addons[$key]['sku'],
                'barcode'=>$addons[$key]['barcode'],
                'unit_price'=>$addons[$key]['unit_price'],
                'weight'=>$addons[$key]['weigth'],
                'status'=>$addons[$key]['status'],
                'branch_product_id'=>$request->branch_product_id,
                'addon_id'=>$addons[$key]['addon_id']
            ]);

        }

        return response([
            'message'=>'Branch Product Addons created !',
            
        ],201);

    }

   

    // public function edit(){
        
    // }

}
