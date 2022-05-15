<?php

namespace App\Http\Requests\Validation\Admin\TimeSlap;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class DisableServices extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'disable_date' => 'required'
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
