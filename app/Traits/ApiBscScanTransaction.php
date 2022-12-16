<?php

namespace App\Traits;

use DOMDocument;
use DOMXPath;
use Illuminate\Support\Facades\Log;

trait ApiBscScanTransaction
{
    public function retry($times, $type, $auctionId, $key) {
        $retries = $times;
        $success = false;
        do {
            $result = $this->configCallApi($type, $auctionId);
            $success = $result[$key]['status'];
            $retries--;
            sleep(60);
        } while ($retries > 0 && !$success);
        return $result;
    }

    public function callApiBscScan($auctionId)
    {
        $baseUri = config('defines.api.bsc.url');
        $apiKey = config('defines.api.bsc.api_key');
        $auctionNetworks = $this->auctionInfoService->infoNftAuctionById($auctionId)->auctionNetwork;
        $packages = $this->auctionInfoService->infoNftAuctionById($auctionId)->packages;
        $results = collect([]);
        foreach ($auctionNetworks[0]->type as $auctionNetwork) {
            foreach ($packages as $package) {
                if ($auctionNetwork->contract_wallet) {
                    $params = [
                        'module' => 'account',
                        'action' => 'tokentx',
                        'contractaddress' => $auctionNetwork->contract_wallet,
                        'address' => $package->destination_address,
                        'page' => 1,
                        'offset' => 10000,
                        'startblock' => 0,
                        'endblock' => 99999999,
                        'sort' => 'asc',
                        'apikey' => $apiKey,
                    ];
                    $url = $baseUri . '?' . http_build_query($params, '&');
                    $response = $this->cloudFlareBypass($url);
                    $results->push(json_decode($response, true));
                }
            }
        }
        return $results;
    }

    public function callApiBscScanTokenTicket()
    {
        $baseUri = config('defines.api.bsc.url');
        $apiKey = config('defines.api.bsc.api_key');
        $results = collect([]);
        $ticketContractWallet = config('defines.ticket.contract_wallet');
        $ticketDestinationAddress = config('defines.ticket.destination_address');
        $params = [
            'module' => 'account',
            'action' => 'tokentx',
            'contractaddress' => $ticketContractWallet,
            'address' => $ticketDestinationAddress,
            'page' => 1,
            'offset' => 10000,
            'startblock' => 0,
            'endblock' => 99999999,
            'sort' => 'asc',
            'apikey' => $apiKey,
        ];
        $url = $baseUri . '?' . http_build_query($params, '&');
        $response = $this->cloudFlareBypass($url);
        $results->push(json_decode($response, true));
        return $results;
    }

    /**
     * @return $response
     */
    public function getAllTransactionsBscScan($type, $auctionId)
    {
        try {
            $results = $this->configCallApi($type, $auctionId);
            $response = [];
            foreach ($results as $key => $result) {
                if ($result['status'] == '0') {
                    $results = $this->retry(3, $type, $auctionId, $key);
                    if ($result['status'] == '1') {
                        $result = $this->configCallApi($type, $auctionId);
                        $response[] = $result['result'];
                    }
                } else {
                    $response[] = $result['result'];
                }
            }
            $response = collect(array_merge([], ...$response));
            return json_decode($response, true);
        } catch (\Exception $e) {
            Log::error('getAllTransactionsBscScan::', [json_encode($e)]);
        }
    }

    /**
     * @return $response
     */
    public function configCallApi($type, $auctionId)
    {
        switch ($type) {
            case 'ticket':
                return $this->callApiBscScanTokenTicket();
            case 'transaction':
                return $this->callApiBscScan($auctionId);
        }
    }

    /**
     * cloudFlareBypass function
     *
     * @param $url
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
                $action = $url . $xpath->query('//form/@action')->item(0)->textContent;
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
                        'Referer: ' . $url,
                        'Origin: ' . $url,
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
            Log::error('Error CloudFlare Bypass::' . json_encode($error));
        }
    }

    /**
     * convert amount to token decimal and value
     *
     * @param int $tokenDecimal
     * @param string $value
     * @return int
     */
    public function convertAmount(int $tokenDecimal, $value)
    {
        $decimal = $tokenDecimal;
        $divisor = pow(10, $decimal);
        return number_format($value / $divisor, 6, '.', '');
    }
    /**
     * convert data config
     *
     * @param string $contractAddress
     * @return array
     */
    public function dataConfig($contractAddress)
    {
        $destinationAddress = $this->auctionInfoService->infoNftAuctionById(3)->packages[0]->destination_address;
        $auctionNetworks = $this->auctionInfoService->infoNftAuctionById(3)->auctionNetwork;
        foreach ($auctionNetworks[0]->type as $auctionNetwork) {
            if (strtolower($auctionNetwork->contract_wallet) == strtolower($contractAddress) && $auctionNetwork->code == 'BUSD') {
                return [
                    'chain' => 'bsc-BUSD',
                    'token' => 'BUSD',
                    'destination_address' => $destinationAddress
                ];
            }

            if (strtolower($auctionNetwork->contract_wallet) == strtolower($contractAddress) && $auctionNetwork->code == 'USDT') {
                return [
                    'chain' => 'bsc-BSC-USD',
                    'token' => 'USDT',
                    'destination_address' => $destinationAddress
                ];
            }
        }
    }

    /**
     * check Amount By Raw Data function
     *
     * @param array $transactionData
     * @param array $destinationAddress
     * @return void
     */
    public function checkAmountByRawData($transactionData, $destinationAddress)
    {
        if ($transactionData['value'] > 0 && strtolower($transactionData['to']) == strtolower($destinationAddress['destination_address'])) {
            return $transactionData;
        } else {
            return null;
        }
    }
}