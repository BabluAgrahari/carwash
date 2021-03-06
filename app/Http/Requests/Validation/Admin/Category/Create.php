<?php

namespace App\Http\Requests\Validation\Admin\Category;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class Create extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            // 'demo' => 'required'
            // 'icon' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
       // print_r($validator);
        // throw new HttpResponseException();
        throw new HttpResponseException(response()->json(array(
            'status' => "error",
            'type'   => "validation",
            'message' => $validator->errors()
        )));

    }
}
