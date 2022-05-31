<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\ShopOwner;
use App\Models\Admin\Service;
use App\Http\Requests\Validation\Admin\CreateShop;
use App\Http\Requests\Validation\Admin\UpdateShop;
use App\Models\Admin\TimeSlap;
use App\Models\User;
use Exception;
use Facade\FlareClient\Time\Time;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class shopOwnerController extends Controller
{
    public function index()
    {
        try {
            $lists = ShopOwner::desc()->get();
            if ($lists->isEmpty())
                return response(['status' => 'error', 'message' => "no found any record."]);


            $records = [];
            foreach ($lists as $list) {
                $records[] = [
                    '_id'              => $list->_id,
                    'name'             => $list->name,
                    'email'            => $list->email,
                    'mobile_no'        => $list->mobile_no,
                    'business_name'    => $list->business_name,
                    'business_email'   => $list->business_email,
                    'phone'            => $list->phone,
                    'city'             => $list->city,
                    'pincode'          => $list->pincode,
                    'country'          => $list->country,
                    'state'            => $list->state,
                    'gstin_no'         => $list->gstin_no,
                    'address'          => $list->address,
                    'description'      => $list->description,
                    'store_status'     => $list->store_status,
                    'verified_store'   => $list->verified_store,
                    'whatsapp_no'      => $list->whatsapp_no,
                    'bank_details'     => $list->bank_details,
                    'created'          => $list->dFormat($list->created),
                    'updated'          => $list->dFormat($list->updated)
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

            $shopOwner = new ShopOwner();
            $shopOwner->user_id        = Auth::user()->_id;
            $shopOwner->name           = $request->name;
            $shopOwner->email          = $request->email;
            $shopOwner->mobile_no      = $request->mobile_no;
            $shopOwner->business_name  = $request->business_name;
            $shopOwner->business_email = $request->business_email;
            $shopOwner->phone          = $request->phone;
            $shopOwner->city           = $request->city;
            $shopOwner->pincode        = $request->pincode;
            $shopOwner->country        = $request->country;
            $shopOwner->state          = $request->state;
            $shopOwner->address        = $request->address;
            $shopOwner->description    = $request->description;
            $shopOwner->store_status   = $request->store_status;
            $shopOwner->verified_store = $request->verified_store;
            $shopOwner->whatsapp_no    = $request->whatsapp_no;
            $shopOwner->gstin_no       = $request->gstin_no;
            $shopOwner->bank_details   = json_decode($request->bank_details);

            if (!empty($request->file('logo')))
                $shopOwner->logo  = singleFile($request->file('logo'), 'shop/');

            if (!empty($request->file('cover_photo')))
                $shopOwner->cover_photo  = singleFile($request->file('cover_photo'), 'shop/');

            if (!$shopOwner->save())
                return response(['status' => 'error', 'message' => 'Shop Owner not created Successfully!']);

            $this->createUser($request, $shopOwner->_id);
            return response(['status' => 'success', 'message' => 'Shop Owner created successfully!']);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    private function createUser($request, $id)
    {
        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile_no = $request->mobile_no;
            $user->password = Hash::make($request->password);
            $user->role = 'vendor';
            $user->vendor_id = $id;
            if ($user->save()) {

                $vendor = ShopOwner::find($id);
                $vendor->account_id = $user->_id;
                $vendor->save();

                $this->addTimeSlap($user->_id);
            }
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $list = ShopOwner::find($id);
            $record = [
                '_id'              => $list->_id,
                'name'             => $list->name,
                'email'            => $list->email,
                'mobile_no'        => $list->mobile_no,
                'business_name'    => $list->business_name,
                'business_email'   => $list->business_email,
                'phone'            => $list->phone,
                'city'             => $list->city,
                'pincode'          => $list->pincode,
                'country'          => $list->country,
                'state'            => $list->state,
                'gstin_no'         => $list->gstin_no,
                'address'          => $list->address,
                'description'      => $list->description,
                'store_status'     => $list->store_status,
                'verified_store'   => $list->verified_store,
                'whatsapp_no'      => $list->whatsapp_no,
                'bank_details'     => $list->bank_details
            ];
            return response(['status' => true, 'data' => $record]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $shopOwner = ShopOwner::find($id);
            $shopOwner->name           = $request->name;
            $shopOwner->email          = $request->email;
            $shopOwner->mobile_no      = $request->mobile_no;
            $shopOwner->business_name  = $request->business_name;
            $shopOwner->business_email = $request->business_email;
            $shopOwner->phone          = $request->phone;
            $shopOwner->city           = $request->city;
            $shopOwner->pincode        = $request->pincode;
            $shopOwner->country        = $request->country;
            $shopOwner->state          = $request->state;
            $shopOwner->address        = $request->address;
            $shopOwner->description    = $request->description;
            $shopOwner->store_status   = $request->store_status;
            $shopOwner->verified_store = $request->verified_store;
            $shopOwner->whatsapp_no    = $request->whatsapp_no;
            $shopOwner->gstin_no       = $request->gstin_no;
            $shopOwner->bank_details   = json_decode($request->bank_details);

            if (!empty($request->file('logo')))
                $shopOwner->logo  = singleFile($request->file('logo'), 'shop/');

            if (!empty($request->file('cover_photo')))
                $shopOwner->cover_photo  = singleFile($request->file('cover_photo'), 'shop/');

            if ($shopOwner->save())
                return response(['status' => 'success', 'message' => 'Shop Owner updated Successfully!']);

            return response(['status' => 'error', 'message' => 'Shop Owner not updated!']);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function destroy(ShopOwner $ShopOwner)
    {
        if ($ShopOwner->delete())
            return response(['status' => 'success', 'message' => 'Shop Owner deleted Successfully!']);

        return response(['status' => 'error', 'message' => 'Shop Owner not deleted!']);
    }


    public function assignServices(Request $request, $id)
    {

        $services   = $request->services;
        $shopOwner = ShopOwner::find($id);
        $shopOwner->services = $services;

        if (!$shopOwner->save())
            return response(['status' => 'error', 'message' => 'Services not Assigned']);

        $shop_ids = [];
        foreach ($services as $service) {
            $service = Service::find($service);
            $shop_ids = $service->shop_owners;
            $shop_ids[] = $id;
            $service->shop_owners = $shop_ids;
            $service->save();
        }

        return response(['status' => 'success', 'message' => 'Services assigned Successfully!']);
    }


    private function addTimeSlap($vendor_id)
    {

        $days = ['monday', 'tuesday', 'webnesday', 'thrusday', 'friday', 'saturday', 'sunday'];
        $slaps = [
            [
                'start_time'    => '10:00 AM',
                'end_time'      => '11:00 AM',
                'no_of_services' => 1,
            ],
            [
                'start_time'    => '11:00 AM',
                'end_time'      => '12:00 AM',
                'no_of_services' => 1,
            ],
            [
                'start_time'    => '12:00 AM',
                'end_time'      => '01:00 PM',
                'no_of_services' => 1,
            ],
            [
                'start_time'    => '01:00 PM',
                'end_time'      => '02:00 PM',
                'no_of_services' => 1,
            ],
            [
                'start_time'    => '03:00 PM',
                'end_time'      => '04:00 PM',
                'no_of_services' => 1,
            ],
            [
                'start_time'    => '04:00 PM',
                'end_time'      => '05:00 PM',
                'no_of_services' => 1,
            ],
            [
                'start_time'    => '05:00 PM',
                'end_time'      => '06:00 PM',
                'no_of_services' => 1,
            ],
            [
                'start_time'    => '06:00 PM',
                'end_time'      => '07:00 PM',
                'no_of_services' => 1,
            ],
        ];
        foreach ($days as $day) {
            $timeSlap = new TimeSlap();
            $timeSlap->vendor_id = $vendor_id;
            $timeSlap->day             = $day;
            $timeSlap->day_code        = substr($day, 0, 2);
            $timeSlap->status          = "1";
            $timeSlap->slaps           = $slaps;
            $timeSlap->save();
        }
    }
}
