<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CountryController extends Controller
{
   public function show(){

    return  DB::table('countries')->select('iso3' ,'name' ,'phonecode','emoji')->get();
   }

   public function cities($id){
      $c_id = DB::table('countries')->where('name' , $id)->first()->id;
      
      return DB::table('cities')->select('name')->where('country_id' ,$c_id)->get();

   }
}
