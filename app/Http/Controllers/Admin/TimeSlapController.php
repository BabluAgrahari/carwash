<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\TimeSlap;
use Exception;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;

class TimeSlapController extends Controller
{
    public function index()
    {
        try {
            $lists = TimeSlap::desc()->get();

            if ($lists->isEmpty())
                return response(['status' => 'error', 'message' => "no found any record."]);


            $records = [];
            foreach ($lists as $list) {
                $records[] = [
                    '_id'          => $list->_id,
                    'start_time'   => $list->start_time,
                    'end_time'     => $list->end_time,
                    'no_of_service'=> $list->no_of_service,
                    'created'      => $list->dFormat($list->created),
                    'updated'      => $list->dFormat($list->updated)
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
            $timeSlap->start_time      = $request->start_time;
            $timeSlap->end_time        = $request->end_time;
            $timeSlap->no_of_service   = $request->no_of_service;

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
            $timeSlap = TimeSlap::find($id);
            $timeSlap->start_time      = $request->start_time;
            $timeSlap->end_time        = $request->end_time;
            $timeSlap->no_of_service   = $request->no_of_service;

            if ($timeSlap->save())
                return response(['status' => 'success', 'message' => 'Time Slap updated Successfully!']);

            return response(['status' => 'error', 'message' => 'Time Slap not updated!']);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function destroy(TimeSlap $timeSlap)
    {
        if ($timeSlap->delete())
            return response(['status' => 'success', 'message' => 'Time Slap deleted Successfully!']);

        return response(['status' => 'error', 'message' => 'Time Slap not deleted!']);
    }
}
