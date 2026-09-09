<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tree extends Model
{
    //fillable attributes
    protected $fillable = [
        'name',
        'user_id'
    ];
}
