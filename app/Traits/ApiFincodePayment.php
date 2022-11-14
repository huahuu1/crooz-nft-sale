<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait ApiFincodePayment
{
    /**
     * @param $baseUri, $apiKey, $networkId, $to, $amount, $tokenType
     * @return $response
     */
    public function payment($baseUri, $bearerToken, $id, $payType, $accessId, $method, $paymentToken)
    {
        try {
            $params = [
                'id' => $id,
                'pay_type' => $payType,
                'access_id' => $accessId,
                'method' => $method,
                'token' => (string) $paymentToken
            ];

            $response = Http::withToken($bearerToken)
            ->withBody(json_encode($params), 'application/json')
            ->put($baseUri . '/payments/' . $id);

            return [
                'response' => $response->json(),
                'statusCode' => $response->getStatusCode()
            ];
        } catch (\Exception $e) {
            Log::error($e);
        }
    }
}
