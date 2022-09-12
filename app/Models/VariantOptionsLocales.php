<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VariantOptionsLocales extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable =[
        'name',
        'lang',
        'variant_option_id',
        'status'

    ];
}