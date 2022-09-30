<?php

namespace App\Models;

use App\Http\Controllers\BranchProductController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchProductCatalogs extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'branch_product_id',
        'cat_id',
        'catagory_id',
        'status'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function catalocales(){
        if(\request()->lang){

        return $this->hasMany(CatagoryLocalsModel::class , 'catagory_id' , 'cat_id')->where('lang' , \request()->lang);
        }
        return $this->hasMany(CatagoryLocalsModel::class , 'catagory_id' , 'cat_id');

    }
    public function children(){
        return $this->hasMany(BranchProductCatalogs::class , 'catagory_id' , 'cat_id');
    }
    public function sub(){
        if(\request()->lang){
        return $this->children()->with('sub')->with(['catalocales'=>function($query){
            $query->where('lang' , \request()->lang);
        }]);
            
        }

        return $this->children()->with('sub')->with('catalocales');
    }

}
