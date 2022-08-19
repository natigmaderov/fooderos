<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TagLocales extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'tag_id',
        'name',
        'description',
        'lang'

    ];


    public function tag(){

        return $this->belongsTo(Tag::class , 'tag_id' , 'id');
    }

    public function store(){

        return $this->belongsTo(StoreTags::class , 'tag_id' , 'tag_id');
    }
}
