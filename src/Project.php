<?php

namespace Moogeek\Tilda;

use Moogeek\Tilda\Client as TildaClient;
use Moogeek\Tilda\Page as TildaPage;

/**
 * Tilda project wrapper.
 */
class Project
{
    /**
     * @var Client API client
     */
    private $_client;

    /**
     * @var int project id
     */
    private $_id;

    /**
     * @var string project title
     */
    private $_title;

    /**
     * @var string project description
     */
    private $_description;

    /**
     * @var string project custom domain
     */
    private $_domain;

    /**
     * @var array project css files
     */
    private $_css;

    /**
     * @var array project js files
     */
    private $_js;

    /**
     * @var stdClass export data
     */
    private $_export;

    /**
     * @param TildaClient $client API client
     * @param int         $id     project id
     */
    public function __construct(TildaClient $client, int $id)
    {
        $this->_id = $id;
        $this->_client = $client;
    }

    /**
     * Fetches and sets project info.
     */
    public function fetch()
    {
        $response = $this->_client->call(
            'GET',
            'getproject',
            [
                'projectid' => $this->_id,
            ]
        );

        $this->setTitle($response->result->title);
        $this->setDescription($response->result->descr);
        $this->setDomain($response->result->customdomain);
        $this->setCss($response->result->css);
        $this->setJs($response->result->js);

        return $this;
    }

    /**
     * @return stdClass project export data
     */
    public function getExport()
    {
        $response = $this->_client->call(
            'GET',
            'getprojectexport',
            [
                'projectid' => $this->_id,
            ]
        );

        $result = new \stdClass();
        $result->cssPath = $response->result->export_csspath;
        $result->jsPath = $response->result->export_jspath;
        $result->imgPath = $response->result->export_imgpath;
        $result->indexPageId = $response->result->indexpageid;
        $result->favicon = $response->result->favicon;
        $result->page404id = $response->result->page404id;
        $result->images = $response->result->images;
        $result->htaccess = $response->result->htaccess;
        $result->css = $response->result->css;
        $result->js = $response->result->js;

        return $result;
    }

    /**
     * @return array pages
     */
    public function getPages()
    {
        $response = $this->_client->call(
            'GET',
            'getpageslist',
            [
                'projectid' => $this->_id,
            ]
        );

        $result = [];
        foreach ($response->result as $pageData) {
            $page = new TildaPage($this->_client, $pageData->id);

            $page->setTitle($pageData->title);
            $page->setDescription($pageData->descr);
            $page->setImage($pageData->img);
            $page->setFeatureImage($pageData->featureimg);
            $page->setAlias($pageData->alias);
            $page->setDate($pageData->date);
            $page->setSort($pageData->sort);
            $page->setPublished($pageData->published);
            $page->setFilename($pageData->filename);

            $result[] = $page;
        }

        return $result;
    }

    /**
     * @param int $id page id
     *
     * @return TildaPage page
     */
    public function getPage(int $id)
    {
        $page = new TildaPage($this->_client, $id);

        return $page->fetch();
    }

    /**
     * @return string title
     */
    public function getTitle(): ?string
    {
        return $this->_title;
    }

    /**
     * @param string $title project title
     */
    public function setTitle(?string $title)
    {
        $this->_title = $title;
    }

    /**
     * @return string description
     */
    public function getDescription(): ?string
    {
        return $this->_description;
    }

    /**
     * @param string $description project description
     */
    public function setDescription(?string $description)
    {
        $this->_description = $description;
    }

    /**
     * @return string domain
     */
    public function getDomain(): ?string
    {
        return $this->_domain;
    }

    /**
     * @param string $domain project custom domain
     */
    public function setDomain(?string $domain)
    {
        $this->_domain = $domain;
    }

    /**
     * @return array css
     */
    public function getCss(): array
    {
        return $this->_css;
    }

    /**
     * @param array $css project css files
     */
    public function setCss(?array $css)
    {
        $this->_css = $css;
    }

    /**
     * @return array js
     */
    public function getJs(): array
    {
        return $this->_js;
    }

    /**
     * @param array $js project js files
     */
    public function setJs(?array $js)
    {
        $this->_js = $js;
    }
}
