<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Booking;
use App\Models\Admin\Service;
use Exception;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        // try {

        $booking = Booking::get();

        if ($booking->isEmpty())
            return response(['status' => 'error', 'message' => "no found any record."]);

        $records = [];
        foreach ($booking as $list) {
            $records[] = [
                '_id'            => $list->_id,
                'customer_id'    => $list->customer_id,
                'customer_name'  => !empty($list->customerName['name']) ? $list->customerName['name'] : '',
                'vendor_id'      => $list->vendor_id,
                'vendor_name'    => !empty($list->vendorName['business_name']) ? $list->vendorName['business_name'] : '',
                'booking_no'     => $list->booking_no,
                'time_slab'      => $list->time_slab,
                'booking_date'   => $list->dFormat($list->booking_date),
                'total_amount'   => $list->total_amount,
                'payment_status' => strtoupper($list->payment_status),
                'booking_status' => $list->booking_status,
                'payment_mode'   => ucwords($list->payment_mode)
            ];
        }

        return response(['status' => 'success', 'data' => $records]);
        // } catch (Exception $e) {
        //     return response(['status' => 'error', 'message' => $e->getMessage()]);
        // }
    }


    public function update(Request $request, $id)
    {
        try {
            $booking = Booking::find($id);
            $booking->booking_status = $request->booking_status;
            if (!$booking->save())
                return response(['status' => 'error', 'message' => 'Booking Status not updated']);

            // $this->passbook($booking);
            return response(['status' => 'success', 'message' => 'Booking Status updated successfully']);
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
                $passbook->customer_id =  $this->AppAuth('_id');
                $passbook->vendor_id = $request->vendor_id;
                $passbook->type      = 'debit';
                $passbook->amount    =  $request->total_amount;
                $passbook->debit_by  = 'admin';
                $passbook->status    = 'success';

                if (!$passbook->save())
                    return response(['status' => 'error', 'message' => 'Passbook History not maintain']);

                $service = Service::find($request->service_id);

                // $service_charges = $service->total_charges;

                $updatePassbook = Passbook::find($passbook->_id);
                $closing_amount = (int)$existClosingAmount - (int)$passbook->amount;
                $updatePassbook->closing_amount = $closing_amount;
                $updatePassbook->save();
            }
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
