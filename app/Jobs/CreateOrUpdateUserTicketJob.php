<?php

namespace App\Jobs;

use App\Services\TicketService;
use App\Services\UserService;
use App\Traits\ApiBscScanTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateOrUpdateUserTicketJob implements ShouldQueue
{
    use ApiBscScanTransaction, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Data Ticket variable
     *
     * @var array
     */
    protected $dataTicket;

    /**
     * Auction Id variable
     *
     * @var int
     */
    protected $auctionId;

    /**
     * User Service variable
     *
     * @var App\Services\UserService
     */
    protected $userService;

    /**
     * User Service variable
     *
     * @var App\Services\TicketService
     */
    protected $ticketService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($dataTicket, $auctionId)
    {
        $this->dataTicket = $dataTicket;
        $this->auctionId = $auctionId;
        $this->userService = new UserService();
        $this->ticketService = new TicketService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->dataTicket as $val) {
            // get user by from address
            $user = $this->userService->hasUserByWalletAddress($val['wallet_address']);
            // get token id
            $totalTicket = (int) floor($this->convertAmount($val['token_decimal'], $val['value']));

            if ($totalTicket > 0) {
                $userTicket = $this->ticketService->getUserFreeTicketsNumber($user->id);
                if (empty($userTicket)) {
                    // create or update user ticket
                    $ticket = $this->ticketService->createFreeGachaTicketData(
                        $user->id,
                        $totalTicket,
                        $this->auctionId,
                        date('Y-m-d H:i:s', $val['time_stamp'])
                    );
                    info("[SUCCESS] Create user ticket ID: " . $ticket->id);
                } else {
                    if ($totalTicket > $userTicket->total_ticket) {
                        $quantityAdded = $totalTicket - $userTicket->total_ticket;
                        $userTicket->remain_ticket += $quantityAdded;
                        $userTicket->total_ticket += $quantityAdded;
                        $userTicket->update();
                        info("[SUCCESS] Update user ticket ID: " . $userTicket->id);
                    }
                }
            }
        }
    }
}