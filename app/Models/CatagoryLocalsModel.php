<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CatagoryLocalsModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'status',
        'catagory_id',
        'lang'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    public function catagory(){

        return $this->belongsTo(CatagoryModel::class);
    }
}
