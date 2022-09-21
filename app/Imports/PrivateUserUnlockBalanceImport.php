<?php

namespace App\Imports;

use App\Models\TokenSaleInfo;
use App\Models\PrivateUserUnlockBalance;
use App\Models\UserBalance;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class PrivateUserUnlockBalanceImport implements ToModel, WithHeadingRow
{
    use RemembersRowNumber;

    protected $privateUserUnlockBalance;

    public function __construct(PrivateUserUnlockBalance $privateUserUnlockBalance)
    {
        $this->privateUserUnlockBalance = $privateUserUnlockBalance;
        $this->userService = new UserService();
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
        //check co user chua
        $user = $this->userService->getUserByWalletAddress($row['wallet_address'])->count();
        //neu co user roi thi update
        if (!$user) {
            # code...
        }
        //co user balance chua, co roi thi update chua co thi create

        //neu chua co user thi create

        //create user balance


        UserBalance::where('user_id', $row['user_id'])
                   ->where('token_id', $row['token_id'])
                   ->update([
                       'amount_total' => DB::raw('amount_total + '.$row['amount_lock']),
                       'amount_lock' => DB::raw('amount_lock + '.$row['amount_lock']),
                   ]);

        //create next_run_date column data
        $tokenSaleInfo = TokenSaleInfo::where('id', $row['token_sale_id'])->with('lock_info')->first();
        $endDate = new Carbon($tokenSaleInfo->end_date);
        $nextRunDate = $endDate->addDays($tokenSaleInfo->lock_info->lock_day);

        return new PrivateUserUnlockBalance([
            'token_id' => $row['token_id'],
            'token_sale_id' => $row['token_sale_id'],
            'wallet_address' => $row['wallet_address'],
            'amount_lock' => $row['amount_lock'],
            'amount_lock_remain' => $row['amount_lock_remain'],
            'next_run_date' => $nextRunDate,
        ]);
    }

    public function importPrivateUserUnlockBalance()
    {
        try {
            Excel::import(new PrivateUserUnlockBalanceImport($this->privateUserUnlockBalance), request()->file('file'));
        } catch (ValidationException $e) {
            Log::info($e);
        }
    }
}
