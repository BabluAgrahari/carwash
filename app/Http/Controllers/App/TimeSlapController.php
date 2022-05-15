<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Admin\TimeSlap;
use Exception;
use Illuminate\Http\Request;

class TimeSlapController extends Controller
{
    public function index($vendor_id)
    {
        try {
            $lists = TimeSlap::where('vendor_id', $vendor_id)->get();

            if ($lists->isEmpty())
                return response(['status' => 'error', 'message' => "no found any record."]);

            for ($i = 0; $i < 5; $i++) {
                $dates[] = strtotime('+' . $i . 'days');
                $days[]  = strtolower(date('l', strtotime('+' . $i . 'days')));
            }
            $records = [];

            foreach ($days as $day_k=>$day) {
                foreach ($lists as $key => $list) {
                    if ($day == $list->day) {
                        $records[] = [
                            '_id'       => $list->_id,
                            'vendor_id' => $list->vendor_id,
                            'vendor_name' => !empty($list->vendorName['name']) ? $list->vendorName['name'] : '',
                            'day'       => $list->day,
                            'day_code'  => $list->day_code,
                            'date'      => !empty($dates[$day_k])?$dates[$day_k]:'',
                            'slaps'     => $list->slaps,
                            'status'    => $list->status
                        ];
                    }
                }
            }

            return response(['status' => 'success', 'data' => $records]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function updateServices(Request $request, $id, $key)
    {
        try {
            $timeSlap = TimeSlap::find($id);
            $slaps = $timeSlap->slaps;
            $slaps[$key]['no_of_services'] = $request->no_of_services;

            $timeSlap->slaps = $slaps;
            if (!$timeSlap->save())
                return response(['status' => 'error', 'message' => 'Serevices quantity not updated!']);

            return response(['status' => 'success', 'message' => 'Updated Services quanitity!']);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
