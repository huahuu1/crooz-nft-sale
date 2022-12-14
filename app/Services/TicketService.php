<?php

namespace App\Services;

use App\Models\GachaTicket;
use Carbon\Carbon;
use DB;
use Exception;

class TicketService
{
    /**
     * Create gacha ticket data
     *
     * @param int $userId
     * @param int $ticketNumber
     * @param \App\Models\GachaTicket::ticket_type $ticketType
     */
    public function createGachaTicketData(
        $userId,
        $ticketNumber,
        $ticketType
    ) {
        $ticketTypes = [GachaTicket::PAID_TICKET, GachaTicket::FREE_TICKET];
        try {
            // set default value
            $gaChaTicket = [
                'user_id' => $userId,
                'total_ticket' => $ticketNumber,
                'remain_ticket' => $ticketNumber,
            ];
            foreach ($ticketTypes as $type) {
                $gaChaTicket['ticket_type'] = $type;
                // set total_ticket  remain_ticket by ticket type
                if ($ticketType != $type) {
                    $gaChaTicket['total_ticket'] = 0;
                    $gaChaTicket['remain_ticket'] = 0;
                }

                GachaTicket::create($gaChaTicket);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    /**
     * Create Paid gaCha ticket
     *
     * @param int $userId
     * @param int $ticketNumber
     * @package int $auctionId
     * @return \App\Models\GachaTicket::PAID_TICKET
     */
    public function createPaidGachaTicketData(
        $userId,
        $ticketNumber,
        $auctionId
    ) {
        try {
            // set default value
            $gaChaTicket = [
                'user_id' => $userId,
                'nft_auction_id' => $auctionId,
                'total_ticket' => $ticketNumber,
                'remain_ticket' => $ticketNumber,
                'ticket_type' => GachaTicket::PAID_TICKET
            ];
            GachaTicket::create($gaChaTicket);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Create gacha ticket history used data
     *
     * @param int $gachaTicketId
     * @param int $usedQuantity
     */
    public function createGachaTicketHistory(
        $gachaTicketId,
        $usedQuantity
    ) {
        DB::beginTransaction();
        try {
            DB::table('ticket_used_histories')->insert([
                'gacha_ticket_id' => $gachaTicketId,
                'used_quantity' => $usedQuantity,
                'used_time' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Checking user has gacha ticket info or not by user id.
     *
     * @param $userId
     * @return int
     */
    public function hasGachaInfoByUserId($userId)
    {
        return GachaTicket::where('user_id', $userId)
            ->where('ticket_type', GachaTicket::PAID_TICKET)
            ->count();
    }

    /**
     * Get gacha ticket info by user id and ticket type.
     *
     * @param $userId, $ticketType, $auctionId
     * @return array|object
     */
    public function getGachaTicketByUserIdAndType($userId, $ticketType, $auctionId): array|object
    {
        return GachaTicket::where('user_id', $userId)
            ->where('ticket_type', $ticketType)
            ->where('nft_auction_id', $auctionId)
            ->lockForUpdate()
            ->first();
    }

    /**
     * Get user's tickets number.
     *
     * @param $userId, $ticketType
     * @return array|object
     */
    public function getUserTicketsNumber($userId): array|object
    {
        return GachaTicket::select('ticket_type', 'remain_ticket')
            ->where('user_id', $userId)
            ->get();
    }
}