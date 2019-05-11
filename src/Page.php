<?php

namespace Moogeek\Tilda;

use Moogeek\Tilda\Client as TildaClient;

/**
 * Tilda page wrapper.
 */
class Page
{
    /**
     * @var Client API client
     */
    private $_client;

    /**
     * @var int page id
     */
    private $_id;

    /**
     * @var string page title
     */
    private $_title;

    /**
     * @var string page description
     */
    private $_description;

    /**
     * @var string image
     */
    private $_image;

    /**
     * @var string feature image
     */
    private $_featureImage;

    /**
     * @var string alias
     */
    private $_alias;

    /**
     * @var string date
     */
    private $_date;

    /**
     * @var string published
     */
    private $_published;

    /**
     * @var string filename
     */
    private $_filename;

    /**
     * @var string html
     */
    private $_html;

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
     * Fetches and sets page info.
     */
    public function fetch()
    {
        $response = $this->_client->call(
            'GET',
            'getpagefull',
            [
                'pageid' => $this->_id,
            ]
        );

        $this->setTitle($response->result->title);
        $this->setDescription($response->result->descr);
        $this->setImage($response->result->img);
        $this->setFeatureImage($response->result->featureimg);
        $this->setAlias($response->result->alias);
        $this->setDate($response->result->date);
        $this->setSort($response->result->sort);
        $this->setPublished($response->result->published);
        $this->setFilename($response->result->filename);
        $this->setHtml($response->result->html);

        return $this;
    }

    /**
     * @return string page body contents
     */
    public function getBody()
    {
        $response = $this->_client->call(
            'GET',
            'getpage',
            [
                'pageid' => $this->_id,
            ]
        );

        return $response->result->html;
    }

    /**
     * @return stdClass page export data
     */
    public function getExport()
    {
        $response = $this->_client->call(
            'GET',
            'getpagefullexport',
            [
                'pageid' => $this->_id,
            ]
        );

        $result = new \stdClass();
        $result->html = $response->result->html;
        $result->images = $response->result->images;
        $result->css = $response->result->css;
        $result->js = $response->result->js;

        return $result;
    }

    /**
     * @return stdClass page body export data
     */
    public function getBodyExport()
    {
        $response = $this->_client->call(
            'GET',
            'getpageexport',
            [
                'pageid' => $this->_id,
            ]
        );

        $result = new \stdClass();
        $result->html = $response->result->html;
        $result->images = $response->result->images;
        $result->css = $response->result->css;
        $result->js = $response->result->js;

        return $result;
    }

    /**
     * @return string title
     */
    public function getTitle(): ?string
    {
        return $this->_title;
    }

    /**
     * @param string $title page title
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
     * @param string $description page description
     */
    public function setDescription(?string $description)
    {
        $this->_description = $description;
    }

    /**
     * @return string image
     */
    public function getImage(): ?string
    {
        return $this->_image;
    }

    /**
     * @param string $image page image
     */
    public function setImage(?string $image)
    {
        $this->_image = $image;
    }

    /**
     * @return string feature image
     */
    public function getFeatureImage(): ?string
    {
        return $this->_featureImage;
    }

    /**
     * @param string $featureImage page feature image
     */
    public function setFeatureImage(?string $featureImage)
    {
        $this->_featureImage = $featureImage;
    }

    /**
     * @return string alias
     */
    public function getAlias(): ?string
    {
        return $this->_alias;
    }

    /**
     * @param string $alias page alias
     */
    public function setAlias(?string $alias)
    {
        $this->_alias = $alias;
    }

    /**
     * @return string date
     */
    public function getDate(): ?string
    {
        return $this->_date;
    }

    /**
     * @param string $date page date
     */
    public function setDate(?string $date)
    {
        $this->_date = $date;
    }

    /**
     * @return string sort
     */
    public function getSort(): ?int
    {
        return $this->_sort;
    }

    /**
     * @param int $sort page sort
     */
    public function setSort(?int $sort)
    {
        $this->_sort = $sort;
    }

    /**
     * @return string published
     */
    public function getPublished(): ?string
    {
        return $this->_published;
    }

    /**
     * @param int $published page published
     */
    public function setPublished(?string $published)
    {
        $this->_published = $published;
    }

    /**
     * @param string filename
     */
    public function getFilename(): ?string
    {
        return $this->_filename;
    }

    /**
     * @param string $filename page filename
     */
    public function setFilename(?string $filename)
    {
        $this->_filename = $filename;
    }

    /**
     * @return string html
     */
    public function getHtml(): ?string
    {
        return $this->_html;
    }

    /**
     * @param string $html page html
     */
    public function setHtml(?string $html)
    {
        $this->_html = $html;
    }
}
