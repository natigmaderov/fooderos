<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
      
        'name',
        'store_id',
        'address',
        'country',
        'city',
        'lat',
        'long',
        'phone',
        'profile',
        'cover',
        'currency',
        'payment',
        'cash_limit',
        'amount',
        'payload',
        'status',
        'name',
        'max_distance'
    
    ];
    
    public function schedule(){

        return $this->hasMany(BranchSchedule::class);
    }

    public function locals(){
        return $this->hasMany(BranchLocals::class);
    }

}
