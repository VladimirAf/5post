<?php


namespace Ipol\Fivepost\Fivepost\Controller;


use Ipol\Fivepost\Api\Entity\Request\Part\Warehouse\WarehouseElemList;
use \Ipol\Fivepost\Api\Entity\Request\Warehouse as WarehouseRequest;
use Ipol\Fivepost\Fivepost\Entity\WarehouseResult;

/**
 * Class Warehouse
 * @package Ipol\Fivepost\Fivepost
 * @subpackage Controller
 */
class Warehouse extends AutomatedCommonRequest
{
    /**
     * Warehouse constructor.
     * @param WarehouseElemList $warehouses
     */
    public function __construct(WarehouseElemList $warehouses)
    {
        parent::__construct(new WarehouseResult());

        $data = new WarehouseRequest();
        $data->setWarehouses($warehouses);
        $this->setRequestObj($data);
    }

}