<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PDO;

class TagTypes extends Model
{
    use HasFactory;
    protected $fillabele = [
        'name',
        'status',
    ];

    public function tag(){


        return $this->hasMany(Tag::class , 'type_id' , 'id');
    }
}
