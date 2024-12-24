<?php


namespace Ipol\Fivepost\Api\Entity\Request;

use Ipol\Fivepost\Api\Entity\Request\Part\Warehouse\WarehouseElemList;

/**
 * Class Warehouse
 * @package Ipol\Fivepost\Api\Entity\Request
 */
class Warehouse extends AbstractRequest
{
    /**
     * @var WarehouseElemList
     */
    protected $warehouses;

    /**
     * @return WarehouseElemList
     */
    public function getWarehouses()
    {
        return $this->warehouses;
    }

    /**
     * @param WarehouseElemList $warehouses
     * @return Warehouse
     */
    public function setWarehouses($warehouses)
    {
        $this->warehouses = $warehouses;
        return $this;
    }

}