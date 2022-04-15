<?php

namespace App\Models\Admin;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleBrand extends BaseModel
{
    use HasFactory;
    
    protected $dates = [
        'created_at',
        'updated_at',
    ];
     protected $fillable = [
         'name', 'icon', 'status','created_at','updated_at',
     ];
}
