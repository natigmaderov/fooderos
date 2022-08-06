<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function show(){
        
        return Tag::where('status' , 1)->get();
    }
    
}
