<?php

namespace Etherscan;

use Etherscan\Api\ApiInterface;
use Etherscan\Exception\InvalidArgumentException;

/**
 * Simple PHP Etherscan client.
 *
 * Class Client
 *
 * @author Maslakou Ihar <igormaslakoff@gmail.com>
 */
class Client
{
    /**
     * Etherscan API key token.
     *
     * @var string
     */
    private $apiKeyToken;

    /**
     * @var string testnet name or mainnet by default.
     */
    private $netName;

    public function __construct($apiKeyToken = null, $netName = null)
    {
        if (is_null($apiKeyToken)) {
            return;
        }

        $this->apiKeyToken = $apiKeyToken;
        $this->netName = $netName;
    }

    /**
     * @param  string  $name
     * @return ApiInterface
     *
     * @throws InvalidArgumentException
     */
    public function api($name)
    {
        switch ($name) {
            case 'account':
                $api = new Api\Account($this);
                break;
            case 'block':
                $api = new Api\Block($this);
                break;
            case 'contract':
                $api = new Api\Contract($this);
                break;
                break;
            case 'gas':
                $api = new Api\Gas($this);
                break;
            case 'eventLog':
                $api = new Api\EventLog($this);
                break;
            case 'proxy':
                $api = new Api\Proxy($this);
                break;
            case 'stats':
                $api = new Api\Stats($this);
                break;
            case 'token':
                $api = new Api\Token($this);
                break;
            case 'transaction':
                $api = new Api\Transaction($this);
                break;
            case 'websocket':
                $api = new Api\Websocket($this);
                break;
            default:
                throw new InvalidArgumentException(sprintf('Undefined api instance called: "%s"', $name));
        }

        return $api;
    }

    /**
     * @return string
     */
    public function getApiKeyToken(): string
    {
        return $this->apiKeyToken;
    }

    /**
     * @return string|null
     */
    public function getNetName(): ?string
    {
        return $this->netName;
    }
}
