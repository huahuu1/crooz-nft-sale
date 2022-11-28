<?php

namespace App\Jobs;

use App\Services\AuctionInfoService;
use App\Services\RankingService;
use App\Traits\ApiBscScanTransaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateRankingJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use ApiBscScanTransaction;

    protected $transactions;

    protected $transactionHistory;

    protected $rankingService;

    protected $auctionInfoService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($transactions, $transactionHistory)
    {
        $this->transactions = $transactions;
        $this->transactionHistory = $transactionHistory;
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
            $rawData = collect([]);
            foreach ($this->transactions as $transaction) {
                $data = $this->dataConfig($transaction['contractAddress']);
                //value must greater than 0 and transaction to must be company wallet
                if ($transaction['value'] > 0 && strtolower($transaction['to']) == strtolower($data['destination_address'])) {
                    $value = $this->convertAmount((int)$transaction['tokenDecimal'], $transaction['value']);
                    $timeStamp = Carbon::createFromTimestamp($transaction['timeStamp'])->format('Y-m-d H:i:s');
                    //insert data to transaction_raw_data
                    $this->rankingService->createTransactionRawData(
                        $data['chain'],
                        $transaction['hash'],
                        $transaction['from'],
                        $transaction['to'],
                        $data['token'],
                        $value,
                        $timeStamp
                    );


                    $rawData->push([
                        'wallet_address' => $transaction['from'],
                        'tx_hash' => $transaction['hash'],
                        'value' => $value
                    ]);
                    //insert data to transaction_rankings
                    // $this->rankingService->createTransactionRanking(
                    //     $transaction['from'],
                    //     $value
                    // );
                    if (!$this->transactionHistory) {
                        //insert data to transaction_histories
                        $this->rankingService->createTransactionHistory(
                            $data['chain'],
                            $transaction['hash'],
                            $transaction['from'],
                            $transaction['to'],
                            $data['token'],
                            $value,
                            $timeStamp
                        );
                    }
                    // Log::info(
                    //     '[SUCCESS] Update ranking for: '
                    //         . $transaction['hash']
                    // );
                }
            }
            $this->insertDataRanking($rawData);
        } catch (Exception $e) {
            Log::error($e);
        }
    }

    public function insertDataRanking($rawData)
    {
        $newRawData = collect([]);
        // foreach ($rawData as $value) {
        //     $newRawData->push($value);
        // }
        // $test = $rawData->groupBy('wallet_address');

        // $test = $rawData->groupBy('wallet_address')->flatMap(function ($items) {

        //     $quantity = $items->sum('value');

        //     return $items->map(function ($item) use ($quantity) {

        //         $item->quantity = $quantity;

        //         return $item;

        //     });
        // });
        $test = $rawData->mapToGroups(function ($item) {
            return [
                $item['wallet_address'] => $item['value']
            ];
         })->map->sum();


         info($test->wallet_address);
        // foreach ($test as $value) {
        //     # code...
        //     info($value);
        // }
        // foreach ($rawData->all() as $value) {
        //     $newRawData->push($value);
        // }


        // $test = $rawData->flatten(1);
        // info($test->values()->all());
        // $rawData->each(function ($item) use ($rankingData) {
        //     if ($auctions->contains('tx_hash', $item['hash']) === false && $auctions->where('confirmations', '>=', (string)24)) {
        //         $newAuctionHistories->push($item);
        //     }
        // });
    }
}
