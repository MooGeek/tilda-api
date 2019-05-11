<?php

namespace Moogeek\Tilda;

use Moogeek\Tilda\Client as TildaClient;
use Moogeek\Tilda\Project as TildaProject;

/**
 * Tilda account wrapper.
 */
class Account
{
    /**
     * @var TildaClient tilda API client
     */
    private $_client;

    /**
     * @param string $publicKey API public key
     * @param string $secretKey API secret key
     */
    public function __construct(string $publicKey, string $secretKey)
    {
        $this->_client = new TildaClient($publicKey, $secretKey);
    }

    /**
     * @param int $id project ID
     */
    public function getProject(int $id)
    {
        $project = new TildaProject($this->_client, $id);

        return $project->fetch();
    }

    /**
     * @return array
     */
    public function getProjects()
    {
        $response = $this->_client->call(
            'GET',
            'getprojectslist',
            []
        );

        $result = [];
        foreach ($response->result as $projectData) {
            $project = new TildaProject($this->_client, $projectData->id);

            $project->setTitle($projectData->title);
            $project->setDescription($projectData->descr);

            $result[] = $project;
        }

        return $result;
    }
}
