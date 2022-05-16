<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Booking;
use Exception;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        try {

            $booking = Booking::get();

            if ($booking->isEmpty())
                return response(['status' => 'error', 'message' => "no found any record."]);

            $records = [];
            foreach ($booking as $list) {
                $records[] = [
                    '_id'            => $list->_id,
                    'user_id'        => $list->user_id,
                    'booking_no'     => $list->booking_no,
                    'time_slab'      => $list->time_slab,
                    'booking_date'   => $list->dFormat($list->booking_date),
                    'total_amount'   => $list->total_amount,
                    'payment_status' => strtoupper($list->payment_status),
                    'payment_mode'   => ucwords($list->payment_mode)
                ];
            }

            return response(['status' => 'success', 'data' => $records]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
