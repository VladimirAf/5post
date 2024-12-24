<?php


namespace Ipol\Fivepost\Api\Entity\Request;

use Ipol\Fivepost\Api\Entity\Request\Part\GetOrderStatus\OrderStatusList;

/**
 * Class GetOrderStatus
 * @package Ipol\Fivepost\Api\Entity\Request
 */
class GetOrderStatus extends AbstractRequest
{
    /**
     * @var OrderStatusList
     */
    protected $order_statuses;

    /**
     * @return OrderStatusList
     */
    public function getOrderStatuses()
    {
        return $this->order_statuses;
    }

    /**
     * @param OrderStatusList $order_statuses
     * @return GetOrderStatus
     */
    public function setOrderStatuses($order_statuses)
    {
        $this->order_statuses = $order_statuses;
        return $this;
    }

}