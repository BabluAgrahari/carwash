<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\TimeSlap;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimeSlapController extends Controller
{
    public function index()
    {
        try {
            $lists = TimeSlap::get();

            if ($lists->isEmpty())
                return response(['status' => 'error', 'message' => "no found any record."]);


            $records = [];
            foreach ($lists as $list) {
                $records[] = [
                    '_id'           => $list->_id,
                    'day'           => ucwords($list->day),
                    'slaps'         => $list->slaps,
                    'disable_date'  => $list->disable_date,
                    'status'        => $list->isActive($list->status),
                    // 'created'      => $list->dFormat($list->created),
                    // 'updated'      => $list->dFormat($list->updated)
                ];
            }

            return response(['status' => 'success', 'data' => $records]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function store(Request $request)
    {
        try {
            $timeSlap = new TimeSlap();
            $timeSlap->vendor_id = Auth::user()->_id;
            $timeSlap->day             = $request->day;
            $timeSlap->day_code        = $request->day_code;
            // $timeSlap->slaps           = $slap_data;
            $timeSlap->status          = "1";

            if (!$timeSlap->save())
                return response(['status' => 'error', 'message' => 'Time Slap not created Successfully!']);

            return response(['status' => 'success', 'message' => 'Time Slap created Successfully!']);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function show($id)
    {
        try {
            $list = TimeSlap::find($id);
            $record = [
                '_id'          => $list->_id,
                'start_time'   => $list->start_time,
                'end_time'     => $list->end_time,
                'no_of_service'=> $list->no_of_service,
                'created'      => $list->dFormat($list->created),
                'updated'      => $list->dFormat($list->updated)
            ];
            return response(['status' => true, 'data' => $record]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $slap_data = [
                'start_time'    => $request->start_time,
                'end_time'      => $request->end_time,
                'no_of_services' => $request->no_of_services,
            ];
            $timeSlap = TimeSlap::find($id);
            if (!empty($timeSlap->slaps))
                $slaps = $timeSlap->slaps;
            $slaps[] = $slap_data;
            $timeSlap->slaps           = $slaps;

            if ($timeSlap->save())
                return response(['status' => 'success', 'message' => 'Time Slap updated Successfully!']);

            return response(['status' => 'error', 'message' => 'Time Slap not updated!']);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function disableServices(Request $request)
    {
        try {
            $date = strtotime($request->disable_date);
            $day = date('l', $date);

            $timeSlap = TimeSlap::where('vendor_id', Auth::user()->_id)->where('day', strtolower($day))->first();
            $timeSlap->status = '0';
            $timeSlap->disable_date = $date;
            if (!$timeSlap->save())
                return response(['status' => 'error', 'message' => 'Time Slap not disabled successfully!']);

            return response(['status' => 'success', 'message' => $day . ' Time Slap disabled successfully!']);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
