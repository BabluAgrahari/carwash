<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\VehicleBrand;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\validation\Admin\CreateVehicleBrand;

class VehicleBrandController extends Controller
{
    public function index()
    {
        try {
            $list = VehicleBrand::get();
            return response(['status' => true, 'data' => $list]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function store(CreateVehicleBrand $request)
    {
        try {
            $vehicleBrand = new VehicleBrand();
            $vehicleBrand->user_id = Auth::user()->_id;
            $vehicleBrand->name = $request->name;
            $vehicleBrand->status = $request->status;

            if (!empty($request->file('icon')))
                $vehicleBrand->icon  = singleFile($request->file('icon'), 'icon/');

            if ($vehicleBrand->save())
                return response(['status' => 'success', 'message' => 'Vehicle Brand created Successfully!']);

            return response(['status' => 'error', 'message' => 'Vehicle Brand not created Successfully!']);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        } 
    }

    public function show($id)
    {
        try {
            $list = VehicleBrand::find($id);
            return response(['status' => true, 'data' => $list]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function update(CreateVehicleBrand  $request, $id)
    {
        try {
            $vehicleBrand = VehicleBrand::find($id);
            $vehicleBrand->name   = $request->name;
            $vehicleBrand->status = $request->status;

            if (!empty($request->file('icon')))
                $vehicleBrand->icon  = singleFile($request->file('icon'), 'icon/');

            if ($vehicleBrand->save())
                return response(['status' => 'success', 'message' => 'Vehicle Brand updated Successfully!']);

            return response(['status' => 'error', 'message' => 'Vehicle Brand not updated!']);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        } 
    }

    public function destroy(VehicleBrand $vehicleBrand)
    {
        if($vehicleBrand->delete())
        return response(['status' => 'success', 'message' => 'Vehicle Brand deleted Successfully!']);

         return response(['status' => 'error', 'message' => 'Vehicle Brand not deleted!']);
      
    }
}
