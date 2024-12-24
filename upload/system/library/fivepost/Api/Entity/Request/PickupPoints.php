<?php


namespace Ipol\Fivepost\Api\Entity\Request;


/**
 * Class PickupPoints
 * @package Ipol\Fivepost\Api\Entity\Request
 */
class PickupPoints extends AbstractRequest
{
    /**
     * @var int
     */
    protected $pageSize;
    /**
     * @var int
     */
    protected $pageNumber;

    /**
     * @return int
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * @param int $pageSize
     * @return PickupPoints
     */
    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
        return $this;
    }

    /**
     * @return int
     */
    public function getPageNumber()
    {
        return $this->pageNumber;
    }

    /**
     * @param int $pageNumber
     * @return PickupPoints
     */
    public function setPageNumber($pageNumber)
    {
        $this->pageNumber = $pageNumber;
        return $this;
    }

}