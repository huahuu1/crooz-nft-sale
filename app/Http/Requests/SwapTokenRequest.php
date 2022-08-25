<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SwapTokenRequest extends FormRequest
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
            'token_id_from' => 'required|different:token_id_to',
            'token_id_to' => 'required',
            'wallet_address' => [
                'required',
                'regex:'.config('regex.wallet_address'),
            ],
        ];
    }
}
