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
                        $fail(__('requestValidate.old_password.not_match'));
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
            'password.required' => __('requestValidate.password.required'),
            'password.min' => __('requestValidate.password.min'),
            'password.max' => __('requestValidate.password.max'),
            'password.regex' => __('requestValidate.password.regex'),
            'password_confirm' => __('requestValidate.password_confirm'),
            'old_password.required' => __('requestValidate.old_password.required'),
        ];
    }
}
