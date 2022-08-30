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

    public const TESTNET_GOERLI = 'api-goerli';

    public const TESTNET_ROPSTEN = 'api-ropsten';

    public const TESTNET_KOVAN = 'api-kovan';

    public const TESTNET_RINKEBY = 'api-rinkeby';

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

    public const CLIENT_TYPE_GETH = 'geth';

    public const CLIENT_TYPE_PARITY = 'parity';

    public static $clientTypes = [
        self::CLIENT_TYPE_GETH, self::CLIENT_TYPE_PARITY,
    ];

    public const SYNC_MODE_DEFAULT = 'default';

    public const SYNC_MODE_ARCHIVE = 'archive';

    public static $syncModes = [
        self::SYNC_MODE_DEFAULT, self::SYNC_MODE_ARCHIVE,
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
        }

        return "https://{$net}.etherscan.io/api";
    }
}
