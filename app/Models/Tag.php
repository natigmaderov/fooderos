<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $fillable =[
        'name',
        'status',
        'type_id',
        'store_count',
        'image'
    ];

    public function tagtypes(){
        return $this->belongsTo(TagTypes::class ,'type_id' , 'id');
    }


    public function tag_locals(){
        return $this->hasMany(TagLocales::class  );
}

}
