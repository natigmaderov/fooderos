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
        'branch_id',
        'preparation_time',
        'working_hours'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    public function variants(){

        return $this->hasMany(BranchProductVariants::class , 'branch_product_id');
    }

    public function locales(){
        return $this->hasMany(ProductLocals::class , 'product_id' , 'product_id');
    }

    public function addons(){

        return $this->hasMany(BranchProductAddons::class , 'branch_product_id');
    }

    public function catalogs(){
        return $this->hasMany(BranchProductCatalogs::class , 'branch_product_id')->where('catagory_id' , 0)->with('catalocales');
    }
}
