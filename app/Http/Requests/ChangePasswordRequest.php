<?php

namespace App\Http\Requests;

use Auth;
use Hash;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
     /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'old_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail('The old password does not match');
                    }
                }
            ],
            'password' => [
                'required',
                'min:8',
                'max:16',
                'different:old_password',
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
            'password.min' => 'Please enter a password has at least 8 digits',
            'password.max' => 'Please enter a password has 16 digits or less',
            'password.regex' => 'The password format is invalid',
            'password_confirm' => 'The password confirm and password must match',
        ];
    }
}
