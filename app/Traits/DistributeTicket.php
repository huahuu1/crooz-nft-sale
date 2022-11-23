<?php

namespace App\Traits;

use App\Models\GachaTicket;
use Exception;
use Illuminate\Support\Facades\Log;

trait DistributeTicket
{
    /**
     * @param $transaction, $ticketService, $auctionNftService
     * @return $response
     */
    public function distributeTicket($transaction, $ticketService, $auctionNftService)
    {
        try {

            $packageInfo = $transaction->package;
            $nftQuantity = $transaction->package->reward->nft_quantity;
            $ticketNumber = 0;
            //case user deposits amount not equal with package price
            if ($transaction->amount != $packageInfo->price) {
                // convert total ticket
                $ticketNumber = floor($transaction->amount / $packageInfo->unit_price);
                $nftQuantity = $ticketNumber;
            }
            //case user deposits amount equal with package price
            if ($transaction->amount == $packageInfo->price) {
                // convert total ticket
                $ticketNumber = $transaction->package->reward->ticket_quantity;
            }

            if ($ticketNumber > 0) {
                // get gachaTicket our current user
                $isGachaTicket = $ticketService->hasGachaInfoByUserId($transaction->user_id);
                if (!$isGachaTicket) {
                    // create gacha paid ticket
                    $ticketService->createPaidGachaTicketData(
                        $transaction->user_id,
                        $ticketNumber,
                    );
                } else {
                    // update gacha ticket form user_id and paid ticket
                    $userTicket = $ticketService->getGachaTicketByUserIdAndType(
                        $transaction->user_id,
                        GachaTicket::PAID_TICKET
                    );
                    $userTicket->total_ticket += $ticketNumber;
                    $userTicket->remain_ticket += $ticketNumber;
                    $userTicket->update();
                }
                // create nft auction of user by ticket number
                for ($i = 0; $i < $nftQuantity; $i++) {
                    $auctionNftService->createNftAuction(
                        $transaction->user->wallet_address,
                        $transaction->package->reward->nft_id,
                        $transaction->package->reward->nft_delivery_id,
                        1
                    );
                }
            }
            Log::info(
                '[SUCCESS] Check Status Nft Auction for: '
                    . $transaction->id . ' ('
                    . substr($transaction->tx_hash, 0, 10)
                    . ')'
            );
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
