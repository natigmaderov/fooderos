<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchSchedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'branch_id',
        'name',
        'start',
        'end',
        'isClosed',
        'status'
    ];

    public function branch(){

        return $this->belongsTo(Branch::class);
    }
}
