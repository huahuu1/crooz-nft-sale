<?php

namespace App\Console\Commands;

use DOMDocument;
use DOMXPath;
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
            $data = $this->cloudFlareBypass($url);
            Log::info("CurlGetBscScanCommand--info--2::" . $this->OpenURLcloudflare($url));
            Log::info("CurlGetBscScanCommand--info::" . $data);
        } catch (Exception $error) {
            Log::error("CurlGetBscScanCommand--error::" . json_encode($error));
        }
    }

    /**
     * get website content with url by curl
     */
    public function OpenURLcloudflare($url)
    {
        //get cloudflare ChallengeForm
        $data = $this->OpenURL($url);
        preg_match('/<form id="ChallengeForm" .+ name="act" value="(.+)".+name="jschl_vc" value="(.+)".+<\/form>.+jschl_answer.+\(([0-9\+\-\*]+)\);/Uis', $data, $out);
        if (count($out) > 0) {
            eval("\$jschl_answer=$out[3];");
            $post['act']            = $out[1];
            $post['jschl_vc']        = $out[2];
            $post['jschl_answer']    = $jschl_answer;
            //send jschl_answer to the website
            $data = $this->OpenURL($url, $post);
        }
        return ($data);
    }

    public function OpenURL($url, $post = array())
    {
        $headers[] = 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:13.0) Gecko/20100101 Firefox/13.0.1';
        $headers[] = 'Accept: application/json, text/javascript, */*; q=0.01';
        $headers[] = 'Accept-Language: ar,en;q=0.5';
        $headers[] = 'Connection: keep-alive';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        if (count($post) > 0) {
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/curl.cookie');
        curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/curl.cookie');
        $data = curl_exec($ch);
        return ($data);
    }


    public function cloudFlareBypass($url)
    {

        $userAgent = "Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/W.X.Y.Z‡ Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)";

        $ct = curl_init();

        curl_setopt_array($ct, array(
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array("X-Requested-With: XMLHttpRequest"),
            CURLOPT_REFERER => $url,
            CURLOPT_USERAGENT =>  $userAgent,
            CURLOPT_HEADER => false,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => 'schn=csrf'
        ));

        $html = curl_exec($ct);

        $docHtml = new DOMDocument();
        @$docHtml->loadHTML($html);
        $xpath = new DOMXPath($docHtml);

        // Auth
        if (isset($xpath->query("//input[@name='r']/@value")->item(0)->textContent)) {

            $action = $url . $xpath->query("//form/@action")->item(0)->textContent;
            $r = $xpath->query("//input[@name='r']/@value")->item(0)->textContent;
            $jsChlVc = $xpath->query("//input[@name='jschl_vc']/@value")->item(0)->textContent;
            $pass = $xpath->query("//input[@name='pass']/@value")->item(0)->textContent;

            // Generate curl post data
            $post_data = array(
                'r' => $r,
                'jschl_vc' => $jsChlVc,
                'pass' => $pass,
                'jschl_answer' => ''
            );

            curl_close($ct); // Close curl

            return $html;

            $ct = curl_init();

            // Post cloudflare auth parameters
            curl_setopt_array($ct, array(
                CURLOPT_HTTPHEADER => array(
                    'Accept: application/json, text/javascript, */*; q=0.01',
                    'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
                    'Referer: ' . $url,
                    'Origin: ' . $url,
                    'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
                    'X-Requested-With: XMLHttpRequest'
                ),
                CURLOPT_URL => $action,
                CURLOPT_REFERER => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_USERAGENT => $userAgent,
                CURLOPT_POSTFIELDS => http_build_query($post_data)

            ));

            curl_exec($ct);

            curl_close($ct); // Close curl

        } else {

            // Already auth
            return $html;
        }
    }
}
