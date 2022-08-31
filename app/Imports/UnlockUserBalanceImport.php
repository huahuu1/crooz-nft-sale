<?php

namespace App\Imports;

use App\Models\UnlockUserBalance;
use App\Models\UserBalance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class UnlockUserBalanceImport implements ToModel, WithHeadingRow
{
    use RemembersRowNumber;

    protected $unlockUserBalance;

    public function __construct(UnlockUserBalance $unlockUserBalance)
    {
        $this->unlockUserBalance = $unlockUserBalance;
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
        Log::info('[SUCCESS] Insert excel row: '.$currentRowNumber);

        UserBalance::where('user_id', $row['user_id'])
                   ->where('token_id', $row['token_id'])
                   ->update([
                       'amount_total' => DB::raw('amount_total + '.$row['amount_lock']),
                       'amount_lock' => DB::raw('amount_lock + '.$row['amount_lock']),
                   ]);

        return new UnlockUserBalance([
            'token_id' => $row['token_id'],
            'token_sale_id' => $row['token_sale_id'],
            'user_id' => $row['user_id'],
            'amount_lock' => $row['amount_lock'],
            'amount_lock_remain' => $row['amount_lock_remain'],
        ]);
    }

    public function importUnlockUserBalance()
    {
        try {
            Excel::import(new UnlockUserBalanceImport($this->unlockUserBalance), request()->file('file'));
        } catch (ValidationException $e) {
            Log::info($e);
        }
    }
}
