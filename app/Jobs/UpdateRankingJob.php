<?php

namespace App\Jobs;

use App\Models\TransactionRanking;
use App\Models\TransactionRawData;
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

    protected $rankingService;

    protected $auctionInfoService;


    protected $countTransactionHistory;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($transactions, $countTransactionHistory)
    {
        $this->transactions = $transactions;
        $this->countTransactionHistory = $countTransactionHistory;
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
            // not empty transactions
            if (!empty($this->transactions)) {
                $transactionRawData = collect([]);
                TransactionRawData::truncate();
                // check date time end auction to insert ranking
                $auctionInfo = $this->auctionInfoService->infoNftAuctionById(3);
                $startDate = Carbon::parse($auctionInfo->start_date, 'UTC')->getTimestamp();
                $endDate = Carbon::parse($auctionInfo->end_date, 'UTC')->getTimestamp();
                foreach ($this->transactions->chunk(50) as $transactions) {
                    // push transactions to transactionRawData
                    $transactionRawData->push($transactions);
                    // create Transaction Raw Data
                    TransactionRawDataJob::dispatch($transactions, $this->countTransactionHistory)->onQueue(config('defines.queue.general'));
                }

                $now = Carbon::now('UTC')->getTimestamp();
                if ($now < $endDate) {
                    $transactionRawData = collect($transactionRawData->flatten(1))
                        ->whereBetween('timeStamp', [(string)$startDate, (string)$endDate]);
                    $this->insertDataRanking($transactionRawData);
                }
            }
        } catch (Exception $e) {
            Log::error($e);
        }
    }

    /**
     * insert Data Ranking
     *
     * @param array $rawData
     * @return void
     */
    public function insertDataRanking($transactionRawData)
    {
        // transaction Raw Data
        if (!empty($transactionRawData)) {
            $rawData =  collect([]);
            foreach ($transactionRawData as $rawTransaction) {
                $data = $this->dataConfig($rawTransaction['contractAddress']);
                //value must greater than 0 and transaction to must be company wallet
                if (!empty($this->checkAmountByRawData($rawTransaction, $data))) {
                    $value = $this->convertAmount((int)$rawTransaction['tokenDecimal'], $rawTransaction['value']);
                    // push to new raw data
                    $rawData->push([
                        'wallet_address' => $rawTransaction['from'],
                        'tx_hash' => $rawTransaction['hash'],
                        'value' => $value
                    ]);
                }
            }

            // map by amount sum
            $newRawData = $rawData->mapToGroups(function ($item) {
                return [
                    $item['wallet_address'] =>  $item['value']
                ];
            })->map->sum();

            // not new Raw Data empty
            if (!empty($newRawData)) {
                // truncate TransactionRanking
                TransactionRanking::truncate();
                foreach ($newRawData as $key => $value) {
                    // create ranking
                    TransactionRanking::firstOrCreate([
                        'wallet_address' => $key
                    ], [
                        'amount' => $value
                    ]);

                    info("[SUCCESS] create ranking for: " . $key);
                }
            }
        }
    }
}
