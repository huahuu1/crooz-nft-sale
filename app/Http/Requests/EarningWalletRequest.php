<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EarningWalletRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'amount' => 'required|numeric|min:0',
            'wallet_address' => 'required',
            'tx_hash' => 'required',
        ];
    }
}
