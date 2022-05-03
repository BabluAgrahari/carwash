<?php

namespace App\Http\Traits;

use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Session as FacadesSession;

trait AppAuthTraits
{

    function AppAuth($key)
    {
        $user = session('AppUser');
        if (empty($user))
            return response()->json(['status' => FALSE, 'message' => 'User session Expired!']);
        if (!empty($user) && is_array($user)) {
            if (!empty($user[$key]))
                return $user[$key];

            return response()->json(['status' => FALSE, 'message' => 'User Data not Avaliable in session!']);
        }
    }
}
