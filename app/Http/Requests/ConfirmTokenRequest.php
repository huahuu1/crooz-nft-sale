<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmTokenRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'token_validate' => 'required|numeric|digits:6',
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
            'token_validate.required' => __('requestValidate.token_validate.required'),
            'token_validate.numeric' => __('requestValidate.token_validate.numeric'),
            'token_validate.digits' => __('requestValidate.token_validate.digits'),
            'wallet_address.required' => __('requestValidate.wallet_address.required'),
            'wallet_address.regex' => __('requestValidate.wallet_address.regex'),
        ];
    }
}
