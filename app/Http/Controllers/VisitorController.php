<?php

namespace App\Http\Controllers;

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
}
