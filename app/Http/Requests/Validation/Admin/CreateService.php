<?php

namespace App\Http\Requests\validation\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class CreateService extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [

            'category'          => 'required',
            'vehicle_brand'     => 'required',
            'vehicle_model'     => 'required',
            'service_type'      => 'required|string',
            'title'             => 'required|string|max:255',
            'sort_description'  => 'required|string|max:255',
            'description'       => 'required|string|max:500',
            'time_duration'     => 'required',
           // 'video'             => 'required|file|mimetypes:video/mp4',
           // 'multiple_images'   => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'service_charge'    => 'required|numeric',
            'discount'          => 'required|string',
            'gst_charges'       => 'required|string',
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
            'success' => false,
            'message' => $validator->errors(),
        ], 400));
    }
}
