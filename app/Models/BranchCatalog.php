<?php

namespace App\Models;

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
        'parent_id'
    ];

    public function catalocales(){

        return $this->hasMany(CatagoryLocalsModel::class , 'catagory_id' , 'catagory_id');

    }

    public function children(){
        return $this->hasMany(BranchCatalog::class , 'parent_id');
    }

    public function sub(){

        return $this->children()->select('id','branch_id' , 'catagory_id', 'product_count','status' ,'parent_id' , 'isActive')->with(['sub'=>function($query){
            $query->select('id','branch_id', 'catagory_id' , 'product_count','status' ,'parent_id' , 'isActive');
        }])->with(['catalocales' =>function($query) {
            $query->select('name' , 'catagory_id' , 'lang')->where('lang' , \request()->header('lang'));
        }]);
    }

  

}
