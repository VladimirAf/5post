<?php
namespace Ipol\Fivepost\Api\Entity\Request;

/**
 * Class OrderLabelsByNumber
 * @package Ipol\Fivepost\Api\Entity\Request
 */
class OrderLabelsByNumber extends AbstractRequest
{
    /**
     * @var string[] CMS order numbers
     */
    protected $senderOrderIds;

    public function __construct(array $senderOrderIds)
    {
        parent::__construct();
        $this->setSenderOrderIds($senderOrderIds);
    }

    /**
     * @return string[]
     */
    public function getSenderOrderIds(): array
    {
        return $this->senderOrderIds;
    }

    /**
     * @param string[] $senderOrderIds
     * @return OrderLabelsByNumber
     */
    public function setSenderOrderIds(array $senderOrderIds): OrderLabelsByNumber
    {
        $this->senderOrderIds = $senderOrderIds;
        return $this;
    }
}