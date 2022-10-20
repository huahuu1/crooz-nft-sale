<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyEmailRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'wallet_address' => 'required|regex:' . config('regex.wallet_address'),
            'password' => [
                'required',
                'min:8',
                'max:16',
                'regex:' . config('regex.password')],
            'password_confirm' => 'required|same:password'
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
            'email.required' => __('requestValidate.email.required'),
            'email.invalid' => __('requestValidate.email.invalid'),
            'wallet_address.required' => __('requestValidate.wallet_address.required'),
            'wallet_address.regex' => __('requestValidate.wallet_address.regex'),
            'password.required' => __('requestValidate.password.required'),
            'password.min' => __('requestValidate.password.min'),
            'password.max' => __('requestValidate.password.max'),
            'password.regex' => __('requestValidate.password.regex'),
            'password_confirm' => __('requestValidate.password_confirm'),
        ];
    }
}
