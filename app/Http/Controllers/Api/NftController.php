<?php

namespace App\Http\Controllers\Api;

use App\Exports\NftItemExport;
use App\Http\Controllers\Controller;
use App\Imports\NftItemImport;
use App\Jobs\NftItemJob;
use Exception;
use Illuminate\Support\Facades\Log;

class NftController extends Controller
{
    protected $nftItemImport;

    protected $nftItemExport;

    /**
     * NftController constructor.
     *
     * @param use NftItemImport $nftItemImport, UserService $userService, UserWithdrawalService $userWithdrawalService
     */
    public function __construct(
        NftItemImport $nftItemImport,
        NftItemExport $nftItemExport,
    ) {
        $this->nftItemImport = $nftItemImport;
        $this->nftItemExport = $nftItemExport;
    }

    /**
     * Import nft item by excel
     *
     * @return \Illuminate\Http\Response
     */
    public function importNft()
    {
        try {
            $this->nftItemImport->importNft();
            // get session nft to run job upload image to s3
            if (! empty(session()->get('nft_item'))) {
                NftItemJob::dispatch()->delay(now()->seconds(10));
            }

            return response()->json([
                'message' => 'Import nft successfully!!',
            ], 200);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => 'Import nft failed!!',
                'error' => $e,
            ], 500);
        }
    }

    public function exportNft()
    {
        try {
            $this->nftItemExport->exportNft();

            return response()->json([
                'message' => 'Export successfully!!',
            ], 200);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => 'Export failed!!',
                'error' => $e,
            ], 500);
        }
    }
}
