<?php


namespace Ipol\Fivepost\Api\Entity\Response\Part\GetOrderStatus;

use Ipol\Fivepost\Api\Entity\AbstractEntity;
use Ipol\Fivepost\Api\Entity\Response\Part\AbstractResponsePart;


/**
 * Class OrderStatus
 * @package Ipol\Fivepost\Api\Entity\Response\Part\GetOrderStatus
 */
class OrderStatus extends AbstractEntity
{
    use AbstractResponsePart;

    /**
     * @var string
     */
    protected $status;
    /**
     * @var string
     */
    protected $orderId;
    /**
     * @var string
     */
    protected $senderOrderId;
    /**
     * @var string
     */
    protected $executionStatus;
    /**
     * @var string
     */
    protected $changeDate;

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return OrderStatus
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

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

    /**
     * @return string
     */
    public function getExecutionStatus()
    {
        return $this->executionStatus;
    }

    /**
     * @param string $executionStatus
     * @return OrderStatus
     */
    public function setExecutionStatus($executionStatus)
    {
        $this->executionStatus = $executionStatus;
        return $this;
    }

    /**
     * @return string
     */
    public function getChangeDate()
    {
        return $this->changeDate;
    }

    /**
     * @param string $changeDate
     * @return OrderStatus
     */
    public function setChangeDate($changeDate)
    {
        $this->changeDate = $changeDate;
        return $this;
    }

}