<?php

namespace App\Http\Controllers;

use App\Models\BranchSchedule;
use Illuminate\Http\Request;

class BranchScheduleController extends Controller
{   
    public function store($request , $branch){

    $request->validate([
        'schedule'=>'required'
    ]);
    $schedule = explode(',',$request->schedule);
    $days = ['monday' ,'tuesday' , 'wednesday', 'thursday' , 'friday' , 'saturday', 'sunday'];
    $c = 0;
    for ($i=0; $i < count($schedule); $i+=3) { 
        
        $data =BranchSchedule::create([
            'name'=>$days[$c],
            'branch_id'=>$branch->id,
            'start'=>$schedule[$i],
            'end'=>$schedule[$i+1],
            'isClosed'=>$schedule[$i+2],
            'status'=>1

        ]);
        $c+=1;
    }

    }
}
