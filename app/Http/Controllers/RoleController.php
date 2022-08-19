<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function show(){

        return Roles::all();

    }


    public function create(Request $request){

        $request->validate([
            'name'=>'required',
            'description'=>'required'
        ]);

        $role = Roles::create([
            'name'=>$request->name,
            'description'=>$request->description,
            'stauts'=>1
        ]);

        return response([
            'message' => 'New Role Added'
        ],201);

    }


    public function edit(Request $request)
    {
      $request->validate([
        'id'=>'required'
      ]);
       $role = Roles::update([
        'name'=>$request->name,
        'description'=>$request->description,
        'stauts'=>$request->status
       ]);
       return response([
        'message'=>'Role updated successfully'

       ],201);

    }

    public function destroy(Request $request){

        $request->validate([
            'id'=>'required'
        ]);

        Roles::find($request->id)->delete();

        return response([
            'message'=>'Roles Deleted!'
        ],201);

    }


    public function status(Request $request){
        $request->validate([
            'id'=>'required',
            'status'=>'required'
        ]); 

        $role = Roles::find($request->id);
        $role->status = $request->status;
        $role->save();

        return response([
            'Message'=>'Status changed'
        ],201);
    }

}
