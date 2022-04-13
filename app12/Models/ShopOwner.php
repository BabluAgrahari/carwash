<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopOwner extends BaseModel
{
    use HasFactory;
    
    protected $dates = [
        'created_at',
        'updated_at',
    ];
     protected $fillable = [
         'name', 'email', 'phone_no','address','status','created_at','updated_at',
     ];
}
