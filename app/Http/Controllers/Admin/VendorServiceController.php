<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Validation\Admin\Services\Create;
use App\Models\Admin\Service;
use App\Models\Admin\VendorService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class VendorServiceController extends Controller
{
    public function index()
    {
        try {
            $query = Service::desc();
            $_id = Auth::user()->vendor_id;
            $query->where(function ($q) use ($_id) {
                $q->where('shop_owners', 'all', [$_id]);
            });
            $lists = VendorService::where('user_id',Auth::user()->_id)->get();
            if ($lists->isEmpty())
                return response(['status' => 'error', 'message' => "no found any record."]);


            $records = [];
            foreach ($lists as $list) {
                $records[] = [
                    '_id'             => $list->_id,
                    'user_id'         => $list->_id,
                    'service_id'      => $list->service_id,
                    'title'           => !empty($list->service['title'])?$list->service['title']:'',
                    'description'     => !empty($list->service['description'])?$list->service['description']:'',
                    'service_charge'  => $list->service_charge,
                    'discount'        => $list->discount,
                    'gst_charges'     => $list->gst_charges,
                    'total_charges'   => $list->total_charges,
                    'vehicle_brand'   => !empty($list->vehicleBrand['name']) ? $list->vehicleBrand['name'] : '',
                    'vehicle_brand_id'=> $list->vehicle_brand,
                    'vehicle_model'   => !empty($list->vehicleModel['name']) ? $list->vehicleModel['name'] : '',
                    'vehicle_model_id'=> $list->vehicle_model,
                    'service_type'    => $list->service_type,
                    'vehicle_brand_id'=> $list->vehicle_brand,
                    'icon'            => !empty($list->service['icon']) ? asset('services/' . $list->service['icon']) : '',
                    'created'         => $list->dFormat($list->created),
                    'updated'         => $list->dFormat($list->updated)
                ];
            }

            return response(['status' => 'success', 'data' => $records]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function store(Request $request)
    {
        try {

            $service = new VendorService();
            $service->user_id           = Auth::user()->_id;
            $service->category_id       = $request->category_id;
            $service->service_id        = $request->service_id;
            $service->vehicle_brand     = $request->vehicle_brand;
            $service->vehicle_model     = $request->vehicle_model;
            $service->service_type      = $request->service_type;
            $service->service_charge    = $request->service_charge;
            $service->discount          = $request->discount;
            $service->total_charges     = $request->total_charges;

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
            $list = VendorService::find($id);
            return response(['status' => true, 'data' => $list]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {

        try {
            $service = VendorService::find($id);
            $service->category_id       = $request->category_id;
            $service->service_id        = $request->service_id;
            $service->vehicle_brand     = $request->vehicle_brand;
            $service->vehicle_model     = $request->vehicle_model;
            $service->service_type      = $request->service_type;
            $service->service_charge    = $request->service_charge;
            $service->discount          = $request->discount;
            $service->total_charges     = $request->total_charges;

            if ($service->save())
                return response(['status' => 'success', 'message' => 'Service Updated successfully!']);

            return response(['status' => 'error', 'message' => 'Service Not Updated Successfully!']);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function destroy(VendorService $service)
    {
        if ($service->delete())
            return response(['status' => 'success', 'message' => 'Service deleted Successfully!']);

        return response(['status' => 'error', 'message' => 'Service not deleted!']);
    }
}
