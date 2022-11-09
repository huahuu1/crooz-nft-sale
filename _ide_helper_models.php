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
 * App\Models\AuctionNft
 *
 * @property int $id
 * @property string|null $wallet_address
 * @property int $nft_id
 * @property int $nft_delivery_source_id
 * @property int|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Nft $nfts
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|AuctionNft newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AuctionNft newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AuctionNft query()
 * @method static \Illuminate\Database\Eloquent\Builder|AuctionNft whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuctionNft whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuctionNft whereNftDeliverySourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuctionNft whereNftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuctionNft whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuctionNft whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuctionNft whereWalletAddress($value)
 */
	class AuctionNft extends \Eloquent {}
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
 * @property string|null $payment_method
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
 * @method static \Illuminate\Database\Eloquent\Builder|CashFlow wherePaymentMethod($value)
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
 * App\Models\ExchangeRate
 *
 * @property int $id
 * @property string $symbol
 * @property string $rate
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|ExchangeRate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExchangeRate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExchangeRate query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExchangeRate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExchangeRate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExchangeRate whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExchangeRate whereSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExchangeRate whereUpdatedAt($value)
 */
	class ExchangeRate extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\GachaTicket
 *
 * @property int $id
 * @property int $user_id
 * @property int $ticket_type
 * @property int $total_ticket
 * @property int $remain_ticket
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|GachaTicket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GachaTicket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GachaTicket query()
 * @method static \Illuminate\Database\Eloquent\Builder|GachaTicket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GachaTicket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GachaTicket whereRemainTicket($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GachaTicket whereTicketType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GachaTicket whereTotalTicket($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GachaTicket whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GachaTicket whereUserId($value)
 */
	class GachaTicket extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\GxePartnerUsers
 *
 * @property int $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|GxePartnerUsers newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GxePartnerUsers newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GxePartnerUsers query()
 * @method static \Illuminate\Database\Eloquent\Builder|GxePartnerUsers whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GxePartnerUsers whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GxePartnerUsers whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GxePartnerUsers whereUserId($value)
 */
	class GxePartnerUsers extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\NetworkMaster
 *
 * @property int $id
 * @property string $chain_id
 * @property string $rpc_urls
 * @property string $block_explorer_urls
 * @property string $chain_name
 * @property string $unit
 * @property string|null $contract_wallet
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|NetworkMaster newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NetworkMaster newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NetworkMaster query()
 * @method static \Illuminate\Database\Eloquent\Builder|NetworkMaster whereBlockExplorerUrls($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NetworkMaster whereChainId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NetworkMaster whereChainName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NetworkMaster whereContractWallet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NetworkMaster whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NetworkMaster whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NetworkMaster whereRpcUrls($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NetworkMaster whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NetworkMaster whereUpdatedAt($value)
 */
	class NetworkMaster extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Nft
 *
 * @property int $nft_id
 * @property int $nft_type
 * @property string $name
 * @property string $image_url
 * @property int|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AuctionNft[] $auctionNfts
 * @property-read int|null $auction_nfts_count
 * @property-read \App\Models\NftType $nftType
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|Nft newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Nft newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Nft query()
 * @method static \Illuminate\Database\Eloquent\Builder|Nft whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nft whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nft whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nft whereNftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nft whereNftType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nft whereStatus($value)
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
 * @property string|null $payment_method
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TokenMaster $tokenMaster
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
 * @method static \Illuminate\Database\Eloquent\Builder|NftAuctionHistory wherePaymentMethod($value)
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
 * @property string|null $name
 * @property string|null $fixed_price
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
 * @method static \Illuminate\Database\Eloquent\Builder|NftAuctionInfo whereFixedPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NftAuctionInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NftAuctionInfo whereMinPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NftAuctionInfo whereName($value)
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Nft[] $auctionNfts
 * @property-read int|null $auction_nfts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Nft[] $nfts
 * @property-read int|null $nfts_count
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
 * App\Models\PasswordReset
 *
 * @property int $id
 * @property string $email
 * @property string $token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset query()
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PasswordReset whereUpdatedAt($value)
 */
	class PasswordReset extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PrivateUnlockBalanceHistory
 *
 * @property int $id
 * @property int $unlock_id
 * @property string $amount
 * @property string $unlock_token_date
 * @property int $admin_id
 * @property int $network_id
 * @property string $tx_hash
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $admin
 * @property-read \App\Models\PrivateUserUnlockBalance $privateUserUnlockBalance
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|PrivateUnlockBalanceHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrivateUnlockBalanceHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrivateUnlockBalanceHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|PrivateUnlockBalanceHistory whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrivateUnlockBalanceHistory whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrivateUnlockBalanceHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrivateUnlockBalanceHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrivateUnlockBalanceHistory whereNetworkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrivateUnlockBalanceHistory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrivateUnlockBalanceHistory whereTxHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrivateUnlockBalanceHistory whereUnlockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrivateUnlockBalanceHistory whereUnlockTokenDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrivateUnlockBalanceHistory whereUpdatedAt($value)
 */
	class PrivateUnlockBalanceHistory extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PrivateUserUnlockBalance
 *
 * @property int $id
 * @property int $token_id
 * @property int $token_type
 * @property int $investor_classification
 * @property string $investor_name
 * @property string $wallet_address
 * @property string $token_unlock_volume
 * @property string|null $unlock_date
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TokenMaster $tokenMaster
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|PrivateUserUnlockBalance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrivateUserUnlockBalance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PrivateUserUnlockBalance query()
 * @method static \Illuminate\Database\Eloquent\Builder|PrivateUserUnlockBalance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrivateUserUnlockBalance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrivateUserUnlockBalance whereInvestorClassification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrivateUserUnlockBalance whereInvestorName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrivateUserUnlockBalance whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrivateUserUnlockBalance whereTokenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrivateUserUnlockBalance whereTokenType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrivateUserUnlockBalance whereTokenUnlockVolume($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrivateUserUnlockBalance whereUnlockDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrivateUserUnlockBalance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PrivateUserUnlockBalance whereWalletAddress($value)
 */
	class PrivateUserUnlockBalance extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TicketUsedHistory
 *
 * @property int $id
 * @property int $gacha_ticket_id
 * @property int $used_quantity
 * @property string $used_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|TicketUsedHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketUsedHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketUsedHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketUsedHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketUsedHistory whereGachaTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketUsedHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketUsedHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketUsedHistory whereUsedQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketUsedHistory whereUsedTime($value)
 */
	class TicketUsedHistory extends \Eloquent {}
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
 * @property int|null $network_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\NetworkMaster|null $networkMaster
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
 * @method static \Illuminate\Database\Eloquent\Builder|TokenMaster whereNetworkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenMaster whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TokenMaster whereUpdatedAt($value)
 */
	class TokenMaster extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string|null $email
 * @property string|null $wallet_address
 * @property string|null $password
 * @property string|null $token_validate
 * @property int $status
 * @property int $vip_member
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\GxePartnerUsers|null $gxePartnerUser
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
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTokenValidate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereVipMember($value)
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
 * @property-read \App\Models\TokenMaster $tokenMaster
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
 * @property int $private_unlock_id
 * @property string $amount
 * @property string $request_time
 * @property string $status
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PrivateUserUnlockBalance $privateUnlock
 * @property-read \App\Models\PrivateUnlockBalanceHistory $privateUnlockHistory
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal wherePrivateUnlockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal whereRequestTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal whereTokenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal whereUserId($value)
 */
	class UserWithdrawal extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\WhitelistUser
 *
 * @property int $id
 * @property int $user_id
 * @property int $nft_auction_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|WhitelistUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WhitelistUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WhitelistUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|WhitelistUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WhitelistUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WhitelistUser whereNftAuctionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WhitelistUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WhitelistUser whereUserId($value)
 */
	class WhitelistUser extends \Eloquent {}
}

