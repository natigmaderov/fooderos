<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $fillable =[
        'Name',
        'Status',
        'Type_id',
        'store_count',
        'image'
    ];

    public function tagtypes(){
        return $this->belongsTo(TagTypes::class ,'type_id' , 'id');
    }
}
