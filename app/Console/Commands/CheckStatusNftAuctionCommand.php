<?php

namespace App\Console\Commands;

use App\Models\NftAuctionHistory;
use App\Models\TokenSaleHistory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CheckStatusNftAuctionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:nft-auction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Transactions NFT Auction Command';

    protected $transactions;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->transactions = new NftAuctionHistory();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return $this->validateTransactions();
    }

    /**
     * Validate Metamask Transaction
     *
     * @return void
     */
    public function validateTransactions()
    {
        $company_wallet = env('COMPANY_WALLET');

        $transactions = $this->transactions->pendingNftAuctionTransactions();

        foreach ($transactions as $transaction) {
            //get transaction information from etherscan
            $response = $this->checkWithEtherScan($transaction->tx_hash);
            //validate response
            if ($response && array_key_exists('result', $response) && $response['result'] != null) {
                $result = $response['result'];
                //validate transaction destination with our account
                if (strtolower($result['to']) == strtolower($company_wallet)) {
                    // Update Transaction As Success
                    $transaction->status = 'CLOSE';
                    $transaction->update();
                }
            } else {
                // Update Transaction As Canceled
                $transaction->status = 'FORCECLOSE';
                $transaction->update();
            }
        }
    }

    /**
     * Check Transaction With Ether Scan
     *
     * @param  mixed $transaction_hash
     * @return mixed
     */
    public function checkWithEtherScan($transaction_hash)
    {
        $api_key = env('ETHERSCAN_API_KEY'); // api from from Etherscan.io
        $test_network = "https://api-ropsten.etherscan.io"; //use in testnet
        $main_network = "https://etherscan.io"; //use in mainnet
        $response = Http::get(
            $test_network
            . "/api/?module=proxy&action=eth_getTransactionByHash&txhash="
            . $transaction_hash
            . '&apikey=' . $api_key);
        return $response->json();
    }
}
