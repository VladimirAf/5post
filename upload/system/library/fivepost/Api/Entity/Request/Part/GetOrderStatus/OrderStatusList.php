<?php


namespace Ipol\Fivepost\Api\Entity\Request\Part\GetOrderStatus;

use Ipol\Fivepost\Api\Entity\AbstractCollection;

class OrderStatusList extends AbstractCollection
{
    protected $OrderStatuses;

    public function __construct()
    {
        parent::__construct('OrderStatuses');
        $this->setChildClass(OrderStatus::class);
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