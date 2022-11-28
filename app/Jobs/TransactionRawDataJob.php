<?php

namespace App\Jobs;

use App\Services\AuctionInfoService;
use App\Services\RankingService;
use App\Traits\ApiBscScanTransaction;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TransactionRawDataJob implements ShouldQueue
{
    use ApiBscScanTransaction, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $transactions;

    protected $auctionInfoService;

    protected $rankingService;

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
        // not empty transactions
        if (!empty($this->transactions)) {
            foreach ($this->transactions as $transaction) {
                $data = $this->dataConfig($transaction['contractAddress']);
                //value must greater than 0 and transaction to must be company wallet
                if (!empty($this->checkAmountByRawData($transaction, $data))) {
                    // convert amount
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
                    if (!$this->countTransactionHistory) {
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
                    info(
                        '[SUCCESS] Update ranking for: '
                            . $transaction['hash']
                    );
                }
            }
        }
    }
}