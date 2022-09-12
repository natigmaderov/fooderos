<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariantsCombination extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable =[
        'variant_option_values_id',
        'status',
        'product_variant_id'
    ];
}
