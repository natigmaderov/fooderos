<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\Request;
use App\Models\Visitor;
use Illuminate\Support\Str;


class VisitorController extends Controller
{
    public function visitor(Request $request){

        $token = Str::random(32);
        $visitor = Visitor::create([
            'token'=> $token
        ]);
        return response([
            'token'=>$token
        ],201); 
    
        }

//For testing Cavansir
        public function cava(Request $request){
            $lang = Language::find($request->id)->first();

            return $lang;
        }
}
