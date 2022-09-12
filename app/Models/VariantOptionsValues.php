<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VariantOptionsValues extends Model
{
    use HasFactory;
    use SoftDeletes;



    protected $fillable = [
        'variant_option_id',
        'status',
    ];
}
