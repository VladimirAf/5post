<?php


namespace Ipol\Fivepost\Api\Entity\Response\Part\GetOrderStatus;

use Ipol\Fivepost\Api\Entity\AbstractCollection;

class OrderStatusList extends AbstractCollection
{
    protected $OrderStatuses;

    public function __construct()
    {
        parent::__construct('OrderStatuses');
        $this->setChildClass(OrderStatus::class); //TODO: check if not absolute path works well
        return $this;
    }

    /**
     * @return OrderStatus
     */
    public function getFirst()
    {
        return parent::getFirst();
    }

    /**
     * @return OrderStatus
     */
    public function getNext()
    {
        return parent::getNext();
    }
}