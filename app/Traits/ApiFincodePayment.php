<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait ApiFincodePayment
{
    /**
     * @param $baseUri, $bearerToken, $payType, $jobCode, $amount
     * @return $response
     */
    public function registerPaymentCredit($baseUri, $bearerToken, $payType, $jobCode, $amount)
    {
        try {
            $params = [
                'pay_type' => $payType,
                'job_code' => $jobCode,
                'amount' => $amount
            ];

            $response = Http::withToken($bearerToken)
            ->withBody(json_encode($params), 'application/json')
            ->post($baseUri . '/payments');

            return [
                'response' => $response->json(),
                'statusCode' => $response->getStatusCode()
            ];
        } catch (\Exception $e) {
            Log::error($e);
        }
    }

    /**
     * @param $baseUri, $bearerToken, $id, $payType, $accessId, $method, $paymentToken
     * @return $response
     */
    public function completePaymentCredit($baseUri, $bearerToken, $id, $payType, $accessId, $method, $paymentToken)
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
