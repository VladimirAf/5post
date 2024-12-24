<?php


namespace Ipol\Fivepost\Api\Entity\Response;

use Ipol\Fivepost\Api\Entity\Response\Part\Warehouse\WarehouseElemList;
use Ipol\Fivepost\Api\Tools;

/**
 * Class Warehouse
 * @package Ipol\Fivepost\Api\Entity\Response
 */
class Warehouse extends AbstractResponse
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
     * @param array $array
     * @return Warehouse
     * @throws \Exception
     */
    public function setWarehouses($array)
    {
        if(Tools::isSeqArr($array))
        {
            $collection = new WarehouseElemList();
            $this->warehouses = $collection->fillFromArray($array);
            return $this;
        }
        else
        {
            throw new \Exception(__FUNCTION__.' requires parameter to be SEQUENTIAL array. '. gettype($array). ' given.');
        }
    }

    public function setDecoded($decoded)
    {
        if(Tools::isSeqArr($decoded))
        {
            parent::setDecoded(['warehouses' => $decoded]);
        }
        else{
            parent::setDecoded($decoded);
        }
        return $this;
    }

}