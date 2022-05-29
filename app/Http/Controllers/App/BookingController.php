<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Admin\Booking;
use App\Models\Admin\Passbook;
use App\Models\Admin\PayHistory;
use App\Models\Admin\TimeSlap;
use Exception;
use Illuminate\Http\Request;

class BookingController extends AppController
{
    // public function index($vendor_id)
    // {
    //     try {

    //         return response(['status' => 'success', 'data' => $records]);
    //     } catch (Exception $e) {
    //         return response(['status' => 'error', 'message' => $e->getMessage()]);
    //     }
    // }


    public function store(Request $request)
    {
        try {
            $booking = new Booking();
            $booking->booking_no      = substr(time(), 4, 6);
            $booking->customer_id     = $this->AppAuth('_id');
            $booking->vendor_id       = $request->vendor_id;
            $booking->service_id      = $request->service_id;
            $booking->time_slab_id    = $request->time_slab_id;
            $booking->time_slab       = $request->time_slab;
            $booking->booking_date    = $request->booking_date;
            $booking->total_amount    = $request->total_amount;
            $booking->payment_ref_id  = $request->payment_ref_id;
            $booking->payment_status  = $request->payment_status;
            $booking->payment_mode    = $request->payment_mode;
            $booking->booking_status  = 'pending';
            if (!$booking->save())
                return response(['status' => 'error', 'message' => 'Your Service is not booked']);
            $this->passbook($request);

            $payHistory = [
                'user_id'   => $this->AppAuth('_id'),
                'vendor_id' => $request->vendor_id,
                'service_id' => $request->service_id,
                'amount'    => $request->amount,
                'comission' => 0,
                'total_amount' => $request->amount
            ];

            $this->payHistory($payHistory);

            return response(['status' => 'success', 'message' => 'Your Service is booked']);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function passbook($request)
    {
        try {

            if (empty($request) || !is_object($request))
                return response(['status' => 'error', 'message' => 'Invalid data find']);

            if ($request->payment_status === 'paid') {

                $exist_p = Passbook::where('vendor_id', $request->vendor_id)->desc()->first();
                $existClosingAmount = 0;
                if (!empty($exist_p->closing_amount))
                    $existClosingAmount = $exist_p->closing_amount;

                $passbook = new Passbook();
                $passbook->customer_id = $this->AppAuth('_id');
                $passbook->vendor_id   = $request->vendor_id;
                $passbook->type        = 'credit';
                $passbook->amount      =  $request->total_amount;
                $passbook->credit_by   = 'customer';
                $passbook->status      = 'success';

                if (!$passbook->save())
                    return response(['status' => 'error', 'message' => 'Passbook History not maintain']);

                $updatePassbook = Passbook::find($passbook->_id);
                $closing_amount = (int)$existClosingAmount + (int)$passbook->amount;
                $updatePassbook->closing_amount = $closing_amount;
                $updatePassbook->save();
            }
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function payHistory($request)
    {
        try {
            $request = (object)$request;
            $payHistory = new PayHistory();
            $payHistory->vendor_id    = $request->vendor_id;
            $payHistory->service_id   = $request->service_id;
            $payHistory->amount       = $request->amount;
            $payHistory->comission    = $request->comission;
            $payHistory->total_amount = $request->total_amount;
            $payHistory->save();
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
