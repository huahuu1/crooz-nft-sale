<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Http\Requests\TransactionRequest;
use App\Imports\PrivateUserUnlockBalanceImport;
use App\Jobs\DistributeTicketJob;
use App\Models\CashFlow;
use App\Models\Nft;
use App\Models\NftAuctionHistory;
use App\Models\TokenMaster;
use App\Services\AuctionInfoService;
use App\Services\CashFlowService;
use App\Services\HistoryListService;
use App\Services\UserService;
use App\Traits\ApiFincodePayment;
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

    use ApiFincodePayment;

    protected $userService;

    protected $privateUserUnlockBalanceImport;

    protected $historyListService;

    protected $cashFlowService;

    protected $auctionInfoService;

    /**
     * TransactionController constructor.
     *
     * @param UserService $userService
     * @param PrivateUserUnlockBalanceImport $privateUserUnlockBalanceImport
     * @param HistoryListService $historyListService
     * @param CashFlowService $cashFlowService
     * @param AuctionInfoService $auctionInfoService
     */
    public function __construct(
        UserService $userService,
        PrivateUserUnlockBalanceImport $privateUserUnlockBalanceImport,
        HistoryListService $historyListService,
        CashFlowService $cashFlowService,
        AuctionInfoService $auctionInfoService
    ) {
        $this->userService = $userService;
        $this->privateUserUnlockBalanceImport = $privateUserUnlockBalanceImport;
        $this->historyListService = $historyListService;
        $this->cashFlowService = $cashFlowService;
        $this->auctionInfoService = $auctionInfoService;
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
            $minPrice = $this->auctionInfoService->infoNftAuctionById($request->nft_auction_id)->min_price;
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

            if (!$user) {
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
                $request->tx_hash,
                NftAuctionHistory::METHOD_CRYPTO
            );

            $this->cashFlowService->createCashFlow(
                $user->id,
                $request->token_id,
                $request->amount,
                CashFlow::NFT_DEPOSIT,
                $request->tx_hash,
                CashFlow::METHOD_CRYPTO
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
                $network = $this->historyListService->getNftAuctionHistoryByTxHash($transaction['tx_hash'])->networkMaster->chain_id;
                $isTransactionExistedOnBlockChain = $this->isTransactionExisted($transaction['tx_hash'], $network);
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
                        $transaction['tx_hash'],
                        NftAuctionHistory::METHOD_CRYPTO
                    );

                    $this->cashFlowService->createCashFlow(
                        $user->id,
                        $transaction['token_id'],
                        $transaction['amount'],
                        CashFlow::NFT_DEPOSIT,
                        $transaction['tx_hash'],
                        CashFlow::METHOD_CRYPTO
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
     * Get purchase list of nft auction
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPurchaseListOfNftAuctionOfUser($user, $maxPerPage = null)
    {
        $maxPerPage = $maxPerPage ?? config('defines.pagination.nft_auction');
        $user = $this->userService->getUserByWalletAddressOrByUserId($user);

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $nftAuctionHistory = $this->historyListService->getSuccessNftAuctionHistoryByUserIdHasPagination(
            $user->id,
            $maxPerPage
        );

        return response()->json([
            'data' => $nftAuctionHistory->values()->all(),
            'total_pages' => $nftAuctionHistory->lastPage(),
        ]);
    }

    /**
     * Get purchase list of nft auction
     *
     * @return \Illuminate\Http\JsonResponse
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
    public function importPrivateUserUnlockBalance(Request $request)
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
        if (!$validator->fails()) {
            try {
                $this->privateUserUnlockBalanceImport->importPrivateUserUnlockBalance();

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

    /**
     * Create transaction with credit card.
     *
     * @param  \App\Http\Requests\PaymentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function paymentWithCreditCard(PaymentRequest $request)
    {
        try {
            $bearerToken = config('defines.fincode_authorization_token');
            $baseUri = config('defines.fincode_api_url');
            $user = $this->userService->getUserByWalletAddress($request->wallet_address);
            $fixedPrice = $this->auctionInfoService->infoNftAuctionById($request->nft_auction_id)->fixed_price;
            $tokenName = TokenMaster::getTokenMasterById($request->token_id)->code;
            //prevent amount smaller than min price
            if ($request->amount < $fixedPrice) {
                return response()->json([
                    'message' => __(
                        'transaction.createDepositNftTransaction.min_price',
                        [
                            'tokenName' => $tokenName,
                            'minPrice' => $fixedPrice
                        ]
                    ),
                ], 400);
            }
            //case not found user
            if (!$user) {
                return response()->json([
                    'message' => __('transaction.createDepositNftTransaction.connect_metamask'),
                ], 400);
            }
            //create data in nft auction history with status pending
            $auctionFiat = $this->historyListService->createNftAuctionHistory(
                $user->id,
                $request->token_id,
                $request->nft_auction_id,
                $request->amount,
                NftAuctionHistory::PENDING_STATUS,
                null,
                NftAuctionHistory::METHOD_CREDIT
            );
            //call api payment fincode
            $result = $this->payment(
                $baseUri,
                $bearerToken,
                $request->id,
                $request->pay_type,
                $request->access_id,
                $request->method,
                $request->token,
            );
            //case error with call api payment
            if ($result['statusCode'] !== 200) {
                //update status and tx hash nft auction history
                $auctionFiat->status = NftAuctionHistory::FAILED_STATUS;
                $auctionFiat->update();
                return response()->json([
                    'message' => $result,
                ], $result['statusCode']);
            }
            //case success with call api payment
            if ($result['statusCode'] === 200) {
                // AuctionFiat is not empty
                if (!empty($auctionFiat)) {
                    //update status and tx hash nft auction history
                    $auctionFiat->tx_hash = $result['response']['id'];
                    $auctionFiat->status = NftAuctionHistory::SUCCESS_STATUS;
                    $auctionFiat->update();
                    // Call Job Distribute Ticket
                    DistributeTicketJob::dispatch($auctionFiat)->onQueue(config('defines.queue.general'))->delay(now()->addSeconds(1));
                    // Insert record in cash flow
                    $this->cashFlowService->createCashFlow(
                        $user->id,
                        $request->token_id,
                        $request->amount,
                        CashFlow::NFT_DEPOSIT,
                        $result['response']['id'],
                        CashFlow::METHOD_CREDIT
                    );
                }
            }
            return response()->json([
                'message' => __('transaction.createDepositNftTransaction.success'),
            ], $result['statusCode']);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => __('transaction.createDepositNftTransaction.fail'),
                'error' => $e,
            ], 400);
        }
    }

    public function gachaTicketApi(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'wallet_id' => 'required',
                    'gacha_id' => 'required',
                ]
            );
            if (!$validator->fails()) {
                return response()->json([
                    'data' => Nft::getRandomNfts(),
                ], 200);
            } else {
                return response()->json([
                    'message' => $validator->errors()
                ], 400);
            }
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => 'failed',
                'error' => $e,
            ], 400);
        }
    }
}
