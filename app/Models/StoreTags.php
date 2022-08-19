<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreTags extends Model
{
    use HasFactory;
    protected $fillable = [
        'store_id',
        'tag_id',
        'status',
    ];


    public function store(){

       return $this->belongsTo(Store::class);
    }

    public function tag(){
        return $this->hasMany(TagLocales::class, 'tag_id', 'tag_id');
    }
}
