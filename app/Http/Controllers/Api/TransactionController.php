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
use App\Traits\CheckTransactionWithApiScan;
use App\Traits\ApiScanTransaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Nullix\CryptoJsAes\CryptoJsAes;

class TransactionController extends Controller
{
    use CheckTransactionWithApiScan;

    use ApiScanTransaction;

    protected $userService;

    protected $unlockUserBalanceImport;

    protected $historyListService;

    protected $cashFlowService;

    /**
     * TransactionController constructor.
     *
     * @param UserService $userService
     * @param UnlockUserBalanceImport $unlockUserBalanceImport
     * @param HistoryListService $historyListService
     * @param CashFlowService $cashFlowService
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
     * @param  \App\Http\Requests\TransactionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createDepositTokenTransaction(TransactionRequest $request)
    {
        try {
            $depositTransaction = $this->historyListService->getTokenSaleHistoryByTxHash($request->tx_hash);

            //prevent duplicate transactions
            if ($depositTransaction) {
                return response()->json([
                    'message' => __('createDepositTokenTransaction.duplicate'),
                ], 400);
            }

            $user = $this->userService->getUserByWalletAddress($request->wallet_address);

            if (! $user) {
                return response()->json([
                    'message' => __('createDepositTokenTransaction.connect_metamask'),
                ], 400);
            }

            $this->historyListService->createTokenSaleHistory(
                $user->id,
                $request->token_id,
                $request->token_sale_id,
                $request->amount,
                TokenSaleHistory::PENDING_STATUS,
                $request->tx_hash
            );

            $this->cashFlowService->createCashFlow(
                $user->id,
                $request->token_id,
                $request->amount,
                CashFlow::TOKEN_DEPOSIT,
                $request->tx_hash
            );

            return response()->json([
                'message' => __('createDepositTokenTransaction.success'),
            ], 200);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => __('createDepositTokenTransaction.fail'),
                'error' => $e,
            ], 400);
        }
    }

    /**
     * Create transaction when a user deposit crypto.
     *
     * @param  \App\Http\Requests\TransactionRequest $request
     * @return \Illuminate\Http\JsonResponse
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
                    'message' => __('transaction.createDepositNftTransaction.duplicate'),
                ], 400);
            }

            //prevent amount smaller than min price
            if ($request->amount < $minPrice) {
                return response()->json([
                    'message' => __(
                        'transaction.createDepositNftTransaction.min_price',
                        [
                        'tokenName' => $tokenName,
                        'minPrice' => $minPrice
                        ]
                    ),
                ], 400);
            }

            $user = $this->userService->getUserByWalletAddress($request->wallet_address);

            if (! $user) {
                return response()->json([
                    'message' => __('transaction.createDepositNftTransaction.connect_metamask'),
                ], 400);
            }

            $this->historyListService->createNftAuctionHistory(
                $user->id,
                $request->token_id,
                $request->nft_auction_id,
                $request->amount,
                NftAuctionHistory::PENDING_STATUS,
                $request->tx_hash
            );

            $this->cashFlowService->createCashFlow(
                $user->id,
                $request->token_id,
                $request->amount,
                CashFlow::NFT_DEPOSIT,
                $request->tx_hash
            );

            return response()->json([
                'message' => __('transaction.createDepositNftTransaction.success'),
            ], 200);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => __('transaction.createDepositNftTransaction.fail'),
                'error' => $e,
            ], 400);
        }
    }

    /**
     * Create transaction when a user deposit crypto.
     *
     * @param $transactions
     * @return \Illuminate\Http\JsonResponse
     */
    public function insertMissedTransaction(Request $request)
    {
        try {
            $password = config('defines.password_decrypte');
            $request = $request->all();
            $transactions = CryptoJsAes::decrypt($request['data'] ?? '', $password);
            $results = collect([]);
            foreach ($transactions as $transaction) {
                $nftAuctionHistory = $this->historyListService->getNftAuctionHistoryByTxHash($transaction['tx_hash']);
                $isTransactionExistedOnBlockChain = $this->isTransactionExisted($transaction['tx_hash']);
                //case transaction is existed in history table and on blockchain
                //and case not existed on blockchain
                if ($nftAuctionHistory && $isTransactionExistedOnBlockChain || !$isTransactionExistedOnBlockChain) {
                    $results->push([
                        'tx_hash' => $transaction['tx_hash'],
                        'insert' => false,
                        'count' => $transaction['count'] + 1
                    ]);
                }
                //case transaction is not existed in history table but existed on blockchain
                if (!$nftAuctionHistory && $isTransactionExistedOnBlockChain) {
                    $results->push([
                        'tx_hash' => $transaction['tx_hash'],
                        'insert' => true,
                    ]);

                    $user = $this->userService->getUserByWalletAddress($transaction['wallet_address']);

                    $this->historyListService->createNftAuctionHistory(
                        $user->id,
                        $transaction['token_id'],
                        $transaction['nft_auction_id'],
                        $transaction['amount'],
                        NftAuctionHistory::PENDING_STATUS,
                        $transaction['tx_hash']
                    );

                    $this->cashFlowService->createCashFlow(
                        $user->id,
                        $transaction['token_id'],
                        $transaction['amount'],
                        CashFlow::NFT_DEPOSIT,
                        $transaction['tx_hash']
                    );
                }
            }
            return response()->json([
                'message' => __('transaction.createDepositNftTransaction.success'),
                'results' => $results
            ], 200);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => __('transaction.createDepositNftTransaction.fail'),
                'error' => $e,
            ], 400);
        }
    }

    /**
     * Get purchase list of token sale
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPurchaseListOfTokenSaleByWalletAddress($walletAddress, $maxPerPage = null)
    {
        $maxPerPage = $maxPerPage ?? config('defines.pagination.token_sale');

        $user = $this->userService->getUserByWalletAddress($walletAddress);

        if (! $user) {
            return response()->json([
                'message' => __('user.getUser.not_found'),
            ], 404);
        }

        $tokeSaleHistory = $this->historyListService->getSuccessTokenSaleHistoryByUserIdHasPagination(
            $user->id,
            $maxPerPage
        );

        return response()->json([
            'data' => $tokeSaleHistory->values()->all(),
            'total_pages' => $tokeSaleHistory->lastPage(),
        ]);
    }

    /**
     * Get purchase list of nft auction
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPurchaseListOfNftAuctionOfUser($user, $maxPerPage = null)
    {
        $maxPerPage = $maxPerPage ?? config('defines.pagination.nft_auction');
        $user = $this->userService->getUserByWalletAddressOrByUserId($user);

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
        $nftAuctionHistory = $this->historyListService->getSuccessNftAuctionHistoryHasPagination($maxPerPage);

        return response()->json([
            'data' => $nftAuctionHistory->values()->all(),
            'total_pages' => $nftAuctionHistory->lastPage(),
        ]);
    }

    /**
     * Import unlock user balance by excel
     *
     * @return \Illuminate\Http\JsonResponse
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
                    'message' => __('transaction.importUnlockUserBalance.success'),
                ], 200);
            } catch (Exception $e) {
                Log::error($e);

                return response()->json([
                    'message' => __('transaction.importUnlockUserBalance.fail'),
                    'error' => $e,
                ], 400);
            }
        } else {
            return response()->json([
                'message' => $validator->messages(),
            ], 201);
        }
    }
}
