<?php

namespace App\Models\Admin;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOtp extends BaseModel
{
    use HasFactory;

    protected $dates = [
       'created_at',
       'updated_at',
   ];
    protected $fillable = [
        'user_id', 'phone_no', 'otp','otp_date_time','created_at','updated_at',
    ];
}
