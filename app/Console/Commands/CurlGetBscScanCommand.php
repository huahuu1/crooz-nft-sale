<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CurlGetBscScanCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:bsc_scan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $apiKey = config('defines.api.bsc.api_key');
        $apiUrl = config('defines.api.bsc.url');
        try {
            //code...
            $url  = 'https://api-testnet.bscscan.com/api?module=proxy&action=eth_getTransactionByHash&txhash=0xde2ed71997dd8cd7fedf4b4285906b34578b5c62332ae38fd540e5b34043ab23&apikey=G7HAM1MRFHGKUQV5QIH5VJJ28E52YZYNVM';
            $data = $this->getUrlContent($url);
            Log::info("CurlGetBscScanCommand--info::". $data);
        } catch (Exception $error) {
            Log::error("CurlGetBscScanCommand--error::". json_encode($error));
        }
    }

     /**
     * get website content with url by curl
     */
    public function getUrlContent($url): String
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; Etherscan PHP API; ' . php_uname('a') . '; PHP/' . phpversion() . ')');
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_VERBOSE, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10000);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
