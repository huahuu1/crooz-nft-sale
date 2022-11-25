<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Http\Requests\TransactionRequest;
use App\Imports\PrivateUserUnlockBalanceImport;
use App\Models\CashFlow;
use App\Models\Nft;
use App\Models\NftAuctionHistory;
use App\Models\NftAuctionPackageStock;
use App\Services\AuctionInfoService;
use App\Services\AuctionNftService;
use App\Services\CashFlowService;
use App\Services\HistoryListService;
use App\Services\TicketService;
use App\Services\UserService;
use App\Traits\ApiFincodePayment;
use App\Traits\CheckTransactionWithApiScan;
use App\Traits\ApiScanTransaction;
use App\Traits\DistributeTicket;
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

    use DistributeTicket;

    protected $userService;

    protected $privateUserUnlockBalanceImport;

    protected $historyListService;

    protected $cashFlowService;

    protected $auctionInfoService;

    protected $ticketService;

    protected $auctionNftService;

    /**
     * TransactionController constructor.
     *
     * @param UserService $userService
     * @param PrivateUserUnlockBalanceImport $privateUserUnlockBalanceImport
     * @param HistoryListService $historyListService
     * @param CashFlowService $cashFlowService
     * @param AuctionInfoService $auctionInfoService
     * @param TicketService $ticketService
     * @param AuctionNftService $auctionNftService
     */
    public function __construct(
        UserService $userService,
        PrivateUserUnlockBalanceImport $privateUserUnlockBalanceImport,
        HistoryListService $historyListService,
        CashFlowService $cashFlowService,
        AuctionInfoService $auctionInfoService,
        TicketService $ticketService,
        AuctionNftService $auctionNftService,
    ) {
        $this->userService = $userService;
        $this->privateUserUnlockBalanceImport = $privateUserUnlockBalanceImport;
        $this->historyListService = $historyListService;
        $this->cashFlowService = $cashFlowService;
        $this->auctionInfoService = $auctionInfoService;
        $this->ticketService = $ticketService;
        $this->auctionNftService = $auctionNftService;
    }

    /**
     * Create transaction when a user deposit crypto.
     *
     * @param  \App\Http\Requests\TransactionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createDepositNftTransaction(Request $request)
    {
        try {
            info($request->all());
            $depositTransaction = $this->historyListService->getNftAuctionHistoryByTxHash($request->tx_hash);
            $user = $this->userService->getUserByWalletAddress($request->wallet_address);
            $packageStock = NftAuctionPackageStock::getPackageStockByPackageId($request->package_id);
            if ((int) $request->package_id === 3) {
                $auctionHistory = $this->historyListService->getNftAuctionHistoriesByPackage(
                    $user->id,
                    $request->package_id,
                    $request->nft_auction_id
                );
                //in case 1 user can buy 1 package
                if ($auctionHistory) {
                    return response()->json([
                        'message' => __('transaction.createDepositNftTransaction.package_owned'),
                    ], 400);
                }
            }
            //prevent out of stock package
            if (!empty($packageStock) && $packageStock->remain <= 0) {
                return response()->json([
                    'message' => __('transaction.createDepositNftTransaction.out_of_stock'),
                ], 400);
            }

            //prevent duplicate transactions
            if ($depositTransaction) {
                return response()->json([
                    'message' => __('transaction.createDepositNftTransaction.duplicate'),
                ], 400);
            }

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
                NftAuctionHistory::METHOD_CRYPTO,
                $request->package_id
            );

            //subtract ticket when transaction is pending
            if (!empty($packageStock)) {
                $packageStock->remain -= 1;
                $packageStock->update();
            }

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
            info($request->all());
            $password = config('defines.password_decrypte');
            $request = $request->all();
            $transactions = CryptoJsAes::decrypt($request['data'] ?? '', $password);
            $results = collect([]);
            foreach ($transactions as $transaction) {
                $nftAuctionHistory = $this->historyListService->getNftAuctionHistoryByTxHash($transaction['tx_hash']);
                $networkInfo = $this->auctionInfoService->infoNftAuctionById($transaction['nft_auction_id']);
                foreach ($networkInfo->auctionNetwork as $network) {
                    if ($this->isTransactionExisted($transaction['tx_hash'], $network->chain_id)) {
                        $isTransactionExistedOnBlockChain = true;
                    }
                }
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
                        NftAuctionHistory::METHOD_CRYPTO,
                        $transaction['package_id']
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
                'message' => __('transaction.createDepositNftTransaction.connect_metamask'),
            ], 404);
        }

        $nftAuctionHistory = $this->historyListService->getPendingAndSuccessNftAuctionHistoryByUserIdHasPagination(
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
     * Register payment with credit card.
     *
     * @param  \App\Http\Requests\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerPaymentWithCreditCard(Request $request)
    {
        try {
            info('registerPaymentWithCreditCard-Request',[$request->all()]);
            $bearerToken = config('defines.fincode_authorization_token');
            $baseUri = config('defines.fincode_api_url');
            //call api register payment fincode
            $result = $this->registerPaymentCredit(
                $baseUri,
                $bearerToken,
                "Card",
                "CAPTURE",
                $request->amount
            );

            info('registerPaymentWithCreditCard-registerPaymentCredit',[$result]);

            //case error with call api payment
            if ($result['statusCode'] !== 200) {
                return response()->json([
                    'message' => $result,
                ], $result['statusCode']);
            }
            return response()->json([
                'message' => 'Registered credit successfully',
                'id' => $result['response']['id'],
                'access_id' => $result['response']['access_id']
            ], $result['statusCode']);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => __('transaction.createDepositNftTransaction.fail'),
                'error' => $e,
            ], 400);
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
            info("paymentWithCreditCard-PaymentRequest",[$request->all()]);
            $bearerToken = config('defines.fincode_authorization_token');
            $baseUri = config('defines.fincode_api_url');
            $user = $this->userService->getUserByWalletAddress($request->wallet_address);
            //case not found user
            if (!$user) {
                return response()->json([
                    'message' => __('transaction.createDepositNftTransaction.connect_metamask'),
                ], 400);
            }
            $packageStock = NftAuctionPackageStock::getPackageStockByPackageId($request->package_id);
            //prevent out of stock package
            if (!empty($packageStock) && $packageStock->remain <= 0) {
                return response()->json([
                    'message' => __('transaction.createDepositNftTransaction.out_of_stock'),
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
                NftAuctionHistory::METHOD_CREDIT,
                $request->package_id
            );
            //call api payment fincode
            $result = $this->completePaymentCredit(
                $baseUri,
                $bearerToken,
                $request->id,
                $request->pay_type,
                $request->access_id,
                $request->method,
                $request->token
            );

            info("paymentWithCreditCard-completePaymentCredit", [$result]);
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
                //subtract ticket when transaction is success
                if (!empty($packageStock)) {
                    $packageStock->remain -= 1;
                    $packageStock->update();
                }
                // AuctionFiat is not empty
                if (!empty($auctionFiat)) {
                    //update status and tx hash nft auction history
                    $auctionFiat->tx_hash = $result['response']['id'];
                    $auctionFiat->status = NftAuctionHistory::SUCCESS_STATUS;
                    $auctionFiat->update();
                    // Call Job Distribute Ticket
                    $this->distributeTicket($auctionFiat, $this->ticketService, $this->auctionNftService);
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
                return Response([
                    'result' => Nft::getRandomNfts(),
                    'status' => 0,
                ]);
            } else {
                return Response([
                    'status' => 1,
                ]);
            }
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => 'failed',
                'error' => $e,
            ], 400);
        }
    }

    public function historyNftAuctionByPackage(Request $request)
    {
        $user = $this->userService->getUserByWalletAddress($request->wallet_address);
        if(empty($user) || empty($request->package_id) || empty($request->auction_id)){
            return response()->json([
                'message' => 'Not Found'
            ], 400);
        }

        $auctionHistory = $this->historyListService->getNftAuctionHistoriesByPackage(
            $user->id,
            $request->package_id,
            $request->auction_id
        );

        return response()->json([
            'status' => $auctionHistory ? true : false
        ], 200);
    }
}
