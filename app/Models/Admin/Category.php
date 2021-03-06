<?php

namespace App\Models\Admin;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends BaseModel
{
    use HasFactory;

    protected $dates = [
       'created_at',
       'updated_at',
   ];
    protected $fillable = [
        'name', 'icon', 'status','is_trending','created_at','updated_at',
    ];
}
