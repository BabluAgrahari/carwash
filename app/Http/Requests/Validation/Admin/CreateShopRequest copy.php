<?php

namespace App\Http\Requests\validation\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class CreateShopRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'     => 'required|string|between:2,30',
            'email'    => 'required|email|max:155',
            'phone_no' => 'required|numeric|digits:10',
            'address'  => 'required|string|between:2,100'
        ];
    }
    public function messages()
    {
        return [
            'name.required'          => 'Name field is Required.',
            'name.string'            => 'Name should be string.',
            'name.max'               => 'Name should not be maximum 30 Character.',
            'email.required'         => 'Email field is Required.',
            'email.email'            => 'Please enter valid Email.',
            'email.max'              => 'Email should not be maximum 30 Character.',
            'phone_no.required'      => 'Phone Number field is Required.',
            'phone_no.numeric'       => 'Phone Number Must be numeric value.',
            'phone_no.digits'        => 'Phone Number Must be 10 digits.',
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
