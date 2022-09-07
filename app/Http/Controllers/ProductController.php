<?php

namespace App\Http\Controllers;

use App\Models\CatagoryModel;
use App\Models\Manager;
use App\Models\Product;
use App\Models\ProductAddons;
use App\Models\ProductLocals;
use App\Models\ProductVariants;
use App\Models\Rest;
use App\Models\Store;
use App\Models\StoreLocals;
use App\Models\Variants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;



class ProductController extends Controller
{
    public function storeProduct(Request $request)
    {
        $request->validate([
            'sku',
            'rest',
            'manager',
            'isVariant',
            'isAddons'
        ]);

        $rest = Rest::where('name', $request->rest)->first();
        $store = StoreLocals::where('name', $request->store)->first();
        $manager = Manager::where('name', $request->manager)->first();
        $product = Product::create([
            'store_id' => $store->store_id ?? 0,
            'image' => '',
            'sku' => $request->sku,
            'rest_id' => $rest->id,
            'barcode' => $request->barcode ?? '',
            'position_id' => $request->position_id ?? '',
            'manager' => $manager->id ?? 0,
            'price' => $request->price,
            'isVariant' => $request->isVariant,
            'idAddons' => $request->isAddons,
            'status' => 1
        ]);

        if ($request->hasFile('image')) {
            $dest_path = 'public/product/images';
            $path = $request->file('image')->storeAs($dest_path, $product->id . "_image");
            $product->image = $product->id . "_image";
        }

        $product->save();

        $Locals = new ProductLocalsController();
        $Locals->store($request, $product);

        return response([
            "product_id" => $product->id,
            "Variants" => $product->isVariant,
            "Addons" => $product->isAddons,
            "variants" => Variants::select('id', 'name')->where('status', 1)->get()
        ], 201);
    }


    ////////////////////////////////////////////////////////////////////////////////////


    public function storeVariants(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'variants' => 'required',
        ]);
        $variants = explode(',', $request->variants);

        for ($i = 0; $i < count($variants); $i += 6) {

            $data = ProductVariants::create([    
                'image' => '',
                'sku' => $variants[$i + 1],
                'barcode' => $variants[$i + 2],
                'unit_price' => $variants[$i + 3],
                'weight' => $variants[$i + 4],
                'status' => $variants[$i + 5],
                'product_id' => $request->product_id
            ]); 
            $Locals = new ProductLocalsController();
            $Locals->storeVariants($data->id , $variants[$i]);
            
            $image_data = $variants[$i] . '_image';
            if ($request->hasFile($image_data)) {
                $dest_path = 'public/product/variants/images';
                $path = $request->file($image_data)->storeAs($dest_path, $image_data);
                $data->image = $image_data;
            }
            $data->save();
        }
        return response([
            'Message' => 'Variants Created',
            'product_id' => $request->product_id
        ], 201);
    }


    ////////////////////////////////////////////////////////////////////////////////////



    public function storeAddons(Request $request)
    {

        $request->validate([
            'product_id' => 'required',
            'addons' => 'required'
        ]);
        $addons = $request->addons;
        foreach ($addons as $key => $value) {
            $data = ProductAddons::create([
                'sku' => $addons[$key]->sku,
                'barcode' => $addons[$key]->barcode,
                'unit_price' => $addons[$key]->addons,
                'weight' => $addons[$key]->weight,
                'status' => $addons[$key]->status,
                'product_id' => $request->product_id
            ]);
            $Locals = new ProductLocalsController();
            $Locals->storeAddons($addons[$key] , $data->id);
        }
        $product = Product::with('locals')->with('addons')->with('variants')->find($request->product_id);

        return response([
            "message" => 'Addons created !',
            'review'=>$product
        ], 201);
    }



    public function editProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'page' => 'required'
        ]);

        if ($request->page == 'product') {

            $request->validate([
                'sku',
                'rest',
                'manager',
            ]);
            $store = StoreLocals::where('name', $request->store)->first();
            $manager = Manager::where('name', $request->manager)->first();
            $product = Product::find($request->product_id);
            $product->update([
                'store_id' => $store->store_id ?? 0,
                'sku' => $request->sku,
                'barcode' => $request->barcode ?? '',
                'position_id' => $request->position_id ?? '',
                'manager' => $manager->id ?? 0,
                'price' => $request->price,
            ]);
            if ($request->hasFile('image')) {
                $dest_path1 = 'storage/product/images/' . $product->image;
                if (File::exists($dest_path1)) {
                    File::delete($dest_path1);
                }
                $dest_path = 'public/product/images';
                $path = $request->file('image')->storeAs($dest_path, $product->id . "_image");
                $product->image = $product->id . "_image";
            }
            $product->save();
            $Locals = new ProductLocalsController();
            $Locals->edit($request, $product);
            return response([
                'message' => 'updated !',
                'product_id' => $product->id,

            ], 201);
        }


        if ($request->page == 'variants') {

            $variants = explode(',', $request->variants);

            for ($i = 0; $i < count($variants); $i += 6) {

                if ($product = ProductVariants::where('product_id', $request->product_id)->where('name', $variants[$i])->first()) {
                    $product->update([
                        'name' => $variants[$i],
                        'image' => '',
                        'sku' => $variants[$i + 1],
                        'barcode' => $variants[$i + 2],
                        'unit_price' => $variants[$i + 3],
                        'weight' => $variants[$i + 4],
                        'status' => $variants[$i + 5],
                    ]);
                    $image_data = $variants[$i] . '_image';
                    if ($request->hasFile($image_data)) {
                        $dest_path1 = 'storage/product/variants/images/' . $product->image;
                        if (File::exists($dest_path1)) {
                            File::delete($dest_path1);
                        }
                        $dest_path = 'public/product/variants/images';
                        $path = $request->file($image_data)->storeAs($dest_path, $image_data);
                        $product->image = $image_data;
                    }
                    $product->save();
                } 
                else {
                    $data = ProductVariants::create([
                        'name' => $variants[$i],
                        'image' => '',
                        'sku' => $variants[$i + 1],
                        'barcode' => $variants[$i + 2],
                        'unit_price' => $variants[$i + 3],
                        'weight' => $variants[$i + 4],
                        'status' => $variants[$i + 5],
                        'product_id' => $request->product_id

                    ]);

                    $image_data = $variants[$i] . '_image';

                    if ($request->hasFile($image_data)) {

                        $dest_path = 'public/product/variants/images';
                        $path = $request->file($image_data)->storeAs($dest_path, $image_data);
                        $data->image = $image_data;
                    }
                    $data->save();
                }
            }
            return response([
                'Message' => 'Variants Updated',
                'product_id' => $request->product_id
            ], 201);
        }

        if ($request->page == 'addons') {

            $request->validate([
                'addons' => 'required'
            ]);

            $addons = $request->addons;

            foreach ($addons as $key => $value) {
                
                if($data =ProductAddons::where('product_id' ,$request->product_id )->where('name' , $addons[$key]->name)->first()){
                $data->update([
                    'name' => $addons[$key]->name,
                    'sku' => $addons[$key]->sku,
                    'barcode' => $addons[$key]->barcode,
                    'unit_price' => $addons[$key]->addons,
                    'weight' => $addons[$key]->weight,
                    'status' => $addons[$key]->status,
                    
                ]);
                }
                else{
                    $data = ProductAddons::create([
                        'name' => $addons[$key]->name,
                        'sku' => $addons[$key]->sku,
                        'barcode' => $addons[$key]->barcode,
                        'unit_price' => $addons[$key]->addons,
                        'weight' => $addons[$key]->weight,
                        'status' => $addons[$key]->status,
                        'product_id' => $request->product_id
                    ]);
                }
            }
            return response([
                'message'=>'Addons updated !',
                'product_id'=>$request->product_id
            ],201);
        }
    }

    public function delete(Request $request){
        $request->validate([
            'id'=>'required'
        ]);
        ProductLocals::where('product_id' , $request->id)->delete();
        ProductAddons::where('product_id' ,$request->id)->delete();
        $variants = ProductVariants::where('product_id' ,$request->id)->get();
        $product = Product::find($request->id);
        $dest_path1 = 'storage/product/images/'.$product->image;
        if(File::exists($dest_path1)) 
        {
            File::delete($dest_path1);
        }
        foreach($variants as $var){
            if(File::exists('storage/product/variants/images'.$var->image)){
               File::delete($dest_path1);
            }
            $var->delete();
        }
        $product->delete();



        return response([

            'Message'=>'Deleted !'
        ],201);
        
    }

    public function status(Request $request){
        $request->validate([
            'id'=>'required',
            'status'=>'required',
        ]);
        $product = Product::find($request->id);
        $product->status = $request->status;
        $product->save();

    }


    public function showProducts($lang,$rest){
        $product = Product::with(["locals"=>function($query) use($lang){
            $query->where('lang' , $lang)->select('name','lang' , 'product_id');
        }])->with(["store"=>function($query) use($lang){
            $query->where('lang' , $lang)->select('name','lang' ,'store_id');
        }])->select('id','image','store_id','rest_id','status')->where('rest_id' ,$rest)->get();
        
        return $product;
    }

    public function showId($id){
        $product = Product::with('locals')->with('variants')
        ->with('addons')->with('store')->find($id);

        return $product;

    }

}
