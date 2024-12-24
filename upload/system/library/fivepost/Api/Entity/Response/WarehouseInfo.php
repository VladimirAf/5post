<?php
namespace Ipol\Fivepost\Api\Entity\Response;

use Ipol\Fivepost\Api\Entity\Response\Part\Common\WarehouseEntity;

/**
 * Class WarehouseInfo
 * @package Ipol\Fivepost\Api\Entity\Response
 */
class WarehouseInfo extends AbstractResponse
{
    /**
     * @var WarehouseEntity
     */
    protected $warehouseEntity;

    /**
     * @return WarehouseEntity
     */
    public function getWarehouseEntity(): WarehouseEntity
    {
        return $this->warehouseEntity;
    }

    /**
     * @param array $array
     * @return WarehouseInfo
     */
    public function setWarehouseEntity(array $array): WarehouseInfo
    {
        $this->warehouseEntity = new WarehouseEntity($array);
        return $this;
    }

    public function setFields($fields): WarehouseInfo
    {
        return parent::setFields(['warehouseEntity' => $fields]);
    }
}