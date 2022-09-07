<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VariantLocals extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable =[
        'name',
        'variant_id',
        'status',
        'lang'

    ];

    public function variats(){

        return $this->belongsTo(ProductVariants::class , 'variant_id' , 'variant_id');
    }
}
