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
        'path',
        'image',
        'product_id',
        'unit_price',
        'weight',
        'order_id',
        'isPublic',
        'working_hours',
        'status', 
    ];


    public function product(){

        return $this->hasOne(Product::class , 'product_id' , 'product_id');
    }

    public function catalogs(){

        return $this->hasMany(BranchProductCatalogs::class , 'branch_product_id');
    }
}
