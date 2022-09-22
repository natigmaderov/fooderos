<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchProductAddons extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'sku',
        'barcode',
        'unit_price',
        'weight',
        'status',
        'branch_product_id',
        'addon_id'
    ];
    public function locales(){

        return $this->hasMany(AddonsLocals::class , 'addon_id' , 'addon_id');
    }
}
