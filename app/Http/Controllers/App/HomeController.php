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
                    '_id'          => $list->_id,
                    'title'        => $list->title,
                    'icon'         => asset('icon/' . $list->icon),
                    'created'      => $list->created
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
                    '_id'            => $list->_id,
                    'business_name'  => $list->business_name,
                    'logo'           => asset('shop/' . $list->logo),
                    'created'        => $list->created
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
