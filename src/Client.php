<?php

namespace Moogeek\Tilda;

use GuzzleHttp\Client as GuzzleClient;

/**
 * Tilda API client.
 */
class Client
{
    /**
     * @var string Tilda API base endpoint
     */
    protected $apiUrl = 'https://api.tildacdn.info/v1/';

    /**
     * @var int
     */
    protected $timeout = 20;

    /**
     * @var handler
     */
    private $_client;

    /**
     * @var string account public key
     */
    private $_publicKey;

    /**
     * @var string account secret key
     */
    private $_secretKey;

    /**
     * @param string $publicKey API public key
     * @param string $secretKey API secret key
     **/
    public function __construct(string $publicKey, string $secretKey)
    {
        $this->_publicKey = $publicKey;
        $this->_secretKey = $secretKey;

        $this->_client = new GuzzleClient(
            [
                'base_uri' => $this->apiUrl,
                'timeout' => $this->timeout,
            ]
        );
    }

    /**
     * @param string $method HTTP request type
     * @param string $path   API method name
     * @param array  $params API query values
     *
     * @return object response
     **/
    public function call(string $method, string $path, array $params): object
    {
        $params['publickey'] = $this->_publicKey;
        $params['secretkey'] = $this->_secretKey;

        $response = $this->_client->request($method, $path, ['query' => $params]);

        return json_decode($response->getBody());
    }
}
