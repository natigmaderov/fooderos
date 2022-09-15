<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariantsCombination extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable =[
        'variant_option_values_id',
        'variant_option_id',
        'status',
        'product_variant_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    public function scopeLocales(Builder $query){
            return $query->with(['localesValue'=>function($query){
                $query->select('variant_option_value_id' , 'name' , 'lang');
            }])->with(['localesOption'=>function($query){
                $query->select('variant_option_id' , 'name' , 'lang');
            }]);
    }


    public function localesValue(){
        return $this->hasMany(VariantOptionsValuesLocales::class ,'variant_option_value_id', 'variant_option_values_id');
    }
    public function localesOption(){
        return $this->hasMany(VariantOptionsLocales::class , 'variant_option_id' ,'variant_option_id');
    }

  
}
