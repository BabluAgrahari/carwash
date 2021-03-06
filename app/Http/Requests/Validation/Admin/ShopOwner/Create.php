<?php

namespace App\Http\Requests\Validation\Admin\ShopOwner;

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
            'business_name'     => 'required|string|min:2|max:30',
            'business_email'    => 'required|email|min:2|max:155|unique:users',
            'mobile'            => 'required|numeric|digits:10|unique:shop_owners',
            'phone'             => 'required|numeric|digits:10|unique:shop_owners',
            'city'              => 'required|numeric|min:2|max:50',
            'state'             => 'required|numeric|min:2|max:50',
            'pincode'           => 'required|numeric|digits:10',
            'country'           => 'required|numeric|min:2|max:30',
            'address'           => 'nullable|string|max:1000',
            'description'       => 'nullable|string|max:1000',
            'store_status'      => 'required',
            'verified_store'    => 'required',
            'whatsapp_no'       => 'required|numeric|digits:10|unique:shop_owners',
            'password'          => 'required|max:16|min:6',
            'gstin_no'          => 'nullable|min:2|max:50',
            // 'bank_details.*.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // throw new HttpResponseException();
        throw new HttpResponseException(response()->json([
            'success' => 'error',
            'type'   => "validation",
            'message' => $validator->errors(),
        ]));
    }
}
