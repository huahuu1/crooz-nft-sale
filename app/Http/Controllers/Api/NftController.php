<?php

namespace App\Http\Controllers\Api;

use App\Exports\NftItemExport;
use App\Http\Controllers\Controller;

use App\Imports\NftItemImport;
use App\Models\Image;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class NftController extends Controller
{
    protected $nftItemImport;
    protected $nftItemExport;
    protected $image;

    /**
     * NftController constructor.
     *
     * @param use NftItemImport $nftItemImport, UserService $userService, UserWithdrawalService $userWithdrawalService
     */
    public function __construct(
        NftItemImport $nftItemImport,
        NftItemExport $nftItemExport,
        Image $image
    ) {
        $this->nftItemImport = $nftItemImport;
        $this->nftItemExport = $nftItemExport;
        $this->image = $image;
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

            return response()->json([
                'message' => 'Import successfully!!',
            ], 200);
        } catch (Exception $e) {
            Log::error($e);

            return response()->json([
                'message' => 'Import failed!!',
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

    public function postUpload(Request $request)
    {
        try {
            $path = Storage::disk('s3')->put('/', $request->file, 'public');

            $request->merge([
                'size' => $request->file->getSize(),
                'path' => $path,
                'title' => 'test',
                'auth_by' => 1
            ]);
            $test = $this->image->create($request->only('path', 'title', 'size', 'auth_by'));
            info($test->toArray());
            return response()->json([
                'message' => 'Export successfully!!',
            ], 200);
        } catch (Exception $e) {
            Log::info($e);
        }
    }
}
