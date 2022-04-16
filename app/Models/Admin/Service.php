<?php

namespace App\Models\Admin;

use App\Models\BaseModel;
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


     function vehicleBrand(){

     	return $this->hasOne('App\Models\Admin\VehicleBrand','_id','vehicle_brand')->select('name');
     }


     function vehicleModel(){

     	return $this->hasOne('App\Models\Admin\VehicleModel','_id','vehicle_model')->select('name');
     }


     function cCategory(){

     	return $this->hasOne('App\Models\Admin\Category','_id','category')->select('name');
     }

}
