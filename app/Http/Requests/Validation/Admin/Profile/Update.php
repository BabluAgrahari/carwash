<?php

namespace App\Http\Requests\Validation\Admin\Profile;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|min:2|max:50',
            'email' => 'nullable|email|min:2|max:50',
            'phone' => 'nullable|numeric|digits:10',
            'city'    =>  'nullable|min:2|max:30',
            'state'   =>  'nullable|min:2|max:30',
            'country' =>  'nullable|min:2|max:30',
            'address' => 'nullable|min:2|max:1000'

            // 'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
            'status' => "error",
            'type'   => "validation",
            'message' => $validator->errors(),
        ]));
    }
}
