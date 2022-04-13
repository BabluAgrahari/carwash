<?php

namespace App\Http\Requests\validation\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class CreateVehicleModelRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'brand_id' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'name.required'          => 'Name field is Required.',
            'name.string'            => 'Name should be string.',
            'name.max'               => 'Name should not be maximum 30 Character.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // throw new HttpResponseException();
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => $validator->errors(),
        ], 400));
    }
}
