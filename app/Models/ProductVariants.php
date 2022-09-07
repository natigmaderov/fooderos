<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariants extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable =[
        'image',
        'sku',
        'barcode',
        'unit_price',
        'weight',
        'status',
        'product_id'
    ];


    public function product(){

        return $this->belongsTo(Product::class);
    }


    public function locals(){
        return $this->hasMany(VariantLocals::class , 'variant_id'); 
    }
}
