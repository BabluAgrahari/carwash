<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Admin\Booking;
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
            $booking->user_id         = $this->AppAuth('_id');
            $booking->vendor_id       = $request->vendor_id;
            $booking->service_ids     = $request->service_ids;
            $booking->time_slab_id    = $request->time_slab_id;
            $booking->time_slab       = $request->time_slab;
            $booking->booking_date    = $request->booking_date;
            $booking->total_amount    = $request->total_amount;
            $booking->payment_ref_id  = $request->payment_ref_id;
            $booking->payment_status  = $request->payment_status;
            $booking->payment_mode    = $request->payment_mode;
            if ($booking->save())
                return response(['status' => 'success', 'message' => 'Your Service is booked']);

            return response(['status' => 'error', 'message' => 'Your Service is not booked']);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // private function updateInventory($request)
    // {
    //     $timeSlab = TimeSlap::find($request->time_slab_id);
    //     $
    // }
}
