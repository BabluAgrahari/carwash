<?php

namespace App\Models\Admin;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PayHistory extends BaseModel
{
    use HasFactory;

    function customerName()
    {
        return $this->hasOne('App\Models\User', '_id', 'customer_id')->select('name');
    }

    function serviceName()
    {
        return $this->hasOne('App\Models\Admin\Service', '_id', 'service_id')->select('title');
    }

    function vendorName()
    {
        return $this->hasOne('App\Models\Admin\ShopOwner', '_id', 'vendor_id')->select('business_name');
    }
}
