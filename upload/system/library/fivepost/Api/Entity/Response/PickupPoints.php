<?php


namespace Ipol\Fivepost\Api\Entity\Response;


use Ipol\Fivepost\Api\Entity\Response\Part\PickupPoint\ContentList;
use Ipol\Fivepost\Api\Tools;

/**
 * Class PickupPoints
 * @package Ipol\Fivepost\Api\Entity\Response
 */
class PickupPoints extends AbstractResponse
{
    /**
     * @var ContentList
     */
    protected $content;
    /**
     * @var int
     */
    protected $totalPages;
    /**
     * @var int
     */
    protected $totalElements;
    /**
     * @var int
     */
    protected $numberOfElements;

    /**
     * @return ContentList
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param array $content
     * @return PickupPoints
     * @throws \Exception
     */
    public function setContent($content)
    {
        if(Tools::isSeqArr($content))
        {
            $collection = new ContentList();
            $this->content = $collection->fillFromArray($content);
            return $this;
        }
        else
        {
            throw new \Exception(__FUNCTION__.' requires parameter to be SEQUENTIAL array. '. gettype($content). ' given.');
        }
    }

    /**
     * @return int
     */
    public function getTotalPages()
    {
        return $this->totalPages;
    }

    /**
     * @param int $totalPages
     * @return PickupPoints
     */
    public function setTotalPages($totalPages)
    {
        $this->totalPages = $totalPages;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalElements()
    {
        return $this->totalElements;
    }

    /**
     * @param int $totalElements
     * @return PickupPoints
     */
    public function setTotalElements($totalElements)
    {
        $this->totalElements = $totalElements;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumberOfElements()
    {
        return $this->numberOfElements;
    }

    /**
     * @param int $numberOfElements
     * @return PickupPoints
     */
    public function setNumberOfElements($numberOfElements)
    {
        $this->numberOfElements = $numberOfElements;
        return $this;
    }

}