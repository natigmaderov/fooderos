<?php

namespace App\Http\Controllers;
use App\Models\SocialUsers;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
class SocialVerify extends Controller
{
    public function verify(Request  $request){

        $request ->validate([
           'name'=>'required',
            'email'=>'required',
            'social_providers'=>'required'
        ]);

        if($SocialUser = SocialUsers::where('email',$request->email)->first()){

            if($user = User::where('phone' , $SocialUser->phone)->first()){


		  $token = JWTAuth::fromUser($user);



                return response([
                    'Message'=>"verified user!!!",
                    'status'=>'1',
		    'toke'=>$token
                ],201);
            }

            return response([
               'Message'=>"Unverified User Phone Required",
               'status' => '0'
            ],201);

        }
        $SocialUser = SocialUsers::create([
            'name' => $request->name,
            'email'=>$request->email,
            'social_providers'=>$request->social_providers,
            'status'=>1
        ]);

        return response([
           'message'=>'new social user added',
           'status'=>0
        ],201);

    }


    public  function createUser(Request  $request){

    }
}
