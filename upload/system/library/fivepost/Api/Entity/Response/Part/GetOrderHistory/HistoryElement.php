<?php


namespace Ipol\Fivepost\Api\Entity\Response\Part\GetOrderHistory;

use Ipol\Fivepost\Api\Entity\AbstractEntity;
use Ipol\Fivepost\Api\Entity\Response\Part\AbstractResponsePart;


/**
 * Class GetOrderHistory
 * @package Ipol\Fivepost\Api\Entity\Response\Part\GetOrderHistory
 */
class HistoryElement extends AbstractEntity
{
    use AbstractResponsePart;

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
    protected $status;
    /**
     * @var \DateTime ("2020-05-20T11:18:58.243302+03:00")
     */
    protected $changeDate;
    /**
     * @var string
     */
    protected $executionStatus;

    /**
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param string $orderId
     * @return HistoryElement
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
     * @return HistoryElement
     */
    public function setSenderOrderId($senderOrderId)
    {
        $this->senderOrderId = $senderOrderId;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return HistoryElement
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getChangeDate()
    {
        return $this->changeDate;
    }

    /**
     * @param \DateTime $changeDate
     * @return HistoryElement
     */
    public function setChangeDate($changeDate)
    {
        $this->changeDate = $changeDate;
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
     * @return HistoryElement
     */
    public function setExecutionStatus($executionStatus)
    {
        $this->executionStatus = $executionStatus;
        return $this;
    }

}