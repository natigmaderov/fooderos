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
        'product_id',
        'sku',
        'barcode',
        'price',
        'weight',
        'status',

    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    public function product(){

        return $this->belongsTo(Product::class);
    }


    public function locals(){
        return $this->hasMany(VariantLocals::class , 'variant_id'); 
    }

    public function combination(){

        return $this->hasMany(ProductVariantsCombination::class , 'product_variant_id');
    }
}
