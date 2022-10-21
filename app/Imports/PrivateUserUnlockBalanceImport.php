<?php

namespace App\Imports;

use App\Models\PrivateUserUnlockBalance;
use App\Models\TokenMaster;
use App\Services\UserService;
use App\Models\User;
use App\Models\UserBalance;
use App\Services\SaleInfoService;
use App\Services\UserBalanceService;
use App\Traits\CalculateNextRunDate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PrivateUserUnlockBalanceImport implements ToModel, WithHeadingRow
{
    use RemembersRowNumber;
    use CalculateNextRunDate;

    protected $privateUserUnlockBalance;

    protected $saleInfoService;

    protected $userBalanceService;

    protected $userService;

    public function __construct(PrivateUserUnlockBalance $privateUserUnlockBalance)
    {
        $this->privateUserUnlockBalance = $privateUserUnlockBalance;
        $this->userBalanceService = new UserBalanceService();
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
        //check user is existed
        $user = $this->userService->getUserByWalletAddress($row['wallet_address']);

        if (!$user) {
            $user = User::create([
                'wallet_address' => $row['wallet_address'],
                'vip_member' => User::VIP_MEMBER,
            ]);
        }

        //check user balance is existed
        $balance = $this->userBalanceService->hasBalancesByUserId($user->id);

        if (!$balance) {
            $this->userBalanceService->createDefaultUserBalance($user->id);
        }

        $user->vip_member = User::VIP_MEMBER;
        $user->save();

        //check if the token unlock date is up to date
        $currentDate = Carbon::createMidnightDate();
        $releaseDay = $currentDate->diffInDays(Date::excelToDateTimeObject($row['unlock_date'])->format('Y-m-d'), false);

        UserBalance::where('user_id', $user->id)
                   ->where('token_id', TokenMaster::GT)
                   ->update([
                       'amount_total' => DB::raw('amount_total + ' . $row['token_unlock_volume']),
                       'amount_lock' => DB::raw('amount_lock + ' . $row['token_unlock_volume']),
                   ]);

        return new PrivateUserUnlockBalance([
            'wallet_address' => $row['wallet_address'],
            'token_unlock_volume' => $row['token_unlock_volume'],
            'unlock_date' => Date::excelToDateTimeObject($row['unlock_date'])->format('Y-m-d'),
            'token_id' => TokenMaster::GT,
            'status' => $releaseDay > 0 ? 0 : 1
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
