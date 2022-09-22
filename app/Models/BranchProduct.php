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
        'product_id',
        'isPublish',
        'status',
        'sku',
        'barcode',
        'price',
        'weight',
        'vendor',
        'preparation_time',
        'working_hours'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
