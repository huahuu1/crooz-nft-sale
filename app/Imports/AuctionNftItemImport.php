<?php

namespace App\Imports;

use App\Models\AuctionNft;
use App\Services\UserService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class AuctionNftItemImport implements ToModel, WithStartRow, WithChunkReading, ShouldQueue
{
    use RemembersRowNumber;

    protected $auctionNft;

    protected $userService;

    public function __construct(AuctionNft $auctionNft)
    {
        $this->auctionNft = $auctionNft;
        $this->userService = new UserService();
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    /**
     * @param  array  $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $currentRowNumber = $this->getRowNumber();
        Log::info('[SUCCESS] Insert excel row: ' . $currentRowNumber);
        $user = $this->userService->getUserByWalletAddress($row[0]);
        return new AuctionNft([
            'owner_id' => $user->id,
            'image_url' => $row[1],
            'type_id' => $row[2],
            'nft_auction_id' => $row[3],
        ]);
    }

    public function importNft()
    {
        try {
            Excel::import(new AuctionNftItemImport($this->auctionNft), request()->file('file'));
        } catch (ValidationException $e) {
            Log::info($e);
        }
    }
}
