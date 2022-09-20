<?php

namespace Etherscan;

/**
 * Class APIConf
 *
 * @author Maslakou Ihar <igormaslakoff@gmail.com>
 */
class APIConf
{
    const API_URL = 'https://api.etherscan.io/api';

    const API_URL_BSCSCAN = 'https://api.bscscan.com/api';

    const TESTNET_GOERLI = 'api-goerli';

    const TESTNET_ROPSTEN = 'api-ropsten';

    const TESTNET_KOVAN = 'api-kovan';

    const TESTNET_RINKEBY = 'api-rinkeby';

    const NET_BSC = 'api-bscscan';

    const TESTNET_BSC = 'api-testnet';

    const TAG_EARLIEST = 'earliest';

    const TAG_LATEST = 'latest';

    const TAG_PENDING = 'pending';

    const BLOCK_TYPE_BLOCKS = 'blocks';

    const BLOCK_TYPE_UNCLES = 'uncles';

    const BLOCK_CLOSEST_BEFORE = 'before';

    const BLOCK_CLOSEST_AFTER = 'after';

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
