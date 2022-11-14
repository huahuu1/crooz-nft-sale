<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'amount' => 'required|numeric|gt:0',
            'wallet_address' => 'required|regex:' . config('regex.wallet_address'),
            'token_id' => 'required',
            'id' => 'required',
            'pay_type' => 'required',
            'access_id' => 'required',
            'method' => 'required',
            'token' => 'required',
            'nft_auction_id' => 'required',
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
            'amount.required' => __('requestValidate.amount.required'),
            'amount.numeric' => __('requestValidate.amount.numeric'),
            'amount.min' => __('requestValidate.amount.min'),
            'wallet_address.required' => __('requestValidate.wallet_address.required'),
            'wallet_address.regex' => __('requestValidate.wallet_address.regex'),
            'token_id.required' => __('requestValidate.token_id.required'),
        ];
    }
}
