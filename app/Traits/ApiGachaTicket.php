<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait ApiGachaTicket
{
    /**
     * @param $baseUri, $apiKey, $networkId, $to, $amount, $tokenType
     * @return $response
     */
    public function gachaTicket($baseUri, $walletId, $gachaId)
    {
        try {
            $params = [
                'wallet_id' => $walletId,
                'gacha_id' => $gachaId
            ];

            $response = Http::withHeaders(['accept' => 'application/json'])
            ->withBody(json_encode($params), 'application/json')
            ->post($baseUri . '/gacha-ticket-api');

            return [
                'response' => $response->json(),
                'statusCode' => $response->getStatusCode()
            ];
        } catch (\Exception $e) {
            Log::error($e);
        }
    }
}
