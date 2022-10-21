<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'mail' => 'required|email|unique:users,mail',
            'status' => 'required',
            'password' => 'required|confirmed',
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
            'mail.required' => __('requestValidate.email.required'),
            'mail.invalid' => __('requestValidate.email.invalid'),
            'unique' => __('requestValidate.mail.unique'),
            'status.required' => __('requestValidate.status.required'),
            'password.required' => __('requestValidate.password.required'),
            'password_confirm' => __('requestValidate.password_confirm'),
        ];
    }
}
