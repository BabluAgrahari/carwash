<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Validation\Admin\CreateDriver;
use App\Http\Requests\Validation\Admin\UpdateDriver;
use App\Models\Admin\Driver;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    public function index()
    {
        try {
            $lists = Driver::desc()->get();

            if ($lists->isEmpty())
                return response(['status' => 'error', 'message' => "no found any record."]);


            $records = [];
            foreach ($lists as $list) {
                $records[] = [
                    '_id'     => $list->_id,
                    'vendor_id' => $list->vendor_id,
                    'name'    => $list->name,
                    'email'   => $list->email,
                    'phone'   => $list->phone,
                    'city'    => $list->city,
                    'state'   => $list->state,
                    'pincode' => $list->pincode,
                    'address' => $list->address,
                    'status'  => $list->isActive($list->status),
                    'image'   => (!empty($list->iamge)) ? asset('profile/' . $list->iamge) : '',
                    'created' => $list->dFormat($list->created),
                    'updated' => $list->dFormat($list->updated)
                ];
            }

            return response(['status' => 'success', 'data' => $records]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function store(CreateDriver $request)
    {
        try {
            $driver = new Driver();
            $driver->vendor_id = Auth::user()->_id;
            $driver->name    = $request->name;
            $driver->email   = $request->email;
            $driver->phone   = $request->phone;
            $driver->city    = $request->city;
            $driver->state   = $request->state;
            $driver->pincode = $request->pincode;
            $driver->address = $request->address;
            $driver->status  = $request->status;

            if (!empty($request->file('image')))
                $driver->image  = singleFile($request->file('image'), 'profile/');

            if (!$driver->save())
                return response(['status' => 'error', 'message' => 'Driver not created Successfully!']);

            $this->createUser($request, $driver->_id);
            return response(['status' => 'success', 'message' => 'Driver created Successfully!']);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    private function createUser($request, $id)
    {
        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = 'vendor';
            $user->driver_id = $id;
            $user->image = $request->image;
            $user->save();
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $list = Driver::find($id);
            $record = [
                '_id'     => $list->id,
                'name'    => $list->name,
                'email'   => $list->email,
                'phone'   => $list->phone,
                'city'    => $list->city,
                'state'   => $list->state,
                'pincode' => $list->pincode,
                'address' => $list->address,
                'status'  => $list->status,
                'image'   => (!empty($list->iamge)) ? asset('profile/' . $list->iamge) : '',
                'created' => $list->dFormat($list->created),
                'updated' => $list->dFormat($list->updated)
            ];
            return response(['status' => true, 'data' => $record]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function update(UpdateDriver $request, $id)
    {
        try {
            $driver = Driver::find($id);

            $driver->name    = $request->name;
            $driver->email   = $request->email;
            $driver->phone   = $request->phone;
            $driver->city    = $request->city;
            $driver->state   = $request->state;
            $driver->pincode = $request->pincode;
            $driver->address = $request->address;
            $driver->status  = $request->status;

            if (!empty($request->file('image')))
                $driver->image  = singleFile($request->file('image'), 'profile/');

            if ($driver->save())
                return response(['status' => 'success', 'message' => 'Driver updated Successfully!']);

            return response(['status' => 'error', 'message' => 'Driver not updated!']);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function destroy(Driver $driver)
    {
        if ($driver->delete())
            return response(['status' => 'success', 'message' => 'Driver deleted Successfully!']);

        return response(['status' => 'error', 'message' => 'Driver not deleted!']);
    }
}
