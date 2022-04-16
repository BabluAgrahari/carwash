<?php

namespace App\Http\Requests\validation\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class CreateVehicleModel extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|min:2|max:255',
            'vehicle_brand' => 'required',
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
            'status' => 'error',
            'message' => $validator->errors(),
        ]));
    }
}
