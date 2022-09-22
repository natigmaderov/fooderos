<?php

namespace App\Http\Controllers;

use App\Models\CatagoryModel;
use App\Models\Language;
use App\Models\Manager;
use App\Models\Product;
use App\Models\ProductAddons;
use App\Models\ProductLocals;
use App\Models\ProductVariants;
use App\Models\ProductVariantsCombination;
use App\Models\Rest;
use App\Models\Store;
use App\Models\StoreLocals;
use App\Models\VariantOptions;
use App\Models\VariantOptionsLocales;
use App\Models\VariantOptionsValues;
use App\Models\VariantOptionsValuesLocales;
use App\Models\Variants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PhpOption\Option;

class ProductController extends Controller
{
    public function storeProduct(Request $request)
    {
        $request->validate([
            'sku',
            'rest',
            'manager',
            'isVariant',
            'isAddons',
            'position_id'
        ]);

        $rest = Rest::where('name', $request->rest)->first();
        $store = StoreLocals::where('name', $request->store)->first();
        $manager = Manager::where('name', $request->manager)->first();
        $product = Product::create([
            'store_id' => $store->store_id??0,
            'image' => '',
            'sku' => $request->sku,
            'rest_id' => $rest->id,
            'barcode' => $request->barcode??'',
            'position_id' => $request->position_id??'',
            'manager_id' => $manager->id,
            'price' => $request->price,
            'isVariant' => $request->isVariant,
            'isAddons' => $request->isAddons,
            'addons_limit'=>0,
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
        ], 201);
    }


    ////////////////////////////////////////////////////////////////////////////////////


    public function storeOptions(Request $request)
    {
        $request->validate([
            'options'=>'required'
        ]);
        $product_id = $request->product_id;
        $options = $request->options;
        foreach($options as $option){
            $variants_option = VariantOptions::create([
                'status'=>1,
                'product_id'=>$product_id
            ]);
            $languages = Language::all();
           
            foreach ($languages as $key => $value) {
                $option_locales = VariantOptionsLocales::create([
                    'name'=>$option['option'][$languages[$key]['lang']."_name"],
                    'lang'=>$languages[$key]['lang'],
                    'status'=>'1',
                    'variant_option_id'=>$variants_option->id
                ]);         
            }    
            foreach($option['values'] as $val){
                $option_values = VariantOptionsValues::create([
                    'status'=>1,
                    'variant_option_id'=>$variants_option->id
                ]);
                foreach($languages as $key => $value){
                    $option_values_locales = VariantOptionsValuesLocales::create([
                        'name'=>$val[$languages[$key]['lang'].'_name'],
                        'status'=>1,
                        'product_id'=>$product_id,
                        'lang'=>$languages[$key]['lang'],
                        'variant_option_value_id'=>$option_values->id
                    ]);
                }
            }
        }
        return response([
            'message'=>'success'
        ],201);
        

    }

    public function storeVariants(Request $request){
        // $request->validate([
        //     'variants'=>'required'
        // ]);

        

        $variants = json_decode($request->test);

        $req = $request;
        $i = 0;
        foreach($variants as $variant){
            $options = $variant->options;
            $product_variants = ProductVariants::create([
                'sku'=>$variant->data->sku,
                'barcode'=>$variant->data->barcode,
                'product_id'=>$request->product_id,
                'price'=>$variant->data->price,
                'weight'=>$variant->data->weight,
                'status'=>$variant->data->status,
                'image'=>''
            ]);
            
            if ($request->hasFile($i)) {
                $dest_path = 'public/product/variants/images';
                $path = $request->file($i)->storeAs($dest_path, $product_variants->id . "_image");
                $product_variants->image = $product_variants->id . "_image";
                $i++;
            
            }
            $product_variants->save();

            $options = $variant->options;
            
            $ids = VariantOptionsValuesLocales::whereIn('name' , $options)->where('product_id' , $request->product_id)->pluck('variant_option_value_id');
            $options = VariantOptionsValues::whereIn('id' , $ids)->select('id' , 'variant_option_id')->get();
            

            foreach($options as $option){
                $combination = ProductVariantsCombination::create([
                    'variant_option_values_id'=>$option->id,
                    'variant_option_id'=>$option->variant_option_id,
                    'status'=>$product_variants->status,
                    'product_variant_id'=>$product_variants->id
                ]);
            }
        }
        return response([
            'message'=>'Product Variants addded !',
            'product_id'=>$request->product_id
        ],201);

        // ProductVariantsCombination::where('product_variant_id' ,1)
        // ->locales()
        // ->select('id','variant_option_values_id' , 'product_variant_id' , 'variant_option_id')->get();

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
                'sku' => $addons[$key]['sku'],
                'barcode' => $addons[$key]['barcode'],
                'unit_price' =>$addons[$key]['price'],
                'weight' => $addons[$key]['weight'],
                'status' => $addons[$key]['status'],
                'addon_limit'=>$addons[$key]['limit'],
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
            return $request->barcode;
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
            
            $variants = json_decode($request->test);
            $i = 0;
             foreach($variants as $variant){
                // $options = $variant->options;
                if(ProductVariants::find($variant->id)){
                $product = ProductVariants::find($variant->id);
                
                $product->update([
                    'sku'=>$variant->sku,
                    'barcode'=>$variant->barcode,
                    'product_id'=>$request->product_id,
                    'price'=>$variant->price,
                    'weight'=>$variant->weight,
                    'status'=>$variant->status,
                ]);
                if ($request->hasFile($i)) {
                    $dest_path1 = 'storage/product/variants/images/'.$product->image;
                    if(File::exists($dest_path1)) {
                        File::delete($dest_path1);
                    }
                    $dest_path = 'public/product/variants/images';
                    $path = $request->file($i)->storeAs($dest_path, $product->id . "_image");
                    $product->image = $product->id . "_image";
                
                }
                $product->save();
                }
                else{
                   $product_variants = ProductVariants::create([
                    'sku'=>$variant->sku,
                    'barcode'=>$variant->barcode,
                    'product_id'=>$request->product_id,
                    'price'=>$variant->price,
                    'weight'=>$variant->weight,
                    'status'=>$variant->status,
                    'image'=>''
                   ]);
                   if ($request->hasFile($i)) {
                    $dest_path1 = 'storage/product/variants/images/'.$product_variants->image;
                    if(File::exists($dest_path1)) {
                        File::delete($dest_path1);
                    }
                    $dest_path = 'public/product/variants/images';
                    $path = $request->file($i)->storeAs($dest_path, $product_variants->id . "_image");
                    $product_variants->image = $product_variants->id . "_image";
                
                }
                $product_variants->save();
                }
                $i++;
        }
    }

        if ($request->page == 'addons') {

            $request->validate([
                'addons' => 'required'
            ]);

            $addons = $request->addons;

            foreach ($addons as $key => $value) {
                
                if($data =ProductAddons::findOrFail($addons[$key]['id'])){
                $data->update([
                    'name' => $addons[$key]->name,
                    'sku' => $addons[$key]->sku,
                    'barcode' => $addons[$key]->barcode,
                    'unit_price' => $addons[$key]->addons,
                    'weight' => $addons[$key]->weight,
                    'status' => $addons[$key]->status,
                  
                ]);
                $Locals = new ProductLocalsController();
                $Locals->editAddons($addons[$key] , $data->id);
                }
                else{
                    $data = ProductAddons::create([
                        'sku' => $addons[$key]['sku'],
                        'barcode' => $addons[$key]['barcode'],
                        'unit_price' =>$addons[$key]['price'],
                        'weight' => $addons[$key]['weight'],
                        'status' => $addons[$key]['status'],
                        'product_id' => $request->product_id
                    ]);
                    $Locals = new ProductLocalsController();
                    $Locals->storeAddons($addons[$key] , $data->id);
                   
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
        $Locals =ProductLocals::select('id')->where('product_id' , $product->id)->get();
        $variants = ProductVariants::select('id')->where('product_id' , $product->id)->get();
        $addons = ProductAddons::select('id')->where('product_id' , $product->id)->get();
        
        ProductVariants::whereIn('id', $variants)->update([
            'status'=>$request->status
        ]);
        ProductAddons::whereIn('id', $addons)->update([
            'status'=>$request->status
        ]);
        ProductLocals::whereIn('id',$Locals)->update([
         'status'=>$request->status
        ]);

        $product->save();

    }


    public function showProducts($lang,$rest){
        $rest_id =Rest::where('name' , $rest)->first()->id;
        $product = Product::with(["locals"=>function($query) use($lang){
            $query->where('lang' , $lang)->select('name','lang' , 'product_id');
        }])->with(["store"=>function($query) use($lang){
            $query->where('lang' , $lang)->select('name','lang' ,'store_id');
        }])->select('id','image','store_id','rest_id','status')->where('rest_id' ,$rest_id)->get();
        
        return $product;
    }

    public function showId($id){
        $product = Product::with('locals')->with(['variants'=>function($query){
            $query->with('combination.localesOption')->with('combination.localesValue');
        }])
        ->with('addons.locales')->with('store')->with(['option'=>function($query){
            $query->with('locales')->with("values.values");
        }])->find($id);
        
        return $product;
    }

    public function test(Request $request){
        $test = json_decode($request->test);

        return $test[0]->test ;

    }



}
