<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Admin
 *
 * @property int $id
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property int $status
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereUpdatedAt($value)
 */
	class Admin extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CashFlow
 *
 * @property int $id
 * @property int $user_id
 * @property int $token_id
 * @property string $amount
 * @property int $type
 * @property string $transaction_type
 * @property string $tx_hash
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|CashFlow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashFlow newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashFlow query()
 * @method static \Illuminate\Database\Eloquent\Builder|CashFlow whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashFlow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashFlow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashFlow whereTokenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashFlow whereTransactionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashFlow whereTxHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashFlow whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashFlow whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashFlow whereUserId($value)
 */
	class CashFlow extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LockInfo
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|LockInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LockInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LockInfo query()
 */
	class LockInfo extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Nft
 *
 * @property int $id
 * @property string $serial_no
 * @property int $type_id
 * @property string $nft_id
 * @property int $nft_owner_id
 * @property string|null $tx_hash
 * @property string $image_url
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\NftType $nft_type
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|Nft newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Nft newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Nft query()
 * @method static \Illuminate\Database\Eloquent\Builder|Nft whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nft whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nft whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nft whereNftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nft whereNftOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nft whereSerialNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nft whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nft whereTxHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nft whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nft whereUpdatedAt($value)
 */
	class Nft extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\NftAuctionHistory
 *
 * @property int $id
 * @property int $user_id
 * @property int $token_id
 * @property int $nft_auction_id
 * @property string $amount
 * @property string $status
 * @property string $tx_hash
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TokenMaster $token_master
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|NftAuctionHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NftAuctionHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NftAuctionHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|NftAuctionHistory whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NftAuctionHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NftAuctionHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NftAuctionHistory whereNftAuctionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NftAuctionHistory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NftAuctionHistory whereTokenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NftAuctionHistory whereTxHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NftAuctionHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NftAuctionHistory whereUserId($value)
 */
	class NftAuctionHistory extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\NftAuctionInfo
 *
 * @property int $id
 * @property string $start_date
 * @property string $end_date
 * @property string $min_price
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\NftAuctionInfoFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|NftAuctionInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NftAuctionInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NftAuctionInfo query()
 * @method static \Illuminate\Database\Eloquent\Builder|NftAuctionInfo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NftAuctionInfo whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NftAuctionInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NftAuctionInfo whereMinPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NftAuctionInfo whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NftAuctionInfo whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NftAuctionInfo whereUpdatedAt($value)
 */
	class NftAuctionInfo extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\NftType
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|NftType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NftType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NftType query()
 * @method static \Illuminate\Database\Eloquent\Builder|NftType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NftType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NftType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NftType whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NftType whereUpdatedAt($value)
 */
	class NftType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TokenMaster
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $description
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|TokenMaster newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TokenMaster newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TokenMaster query()
 * @method static \Illuminate\Database\Eloquent\Builder|TokenMaster whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenMaster whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenMaster whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenMaster whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenMaster whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenMaster whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenMaster whereUpdatedAt($value)
 */
	class TokenMaster extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TokenSaleHistory
 *
 * @property int $id
 * @property int $user_id
 * @property int $token_id
 * @property int $token_sale_id
 * @property string $amount
 * @property string $status
 * @property string $tx_hash
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TokenMaster $token_master
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|TokenSaleHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TokenSaleHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TokenSaleHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|TokenSaleHistory whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenSaleHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenSaleHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenSaleHistory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenSaleHistory whereTokenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenSaleHistory whereTokenSaleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenSaleHistory whereTxHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenSaleHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenSaleHistory whereUserId($value)
 */
	class TokenSaleHistory extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TokenSaleInfo
 *
 * @property int $id
 * @property int $rule_id
 * @property string $start_date
 * @property string $end_date
 * @property int $total
 * @property float|null $total_supply
 * @property string $price
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\TokenSaleInfoFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenSaleInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TokenSaleInfo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TokenSaleInfo query()
 * @method static \Illuminate\Database\Eloquent\Builder|TokenSaleInfo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenSaleInfo whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenSaleInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenSaleInfo wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenSaleInfo whereRuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenSaleInfo whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenSaleInfo whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenSaleInfo whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenSaleInfo whereTotalSupply($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenSaleInfo whereUpdatedAt($value)
 */
	class TokenSaleInfo extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TokenUnlockRule
 *
 * @property int $id
 * @property \Illuminate\Database\Eloquent\Collection|TokenUnlockRule[] $rule_code
 * @property int $period
 * @property string $unit
 * @property string $unlock_percentages
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read int|null $rule_code_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|TokenUnlockRule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TokenUnlockRule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TokenUnlockRule query()
 * @method static \Illuminate\Database\Eloquent\Builder|TokenUnlockRule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenUnlockRule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenUnlockRule wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenUnlockRule whereRuleCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenUnlockRule whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenUnlockRule whereUnlockPercentages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenUnlockRule whereUpdatedAt($value)
 */
	class TokenUnlockRule extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UnlockBalanceHistory
 *
 * @property int $id
 * @property int $unlock_id
 * @property string $amount
 * @property string $release_token_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \App\Models\UnlockUserBalance $unlock_user_balance
 * @method static \Illuminate\Database\Eloquent\Builder|UnlockBalanceHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UnlockBalanceHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UnlockBalanceHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|UnlockBalanceHistory whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnlockBalanceHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnlockBalanceHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnlockBalanceHistory whereReleaseTokenDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnlockBalanceHistory whereUnlockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnlockBalanceHistory whereUpdatedAt($value)
 */
	class UnlockBalanceHistory extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UnlockUserBalance
 *
 * @property int $id
 * @property int $token_id
 * @property int $token_sale_id
 * @property int $user_id
 * @property string $amount_lock
 * @property string $amount_lock_remain
 * @property string|null $next_run_date
 * @property int $current_order_unlock
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TokenMaster $token_master
 * @property-read \App\Models\TokenSaleInfo $token_sale
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|UnlockUserBalance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UnlockUserBalance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UnlockUserBalance query()
 * @method static \Illuminate\Database\Eloquent\Builder|UnlockUserBalance whereAmountLock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnlockUserBalance whereAmountLockRemain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnlockUserBalance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnlockUserBalance whereCurrentOrderUnlock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnlockUserBalance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnlockUserBalance whereNextRunDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnlockUserBalance whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnlockUserBalance whereTokenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnlockUserBalance whereTokenSaleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnlockUserBalance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnlockUserBalance whereUserId($value)
 */
	class UnlockUserBalance extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string|null $email
 * @property string|null $wallet_address
 * @property string|null $token_validate
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTokenValidate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereWalletAddress($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserBalance
 *
 * @property int $id
 * @property int $user_id
 * @property int $token_id
 * @property string $amount_total
 * @property string $amount_lock
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TokenMaster $token_master
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|UserBalance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserBalance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserBalance query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserBalance whereAmountLock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBalance whereAmountTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBalance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBalance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBalance whereTokenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBalance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBalance whereUserId($value)
 */
	class UserBalance extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserWithdrawal
 *
 * @property int $id
 * @property int $user_id
 * @property int $token_id
 * @property string $amount
 * @property string $request_time
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal whereRequestTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal whereTokenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal whereUserId($value)
 */
	class UserWithdrawal extends \Eloquent {}
}

