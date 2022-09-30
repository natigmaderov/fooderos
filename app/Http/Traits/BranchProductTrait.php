<?php
namespace App\Http\Traits;

use App\Models\BranchProduct;
use App\Models\Product;

trait BranchProductTrait {

    public function showProducts($lang , $id){
        $product =BranchProduct::with(['locales'=>function($query) use ($lang){
            $query->where('lang', $lang);
        }])
        ->with('catalogs.sub')->where('branch_id' , $id)->get();
        return $product;

    }

    public function showProductsById($id){
        $product =  BranchProduct::with('locales')->with(['variants'=>function($query){
            $query->with(['combination'=>function ($query){
                $query->locales();
            }]);
        }])->with('addons.locales')->with(['catalogs.sub'])->find($id);
        return $product;
    }


    public function getProductsById($id){

       $product = Product::with('locals')->with(['variants'=>function($query){
            $query->with('combination.localesOption')->with('combination.localesValue');
        }])
        ->with('addons.locales')->with('store')->with(['option'=>function($query){
            $query->with('locales')->with("values.values");
        }])->find(1);

        return $product;
    }
}