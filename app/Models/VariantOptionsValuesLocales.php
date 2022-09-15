<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VariantOptionsValuesLocales extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable =[
        'name',
        'lang',
        'status',
        'variant_option_value_id'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
