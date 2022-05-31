<?php

namespace App\Models\Admin;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends BaseModel
{
    use HasFactory;


    function customerName()
    {
        return $this->hasOne('App\Models\User', '_id', 'user_id')->select('name');
    }
}
