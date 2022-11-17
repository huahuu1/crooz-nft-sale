<?php

namespace App\Jobs;

use App\Models\GachaTicket;
use App\Models\NftAuctionHistory;
use App\Models\NftAuctionInfo;
use App\Services\AuctionInfoService;
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

    protected $auctionNftService;

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
        $this->auctionNftService = new AuctionNftService();
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
            $packageInfo = $this->transaction->package;
            $nftQuantity = $this->transaction->package->reward->nft_quantity;
            //case user deposits amount not equal with package price
            if ($this->transaction->amount != $packageInfo->price) {
                // convert total ticket
                $ticketNumber = floor($this->transaction->amount / $packageInfo->unit_price);
                $nftQuantity = $ticketNumber;
            }
            //case user deposits amount equal with package price
            if ($this->transaction->amount === $packageInfo->price) {
                // convert total ticket
                $ticketNumber = $this->transaction->package->reward->ticket_quantity;
            }

            if ($ticketNumber > 0) {
                // get gachaTicket our current user
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
                for ($i = 0; $i < $nftQuantity; $i++) {
                    $this->auctionNftService->createNftAuction(
                        $this->transaction->user->wallet_address,
                        $this->transaction->package->reward->nft_id,
                        $this->transaction->package->reward->nft_delivery_id,
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
