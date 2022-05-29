<?php

namespace App\Http\Controllers\App;

use App\Models\Admin\Service;
use App\Models\Admin\ShopOwner;
use Exception;

class VendorController extends AppController
{
    public function vendorList($service_id)
    {
        try {
            $service = Service::select('shop_owner')->find($service_id);
            if (empty($service))
                return response(['status' => 'error', 'message' => "invalid Service Id"]);

            $shop_ids = $service->shop_owner;

            if (!empty($shop_ids) && is_array($shop_ids))
               $lists = ShopOwner::whereIn('_id', $shop_ids)->get();

            if (empty($lists))
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


    public function vendorDetails($id)
    {
        try {
            $list = ShopOwner::find($id);
            $service_ids = $list->services;
            $services = Service::select('shop_owner')->whereIn('_id', $service_ids)->get();
            $serviceList = [];
            foreach ($services as $service) {
                $serviceList[] = [
                    '_id'          => $list->_id,
                    'title'        => $list->title,
                    'icon'         => asset('icon/' . $list->icon),
                    'created'      => $list->created
                ];
            }

            $records = [
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
                'service_list' => $serviceList,
                'created'      => $list->created
            ];
            return response(['status' => 'success', 'data' => $records]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
