<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Rest;
use App\Models\Store;
use Illuminate\Http\Request;

class CustomerShowController extends Controller
{
    public function getRestaurants($lang , $rest){
        $rest = Rest::where('name' , $rest)->first();
        $store_ids = Store::where('store_type' , $rest->id)->pluck('id');
        return Branch::whereIn('store_id' , $store_ids)->with(['stores.tags.tag'=>function($query) use ($lang){
            $query->where('lang' , $lang);
        }])->with(['locals'=>function ($query) use ($lang){
            $query->where('lang' , $lang);
        }])->get();

    }
}
