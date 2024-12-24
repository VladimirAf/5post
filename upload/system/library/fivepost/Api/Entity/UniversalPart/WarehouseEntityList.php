<?php
namespace Ipol\Fivepost\Api\Entity\UniversalPart;

use Ipol\Fivepost\Api\Entity\AbstractCollection;

/**
 * Class WarehouseEntityList
 * @package Ipol\Fivepost\Api
 * @subpackage Entity\UniversalPart
 * @method WarehouseEntity getFirst
 * @method WarehouseEntity getNext
 * @method WarehouseEntity getLast
 */
class WarehouseEntityList extends AbstractCollection
{
    protected $WarehouseEntities;

    public function __construct()
    {
        parent::__construct('WarehouseEntities');
        $this->setChildClass(WarehouseEntity::class);
    }
}