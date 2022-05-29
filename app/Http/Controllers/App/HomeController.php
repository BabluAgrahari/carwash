<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Admin\Service;
use App\Models\Admin\ShopOwner;
use App\Models\Admin\VehicleBrand;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class HomeController extends AppController
{
    public function serviceList()
    {
        try {
            $lists = Service::where('status', '1')->get();

            if ($lists->isEmpty())
                return response(['status' => 'error', 'message' => "no record found."]);

            $records = [];
            foreach ($lists as $list) {
               $records[] = [
             '_id'             =>$list->_id,
             'user_id'         =>$list->_id,
             'title'           =>$list->title,
             'sort_description'=>$list->sort_description,
             'description'     =>$list->description,
             'service_charge'  =>$list->service_charge,
             'discount'        =>$list->discount,
             'gst_charges'     =>$list->gst_charges,
             'total_charges'   =>$list->total_charges,
             'vehicle_brand'   =>!empty($list->vehicleBrand['name'])?$list->vehicleBrand['name']:'',
             'vehicle_brand_id'=>$list->vehicle_brand,
             'category'        =>!empty($list->cCategory['name'])?$list->cCategory['name']:'',
             'category_id'     =>$list->category,
             'vehicle_model'   =>!empty($list->vehicleModel['name'])?$list->vehicleModel['name']:'',
             'vehicle_model_id'=>$list->vehicle_model,
             'service_type'    =>$list->service_type,
             'shop_owners'     =>$list->shop_owners,
              'icon'           =>!empty($list->icon)?asset('services/'.$list->icon):'',
             'status'          =>$list->isActive($list->status),
             'created'         =>$list->dFormat($list->created),
             'updated'         =>$list->dFormat($list->updated)
             ];
            }

            return response(['status' => 'success', 'data' => $records]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function vendorList()
    {
        try {
            $lists = ShopOwner::get();

            if ($lists->isEmpty())
                return response(['status' => 'error', 'message' => "no record found."]);

            $records = [];
            foreach ($lists as $list) {
                  $records[] = [
                    '_id'          => $list->_id,
                    'user_id'      => $list->user_id,
                    'name'         => $list->business_name,
                    'email'        => $list->email,
                    'mobile'       => $list->mobile,
                    'city'         => $list->city,
                    'state'        => $list->state,
                    'country'      => $list->country,
                    'address'      => $list->address,
                    'description'  => $list->description,
                    'latitude'     => $list->latitude,
                    'longitude'    => $list->longitude,
                    'logo'         => asset('shop/' . $list->icon),
                    'cover_photo'  => asset('shop/' . $list->cover_photo),
                    'created'      => $list->created
                ];
            }

            return response(['status' => 'success', 'data' => $records]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function vehicleBrand()
    {
        try {
            $vehicleBrand = VehicleBrand::where('status','1')->get();
            $record = array();
            foreach ($vehicleBrand as $vehicle) {
                $record[] = [
                    '_id'     => $vehicle->_id,
                    'name'    => $vehicle->name,
                    'icon'    => asset('icon/' . $vehicle->icon),
                    'created' => $vehicle->created,
                    'vehicle_Modals' => $vehicle->vehicleModal
                ];
            }

            return response(['status' => 'success', 'data' => $record]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


     public function serviceWithType($type)
    {
        try {
            $lists = Service::where('status', '1')->where('service_type',$type)->get();

            if ($lists->isEmpty())
                return response(['status' => 'error', 'message' => "no record found."]);

            $records = [];
            foreach ($lists as $list) {
                $type = '';
                if($list->service_type =='1')
                $type ='Service at Home';
                else if($list->service_type =='2')
                $type = 'Service at Service Point';
                else if($list->service_type =='3')
                $type = 'Pickup & Drop';

                $records[] = [
                    '_id'              =>$list->_id,
                    'title'            =>$list->title,
                    'sort_description' =>$list->sort_description,
                    'description'      =>$list->description,
                    'service_charge'   =>$list->total_charges,
                    'service_type_id'  =>$list->service_type,
                    'service_type'     =>$type,
                    'icon'             =>asset('icon/' . $list->icon),
                    'created'          =>$list->created
                ];
            }

            return response(['status' => 'success', 'data' => $records]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
