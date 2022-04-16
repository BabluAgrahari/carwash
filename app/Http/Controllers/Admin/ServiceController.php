<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\validation\Admin\CreateService;
use App\Models\Admin\Service;
use App\Models\Admin\Category;
use App\Models\Admin\VehicleBrand;
use App\Models\Admin\VehicleModel;
use Storage;
use Exception;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{

    public function index()
    {
        try {
            $list = Service::get();
            return response(['status' => true, 'data' => $list]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function store(CreateService $request)
    {
        try {
            $category = Category::where('user_id', $this->user->id)->where('_id', $request->category)->first();

            if (empty($category)) {
                return response(['status' => 'error', 'message' => 'Invalid Category!']);
            }
            $vehicleBrand = VehicleBrand::where('user_id', $this->user->id)->where('_id', $request->vehicle_brand)->first();

            if (empty($vehicleBrand)) {
                return response(['status' => 'error', 'message' => 'Invalid Vehicle Brand!']);
            }
            $vehicleModel = VehicleModel::where('user_id', $this->user->id)->where('_id', $request->vehicle_model)->first();

            if (empty($vehicleModel)) {
                return response(['status' => 'error', 'message' => 'Invalid Vehicle Model!']);
            }

            $service = new Service();
            $service->user_id  = Auth::user()->_id;
            $service->category          = $request->category;
            $service->vehicle_brand     = $request->vehicle_brand;
            $service->vehicle_model     = $request->vehicle_model;
            $service->service_type      = $request->service_type;
            $service->service_tittle    = $request->service_tittle;
            $service->sort_description  = $request->sort_description;
            $service->description       = $request->description;
            $service->time_duration     = $request->time_duration;
            $service->service_charge    = $request->service_charge;
            $service->discount          = $request->discount;
            $service->gst_charges       = $request->gst_charges;
            $service->status            = $request->status;

            if (!empty($request->file('icon')))
            $service->icon  = singleFile($request->file('icon'), 'icon/');
            
            if (!empty($request->hasFile('multiple_images')))
                $service->multiple_images = json_encode(multipleFile($request->file('multiple_images'), 'images'));

            if (!empty($request->hasFile('video'))) {
                $path = $request->file('video')->store('videos', ['disk' =>      'my_files']);
                $service->video = $path;
            }
            if ($service->save())
                return response(['status' => 'success', 'message' => 'Service created successfully!']);

            return response(['status' => 'error', 'message' => 'Service not created Successfully!']);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function show($id)
    {
        try {
            $list = Service::find($id);
            return response(['status' => true, 'data' => $list]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function update(CreateService $request, $id)
    {
        try {
            $category = Category::where('user_id', $this->user->id)->where('_id', $request->category)->first();

            if (empty($category)) {
                return response(['status' => 'error', 'message' => 'Invalid Category!']);
            }
            $vehicleBrand = VehicleBrand::where('user_id', $this->user->id)->where('_id', $request->vehicle_brand)->first();

            if (empty($vehicleBrand)) {
                return response(['status' => 'error', 'message' => 'Invalid Vehicle Brand!']);
            }
            $vehicleModel = VehicleModel::where('user_id', $this->user->id)->where('_id', $request->vehicle_model)->first();

            if (empty($vehicleModel)) {
                return response(['status' => 'error', 'message' => 'Invalid Vehicle Model!']);
            }

            $service = Service::find($id);
            $service->category          = $request->category;
            $service->vehicle_brand     = $request->vehicle_brand;
            $service->vehicle_model     = $request->vehicle_model;
            $service->service_type      = $request->service_type;
            $service->service_tittle    = $request->service_tittle;
            $service->sort_description  = $request->sort_description;
            $service->description       = $request->description;
            $service->time_duration     = $request->time_duration;
            $service->service_charge    = $request->service_charge;
            $service->discount          = $request->discount;
            $service->gst_charges       = $request->gst_charges;
            $service->status            = $request->status;


            if (!empty($request->file('icon')))
            $service->icon  = singleFile($request->file('icon'), 'icon/');

            if (!empty($request->hasFile('multiple_images')))
                $service->multiple_images = json_encode(multipleFile($request->file('multiple_images'), 'images'));

            if (!empty($request->hasFile('video'))) {
                $path = $request->file('video')->store('videos', ['disk' =>      'my_files']);
                $service->video = $path;
            }
            if ($service->save())
                return response(['status' => 'success', 'message' => 'Service Updated successfully!']);

            return response(['status' => 'error', 'message' => 'Service Not Updated Successfully!']);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function destroy(Service $service)
    {
        if($service->delete())
        return response(['status' => 'success', 'message' => 'Service deleted Successfully!']);

         return response(['status' => 'error', 'message' => 'Service not deleted!']);
    }
}
