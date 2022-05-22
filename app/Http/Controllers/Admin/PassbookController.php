<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Passbook;
use App\Models\Admin\Service;
use Exception;
use Illuminate\Support\Facades\Auth;

class PassbookController extends Controller
{
    public function index()
    {
        try {
            $lists = Passbook::desc()->get();
            if($lists->isEmpty())
                  return response(['status' =>'error', 'message' =>"no found any record."]);

            $records = [];
            foreach($lists as $list){
            $records[] = [
             '_id'             =>$list->_id,
             'vendor_id'       =>$list->vendor_id,
             'store_name'     =>!empty($list->vendorName['business_name'])?$list->vendorName['business_name']:'',
             'amount'          =>$list->amount,
             'type'            =>strtoupper($list->type),
             'closing_amount'  =>$list->closing_amount,
             'status'          =>strtoupper($list->status),
             'created'         =>date('d M Y H:i',$list->created),
             'updated'         =>$list->dFormat($list->updated)
             ];
             }

            return response(['status' => 'success', 'data' => $records]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
