<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Http\Requests\TransactionRequest;
use App\Imports\PrivateUserUnlockBalanceImport;
use App\Jobs\DistributeTicketJob;
use App\Models\CashFlow;
use App\Models\GachaTicket;
use App\Models\Nft;
use App\Models\NftAuctionHistory;
use App\Models\TokenMaster;
use App\Services\AuctionInfoService;
use App\Services\AuctionNftService;
use App\Services\CashFlowService;
use App\Services\HistoryListService;
use App\Services\TicketService;
use App\Services\UserService;
use App\Traits\ApiFincodePayment;
use App\Traits\ApiGachaTicket;
use App\Traits\CheckTransactionWithApiScan;
use App\Traits\ApiScanTransaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Nullix\CryptoJsAes\CryptoJsAes;

class TicketController extends Controller
{
    use ApiGachaTicket;

    protected $userService;

    protected $auctionNftService;

    protected $ticketService;

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
        TicketService $ticketService
    ) {
        $this->userService = $userService;
        $this->auctionNftService = $auctionNftService;
        $this->ticketService = $ticketService;
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
            $user = $this->userService->getUserByWalletAddress($request->wallet_address);
            //case not found user
            if (!$user) {
                return response()->json([
                    'message' => __('transaction.createDepositNftTransaction.connect_metamask'),
                ], 400);
            }
            $remainTicket = $this->ticketService->getGachaTicketByUserIdAndType($user->id, $request->ticket_type);
            //check the number of remain ticket
            if ($remainTicket->remain_ticket == 0) {
                return response()->json([
                    'message' => 'Out of tickets',
                ], 400);
            }
            $result = $this->gachaTicket($baseUri, $request->wallet_address, $request->ticket_type);
            //case error with call api gacha
            if ($result['response']['status'] != 0) {
                return response()->json([
                    'message' => 'This is error gacha ticket',
                ], $result['statusCode']);
            }
            //case success with call api gacha
            if ($result['statusCode'] === 200) {
                //minus the number of tickets used
                $remainTicket->remain_ticket -= 1;
                $remainTicket->update();
                // create nft auction of user by ticket number
                foreach ($result['response']['result'] as $nftId) {
                    $this->auctionNftService->createNftAuction(
                        $request->wallet_address,
                        $nftId,
                        7,
                        1
                    );
                }
            }
            return response()->json([
                'message' => "Use ticket successfully",
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

        if (! $user) {
            return response()->json([
                'message' => __('user.getUser.not_found'),
            ], 404);
        }

        return response()->json([
            'data' => $this->ticketService->getUserTicketsNumber($user->id)->all(),
        ]);
    }
}
