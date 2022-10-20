<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
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
            'password.min' => __('requestValidate.password.min'),
            'password.max' => __('requestValidate.password.max'),
            'password.regex' => __('requestValidate.password.regex'),
            'password_confirm' => __('requestValidate.password_confirm'),
        ];
    }
}
