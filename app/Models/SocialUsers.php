<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialUsers extends Model
{
    use HasFactory;

    protected $fillable = [
      'name',
      'email',
      'user_id',
      'phone',
      'status',
      'social_providers',

    ];
}
