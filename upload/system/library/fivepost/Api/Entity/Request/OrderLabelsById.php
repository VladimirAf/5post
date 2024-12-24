<?php
namespace Ipol\Fivepost\Api\Entity\Request;

/**
 * Class OrderLabelsById
 * @package Ipol\Fivepost\Api\Entity\Request
 */
class OrderLabelsById extends AbstractRequest
{
    /**
     * @var string[] Fivepost uuids
     */
    protected $orderIds;

    public function __construct(array $orderIds)
    {
        parent::__construct();
        $this->setOrderIds($orderIds);
    }

    /**
     * @return string[]
     */
    public function getOrderIds(): array
    {
        return $this->orderIds;
    }

    /**
     * @param string[] $orderIds
     * @return OrderLabelsById
     */
    public function setOrderIds(array $orderIds): OrderLabelsById
    {
        $this->orderIds = $orderIds;
        return $this;
    }
}