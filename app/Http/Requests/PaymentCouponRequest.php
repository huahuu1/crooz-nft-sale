<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PaymentCouponRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'token_id' => ['required', 'numeric', 'exists:token_masters,id'],
            'wallet_address' => ['required', 'regex:' . config('regex.wallet_address', 'exists:users,wallet_address')],
            'auction_id' => ['required', 'numeric', 'exists:nft_auction_infos,id'],
            'package_id' => ['required', 'numeric', 'exists:nft_auction_packages,id'],
            'amount' => ['required', 'numeric']
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
            'token_id' => [
                'required' => __('requestValidate.token_id.required'),
                'numeric' => __('requestValidate.token_id.numeric'),
                'exists' => __('requestValidate.token_id.exists'),
            ],
            'wallet_address' => [
                'required' => __('requestValidate.wallet_address.required'),
                'regex' => __('requestValidate.wallet_address.regex'),
            ],
            'auction_id' => [
                'required' => __('requestValidate.auction_id.required'),
                'numeric' => __('requestValidate.auction_id.numeric'),
                'exists' => __('requestValidate.auction_id.exists'),
            ],
            'package_id' => [
                'required' => __('requestValidate.package_id.required'),
                'numeric' => __('requestValidate.package_id.numeric'),
                'exists' => __('requestValidate.package_id.exists'),
            ],
            'amount' => [
                'required' => __('requestValidate.amount.required'),
                'numeric' => __('requestValidate.amount.numeric'),
            ],

        ];
    }
}
