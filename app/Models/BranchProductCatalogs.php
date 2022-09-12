<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchProductCatalogs extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'branch_product_id',
        'path',
        'catagory_id',
        'status'
    ];
}
