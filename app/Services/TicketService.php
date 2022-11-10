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
     * @param $userId, $ticketNumber
     */
    public function createGachaTicketData(
        $userId,
        $ticketNumber
    ) {
        $ticketTypes = [GachaTicket::PAID_TICKET, GachaTicket::FREE_TICKET];
        DB::beginTransaction();
        try {
            foreach ($ticketTypes as $ticketType) {
                DB::table('gacha_tickets')->insert([
                    'user_id' => $userId,
                    'ticket_type' => $ticketType,
                    'total_ticket' => $ticketNumber,
                    'remain_ticket' => $ticketNumber,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
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
        return GachaTicket::where('user_id', $userId)->count();
    }

    /**
     * Get gacha ticket info by user id and ticket type.
     *
     * @param $userId, $ticketType
     * @return int
     */
    public function getGachaTicketByUserIdAndType($userId, $ticketType)
    {
        return GachaTicket::where('user_id', $userId)
                          ->where('ticket_type', $ticketType)
                          ->first();
    }
}
