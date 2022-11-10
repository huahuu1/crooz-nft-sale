<?php

namespace App\Jobs;

use App\Models\GachaTicket;
use App\Models\NftAuctionHistory;
use App\Services\TicketService;
use App\Traits\ApiScanTransaction;
use App\Traits\CheckTransactionWithApiScan;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DistributeTicketJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $transaction;

    protected $ticketService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($transaction)
    {
        $this->transaction = $transaction;
        $this->ticketService = new TicketService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $ticketNumber = floor($this->transaction->amount / 200);
            if ($ticketNumber > 0) {
                $isGachaTicket = $this->ticketService->hasGachaInfoByUserId($this->transaction->user_id);
                if (!$isGachaTicket) {
                    $this->ticketService->createGachaTicketData(
                        $this->transaction->user_id,
                        $ticketNumber
                    );
                    $this->transaction->status = NftAuctionHistory::SUCCESS_STATUS;
                    $this->transaction->update();
                } else {
                    $userTicket = $this->ticketService->getGachaTicketByUserIdAndType(
                        $this->transaction->user_id,
                        GachaTicket::PAID_TICKET
                    );
                    $userTicket->total_ticket += $ticketNumber;
                    $userTicket->remain_ticket += $ticketNumber;
                    $userTicket->update();

                    $this->transaction->status = NftAuctionHistory::SUCCESS_STATUS;
                    $this->transaction->update();
                }
            }
            Log::info(
                '[SUCCESS] Check Status Nft Auction for: '
                . $this->transaction->id . ' ('
                . substr($this->transaction->tx_hash, 0, 10)
                . ')'
            );
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
