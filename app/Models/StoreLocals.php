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
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function store(){

        return $this->belongsTo(Store::class);
    }
}
