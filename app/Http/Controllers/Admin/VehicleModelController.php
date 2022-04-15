<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\VehicleModel;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\validation\Admin\CreateVehicleModel;

class VehicleModelController extends Controller
{
    public function index()
    {
        try {
            $list = VehicleModel::get();
            return response(['status' => true, 'data' => $list]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function store(CreateVehicleModel $request)
    {

        try {
            $vehicleModel = new VehicleModel();
            $vehicleModel->user_id  = Auth::user()->_id;
            $vehicleModel->name     = $request->name;
            $vehicleModel->brand_id = $request->brand_id;
            $vehicleModel->status   = $request->status;

            if (!empty($request->file('icon')))
                $vehicleModel->icon  = singleFile($request->file('icon'), 'icon/');

            if ($vehicleModel->save())
                return response(['status' => 'success', 'message' => 'Vehicle Model created Successfully!']);

            return response(['status' => 'error', 'message' => 'Vehicle Model not created Successfully!']);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        } 
    }

    public function show($id)
    {
        try {
            $list = VehicleModel::find($id);
            return response(['status' => true, 'data' => $list]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function update(CreateVehicleModel  $request, $id)
    {
        try {
            $vehicleModel = VehicleModel::find($id);
            $vehicleModel->name     = $request->name;
            $vehicleModel->brand_id = $request->brand_id;
            $vehicleModel->status   = $request->status;

            if (!empty($request->file('icon')))
                $vehicleModel->icon  = singleFile($request->file('icon'), 'icon/');

            if ($vehicleModel->save())
                return response(['status' => 'success', 'message' => 'Vehicle Model updated Successfully!']);

            return response(['status' => 'error', 'message' => 'Vehicle Model not updated!']);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function destroy(VehicleModel $vehicleModel)
    {
        if($vehicleModel->delete())
        return response(['status' => 'success', 'message' => 'Category deleted Successfully!']);

         return response(['status' => 'error', 'message' => 'Category not deleted!']);
    }
}
