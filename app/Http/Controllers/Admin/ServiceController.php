<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Validation\Admin\Services\Create;
use App\Models\Admin\Service;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        try {
            $lists = Service::desc()->get();
            if ($lists->isEmpty())
                return response(['status' => 'error', 'message' => "no found any record."]);

            $records = [];
            foreach ($lists as $list) {
                $records[] = [
                    '_id'             => $list->_id,
                    'user_id'         => $list->_id,
                    'title'           => $list->title,
                    'description'     => $list->description,
                    'category'        => !empty($list->cCategory['name']) ? $list->cCategory['name'] : '',
                    'category_id'     => $list->category,
                    'shop_owners'     => $list->shop_owners,
                    'icon'            => !empty($list->icon) ? asset('services/' . $list->icon) : '',
                    'status'          => $list->isActive($list->status),
                    'created'         => $list->dFormat($list->created),
                    'updated'         => $list->dFormat($list->updated)
                ];
            }

            return response(['status' => 'success', 'data' => $records]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function store(Create $request)
    {
        try {

            $service = new Service();
            $service->user_id  = Auth::user()->_id;
            $service->category          = $request->category;
            $service->title             = $request->title;
            $service->description       = $request->description;
            $service->status            = $request->status;

            if (!empty($request->file('icon')))
                $service->icon  = singleFile($request->file('icon'), 'services/');

            // if (!empty($request->hasFile('multiple_images')))
            //     $service->multiple_images = json_encode(multipleFile($request->file('multiple_images'), 'services'));

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

    public function update(Create $request, $id)
    {
        try {
            $service = Service::find($id);
            $service->title             = $request->title;
            $service->category          = $request->category;
            $service->description       = $request->description;
            $service->status            = $request->status;

            if (!empty($request->file('icon')))
                $service->icon  = singleFile($request->file('icon'), 'icon/');

            // if (!empty($request->hasFile('multiple_images')))
            //     $service->multiple_images = json_encode(multipleFile($request->file('multiple_images'), 'images'));

            if ($service->save())
                return response(['status' => 'success', 'message' => 'Service Updated successfully!']);

            return response(['status' => 'error', 'message' => 'Service Not Updated Successfully!']);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function destroy(Service $service)
    {
        if ($service->delete())
            return response(['status' => 'success', 'message' => 'Service deleted Successfully!']);

        return response(['status' => 'error', 'message' => 'Service not deleted!']);
    }



    public function vendorServices()
    {

        try {
            $query = Service::desc();
            $_id = Auth::user()->vendor_id;
            $query->where(function ($q) use ($_id) {
                $q->where('shop_owners', 'all', [$_id]);
            });
            $lists = $query->get();
            if ($lists->isEmpty())
                return response(['status' => 'error', 'message' => "no found any record."]);


            $records = [];
            foreach ($lists as $list) {
                $records[] = [
                    '_id'             => $list->_id,
                    'user_id'         => $list->_id,
                    'title'           => $list->title,
                    'sort_description' => $list->sort_description,
                    'description'     => $list->description,
                    'service_charge'  => $list->service_charge,
                    'discount'        => $list->discount,
                    'gst_charges'     => $list->gst_charges,
                    'total_charges'   => $list->total_charges,
                    'vehicle_brand'   => !empty($list->vehicleBrand['name']) ? $list->vehicleBrand['name'] : '',
                    'vehicle_brand_id' => $list->vehicle_brand,
                    'category'        => !empty($list->cCategory['name']) ? $list->cCategory['name'] : '',
                    'category_id'     => $list->category,
                    'vehicle_model'   => !empty($list->vehicleModel['name']) ? $list->vehicleModel['name'] : '',
                    'vehicle_model_id' => $list->vehicle_model,
                    'service_type'    => $list->service_type,
                    'vehicle_brand_id' => $list->vehicle_brand,
                    'icon'           => !empty($list->icon) ? asset('services/' . $list->icon) : '',
                    'status'          => $list->isActive($list->status),
                    'created'         => $list->dFormat($list->created),
                    'updated'         => $list->dFormat($list->updated)
                ];
            }

            return response(['status' => 'success', 'data' => $records]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function servicesByCat($cat_id = false)
    {
        try {

            if (!$cat_id)
                return false;

            $services = Service::select('_id', 'title')->where('category', $cat_id)->get();

            foreach ($services as $list) {
                $records[] = [
                    'id' => $list->_id,
                    'title' => $list->title
                ];
            }
            return response(['status' => 'success', 'data' => $records]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
