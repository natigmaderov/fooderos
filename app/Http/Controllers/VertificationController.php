<?php

namespace App\Http\Controllers;
use App\Models\PhoneVerfy;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Http;


class VertificationController extends Controller
{

    //First checking of number
    //And Creating Log

    public function check(Request $request){

        $recaptcha = false;
        $request->validate([
            'phone'=>'required',
            'reKey'=> 'required'
        ]);




        $secretkey = '6Lcdaz4hAAAAALKRTJ63NBcHGFYt5nifQPmf3UJy';
        $userIP = \Request::ip();
        $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretkey&response=$request->reKey&remoteip=$userIP";
        $response = \file_get_contents($url);
        $response = json_decode($response);


        if($response->success){
            $recaptcha = true;
        }


        $status = 0;
        if($user = User::where('phone', $request->input('phone'))->first()){
            $status = 1;
        }
        if ($check = PhoneVerfy::where('phone', $request->input('phone'))->first()) {
            //24 hours block checking
            $time =strtotime( $check->updated_at);
            $current_date = strtotime(Carbon::now());
            $val =($current_date -$time);

            if ($check->status == 0){
               if($val < 24){
                   return response([
                       'message' => 'Acount Blocked',
                       'name'=>$user->name??'',
                       'time'=>round(24-$val/60),
                       'status'=>$status,
                       'recaptcha' => $recaptcha
                   ],401);
               }
               $check->status = 1;
               $check->r_count = 0;
               $check->verfied = 1;
               $check->save();
                return response([
                    'message'=>'Success',
                    'name'=>$user->name??'',
                    'status'=>$status,
                    'recaptcha' => $recaptcha
                ],201);

            }
            else {
                $check->verfied = 1;
                $check->save();
                return response([
                    'message'=>'Success',
                    'name'=>$user->name??'',
                    'status'=>$status,
                    'recaptcha' => $recaptcha
                ],201);
            }
        }


        else {
                $phone_verfy = PhoneVerfy::create([
                    'otp'=>'0000',
                    'phone'=>$request->input('phone'),
                    'verfied'=>1,
                    'status' =>1,
                    'r_count'=>0
                ]);
                return response([
                   'message'=>'New Log Created',
                    'status'=>$status,
                    'name'=>$user->name,
                    'recaptcha' => $recaptcha
                ],201);
            }



    }



    public function vertification(Request $request)
    {
        $otp = '0828';
        $request->validate([
            'phone' => 'required',
            'otp'=>'required'
        ]);
        $user = PhoneVerfy::where('phone', $request->input('phone'))->first();

        if($user->verfied = 0 ){
            return response([
               'message' => 'failed to verfy'
            ],401);
        }


        if($otp == $request->input('otp')) {
            //delete log
            $user->delete();



            if($user = User::where('phone', $request->input('phone'))->first()){
                $token = JWTAuth::fromUser($user);
                return response([
                    'Message'=>"Success",
                    'token'=>$token,
                    'token_type'=>'bearer',
                    'expires_in'=>auth()->factory()->getTTL()*60
                ],201);
            }
            else {
//                $user =  User::create([
//                    'name'=>$request->input('name'),
//                    'phone'=>$request->input('phone'),
//                    'role_id'=>1,
//                    'status'=>0
//                ]);
//
//                if ($token = JWTAuth::fromUser($user)){
//                    return response([
//                        'message'=>'success',
//                        'token'=>$token,
//                        'token_type'=>'bearer',
//                        'expires_in'=>auth()->factory()->getTTL()*60
//                    ]);

//            }
                $foo = new UserController();
                return $foo->register($request);

//
            }


        }

        if ($check = PhoneVerfy::where('phone', $request->input('phone'))->first()) {


            if ($check->r_count <3){
                $check->r_count +=1;
                //heleki otp staticdir deye bu sekilde update edirem yeni otpni requestden almiram.
                $check->otp = $request->input('otp');
                $check->save();
                return response([
                  'Message'=>'Invalid Otp'
                ],400);

            }
            else {
                $check->status = 0;
//                $check->b_time = Carbon::now();
                $check->save();
                return response ([
                   'Message'=>'Blocked'
                ],401);
            }

        }

    }
}
