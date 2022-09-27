<?php

namespace App\Traits;

trait CheckTransactionWithApiScan
{
    /**
     * @param $network
     * @return mixed|\Illuminate\Config\Repository
     */
    public function configContractWallet($network)
    {
        switch ($network) {
            case 'ETHERS':
                return config('defines.api.eth.contract_wallet_usdt');
            case 'BSC':
                return config('defines.api.bsc.contract_wallet_usdt');
        }
    }

    /**
     * @param $network
     * @return mixed|\Illuminate\Config\Repository
     */
    public function configSuccessBlockCount($network)
    {
        switch ($network) {
            case 'ETHERS':
                return config('defines.api.eth.block_count');
            case 'BSC':
                return config('defines.api.bsc.block_count');
        }
    }

    /**
     * @param $network
     * @return mixed|\Illuminate\Config\Repository
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
     * @return \Illuminate\Support\Collection
     */
    public function checkWithApiScan($transaction_hash)
    {
        //get config network
        $configNetwork = $this->configNetWork(config('defines.network'));
        //get block of the transaction
        $responseData = $this->getTransactionByHash(
            $transaction_hash,
            $configNetwork['base_uri'],
            $configNetwork['api_key']
        );
        if (! empty($responseData['result'])) {
            $transactionBlockNumber = $responseData['result']['blockNumber'];
            //get current block
            $getCurrentBlockNumber = $this->getBlockNumber(
                $configNetwork['base_uri'],
                $configNetwork['api_key']
            );
            if (!empty($getCurrentBlockNumber['result'])) {
                $blockCount = hexdec($getCurrentBlockNumber['result']) - hexdec($transactionBlockNumber);
            }
        }
        //get transaction status
        $transactionStatus = $this->getTransactionReceiptStatus(
            $transaction_hash,
            $configNetwork['base_uri'],
            $configNetwork['api_key']
        );

        return collect([
            'response' => $responseData,
            'block_count' => $blockCount ?? 0,
            'transaction_status' => $transactionStatus,
        ]);
    }
}
