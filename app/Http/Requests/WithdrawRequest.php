<?php

namespace App\Http\Requests;

use App\Models\UserBalance;
use Illuminate\Foundation\Http\FormRequest;

class WithdrawRequest extends FormRequest
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
            'wallet_address' => [
                'required',
                'regex:'. config('regex.wallet_address'),
            ],
        ];
    }
}
