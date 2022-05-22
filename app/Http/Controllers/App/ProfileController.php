<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\App\AppController;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class ProfileController extends AppController
{

    public function userData()
    {
        try {
            $user = User::find($this->AppAuth('_id'));
            $record = [
                'name'    => $user->name,
                'email'   => $user->email,
                'phone'   => $user->phone,
                'city'    => $user->city,
                'state'   => $user->state,
                'pincode' => $user->pincode,
                'country' => $user->counter,
                'address' => $user->address
            ];

            return response(['status' => 'success', 'data' => $record]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function update(Request $request)
    {
        try {

            $user = User::find($this->AppAuth('_id'));
            $user->name    = $request->name;
            $user->email   = $request->email;
            $user->phone   = $request->phone;
            $user->city    = $request->city;
            $user->state   = $request->state;
            $user->pincode = $request->pincode;
            $user->country = $request->country;
            $user->address = $request->address;

            if (!$user->save())
                return response(['status' => 'error', 'message' => 'Profile not Updated.']);

            return response(['status' => 'success', 'message' => 'Profile Updated Successfully.']);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function updateLocation(Request $request)
    {
        try {
            $user = User::find($this->AppAuth('_id'));
            $user->latitude = $request->latitude;
            $user->longitude = $request->longitude;
            if (!$user->save())
                return response(['status' => 'error', 'message' => 'Location not Updated']);

            return response(['status' => 'success', 'message' => 'Location updated']);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
