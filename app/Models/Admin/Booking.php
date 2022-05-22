<?php

namespace App\Models\Admin;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Booking extends BaseModel
{
    use HasFactory;

    function customerName()
    {
        return $this->hasOne('App\Models\User', '_id', 'customer_id')->select('name');
    }

    function vendorName()
    {
        return $this->hasOne('App\Models\Admin\ShopOwner', '_id', 'vendor_id')->select('business_name');
    }
}
