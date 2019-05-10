<?php

namespace Moogeek\Tilda;

use GuzzleHttp\Client as GuzzleClient;

/**
 * Tilda API client.
 */
class Client
{
    protected $apiUrl = 'https://api.tildacdn.info/v1/';

    /**
     * @var handler
     */
    protected $client;

    /**
     * @var int
     */
    public $timeout = 20;

    /**
     * @var string
     */
    protected $publicKey;

    /**
     * @var string
     */
    protected $secretKey;

    /**
     * @var string
     */
    public $lastError = '';

    /**
     * @param string $publicKey API public key
     * @param string $secretKey API secret key
     **/
    public function __construct(string $publicKey, string $secretKey)
    {
        $this->publicKey = $publicKey;
        $this->secretKey = $secretKey;

        $this->client = new GuzzleClient(
            [
                'base_uri' => $this->apiUrl,
                'timeout' => $this->timeout,
            ]
        );
    }

    /**
     * @return object
     **/
    public function getProjectsList(): ?object
    {
        return $this->_call('getprojectslist', array());
    }

    /**
     * @return object
     **/
    public function getProject(int $projectid): ?object
    {
        return $this->_call('getproject', array('projectid' => $projectid));
    }

    /**
     * @return object
     **/
    public function getProjectExport(int $projectid): ?object
    {
        return $this->_call('getprojectexport', array('projectid' => $projectid));
    }

    /**
     * @return object
     **/
    public function getPagesList(int $projectid): ?object
    {
        return $this->_call('getpageslist', array('projectid' => $projectid));
    }

    /**
     * @return object
     **/
    public function getPage(int $pageid): ?object
    {
        return $this->_call('getpage', array('pageid' => $pageid));
    }

    /**
     * @return object
     **/
    public function getPageFull(int $pageid): ?object
    {
        return $this->_call('getpagefull', array('pageid' => $pageid));
    }

    /**
     * @return object
     **/
    public function getPageExport(int $pageid): ?object
    {
        return $this->_call('getpageexport', array('pageid' => $pageid));
    }

    /**
     * @return object
     **/
    public function getPageFullExport(int $pageid): ?object
    {
        return $this->_call('getpagefullexport', array('pageid' => $pageid));
    }

    /**
     * @param string $method API method name
     * @param array  $params API query values
     *
     * @return object response
     **/
    private function _call(string $method, array $params): ?object
    {
        $this->lastError = '';

        $tildaMethods = array(
            'getprojectslist' => array(),
            'getproject' => array('required' => array('projectid')),
            'getprojectexport' => array('required' => array('projectid')),
            'getpageslist' => array('required' => array('projectid')),
            'getpage' => array('required' => array('pageid')),
            'getpagefull' => array('required' => array('pageid')),
            'getpageexport' => array('required' => array('pageid')),
            'getpagefullexport' => array('required' => array('pageid')),
        );

        if (!isset($tildaMethods[$method])) {
            $this->lastError = 'Unknown Method: '.$method;

            return null;
        }

        if (isset($tildaMethods[$method]['required'])) {
            foreach ($tildaMethods[$method]['required'] as $param) {
                if (!isset($params[$param])) {
                    $this->lastError = 'Param ['.$param.'] required for method ['.$method.']';

                    return null;
                }
            }
        }

        $params['publickey'] = $this->publicKey;
        $params['secretkey'] = $this->secretKey;

        try {
            $response = $this->client->request('GET', $method, ['query' => $params]);
            $result = json_decode($response->getBody());
        } catch (Exception $e) {
            $this->lastError = 'Network error ['.$e->getMessage().']';

            return null;
        }

        if ($result) {
            if (!empty($result->status)) {
                if ($result->status == 'FOUND') {
                    return $result->result;
                } elseif (isset($result->message)) {
                    $this->lastError = $result->message;
                } else {
                    $this->lastError = 'Data not found';
                }

                return null;
            } else {
                $this->lastError = 'Data not found';

                return null;
            }

            return $result;
        } else {
            $this->lastError = 'Unknown Error ['.$result.']';

            return  null;
        }
    }
}
