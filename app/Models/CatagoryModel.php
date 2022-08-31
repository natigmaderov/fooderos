<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CatagoryModel extends Model
{
    use HasFactory;
    Use SoftDeletes;
    protected $fillable = [
        'catagory_id',
        'branch_count',
        'image',
        'status',
        'rest_id',
        'store_id'
    ];

    public function locals(){

        return $this->hasMany(CatagoryLocalsModel::class);
    }
}
