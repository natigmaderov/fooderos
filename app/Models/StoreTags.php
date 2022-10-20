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
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    public function store(){

       return $this->belongsTo(Store::class);
    }

    public function tag(){
        return $this->hasMany(TagLocales::class, 'tag_id', 'tag_id');
    }
}
