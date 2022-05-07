<?php

namespace App\Models\Admin;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TimeSlap extends BaseModel
{
    use HasFactory;

    protected $dates = [
       'created',
       'updated',
   ];
}