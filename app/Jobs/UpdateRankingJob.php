<?php

namespace App\Jobs;

use App\Services\AuctionInfoService;
use App\Services\RankingService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use ZipStream\Bigint;

class UpdateRankingJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $transaction;

    protected $rankingService;

    protected $auctionInfoService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($transaction)
    {
        $this->transaction = $transaction;
        $this->rankingService = new RankingService();
        $this->auctionInfoService = new AuctionInfoService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // info($this->transaction);
            $decimal = (int)$this->transaction['tokenDecimal'];
            $divisor = pow(10, $decimal);
            $value = number_format($this->transaction['value'] / $divisor, 6, '.', '');
            $data = $this->dataConfig($this->transaction['contractAddress']);
            //insert data to transaction_raw_datas
            $this->rankingService->createTransactionRawData(
                $data['chain'],
                $this->transaction['hash'],
                $this->transaction['from'],
                $this->transaction['to'],
                $data['token'],
                $value
            );
            //insert data to transaction_histories
            $this->rankingService->createTransactionHistory(
                $data['chain'],
                $this->transaction['hash'],
                $this->transaction['from'],
                $this->transaction['to'],
                $data['token'],
                $value
            );
            //insert data to transaction_rankings
            $this->rankingService->createTransactionRanking(
                $this->transaction['from'],
                $this->transaction['hash'],
                $value
            );
            Log::info(
                '[SUCCESS] Update ranking for: '
                    . $this->transaction['hash']
            );
        } catch (Exception $e) {
            Log::error($e);
        }
    }

    public function dataConfig($contractAddress)
    {
        $auctionNetworks = $this->auctionInfoService->infoNftAuctionById(3)->auctionNetwork;
        foreach ($auctionNetworks[0]->type as $auctionNetwork) {
            if (strtolower($auctionNetwork->contract_wallet) == strtolower($contractAddress) && $auctionNetwork->code == 'BUSD') {
                return [
                    'chain' => 'bsc-BUSD',
                    'token' => 'BUSD',
                ];
            }

            if (strtolower($auctionNetwork->contract_wallet) == strtolower($contractAddress) && $auctionNetwork->code == 'USDT') {
                return [
                    'chain' => 'bsc-BSC-USD',
                    'token' => 'USDT',
                ];
            }
        }
        // foreach ($tokenTypes as $key => $tokenType) {
        //     # code...
        //     info($tokenType);
        // }
        // switch ($contractAddress) {
        //     case '0xe9e7cea3dedca5984780bafc599bd69add087d56':
        //         return 'bsc-BUSD';
        //         break;
        //     case '0x55d398326f99059ff775485246999027b3197955':
        //         return 'bsc-BSC-USD';
        //         break;
        //     default:
        //         return '';
        //         break;
        // }
    }
}
