<?php

namespace App\Http\Controllers;

use App\Models\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserDetailsController extends Controller
{
    
    public function create(Request $request , $user){

        $User_Data = UserDetails::create([
            'name' => $user->name,
            'user_id'=>$user->id,
            'phone'=>$user->phone,
            'email'=>$request->email??'',
        ]);

        return response([
            'message'=>"succesfly created userdetails"
        ],201);
    }
    
    
    
    public function details(){

        if($user = \auth()->user()){
        
        $data = UserDetails::where('user_id' , $user->id)->first();
        
        if(!$data){
            return response([
                'message'=>'invalid user'
            ],401);
        }
        return $data;
    }
    return response([
        'message'=> "invalid token"
    ],401);

    }


    public function update(Request $request){
       $user = \auth()->user();


        
       UserDetails::where('user_id',$user->id)->update([
            'name' =>$request->name,
            'gender'=>$request->gender,
            'birthday'=>$request->birthday,
       ]);
       $user->name = $request->name;
       $user->save();
       return response([
        'message' => 'user updated'
       ],201);

    }


    public function photo(Request $request){

        $user = \auth()->user();
        $user_data = UserDetails::where('user_id',$user->id)->first();
        if($request->hasFile('image')){
            $dest_path = 'public/profile/images';
            $image = $request->file('image');
            $image_name = $image->getClientOriginalName();
            $path = $request->file('image')->storeAs($dest_path,$user->id.$image_name);
            $path = 'storage/profile/images/'.$image_name;

            $user_data->photo = $user->id.$image_name;
            $user_data->save();

            return response([
                'message'=>'Profile Photo Updated'
            ],201);
        }

    }
}
