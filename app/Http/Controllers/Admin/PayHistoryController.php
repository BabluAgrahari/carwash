<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\PayHistory;
use Exception;
use Illuminate\Http\Request;

class PayHistoryController extends Controller
{
    public function index($vendor_id)
    {
        try {

            $payHistory = PayHistory::get();

            return response(['status' => 'success', 'data' => $records]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

}
