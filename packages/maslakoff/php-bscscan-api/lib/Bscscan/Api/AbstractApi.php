<?php

namespace Bscscan\Api;

use Bscscan\Client;
use Bscscan\HttpClient\Request;

/**
 * Class AbstractApi
 *
 * @author Maslakou Ihar <igormaslakoff@gmail.com>
 */
abstract class AbstractApi implements ApiInterface
{
    /**
     * The client.
     *
     * @var Client
     */
    protected $client;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @param  Client  $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;

        $this->request = new Request($client->getApiKeyToken(), $client->getNetName());
    }
}
