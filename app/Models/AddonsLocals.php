<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddonsLocals extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable =[
        'name',
        'addon_id',
        'status',
        'lang'

    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
}
