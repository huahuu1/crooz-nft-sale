<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WalletAddressRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'wallet_address' => 'required|regex:' . config('regex.wallet_address'),
        ];
    }

    /**
    * Get the error messages for the defined validation rules.
    *
    * @return array
    */
    public function messages()
    {
        return [
            'wallet_address.required' => __('requestValidate.wallet_address.required'),
            'wallet_address.regex' => __('requestValidate.wallet_address.regex'),
        ];
    }
}
