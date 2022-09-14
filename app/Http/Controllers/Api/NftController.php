<?php

namespace App\Http\Controllers\Api;

use App\Exports\NftItemExport;
use App\Http\Controllers\Controller;
use App\Imports\NftItemImport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
                $this->nftItemImport->importNft();

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
