<?php

namespace App\Http\Controllers\Api;

use App\Exports\NftItemExport;
use App\Http\Controllers\Controller;
use App\Imports\AuctionNftItemImport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class NftController extends Controller
{
    protected $auctionNftItemImport;

    protected $nftItemExport;

    /**
     * NftController constructor.
     *
     * @param AuctionNftItemImport $auctionNftItemImport
     * @param NftItemExport $nftItemExport
     */
    public function __construct(
        AuctionNftItemImport $auctionNftItemImport,
        NftItemExport $nftItemExport,
    ) {
        $this->auctionNftItemImport = $auctionNftItemImport;
        $this->nftItemExport = $nftItemExport;
    }

    /**
     * Import nft item by excel
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function importNft(Request $request)
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
                $this->auctionNftItemImport->importNft();

                return response()->json([
                    'message' => __('nft.importNft.success'),
                ], 200);
            } catch (Exception $e) {
                Log::error($e);

                return response()->json([
                    'message' => __('nft.importNft.fail'),
                    'error' => $e,
                ], 400);
            }
        } else {
            return response()->json([
                'message' => $validator->messages(),
            ], 201);
        }
    }

    public function exportNft()
    {
        try {
            $this->nftItemExport->exportNft();

            return response()->json([
                'message' => 'Export successfully',
            ], 200);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => 'Export failed',
                'error' => $e,
            ], 400);
        }
    }
}
