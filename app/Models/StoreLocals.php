<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreLocals extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'lang',
        'store_id'
    ];
}
