<?php

namespace App\Http\Requests\Validation\Admin\Services;

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

            'category'          => 'required',
            // 'vehicle_brand'     => 'required',
            // 'vehicle_model'     => 'required',
            // 'service_type'      => 'required|string',
            'title'             => 'required|string|max:255',
            // 'sort_description'  => 'required|string|max:255',
            // 'description'       => 'nullable|string|max:500',
            // 'video'             => 'nullable|file|mimetypes:video/mp4',
            // 'multiple_images'   => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'service_charge'    => 'required|numeric',
            // 'discount'          => 'required|numeric',
            // 'gst_charges'       => 'required|numeric',
            // 'total_charges'     => 'required|numeric',
        ];
    }
    public function messages()
    {
        return [
            'service_type.required'           => 'Service Field is Required.',
            'service_type.string'             => 'Service Field should be string.',
            'service_type.max'                => 'Service Field should not be maximum 30 Character.',
            'service_title.required'         => 'Service Title is Required.',
            'service_title.string'           => 'Service Title should be string.',
            'service_title.max'              => 'Service Title should not be maximum 30 Character.',
            'description.required'            => 'Description Field is Required.',
            'description.numeric'             => 'Description Field should be string.',
            'description.min'                 => 'Service Field should not be minimum 30 Character.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // throw new HttpResponseException();
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'type'   => "validation",
            'message' => $validator->errors(),
        ]));
    }
}
