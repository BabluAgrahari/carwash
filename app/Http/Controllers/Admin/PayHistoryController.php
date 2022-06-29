<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\PayHistory;
use App\Models\Admin\ShopOwner;
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
                    'created'          => $pay->dFormat($pay->created),
                ];
            }

            return response(['status' => 'success', 'data' => $records]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function vendorAccount($id)
    {
        try {
            $vendor = ShopOwner::find($id);

            $bank_account = !empty($vendor->bank_details[0]) ? $vendor->bank_details[0] : '';
            $bank_account = [
                "holder_name" => $bank_account['holder_name'],
                'account_number' => $bank_account['account_number'], 'bank_name' => $bank_account['bank_name'], 'ifsc_code' => $bank_account['ifsc_code']
            ];

            $record = ['id' => $id, 'store_name' => $vendor->business_name, 'amount' => $vendor->total_amount, 'bank_account' => $bank_account];
            return response(['status' => 'success', 'data' => $record]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function payHistory(Request $request)
    {

        $pay_id   = $request->pay_id;
        $amount   = $request->amount;
        $store_id = $request->store_id;

        $histories = [];
        $payHistory = PayHistory::find($pay_id);
        if (!empty($payHistory->payHistory))
            $histories  = $payHistory->payHistory;

        $histories[] = ['amount' => $request->amount, 'store_id' => $store_id];
        $payHistory->payHistory = $histories;

        if ($payHistory->save())
            return response(['status' => 'success', 'message' => 'Payhistory not created Successfully!']);
    }
}
