<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BasketProduct extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable =[
        'basket_id',
        'product_id',
        'quantity',
        'amount',
    ];

    public function products(){

        return $this->hasMany(BranchProductVariants::class , 'branch_product_id' , 'product_id');
    }
}
