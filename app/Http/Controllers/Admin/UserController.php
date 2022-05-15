<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Validation\Admin\TimeSlap\DisableServices;
use App\Http\Requests\Validation\Admin\TimeSlap\UpdateTimeSlap;
use App\Models\Admin\TimeSlap;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        try {
            $lists = User::where('role','customer')->get();

            if ($lists->isEmpty())
                return response(['status' => 'error', 'message' => "no found any record."]);

            $records = [];
            foreach ($lists as $list) {
                $records[] = [
                    '_id'        => $list->_id,
                    'name'       => ucwords($list->name),
                    'email'      => $list->email,
                    'phone'      => $list->phone,
                    'city'       => $list->city,
                    'state'      => $list->state,
                    'pincode'    => $list->pincode,
                    'country'    => $list->country,
                    'latitude'   => $list->latitude,
                    'longitude'  => $list->longitude,
                    'status'     => $list->isActive($list->status),
                    'created'    => $list->dFormat($list->created),
                    'updated'    => $list->dFormat($list->updated)
                ];
            }

            return response(['status' => 'success', 'data' => $records]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

}
