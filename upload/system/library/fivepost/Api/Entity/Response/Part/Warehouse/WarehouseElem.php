<?php


namespace Ipol\Fivepost\Api\Entity\Response\Part\Warehouse;


use Ipol\Fivepost\Api\Entity\AbstractEntity;
use Ipol\Fivepost\Api\Entity\Response\Part\AbstractResponsePart;

/**
 * Class WarehouseElem
 * @package Ipol\Fivepost\Api\Entity\Response\Part\Warehouse
 */
class WarehouseElem extends AbstractEntity
{
    use AbstractResponsePart;

    /**
     * @var string (uuid)
     */
    protected $id;
    /**
     * @var string
     */
    protected $status;
    /**
     * @var string
     */
    protected $description;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return WarehouseElem
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return WarehouseElem
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return WarehouseElem
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
}