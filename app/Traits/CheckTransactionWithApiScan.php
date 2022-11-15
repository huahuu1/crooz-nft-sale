<?php

namespace App\Traits;

trait CheckTransactionWithApiScan
{
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
        $networkEthIds = config('defines.api.eth.ids');
        $networkBscIds = config('defines.api.bsc.ids');

        if (in_array($network, $networkEthIds)) {
            $baseUri = config('defines.api.eth.url');
            $apiKey = config('defines.api.eth.api_key');
        }

        if (in_array($network, $networkBscIds)) {
            $baseUri = config('defines.api.bsc.url');
            $apiKey = config('defines.api.bsc.api_key');
        }

        return collect([
            'api_key' => $apiKey,
            'base_uri' => $baseUri
        ]);
    }

    /**
     * Check Transaction With API Scan
     *
     * @param $transaction_hash
     * @return \Illuminate\Support\Collection
     */
    public function checkWithApiScan($transaction_hash, $network)
    {
        //get config network
        $configNetwork = $this->configNetWork($network);
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

    /**
     * Check Transaction With API Scan
     *
     * @param $txHash
     * @return bool
     */
    public function isTransactionExisted($txHash, $network)
    {
        //get config network
        $configNetwork = $this->configNetWork($network);
        //get block of the transaction
        $responseData = $this->getTransactionByHash(
            $txHash,
            $configNetwork['base_uri'],
            $configNetwork['api_key']
        );
        return !empty($responseData['result']) ? true : false;
    }
}
