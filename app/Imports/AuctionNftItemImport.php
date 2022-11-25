<?php

namespace App\Imports;

use App\Models\AuctionNft;
use App\Models\User;
use App\Services\AuctionNftService;
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

    protected $auctionNftService;

    protected $userService;

    protected $auctionNft;

    public function __construct(AuctionNft $auctionNft)
    {
        $this->auctionNft = $auctionNft;
        $this->userService = new UserService();
        $this->auctionNftService = new AuctionNftService();
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
        if (!$user) {
            $user = User::create([
                'wallet_address' => $row[0],
            ]);
        }
        return $this->auctionNftService->createNftAuction(
            $row[0],
            $row[1],
            $row[2],
            1
        );
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
