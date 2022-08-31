<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\CurrencyMdoel;
use App\Models\PaymentOptionsModel;
use Illuminate\Http\Request;

class UtulitiesController extends Controller
{
    
    public function paymentOptions(){
        $options  = PaymentOptionsModel::select('name')->get();
        $currency = CurrencyMdoel::select('name')->get();

        return response([
            'Payment options'=>$options,
            'Currencies'=>$currency

        ],201);
    }

    public function addPayment(Request $request){
        $request->validate([
            'name'=>'required'
        ]);

        $payment = PaymentOptionsModel::create([
            'name' =>$request->name
        ]);
        return response([
            'Message'=>'Payment Addded !'
        ]);

    }

    public function addCurrency(Request $request){
        $request->validate([
            'name'=>'required'
        ]);

        $currency = CurrencyMdoel::create([
            'name' =>$request->name
        ]);
        return response([
            'Message'=>'Currency Addded !'
        ]);
    }


    public function destroyCurrency(Request $request){

        $request->validate([
            'name'=>'requried'
        ]);

        CurrencyMdoel::where('name' ,$request->name)->delete();

        $Branch =  Branch::all();

        foreach($Branch as $key => $value){

            $currency = $Branch[$key]->currency;
            $replaced = str_replace($request->name , '' , $currency);
            $currency->currency = $replaced;
            $currency->save();
        
        }
        return response([
            'message' => 'Currency Deleted'
        ],201);

    }
    public function destroyOptions(Request $request){

        $request->validate([
            'name'=>'requried'
        ]);

        PaymentOptionsModel::where('name' ,$request->name)->delete();

        $Branch =  Branch::all();

        foreach($Branch as $key => $value){

            $payment = $Branch[$key]->payment;
            $replaced = str_replace($request->name , '' , $payment);
            $payment->payment = $replaced;
            $payment->save();
        
        }
        return response([
            'message' => 'Payment Option Deleted'
        ],201);

    }
}
