<?php

namespace App\Traits;

trait CheckTransactionWithApiScan
{
    /**
     * @param $network
     * @return collection
     */
    public function configContractWallet($network)
    {
        switch ($network) {
            case 'ETHERS':
                $contract_wallet = config('defines.api.eth.contract_wallet_usdt');
                break;
            case 'BSC':
                $contract_wallet = config('defines.api.bsc.contract_wallet_usdt');
                break;
        }
        return $contract_wallet;
    }

    /**
     * @param $network
     * @return collection
     */
    public function configSuccessBlockCount($network)
    {
        switch ($network) {
            case 'ETHERS':
                $successBlockCount = config('defines.api.eth.block_count');
                break;
            case 'BSC':
                $successBlockCount = config('defines.api.bsc.block_count');
                break;
        }
        return $successBlockCount;
    }

    /**
     * @param $network
     * @return collection
     */
    public function configNetWork($network)
    {
        $apiKey = config('defines.api.bsc.api_key');
        $baseUri = config('defines.api.bsc.url');
        switch ($network) {
            case 'ETHERS':
                $baseUri = config('defines.api.eth.url');
                $apiKey = config('defines.api.eth.api_key');
                break;
            case 'BSC':
                $baseUri = config('defines.api.bsc.url');
                break;
        }
        return collect([
            'api_key' => $apiKey,
            'base_uri' => $baseUri,
        ]);
    }

    /**
     * Check Transaction With API Scan
     *
     * @param $transaction_hash
     * @return collection
     */
    public function checkWithApiScan($transaction_hash)
    {
        //get config network
        $configNetwork = $this->configNetWork(config('defines.network'));
        //get block of the transaction
        $responseData = $this->getTransactionByHash($transaction_hash, $configNetwork['base_uri'], $configNetwork['api_key']);
        if (! empty($responseData['result'])) {
            $transactionBlockNumber = $responseData['result']['blockNumber'];
            //get current block
            $getCurrentBlockNumber = $this->getBlockNumber($configNetwork['base_uri'], $configNetwork['api_key']);
            if(!empty($getCurrentBlockNumber['result'])){
                $blockCount = hexdec($getCurrentBlockNumber['result']) - hexdec($transactionBlockNumber);
            }
        }
        //get transaction status
        $transactionStatus = $this->getTransactionReceiptStatus($transaction_hash, $configNetwork['base_uri'], $configNetwork['api_key']);

        return collect([
            'response' => $responseData,
            'block_count' => $blockCount ?? 0,
            'transaction_status' => $transactionStatus,
        ]);
    }
}
