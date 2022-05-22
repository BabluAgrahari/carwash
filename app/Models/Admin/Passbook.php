<?php

namespace App\Models\Admin;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Passbook extends BaseModel
{
    use HasFactory;

    function vendorName()
    {
        return $this->hasOne('App\Models\Admin\ShopOwner', '_id', 'vendor_id')->select('business_name');
    }
}
