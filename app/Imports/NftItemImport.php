<?php

namespace App\Imports;

use App\Jobs\NftItemJob;
use App\Models\Nft;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class NftItemImport implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue
{
    use RemembersRowNumber;

    protected $nft;

    public function __construct(Nft $nft)
    {
        $this->nft = $nft;
    }

    public function headingRow(): int
    {
        return 1;
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

        // nft to run job upload image to s3
        NftItemJob::dispatch([
            'image_url' => $row['image_url'],
            'serial_no' => $row['serial_no'],
        ])->onQueue(config('defines.queue.general'))->delay(now()->addSeconds($currentRowNumber * 5));

        return new Nft([
            'serial_no' => $row['serial_no'],
            'type_id' => $row['type_id'],
            'nft_id' => $row['nft_id'],
            'nft_owner_id' => $row['nft_owner_id'],
            'tx_hash' => $row['tx_hash'],
            'image_url' => $row['image_url'],
            'status' => $row['status'],
        ]);
    }

    public function importNft()
    {
        try {
            Excel::import(new NftItemImport($this->nft), request()->file('file'));
        } catch (ValidationException $e) {
            Log::info($e);
        }
    }
}
