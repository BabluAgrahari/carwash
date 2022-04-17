<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\VehicleBrand;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\Validation\Admin\CreateVehicleBrand;

class VehicleBrandController extends Controller
{
    public function index()
    {
        try {
            $lists = VehicleBrand::desc()->get();

           if($lists->isEmpty())
                  return response(['status' =>'error', 'message' =>"no found any record."]);

            $records = [];
            foreach($lists as $list){
            $records[] = [
             '_id'          =>$list->_id,
             'user_id'      =>$list->user_id,
             'name'         =>$list->name,
             'icon'         =>(!empty($list->icon))?asset('icon/'.$list->icon):'',
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

    public function store(Request $request)
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
             $record = [
              'name'   =>$list->name,
              'status' =>$list->status,
              'icon'   =>(!empty($list->icon))?asset('icon/'.$list->icon):'',
              'created'=>$list->dFormat($list->created),
              'updated'=>$list->dFormat($list->updated)
            ];
          
            return response(['status' =>'success', 'data' => $record]);
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
