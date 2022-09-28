<?php

namespace App\Models;

use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchCatalog extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable =[
        'branch_id',
        'status',
        'product_count',
        'catagory_id',
        'isActive', 
        'image',
        'parent_id'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function catalocales(){

        return $this->hasMany(CatagoryLocalsModel::class , 'catagory_id' , 'catagory_id');

    }

    public function children(){
        return $this->hasMany(BranchCatalog::class , 'parent_id' , 'catagory_id');
    }

    public function sub(){

        return $this->children()->where('branch_id' , \request()->branch_id)->with(['sub'=>function($query){
            $query->where('isActive' , '1');
        }])->with(['catalocales' =>function($query) {
            $query->select('name' , 'catagory_id' , 'lang')->where('lang', \request()->lang);
        }]);
    }

  

}
