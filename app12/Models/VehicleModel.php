<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleModel extends BaseModel
{
    use HasFactory;
    
    protected $dates = [
        'created_at',
        'updated_at',
    ];
     protected $fillable = [
         'name', 'brand_id','created_at','updated_at',
     ];
}
