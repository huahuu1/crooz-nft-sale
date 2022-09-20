<?php

namespace App\Traits;

use DOMDocument;
use DOMXPath;
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
            $params = [
                'module' => 'proxy',
                'action' => 'eth_getTransactionByHash',
                'txhash' => $txHash,
                'apikey' => $apiKey,
            ];

            $url = $baseUri.'?'.http_build_query($params, '&');
            $response = $this->cloudFlareBypass($url);

            return json_decode($response, true);
        } catch (\Exception $e) {
            Log::error('getBlockNumber::'.json_encode($e));
        }
    }

    /**
     * @param $baseUri, $apiKey
     * @return $response
     */
    public function getBlockNumber($baseUri, $apiKey)
    {
        try {
            $params = [
                'module' => 'proxy',
                'action' => 'eth_blockNumber',
                'apikey' => $apiKey,
            ];

            $url = $baseUri.'?'.http_build_query($params, '&');
            $response = $this->cloudFlareBypass($url);

            return json_decode($response, true);
        } catch (\Exception $e) {
            Log::error('getBlockNumber::'.json_encode($e));
        }
    }

    /**
     * @param $txHash, $baseUri, $apiKey
     * @return $response
     */
    public function getTransactionReceiptStatus($txHash, $baseUri, $apiKey)
    {
        try {
            $params = [
                'module' => 'transaction',
                'action' => 'gettxreceiptstatus',
                'txhash' => $txHash,
                'apikey' => $apiKey,
            ];

            $url = $baseUri.'?'.http_build_query($params, '&');
            $response = $this->cloudFlareBypass($url);

            return json_decode($response, true);
        } catch (\Exception $e) {
            Log::error('getTransactionReceiptStatus::'.json_encode($e));
        }
    }

    /**
     * cloudFlareBypass function
     *
     * @param [string] $url
     */
    public function cloudFlareBypass($url)
    {
        try {
            $userAgent = 'Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/W.X.Y.Z‡ Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)';
            // init curl
            $ct = curl_init();
            curl_setopt_array($ct, [
                CURLOPT_URL => $url,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => ['X-Requested-With: XMLHttpRequest'],
                CURLOPT_REFERER => $url,
                CURLOPT_USERAGENT => $userAgent,
                CURLOPT_HEADER => false,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => 'schn=csrf',
            ]);

            $html = curl_exec($ct);
            // load dom html
            $docHtml = new DOMDocument();
            @$docHtml->loadHTML($html);
            $xpath = new DOMXPath($docHtml);

            // Auth or Already auth
            if (isset($xpath->query("//input[@name='r']/@value")->item(0)->textContent)) {
                // dom action
                $action = $url.$xpath->query('//form/@action')->item(0)->textContent;
                $r = $xpath->query("//input[@name='r']/@value")->item(0)->textContent;
                $jsChlVc = $xpath->query("//input[@name='jschl_vc']/@value")->item(0)->textContent;
                $pass = $xpath->query("//input[@name='pass']/@value")->item(0)->textContent;

                // Generate curl post data
                $post_data = [
                    'r' => $r,
                    'jschl_vc' => $jsChlVc,
                    'pass' => $pass,
                    'jschl_answer' => '',
                ];

                // Post cloudflare auth parameters
                $ct = curl_init();
                curl_setopt_array($ct, [
                    CURLOPT_HTTPHEADER => [
                        'Accept: application/json, text/javascript, */*; q=0.01',
                        'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
                        'Referer: '.$url,
                        'Origin: '.$url,
                        'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
                        'X-Requested-With: XMLHttpRequest',
                    ],
                    CURLOPT_URL => $action,
                    CURLOPT_REFERER => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_USERAGENT => $userAgent,
                    CURLOPT_POSTFIELDS => http_build_query($post_data),

                ]);

                $html = curl_exec($ct);
                // Close curl
                curl_close($ct);

                return $html;
            } else {
                // Close curl
                curl_close($ct);

                return $html;
            }
        } catch (\Exception $error) {
            Log::error('Error CloudFlare Bypass::'.json_encode($error));
        }
    }
}
