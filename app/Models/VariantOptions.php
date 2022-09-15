<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VariantOptions extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'product_id',
        'status',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function locales(){
        return $this->hasMany(VariantOptionsLocales::class , 'variant_option_id');
    }

    public function values(){

        return $this->hasMany(VariantOptionsValues::class , 'variant_option_id');
    }
}
