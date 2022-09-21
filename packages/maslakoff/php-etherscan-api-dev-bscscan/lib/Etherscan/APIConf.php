<?php

namespace Etherscan;

/**
 * Class APIConf
 *
 * @author Maslakou Ihar <igormaslakoff@gmail.com>
 */
class APIConf
{
    public const API_URL = 'https://api.etherscan.io/api';

    public const API_URL_BSCSCAN = 'https://api.bscscan.com/api';

    public const TESTNET_GOERLI = 'api-goerli';

    public const TESTNET_ROPSTEN = 'api-ropsten';

    public const TESTNET_KOVAN = 'api-kovan';

    public const TESTNET_RINKEBY = 'api-rinkeby';

    public const NET_BSC = 'api-bscscan';

    public const TESTNET_BSC = 'api-testnet';

    public const TAG_EARLIEST = 'earliest';

    public const TAG_LATEST = 'latest';

    public const TAG_PENDING = 'pending';

    public const BLOCK_TYPE_BLOCKS = 'blocks';

    public const BLOCK_TYPE_UNCLES = 'uncles';

    public const BLOCK_CLOSEST_BEFORE = 'before';

    public const BLOCK_CLOSEST_AFTER = 'after';

    public static $blockTypes = [
        self::BLOCK_TYPE_BLOCKS, self::BLOCK_TYPE_UNCLES,
    ];

    /**
     * Returns API URL
     *
     * @param  null  $net
     * @return string
     */
    public static function getAPIUrl($net = null)
    {
        if (is_null($net)) {
            return self::API_URL;
        } elseif ($net === self::NET_BSC) {
            return self::API_URL_BSCSCAN;
        } elseif ($net === self::TESTNET_BSC) {
            return "https://{$net}.bscscan.com/api";
        }

        return "https://{$net}.etherscan.io/api";
    }
}
