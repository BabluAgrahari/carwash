<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Admin\Service;
use Exception;

class AppController extends Controller
{
    public function index()
    {
        try {
            $list = Service::get();
            return response(['status' => true, 'data' => $list]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
