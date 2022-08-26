<?php

namespace App\Exports;

use App\Models\Nft;
use Maatwebsite\Excel\Validators\ValidationException;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Throwable;

class NftItemExport implements FromQuery, ShouldQueue
{
    use Exportable;

    /**
    * @return \Illuminate\Support\Query
    */
    public function query()
    {
        return Nft::query();
    }

    public function exportNft()
    {
        try {
            // Excel::download(new NftItemExport, 'nfts.xlsx');
            Excel::store(new NftItemExport(), 'nfts.xlsx');
        } catch (ValidationException $e) {
            Log::info($e);
        }
    }

    public function failed(Throwable $exception): void
    {
        // handle failed export
    }
}
