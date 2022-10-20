<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use App\Models\BasketProduct;
use Illuminate\Http\Request;

class BasketController extends Controller
{
    public function store(Request $request , BasketProduct $basketProduct ,Basket $basket){
        $request->validate([
            'basket' => 'required'
        ]);
        $items = $request->basket;
        $basket->create([
            'user_id'=>\auth()->user()->id,
            'amount'=>0
        ]);
        $totalAmount = 0 ;
        foreach($items as $item){
            $basketProduct->create([
                'basket_id'=>$basket->id,
                'amount'=>$item['amount'],
                'quantity'=>$item['quantity'],
                'product_id'=>$item['quantity']
            ]);
            $totalAmount += $item['quantity']*$item['amount'];
        }
        $basket->amount = $totalAmount;
        $basket->save();

        return response([
            'message'=>'Product added to the basket ! ',
            'basket'=>$basket->with('items')
        ],201);

    }
}
