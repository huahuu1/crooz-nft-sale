<?php

namespace App\Traits;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;

trait ApiScanTransaction
{
    /**
     * @param $txHash, $baseUri, $apiKey
     * @return $response
     */
    public function getTransactionByHash($txHash, $baseUri, $apiKey)
    {
        try {
            $client = new HttpClient(
                [
                    'base_uri' => $baseUri,
                    'headers' => [
                        // 'User-Agent' => 'Mozilla/4.0 (compatible; Etherscan PHP API; ' . php_uname('a') . '; PHP/' . phpversion() . ')',
                        // 'Accept' => 'application/json'
                    ]
                ]
            );
            $params = [
                'query' => [
                    'module' => 'proxy',
                    'action' => 'eth_getTransactionByHash',
                    'txhash' => $txHash,
                    'apikey' => $apiKey,
                ],
            ];
            $uri = '?';
            $response = $client->request(
                'GET',
                $uri,
                $params
            );
            return json_decode($response->getBody()->getContents(), true);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            Log::info($response);
            Log::info($responseBodyAsString);
        }
    }

    /**
     * @param $baseUri, $apiKey
     * @return $response
     */
    public function getBlockNumber($baseUri, $apiKey)
    {
        try {
            $client = new HttpClient(
                [
                    'base_uri' => $baseUri,
                    'headers' => [],
                ]
            );
            $params = [
                'query' => [
                    'module' => "proxy",
                    'action' => "eth_blockNumber",
                    'apikey' => $apiKey,
                ],
            ];
            $uri = '?';
            $response = $client->request(
                'GET',
                $uri,
                $params
            );
            return json_decode($response->getBody()->getContents(), true);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            Log::info($response);
            Log::info($responseBodyAsString);
        }
    }

    /**
     * @param $txHash, $baseUri, $apiKey
     * @return $response
     */
    public function getTransactionReceiptStatus($txHash, $baseUri, $apiKey)
    {
        try {
            $client = new HttpClient(
                [
                    'base_uri' => $baseUri,
                    'headers' => [],
                ]
            );
            $params = [
                'query' => [
                    'module' => "transaction",
                    'action' => "gettxreceiptstatus",
                    'txhash' => $txHash,
                    'apikey' => $apiKey,
                ],
            ];
            $uri = '?';
            $response = $client->request(
                'GET',
                $uri,
                $params
            );
            return json_decode($response->getBody()->getContents(), true);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            Log::info($response);
            Log::info($responseBodyAsString);
        }
    }
}
