<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $filllable = [
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
        'currecny',
        'payment',
        'cash_limit',
        'amount',
        'payload',
        'status'
    ];

    public function schedule(){

        return $this->hasMany(BranchSchedule::class);
    }

}
