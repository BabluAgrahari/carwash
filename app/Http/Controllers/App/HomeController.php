<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Admin\Service;
use App\Models\Admin\ShopOwner;
use App\Models\Admin\UserOtp;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function serviceList()
    {
        try {
            $lists = Service::where('status',1)->get();

             if($lists->isEmpty())
                return response(['status' =>'error', 'message' =>"no record found."]);

            $records = [];
            foreach($lists as $list){
            $records[] = [
             '_id'          =>$list->_id,
             'title'        =>$list->title,
             'icon'         =>asset('icon/'.$list->icon),
             'created'      =>$list->created
             ];
             }

            return response(['status' =>'success', 'data' => $records]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    // public function


    public function vendorList()
    {
        try {
            $lists = ShopOwner::where('status',1)->get();

             if($lists->isEmpty())
                return response(['status' =>'error', 'message' =>"no record found."]);

            $records = [];
            foreach($lists as $list){
            $records[] = [
             '_id'            =>$list->_id,
             'business_name'  =>$list->business_name,
             'logo'           =>asset('shop/'.$list->logo),
             'created'        =>$list->created
             ];
             }

            return response(['status' =>'success', 'data' => $records]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

}
