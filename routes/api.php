<?php

use App\Http\Controllers\LangugeController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserDetailsController;
use App\Http\Controllers\VisitorController;
use App\Models\UserDetails;
use Illuminate\Http\Request;
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
        Route::get('/list' , [TagController::class , 'show']);
        Route::post('/type',[TagController::class , 'store']);
        Route::get('/type',[TagController::class , 'showTypes']);

    });

    Route::prefix('lang')->group(function($routes){
      
        Route::post('/',[LangugeController::class , 'store']);
        Route::delete('/',[LangugeController::class , 'delete']);
        Route::get('/' , [TagController::class ,'show']);
    });
    
});

Route::get('/lang' , [LangugeController::class , 'languageVariable']);
