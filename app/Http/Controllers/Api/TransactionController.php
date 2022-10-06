<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Imports\UnlockUserBalanceImport;
use App\Models\CashFlow;
use App\Models\NftAuctionHistory;
use App\Models\NftAuctionInfo;
use App\Models\TokenMaster;
use App\Models\TokenSaleHistory;
use App\Services\CashFlowService;
use App\Services\HistoryListService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    protected $userService;

    protected $unlockUserBalanceImport;

    protected $historyListService;

    protected $cashFlowService;

    /**
     * TransactionController constructor.
     *
     * @param use userService $userService
     */
    public function __construct(
        UserService $userService,
        UnlockUserBalanceImport $unlockUserBalanceImport,
        HistoryListService $historyListService,
        CashFlowService $cashFlowService
    ) {
        $this->userService = $userService;
        $this->unlockUserBalanceImport = $unlockUserBalanceImport;
        $this->historyListService = $historyListService;
        $this->cashFlowService = $cashFlowService;
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
            $depositTransaction = $this->historyListService->getTokenSaleHistoryByTxHash($request->tx_hash);

            //prevent duplicate transactions
            if ($depositTransaction) {
                return response()->json([
                    'message' => 'This deposit transaction is duplicated',
                ], 500);
            }

            $user = $this->userService->getUserByWalletAddress($request->wallet_address);

            if (! $user) {
                return response()->json([
                    'message' => 'Please connect to metamask',
                ], 500);
            }

            $this->historyListService->createTokenSaleHistory($user->id, $request->token_id, $request->token_sale_id, $request->amount, TokenSaleHistory::PENDING_STATUS, $request->tx_hash);

            $this->cashFlowService->createCashFlow($user->id, $request->token_id, $request->amount, CashFlow::TOKEN_DEPOSIT, $request->tx_hash);

            return response()->json([
                'message' => 'Deposit transaction successfully - 入金成功しました。',
            ], 200);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => 'Deposit failed - 入金失敗しました。',
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
            $depositTransaction = $this->historyListService->getNftAuctionHistoryByTxHash($request->tx_hash);
            $minPrice = (int) NftAuctionInfo::getLatestInfoNftAuction()->min_price;
            $tokenName = TokenMaster::getTokenMasterById($request->token_id)->code;

            //prevent duplicate transactions
            if ($depositTransaction) {
                return response()->json([
                    'message' => 'This deposit transaction is duplicated',
                ], 500);
            }

            //prevent amount smaller than min price
            if ($request->amount < $minPrice) {
                return response()->json([
                    'message' => "The amount of {$tokenName} must be larger or equal to {$minPrice}",
                ], 500);
            }

            $user = $this->userService->getUserByWalletAddress($request->wallet_address);

            if (! $user) {
                return response()->json([
                    'message' => 'Please connect to metamask',
                ], 500);
            }

            $this->historyListService->createNftAuctionHistory($user->id, $request->token_id, $request->nft_auction_id, $request->amount, NftAuctionHistory::PENDING_STATUS, $request->tx_hash);

            $this->cashFlowService->createCashFlow($user->id, $request->token_id, $request->amount, CashFlow::NFT_DEPOSIT, $request->tx_hash);

            return response()->json([
                'message' => 'Deposit transaction successfully - 入金成功しました。',
            ], 200);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => 'Deposit failed - 入金失敗しました。',
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
        $maxPerPage = $maxPerPage ?? config('defines.pagination.token_sale');

        $user = $this->userService->getUserByWalletAddress($walletAddress);

        if (! $user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $tokeSaleHistory = $this->historyListService->getSuccessTokenSaleHistoryByUserIdHasPagination($user->id, $maxPerPage);

        return response()->json([
            'data' => $tokeSaleHistory->values()->all(),
            'total_pages' => $tokeSaleHistory->lastPage(),
        ]);
    }

    /**
     * Get purchase list of nft auction
     *
     * @return \Illuminate\Http\Response
     */
    public function getPurchaseListOfNftAuctionByWalletAddress($walletAddress, $maxPerPage = null)
    {
        $maxPerPage = $maxPerPage ?? config('defines.pagination.nft_auction');
        info($maxPerPage);
        $user = $this->userService->getUserByWalletAddress($walletAddress);

        if (! $user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $nftAuctionHistory = $this->historyListService->getSuccessNftAuctionHistoryByUserIdHasPagination($user->id, $maxPerPage);

        return response()->json([
            'data' => $nftAuctionHistory->values()->all(),
            'total_pages' => $nftAuctionHistory->lastPage(),
        ]);
    }

    /**
     * Get purchase list of nft auction
     *
     * @return \Illuminate\Http\Response
     */
    public function getPurchaseListOfNftAuction($maxPerPage = null)
    {
        $maxPerPage = $maxPerPage ?? config('defines.pagination.nft_auction');
        info('test');
        info($maxPerPage);
        $nftAuctionHistory = $this->historyListService->getSuccessNftAuctionHistoryHasPagination($maxPerPage);

        return response()->json([
            'data' => $nftAuctionHistory->values()->all(),
            'total_pages' => $nftAuctionHistory->lastPage(),
        ]);
    }

    /**
     * Import unlock user balance by excel
     *
     * @return \Illuminate\Http\Response
     */
    public function importUnlockUserBalance(Request $request)
    {
        $validator = Validator::make(
            [
                'file' => $request->file,
                'extension' => strtolower($request->file->getClientOriginalExtension()),
            ],
            [
                'file' => 'required',
                'extension' => 'required|in:csv,xlsx,xls',
            ]
        );
        if (! $validator->fails()) {
            try {
                $this->unlockUserBalanceImport->importUnlockUserBalance();

                return response()->json([
                    'message' => 'Import unlock user balance successfully!!',
                ], 200);
            } catch (Exception $e) {
                Log::error($e);

                return response()->json([
                    'message' => 'Import unlock user balance failed!!',
                    'error' => $e,
                ], 500);
            }
        } else {
            return response()->json([
                'message' => $validator->messages(),
            ], 201);
        }
    }
}
