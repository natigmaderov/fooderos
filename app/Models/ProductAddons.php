<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAddons extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'sku',
        'barcode',
        'unit_price',
        'weight',
        'status',
        'product_id'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    public function product(){

        return $this->belongsTo(Product::class);
    }
    public function locales(){

        return $this->hasMany(AddonsLocals::class , 'addon_id');
    }
}
