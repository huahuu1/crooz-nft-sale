<?php

namespace App\Imports;

use App\Models\TokenSaleInfo;
use App\Models\PrivateUserUnlockBalance;
use App\Services\UserService;
use Carbon\Carbon;
use App\Models\UnlockUserBalance;
use App\Models\UserBalance;
use App\Services\SaleInfoService;
use App\Traits\CalculateNextRunDate;
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
    use CalculateNextRunDate;

    protected $privateUserUnlockBalance;

    protected $saleInfoService;

    protected $unlockUserBalance;

    protected $userService;

    public function __construct(UnlockUserBalance $unlockUserBalance, PrivateUserUnlockBalance $privateUserUnlockBalance)
    {
        $this->unlockUserBalance = $unlockUserBalance;
        $this->privateUserUnlockBalance = $privateUserUnlockBalance;
        $this->saleInfoService = new SaleInfoService();
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
                       'amount_total' => DB::raw('amount_total + ' . $row['amount_lock']),
                       'amount_lock' => DB::raw('amount_lock + ' . $row['amount_lock']),
                   ]);

        //get info of token sale
        $tokenSaleInfo = $this->saleInfoService->getSaleInfoAndUnlockRule($row['token_sale_id']);
        //the next date to run unlock
        $nextRunDate = $this->calculateNextRunDate(
            $tokenSaleInfo->token_unlock_rules[0]->unit,
            $tokenSaleInfo->token_unlock_rules[0]->period,
            $tokenSaleInfo->end_date
        );

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
