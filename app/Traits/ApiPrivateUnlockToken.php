<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait ApiPrivateUnlockToken
{
    /**
     * @param $baseUri, $apiKey, $networkId, $to, $amount, $tokenType
     * @return $response
     */
    public function tokenTransfers($baseUri, $apiKey, $networkId, $to, $amount, $tokenType)
    {
        try {
            $params = [
                'api_key' => $apiKey,
                'network_id' => $networkId,
                'to' => $to,
                'amount' => $amount,
                'token_type' => (string) $tokenType
            ];

            $response = Http::withOptions([
                'proxy' => $baseUri
            ])
            ->withBody(json_encode($params), 'application/json')
            ->post($baseUri . '/token/transfer');

            if ($response->status() == 400) {
                return $response->body();
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error($e);
        }
    }

    /**
     * @param $baseUri, $apiKey, $networkId, $txHashes
     * @return $response
     */
    public function checkTransactionStatuses($baseUri, $apiKey, $networkId, $txHashes)
    {
        try {
            $params = [
                'api_key' => $apiKey,
                'network_id' => $networkId,
                'tx_hashes' => $txHashes,
            ];
            $response = Http::withOptions([
                'proxy' => $baseUri
            ])
            ->withBody(json_encode($params), 'application/json')
            ->post($baseUri . '/transaction/statuses');

            if ($response->status() == 400) {
                return $response->body();
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error($e);
        }
    }
}
