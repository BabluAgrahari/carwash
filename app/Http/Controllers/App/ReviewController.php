<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\App\AppController;
use App\Models\Admin\Review;
use Exception;
use Illuminate\Http\Request;

class ReviewController extends AppController
{

    public function index()
    {
        try {

            $reviews = Review::get();
            $records = array();
            foreach ($reviews as $list) {
                $records[] = [
                    'id'      => $list->_id,
                    'user_id' => $list->user_id,
                    'name'    => $list->name,
                    'rating'  => $list->rating,
                    'message' => $list->message,
                    'created' => $list->created,
                    'updated' => $list->updated,
                ];
            }
            return response(['status' => 'success', 'data' =>$records]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $review = new Review();
            $review->user_id = $this->AppAuth('_id');
            $review->name    = $this->AppAuth('name');
            $review->rating  = $request->rating;
            $review->message = $request->message;
            if (!$review->save())
                return response(['status' => 'error', 'message' => 'Review not Added']);

            return response(['status' => 'success', 'message' => 'Review Added Successfully!']);
        } catch (Exception $e) {
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
