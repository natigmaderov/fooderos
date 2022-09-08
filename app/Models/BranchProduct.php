<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchProduct extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'branch_id',
        'catagory_id',
        'path',
        'product_id',
        'unit_price',
        'weight',
        'order_id',
        'isPublic'
    ];
}
    