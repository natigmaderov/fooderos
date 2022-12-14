<?php

use App\Http\Controllers\BranchCatagoryController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BranchProductController;
use App\Http\Controllers\BranchScheduleController;
use App\Http\Controllers\CatagoryController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LangugeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\Customers\CustomerShowController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserDetailsController;
use App\Http\Controllers\UtulitiesController;
use App\Http\Controllers\VisitorController;
use App\Models\Branch;
use App\Models\BranchCatalog;
use App\Models\BranchProduct;
use App\Models\Product;
use App\Models\UserDetails;
use App\Models\Visitor;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteUri;
use Illuminate\Support\Facades\Route;



Route::post('phone',[\App\Http\Controllers\VertificationController::class , 'check']);
Route::post('phone/verfy', [\App\Http\Controllers\VertificationController::class , 'vertification']);
//for autentiocation
Route::group(['middleware'=>'api'] , function ($routes){

    Route::post('user/create',[\App\Http\Controllers\UserController::class,'register']);
    Route::post('user',[\App\Http\Controllers\UserController::class ,'get_user']);
    Route::post('refresh',[\App\Http\Controllers\UserController::class, 'refreshToken']);
    Route::post('logout',[\App\Http\Controllers\UserController::class, 'logout']);
    Route::post('active',[\App\Http\Controllers\UserController::class,'active']);
    Route::post('social/verify', [\App\Http\Controllers\SocialVerify::class , 'verify']);
    Route::post('visitor', [VisitorController::class , 'visitor']);
//for profile
    Route::prefix('profile')->group(function($routes){

        Route::get('/main' , [UserDetailsController::class , 'details']);
        Route::put('/update' , [UserDetailsController::class, 'update']);
        Route::post('/photo' , [UserDetailsController::class , 'photo']);
    });
//for tags
    Route::prefix('tag')->group(function($routes){
        //tags
        Route::get('/list/{lang}/{rest}' , [TagController::class , 'show']);
        Route::get('/list/{rest}' , [TagController::class , 'showAll']);
        Route::post('/create',[TagController::class , 'create']);
        Route::put('/status' ,[TagController::class , 'status']);
        Route::post('/edit',[TagController::class , 'edit']);
        Route::delete('/delete',[TagController::class , 'delete']);
        Route::get('/show/{id}',[TagController::class , 'showID']);
        //tag_types
        Route::post('/type',[TagController::class , 'store']);
        Route::get('/type',[TagController::class , 'showTypes']);
        Route::put('/typestatus' , [TagController::class , 'TypeStatus']);


    });

    Route::prefix('lang')->group(function($routes){

        Route::post('/',[LangugeController::class , 'store']);
        Route::delete('/',[LangugeController::class , 'delete']);
        Route::get('/' , [LangugeController::class ,'show']);
    });



    Route::prefix('store')->group(function($routes){
        Route::get('/list/{lang}/{type}',[StoreController::class , 'show']);
        Route::post('/', [StoreController::class , 'store']);
        Route::get('/show/{id}',[StoreController::class , 'showID']);
        Route::post('/edit' , [StoreController::class , 'edit']);
        Route::post('/status',[StoreController::class,'status']);
        Route::delete('/',[StoreController::class , 'delete']);
        Route::get('/manager',[StoreController::class , 'manager']);

    });


    Route::prefix('branch')->group(function($routes){
        Route::get('/stores' , [BranchController::class , 'stores']);
        Route::get('/list/{lang}/{id}',[BranchController::class , 'show']);
        Route::post('/',[BranchController::class , 'store']);
        Route::get('/show/{id}',[BranchController::class , 'showID']);
        Route::post('/edit',[BranchController::class , 'edit']);
        Route::post('/status',[BranchController::class , 'status']);
        Route::delete('/' , [BranchController::class , 'delete']);

    });


    Route::prefix('settings')->group(function($routes){
        Route::get('/paymentoptions' , [UtulitiesController::class , 'paymentOptions']);
        Route::post('/currency' , [UtulitiesController::class , 'addCurrency']);
        Route::post('/payment' ,[UtulitiesController::class , 'addPayment']);
        Route::delete('/currency' , [UtulitiesController::class , 'destroyCurrency' ]);
        Route::delete('/paymnet' , [UtulitiesController::class , 'destroyOptions' ]);


    });
    // Route::prefix('roles')->group(function($routes){
    //     Route::get('/',[RoleController::class , 'show']);
    //     Route::post('/',[RoleController::class , 'create']);
    //     Route::post('/edit',[RoleController::class , 'edit']);
    //     Route::delete('/',[RoleController::class , 'destroy']);

    // });


    Route::prefix('catagory')->group(function($routes){
        Route::get('/show/{lang}/{rest}' , [CatagoryController::class , 'show']);
        Route::get('/shows/{id}/{lang}' , [CatagoryController::class , 'showID']);
        Route::post('/' , [CatagoryController::class , 'store']);
        Route::post('/edit' , [CatagoryController::class , 'edit']);
        Route::delete('/' , [CatagoryController::class , 'delete']);
        Route::get('/list/{lang}/{rest}' ,[CatagoryController::class , 'list']);
        Route::post('/status' , [CatagoryController::class , 'status']);

    });

    Route::prefix('product')->group(function($routes){
        //creating products/variants/addons
        Route::post('/', [ProductController::class , 'storeProduct']);
        Route::post('/variants', [ProductController::class , 'storeVariants']);
        Route::post('/addons', [ProductController::class , 'storeAddons']);
        //editing products/variants/addons
        Route::post('/edit' , [ProductController::class , 'editProduct']);
        Route::post('/edit/variants', [ProductController::class , 'editVariants']);
        //showing ....
        Route::get('show/{lang}/{rest}' , [ProductController::class , 'showProducts']);
        //showing inviduals
        Route::get('show/{id}' , [ProductController::class , 'showId']);
        //status change
        Route::post('/status' , [ProductController::class , 'status']);
        //delete
        Route::delete('/' , [ProductController::class , 'delete']);




    });


    Route::prefix('branch/catalogs')->group(function($routes){
        Route::get('/show/{branch_id}/{lang}' , [BranchCatagoryController::class , 'show']);
        Route::post('/', [BranchCatagoryController::class , 'store']);
        Route::post('/status' , [BranchCatagoryController::class , 'status']);
        Route::post('/edit' , [BranchCatagoryController::class , 'edit']);
        Route::delete('/' , [BranchCatagoryController::class , 'delete']);

    });

    Route::prefix('branch/products')->group(function($routes){
        Route::get('/show/{branch_id}/{lang}' , [BranchProductController::class , 'show']);
        Route::get('/get/product/{id}' , [BranchProductController::class, 'getProducts']);
        Route::post('/' , [BranchProductController::class , 'createMain']);
        Route::post('/variats' , [BranchProductController::class , 'createVariants']);
        Route::post('/create' , [BranchProductController::class , 'createAddons']);
        Route::get('/show/{id}' , [BranchProductController::class , 'showID']);
    });

});
//public apis
Route::get('country',[CountryController::class ,'show']);
Route::get('city/{name}' , [CountryController::class , 'cities']);
Route::get('store/list',[StoreController::class , 'StoreListClient']);
Route::post('store/filter' ,[StoreController::class , 'StoreFilterClient']);


Route::get('client/tag/list/{lang}/{rest}' , [TagController::class ,'clientShow']);
Route::get('client/restaurant/list/{lang}/{rest}' , [CustomerShowController::class , 'getRestaurants']);
// Route::get('show/{lang}/{rest}' , [ProductController::class , 'showProducts']);
// Route::get('show/{id}' , [ProductController::class , 'showId']);
// Route::post('/test' , [ProductController::class  , 'test']);

Route::get('/show/{branch_id}/{lang}' , [BranchCatagoryController::class , 'show']);

Route::post('/options', [ProductController::class , 'storeOptions']);
Route::post('/variants', [ProductController::class , 'storeVariants']);
Route::post('/addons', [ProductController::class , 'storeAddons']);

Route::post('/edit/variants' , [ProductController::class , 'editVariants']);


// Route::prefix('test')->group(function($routes){
//     Route::get('/product' , [TestController::class , 'showProducts']);
//     Route::get('/variants/{id}' , [TestController::class , 'showVariants']);
//     Route::post('/st1' , [TestController::class , 'statusVariants']);
//     Route::post('/st2' , [TestController::class , 'statusOptions']);
//     Route::get('/dp/{id}/{lang}' , [BranchProductController::class , 'show']);
//     Route::get('/dp/{id}' , [BranchProductController::class , 'showID']);
//     Route::get('/dps/{id}' , [BranchProductController::class , 'getProducts']);
//     Route::post('/dp/{product}' , [BranchProductController::class , 'createMain']);


// });


