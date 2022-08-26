<?php

namespace App\Imports;

use App\Models\Nft;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Facades\Excel;

class NftItemImport implements ToModel, WithHeadingRow
{
    public function headingRow() : int
    {
        return 1;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Nft([
            'serial_no'     => $row['serial_no'],
            'type_id'       => $row['type_id'],
            'nft_id'        => $row['nft_id'],
            'nft_owner_id'  => $row['nft_owner_id'],
            'tx_hash'       => $row['tx_hash'],
            'image_url'     => $row['image_url'],
            'status'        => $row['status'],
        ]);
    }

    public function import()
    {
        Excel::import(new NftItemImport, request()->file('file'));
    }
}
