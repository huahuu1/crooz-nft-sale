<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuctionNftService;
use App\Services\NftService;
use App\Services\TicketService;
use App\Services\UserService;
use App\Traits\ApiGachaTicket;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    use ApiGachaTicket;

    protected $userService;

    protected $auctionNftService;

    protected $ticketService;

    protected $nftService;


    /**
     * TransactionController constructor.
     *
     * @param UserService $userService
     * @param AuctionNftService $auctionNftService
     * @param TicketService $ticketService
     */
    public function __construct(
        UserService $userService,
        AuctionNftService $auctionNftService,
        TicketService $ticketService,
        NftService $nftService
    ) {
        $this->userService = $userService;
        $this->auctionNftService = $auctionNftService;
        $this->ticketService = $ticketService;
        $this->nftService = $nftService;
    }

    /**
     * Gacha with user's ticket.
     *
     * @param  \App\Http\Requests\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function useGachaTicket(Request $request)
    {
        try {
            $baseUri = config('defines.gacha_api_url');
            $endDay = Carbon::parse(config('defines.day_ticket_exchange_end'), 'UTC')->getTimestamp();

            // Ticket can't exchange after sale finished.
            if (Carbon::now('UTC')->getTimestamp() >= $endDay) {
                return response()->json([
                    'message' => __('transaction.createDepositNftTransaction.out_day'),
                ], 400);
            }

            $user = $this->userService->getUserByWalletAddress($request->wallet_address);
            //case not found user
            if (!$user) {
                return response()->json([
                    'message' => __('transaction.createDepositNftTransaction.connect_metamask'),
                ], 400);
            }

            $gachaTicket = $this->ticketService->getGachaTicketByUserIdAndType($user->id, $request->ticket_type, $request->auction_id ?? null);
            //check the number of remain ticket
            if ($gachaTicket->remain_ticket <= 0) {
                return response()->json([
                    'message' => 'Out of tickets',
                ], 400);
            }

            $result = $this->gachaTicket($baseUri, $request->wallet_address, $request->ticket_type);
            //case error with call api gaCha
            if (!empty($result['response']['status']) && $result['response']['status'] != 0) {
                return response()->json([
                    'message' => 'This is error gacha ticket',
                ], $result['statusCode']);
            }
            //case success with call api gacha
            if ($result['statusCode'] === 200 && !empty($result['response']['result'])) {
                //minus the number of tickets used
                $gachaTicket->remain_ticket -= 1;
                $gachaTicket->update();
                //create gacha ticket history used
                $this->ticketService->createGachaTicketHistory($gachaTicket->id, 1);
                // create nft auction of user by ticket number
                foreach ($result['response']['result'] as $nftId) {
                    $this->auctionNftService->createNftAuction(
                        $request->wallet_address,
                        $nftId,
                        7,
                        1
                    );
                }
                // get all nft of gaCha result
                $nfts = $this->nftService->getNftByIds($result['response']['result']);
            }

            return response()->json([
                'message' => "Use ticket successfully",
                'data' => $nfts ?? []
            ], 200);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => "Use ticket failed",
                'error' => $e,
            ], 400);
        }
    }

    /**
     * Get user's tickets number.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTicketsNumber($user)
    {
        $user = $this->userService->getUserByWalletAddressOrByUserId($user);

        if (!$user) {
            return response()->json([
                'message' => __('user.getUser.not_found'),
            ], 404);
        }

        return response()->json([
            'data' => $this->ticketService->getUserTicketsNumber($user->id)->all(),
        ]);
    }
}