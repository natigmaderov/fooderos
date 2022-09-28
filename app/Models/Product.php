<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'store_id',
        'image',
        'sku',
        'rest_id',
        'barcode',
        'price',
        'position_id',
        'manager_id',
        'isVariant',
        'isAddons',
        'addons_limit',
        'status',

    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    public function locals(){

        return $this->hasMany(ProductLocals::class);
    }

    public function variants(){
        return $this->hasMany(ProductVariants::class);
    }

    public function addons(){

        return $this->hasMany(ProductAddons::class);
    }

    public function store(){

        return $this->hasMany(StoreLocals::class ,'store_id' , 'store_id');
    }

    public function option(){

        return $this->hasMany(VariantOptions::class , 'product_id');
    }
}
