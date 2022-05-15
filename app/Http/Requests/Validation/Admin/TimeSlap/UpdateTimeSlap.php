<?php

namespace App\Http\Requests\Validation\Admin\TimeSlap;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTimeSlap extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'start_time' => 'required',
            'end_time'   => 'required',
            'no_of_services'=> 'required'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // throw new HttpResponseException();
        throw new HttpResponseException(response()->json([
            'status' => "error",
            'type'   => "validation",
            'message' => $validator->errors(),
        ]));
    }
}
