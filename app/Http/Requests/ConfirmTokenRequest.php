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
            'wallet_address' => 'required|regex:'. config('regex.wallet_address'),
        ];
    }
}
