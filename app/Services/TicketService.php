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
     * @return array|object
     */
    public function getGachaTicketByUserIdAndType($userId, $ticketType): array|object
    {
        return GachaTicket::where('user_id', $userId)
            ->where('ticket_type', $ticketType)
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
