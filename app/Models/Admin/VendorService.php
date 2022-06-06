<?php

namespace App\Models\Admin;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorService extends BaseModel
{
    use HasFactory;

    protected $dates = [
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'name', 'icon', 'status', 'created_at', 'updated_at',
    ];


    function service()
    {
        return $this->hasOne('App\Models\Admin\Service', '_id', 'service_id')->select('*');
    }

    function cCategory()
    {

        return $this->hasOne('App\Models\Admin\Category', '_id', 'category_id')->select('name');
    }

    function vehicleBrand()
    {

        return $this->hasOne('App\Models\Admin\VehicleBrand', '_id', 'vehicle_brand')->select('name');
    }

    function vehicleModel()
    {

        return $this->hasOne('App\Models\Admin\VehicleModel', '_id', 'vehicle_model')->select('name');
    }
}
