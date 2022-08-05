<?php

use App\Http\Controllers\VisitorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::post('phone',[\App\Http\Controllers\VertificationController::class , 'check']);
Route::post('phone/verfy', [\App\Http\Controllers\VertificationController::class , 'vertification']);

Route::group(['middleware'=>'api'] , function ($routes){

    Route::post('user/create',[\App\Http\Controllers\UserController::class,'register']);
    Route::post('user',[\App\Http\Controllers\UserController::class ,'get_user']);
    Route::post('refresh',[\App\Http\Controllers\UserController::class, 'refreshToken']);
    Route::post('logout',[\App\Http\Controllers\UserController::class, 'logout']);
    Route::post('active',[\App\Http\Controllers\UserController::class,'active']);
    Route::post('social/verify', [\App\Http\Controllers\SocialVerify::class , 'verify']);
    Route::post('visitor', [VisitorController::class , 'visitor']);
    

});
