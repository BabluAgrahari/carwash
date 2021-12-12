<?php

namespace App\Http\Controllers\Retailer\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Transaction\RetailerTrans;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RetailerTransController extends Controller
{

    public function index()
    {
        try {
            return view('retailer.transaction.retailer_display');
        } catch (Exception $e) {
            return redirect('500')->with(['error' => $e->getMessage()] );;
        }
    }


    public function store(Request $request)
    {

        $RetailerTrans = new RetailerTrans();
        $RetailerTrans->retailer_id    = Auth::user()->_id;
        $RetailerTrans->mobile_number  = Auth::user()->mobile_number;
        $RetailerTrans->sender_name    = Auth::user()->name;
        $RetailerTrans->amount         = $request->amount;
        $RetailerTrans->receiver_name  = $request->receiver_name;
        $RetailerTrans->payment_mode   = $request->payment_mode;
        $RetailerTrans->payment_channel= $request->payment_channel;
        $RetailerTrans->status         = 'pending';
        $RetailerTrans->pancard_no     = $request->pancard_no;

        if (!empty($request->file('pancard')))
        $RetailerTrans->pancard  = singleFile($request->file('pancard'), 'attachment/transaction');

        if ($RetailerTrans->save())
            return response(['status' => 'success', 'msg' => 'Transaction Request Created Successfully!']);

            return response(['status' => 'error', 'msg' => 'Transaction Request not  Created!']);
    }


    public function show(RetailerTrans $RetailerTrans)
    {

    }


    public function edit(RetailerTrans $RetailerTrans)
    {
        try {
            die(json_encode($RetailerTrans));
        } catch (Exception $e) {
            return redirect('500');
        }
    }



    public function ajaxList(Request $request)
    {

        $draw = $request->draw;
        $start = $request->start;
        $length = $request->length;
        $search_arr = $request->search;
        $searchValue = $search_arr['value'];

        // count all data
        $totalRecords = RetailerTrans::AllCount();

        if (!empty($searchValue)) {
            // count all data
            $totalRecordswithFilter = RetailerTrans::LikeColumn($searchValue);
            $data = RetailerTrans::GetResult($searchValue);
        } else {
            // get per page data
            $totalRecordswithFilter = $totalRecords;
            $data = RetailerTrans::offset($start)->limit($length)->orderBy('created', 'DESC')->get();
        }
        $dataArr = [];
        $i = 1;

        foreach ($data as $val) {
            // $action = '<a href="javascript:void(0);" class="text-info edit_bank_account" data-toggle="tooltip" data-placement="bottom" title="Edit" bank_account_id="' . $val->_id . '"><i class="far fa-edit"></i></a>&nbsp;&nbsp;';
            // $action .= '<a href="javascript:void(0);" class="text-danger remove_bank_account"  data-toggle="tooltip" data-placement="bottom" title="Remove" bank_account_id="' . $val->_id . '"><i class="fas fa-trash"></i></a>';

            if ($val->status == 'approved') {
                $status = '<strong class="text-success">'.ucwords($val->status).'</strong>';
            } else if($val->status =='rejected') {
                $status = '<strong class="text-danger">'.ucwords($val->status).'</strong>';
            }else if($val->status == 'pending'){
                $status = '<strong class="text-warning">'.ucwords($val->status).'</strong>';
            }

            $dataArr[] = [
                'sl_no'             => $i,
                'sender_name'       => ucwords($val->sender_name),
                'mobile_number'     => $val->mobile_number,
                'amount'            => $val->amount,
                'receiver_name'     => $val->receiver_name,
                'payment_mode'      => ucwords(str_replace('_',' ',$val->payment_mode)),
                'status'            => $status,
                'created_date'      => date('Y-m-d', $val->created),
                // 'action'            => $action
            ];
            $i++;
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" =>  $totalRecordswithFilter,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $dataArr
        );
        echo json_encode($response);
        exit;
    }

}
