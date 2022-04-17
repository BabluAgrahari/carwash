<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\VehicleModel;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Validation\Admin\CreateVehicleModel;

class VehicleModelController extends Controller
{
    public function index()
    {
        try {
            $lists = VehicleModel::desc()->get();
             
             if($lists->isEmpty())
                  return response(['status' =>'error', 'message' =>"no found any record."]);


            $records = [];
            foreach($lists as $list){
            $records[] = [
             '_id'          =>$list->_id,
             'name'         =>$list->name,
             'vehicle_brand'=>$list->vehicleBrand['name'],
             'vehicle_brand_id'=>$list->vehicle_brand,
             'status'       =>$list->isActive($list->status),
             'created'      =>$list->dFormat($list->created),
             'updated'      =>$list->dFormat($list->updated)
             ];
             }

            return response(['status' =>'success', 'data' => $records]);
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
            $vehicleModel->vehicle_brand = $request->vehicle_brand;
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
            $vehicleModel->vehicle_brand = $request->vehicle_brand;
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
