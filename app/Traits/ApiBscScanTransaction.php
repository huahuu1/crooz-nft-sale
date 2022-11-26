<?php

namespace App\Traits;

use Carbon\Carbon;
use DOMDocument;
use DOMXPath;
use Illuminate\Support\Facades\Log;

trait ApiBscScanTransaction
{
    /**
     * @return $response
     */
    public function getAllTransactionsBscScan()
    {
        try {
            $baseUri = config('defines.api.bsc.url');
            $apiKey = config('defines.api.bsc.api_key');
            $auctionNetworks = $this->auctionInfoService->infoNftAuctionById(3)->auctionNetwork->values();
            $packages = $this->auctionInfoService->infoNftAuctionById(3)->packages->values();
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
            $startDate = Carbon::parse(config('defines.date_auction_start'), 'UTC')->getTimestamp();
            $endDate = Carbon::parse(config('defines.date_auction_end'), 'UTC')->getTimestamp();
            $startRankingDate = Carbon::parse(config('defines.date_auction_ranking_start'), 'UTC')->getTimestamp();
            $now = Carbon::now('UTC')->getTimestamp();
            $results = json_decode($results, true);
            if ($now >= $startRankingDate) {
                $response = collect(array_merge($results[0]['result'], $results[1]['result']))
                    ->whereBetween('timeStamp', [(string)$startDate, (string)$endDate])
                    ->where('confirmations', '>=',(string)24);
            } else {
                $response = collect(array_merge($results[0]['result'], $results[1]['result']))
                    ->whereBetween('timeStamp', [(string)$startDate, (string)$endDate]);
            }

            return json_decode($response, true);
        } catch (\Exception $e) {
            Log::error('getBlockNumber::' . json_encode($e));
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
}
