<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Traits\AppAuthTraits;
use App\Models\Admin\Service;
use App\Models\Admin\UserOtp;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppController extends Controller
{
    use AppAuthTraits;
 
}
