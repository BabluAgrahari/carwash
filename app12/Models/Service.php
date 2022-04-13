<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends BaseModel
{
    use HasFactory;

    protected $dates = [
       'created_at',
       'updated_at',
   ];
    protected $fillable = [
        'category', 'vehicle_brand','vehicle_model','service_type', 'service_tittle','sort_description','description','multiple_images','video','time_duration','service_charge','discount','gst_charges','status','created_at','updated_at',
    ];
}
