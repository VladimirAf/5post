<?php


namespace Ipol\Fivepost\Api\Entity\Request;


class GetOrderHistory extends AbstractRequest
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
     * @return GetOrderHistory
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
     * @return GetOrderHistory
     */
    public function setSenderOrderId($senderOrderId)
    {
        $this->senderOrderId = $senderOrderId;
        return $this;
    }

}