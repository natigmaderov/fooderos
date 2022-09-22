<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class BranchProductVariants extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable =[
        'image',
        'branch_product_id',
        'sku',
        'variant_id',
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
    public function combination(){

        return $this->hasMany(ProductVariantsCombination::class , 'product_variant_id' , 'variant_id');
    }
}
