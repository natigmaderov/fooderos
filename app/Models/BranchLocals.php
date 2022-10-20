<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchLocals extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'lang',
        'status',
        'branch_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    public function branch(){

        return $this->belongsTo(Branch::class);
    }
}
