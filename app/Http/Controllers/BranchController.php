<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchLocals;
use App\Models\BranchSchedule;
use App\Models\PaymentOptionsModel;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;


class BranchController extends Controller
{

    public function stores(){


        return Store::select('id','name')->get();
    }

    public function show($name){
        
        $branch = Branch::where('store', $name)->select('id','name','address','phone','status')->get();
        
        return $branch;
    }
    
    
    
    public function store (Request $request){
       $payment = "";
       $currency = "";
        $request->validate([
            'name'=>'required',
            'store_id'=>'required',
            'address'=>'required',
            'country'=>'required',
            'city'=>'required',
            'lat'=>'required',
            'long'=>'required',
            'phone'=>'required',
            'profile'=>'required',
            'cover'=>'required',
            'currency'=>'required',
            'payment'=>'required',
            'cash_limit'=>'required',
            'amount'=>'required',
            'payload'=>'required',  
        ]);
        $payArray = explode(',', $request->payment);
        $currencyArray = explode(',',$request->currency);
        foreach($payArray as $key=>$value){
            $payment .=  $payArray[$key].",";

        }
        foreach($$currencyArray as $key=>$value){
            $currency .=  $currencyArray[$key].",";

        }
        trim($payment, ',');
        trim($currency, ',');
        $branch = Branch::create([
            'name'=>$request->name,
            'store_id'=>$request->store_id,
            'address'=>$request->address,
            'country'=>$request->country,
            'city' =>$request->city,
            'lat'=>$request->lat,
            'long'=>$request->long,
            'phone'=>$request->phone,
            'currency'=>$currency,
            'payment'=>$payment,
            'cash_limit'=>$request->cash_limit,
            'amount'=>$request->amount,
            'payload'=>$request->payload,
            'profile'=>'null',
            'cover'=>'null',
            'status'=>1

        ]);

        if($request->hasFile('profile')){
            $dest_path = 'public/branch/profiles';
            $image = $request->file('profile');
            $image_name = $image->getClientOriginalName();
            $path = $request->file('profile')->storeAs($dest_path,$branch->id.$branch->name);
            $branch->profile = $branch->id.$branch->name;
        }
        if($request->hasFile('cover')){
            $dest_path = 'public/branch/covers';
            $image = $request->file('cover');
            $image_name = $image->getClientOriginalName();
            $path = $request->file('cover')->storeAs($dest_path,$branch->id.$branch->name);
            $branch->cover = $branch->id.$branch->name;
        }
        $branch->save();

        $schedule = new BranchSchedule();
        $schedule->store($request , $branch);

        $Locals = new BranchLocalsController();
        return $Locals->store($request , $branch);

    }


    public function showID($id){

        if($branch = Branch::find($id)){
            return $branch;
        }

        else {
            return response([
                'Message'=>'invalid id'
            ],400);
        }

    }


    public function edit(Request $request){

        $request->validate([
            'id'=>'required'
        ]);

        $branch = Branch::findOrFail($request->id);

        $branch->update([
            'name'=>$request->name,
            'store_id'=>$request->store_id,
            'address'=>$request->address,
            'country'=>$request->country,
            'city' =>$request->city,
            'lat'=>$request->lat,
            'long'=>$request->long,
            'phone'=>$request->phone,
            'currency'=>$request->currency,
            'payment'=>$request->payment,
            'cash_limit'=>$request->cash_limit,
            'amount'=>$request->amount,
            'payload'=>$request->payload,
        ]);

        $profile = $request->hasFile('profile');
        $cover = $request->hasFile('cover');
        if($profile){
            $dest_path1 = 'storage/branch/profiles/'.$branch->profile;
            if(File::exists($dest_path1)) {
                File::delete($dest_path1);
            }
            $dest_path = 'public/branch/profiles';
            $request->file('profile')->storeAs($dest_path,$branch->id.$branch->name);
            $branch->profile = $branch->id.$branch->name;
        }

        if($cover){
            $dest_path1 = 'storage/branch/covers/'.$branch->cover;
            if(File::exists($dest_path1)) {
                File::delete($dest_path1);
            }
            $dest_path = 'public/branch/covers';
            $request->file('cover')->storeAs($dest_path,$branch->id.$branch->name);
            $branch->cover = $branch->id.$branch->name;
        }

        $branch->save();


        $Locals = new BranchLocalsController();
        return $Locals->edit($request , $branch);


    }


    public function status(Request $request){

        $request->validate([
            'id'=>'required',
            'status'=>'required',
        ]);

       

        $branch = Branch::where('id', $request->id)->first();
        $store = Store::where('id' , $branch->store_id)->first();
        if($store->status == 0){
            return response([
                "Message" => "You need to activate store: ".$store->name,

            ],400);
        }
        
        $branch->status = $request->status;
        $branch->save();

        return response([
            'Message'=>'status changed'
        ],201);

    }

    
    public function delete(Request $request){

        $request->validate([
            'id'=>'required'
        ]);

        Branch::find($request->id)->delete();
        BranchLocals::where('branch_id',$request->id)->delete();

        return response([
            'Message'=>'Branch Deleteed ! '
        ],201);

        
    }


    public function destroy($id){

        $branchs  = Branch::where('store_id' ,$id)->get();

        foreach($branchs as $key => $value){

            BranchLocals::where('branch_id',$branchs[$key]->id)->delete();
        } 
        $branchs->delete();

        return response([
            'message' => 'Store & Branchs deleted !'
        ],201);
        

    }
}
