<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\PayHistory;
use Exception;
use Illuminate\Http\Request;

class PayHistoryController extends Controller
{
    public function index()
    {
        try {

            $payHistory = PayHistory::get();

            foreach ($payHistory as $pay) {
                $records[] = [
                    '_id'              => $pay->_id,
                    'vendor_id'        => $pay->vendor_id,
                    'store_name'       => !empty($pay->vendorName['business_name']) ? $pay->vendorName['business_name'] : '',
                    'customer_id'      => $pay->customer_id,
                    'customer_name'    => !empty($pay->customerName['name']) ? $pay->customerName['name'] : '',
                    'services_id'      => $pay->service_id,
                    'service_name'     => '-',
                    'amount'           => $pay->amount,
                    'comission_amount' => $pay->comission,
                    'total_amount'     => $pay->total_amount,
                ];
            }

            return response(['status' => 'success', 'data' => $records]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
