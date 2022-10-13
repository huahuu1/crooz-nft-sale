<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'wallet_address' => 'required|regex:'.config('regex.wallet_address'),
            'password' => [
                'required',
                'min:6',
                'max:20',
                'regex:'.config('regex.password')],
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
            'password.min' => 'Please enter a password has at least 6 digits',
            'password.max' => 'Please enter a password has 20 digits or less',
            'password.regex' => 'The password format is invalid',
            'password_confirm' => 'The password confirm and password must match',
        ];
    }
}
