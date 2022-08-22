<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Models\CashFlow;
use Illuminate\Http\Request;
use App\Models\NftAuctionHistory;
use App\Models\NftAuctionInfo;
use App\Models\TokenMaster;
use App\Models\TokenSaleHistory;
use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    protected $userService;

    /**
     * TransactionController constructor.
     *
     * @param use userService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Create transaction when a user deposit crypto.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createDepositTokenTransaction(TransactionRequest $request)
    {
        try {
            $depositTransaction = TokenSaleHistory::where('tx_hash', $request->tx_hash)->first();

            //prevent duplicate transactions
            if ($depositTransaction) {
                return response()->json([
                    'message' => 'This deposit transaction is duplicated'
                ], 500);
            }

            $user = $this->userService->getUserByWalletAddress($request->wallet_address);

            if (!$user) {
                return response()->json([
                    'message' => 'Please connect to metamask'
                ], 500);
            }

            TokenSaleHistory::create([
                "user_id" => $user->id,
                "token_id" => $request->token_id,
                "token_sale_id" => 1,
                "amount" => $request->amount,
                "status" => TokenSaleHistory::PENDING_STATUS,
                "tx_hash" => $request->tx_hash,
            ]);

            CashFlow::create([
                "user_id" => $user->id,
                "token_id" => $request->token_id,
                "amount" => $request->amount,
                "transaction_type" => 'TOKEN_DEPOSIT',
                "tx_hash" => $request->tx_hash,
            ]);

            return response()->json([
                'message' => 'Deposit transaction successfully'
            ], 200);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'message' => 'Deposit transaction failed',
                'error' => $e,
            ], 500);
        }
    }

    /**
     * Create transaction when a user deposit crypto.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createDepositNftTransaction(TransactionRequest $request)
    {
        try {
            $depositTransaction = NftAuctionHistory::where('tx_hash', $request->tx_hash)->first();
            $minPrice = (int)NftAuctionInfo::getLatestInfoNftAuction()->min_price;
            $tokenName = TokenMaster::getTokenMasterById($request->token_id)->code;

            //prevent duplicate transactions
            if ($depositTransaction) {
                return response()->json([
                    'message' => 'This deposit transaction is duplicated'
                ], 500);
            }

            //prevent amount smaller than min price
            if ($request->amount < $minPrice) {
                return response()->json([
                    'message' => "The amount of {$tokenName} must be larger or equal to {$minPrice}"
                ], 500);
            }

            $user = $this->userService->getUserByWalletAddress($request->wallet_address);

            if (!$user) {
                return response()->json([
                    'message' => 'Please connect to metamask'
                ], 500);
            }

            NftAuctionHistory::create([
                "user_id" => $user->id,
                "token_id" => $request->token_id,
                "nft_auction_id" => 1,
                "amount" => $request->amount,
                "status" => NftAuctionHistory::PENDING_STATUS,
                "tx_hash" => $request->tx_hash,
            ]);

            CashFlow::create([
                "user_id" => $user->id,
                "token_id" => $request->token_id,
                "amount" => $request->amount,
                "transaction_type" => 'NFT_DEPOSIT',
                "tx_hash" => $request->tx_hash,
            ]);

            return response()->json([
                'message' => 'Deposit transaction successfully'
            ], 200);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'message' => 'Deposit transaction failed',
                'error' => $e,
            ], 500);
        }
    }

    /**
     * Get purchase list of token sale
     *
     * @return \Illuminate\Http\Response
     */
    public function getPurchaseListOfTokenSaleByWalletAddress($walletAddress, $maxPerPage = null)
    {
        $maxPerPage = $maxPerPage ?? env('MAX_PER_PAGE_TOKENSALE');

        $user = $this->userService->getUserByWalletAddress($walletAddress);

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $tokeSaleHistory = TokenSaleHistory::where('status', TokenSaleHistory::SUCCESS_STATUS)
                                           ->where('user_id', $user->id)
                                           ->orderby('amount', 'desc')
                                           ->with(['user', 'token_master'])
                                           ->get()
                                           ->paginate($maxPerPage);
        return response()->json([
            'data' => $tokeSaleHistory->values()->all(),
            'total_pages' => $tokeSaleHistory->lastPage()
        ]);
    }

    /**
     * Get purchase list of nft auction
     *
     * @return \Illuminate\Http\Response
     */
    public function getPurchaseListOfNftAuction($maxPerPage = null)
    {
        $maxPerPage = $maxPerPage ?? env('MAX_PER_PAGE_AUCTION');

        $nftAuctionHistory = NftAuctionHistory::where('status', NftAuctionHistory::SUCCESS_STATUS)
                                              ->orderby('amount', 'desc')
                                              ->with(['user', 'token_master'])
                                              ->get()
                                              ->paginate($maxPerPage);
        return response()->json([
            'data' => $nftAuctionHistory->values()->all(),
            'total_pages' => $nftAuctionHistory->lastPage()
        ]);
    }
}
