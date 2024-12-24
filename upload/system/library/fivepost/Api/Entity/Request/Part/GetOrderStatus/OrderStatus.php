<?php


namespace Ipol\Fivepost\Api\Entity\Request\Part\GetOrderStatus;


use Ipol\Fivepost\Api\Entity\AbstractEntity;

class OrderStatus extends AbstractEntity
{
    /**
     * @var string
     */
    protected $orderId;
    /**
     * @var string
     */
    protected $senderOrderId;

    /**
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param string $orderId
     * @return OrderStatus
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
        return $this;
    }

    /**
     * @return string
     */
    public function getSenderOrderId()
    {
        return $this->senderOrderId;
    }

    /**
     * @param string $senderOrderId
     * @return OrderStatus
     */
    public function setSenderOrderId($senderOrderId)
    {
        $this->senderOrderId = $senderOrderId;
        return $this;
    }
}