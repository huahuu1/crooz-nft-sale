<?php

namespace App\Jobs;

use App\Models\GachaTicket;
use App\Models\NftAuctionHistory;
use App\Services\AuctionNftService;
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

    protected $auctionInfoService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($transaction)
    {
        $this->transaction = $transaction;
        $this->ticketService = new TicketService();
        $this->auctionInfoService = new AuctionNftService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // convert total ticket
            $ticketNumber = floor($this->transaction->amount / config('defines.amount_ticket'));
            if ($ticketNumber > 0) {
                // get gaChaTicket our current user
                $isGachaTicket = $this->ticketService->hasGachaInfoByUserId($this->transaction->user_id);
                if (!$isGachaTicket) {
                    // create gachaTicket
                    $this->ticketService->createGachaTicketData(
                        $this->transaction->user_id,
                        $ticketNumber,
                        GachaTicket::PAID_TICKET
                    );
                } else {
                    // update gaChaTicket form user_id and paid ticket
                    $userTicket = $this->ticketService->getGachaTicketByUserIdAndType(
                        $this->transaction->user_id,
                        GachaTicket::PAID_TICKET
                    );
                    $userTicket->total_ticket += $ticketNumber;
                    $userTicket->remain_ticket += $ticketNumber;
                    $userTicket->update();
                }
                // create nft auction of user by ticket number
                for ($i = 0; $i < $ticketNumber; $i++) {
                    $this->auctionInfoService->createNftAuction(
                        $this->transaction->user->wallet_address,
                        7,
                        7,
                        1
                    );
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
