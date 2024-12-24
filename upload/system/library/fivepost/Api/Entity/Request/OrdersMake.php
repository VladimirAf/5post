<?php
namespace Ipol\Fivepost\Api\Entity\Request;

use Ipol\Fivepost\Api\Entity\Request\Part\OrdersMake\PartnerOrderList;

/**
 * Class OrdersMake
 * @package Ipol\Fivepost\Api\Entity\Request
 */
class OrdersMake extends AbstractRequest
{
    /**
     * @var PartnerOrderList
     */
    protected $partnerOrders;

    /**
     * @return PartnerOrderList
     */
    public function getPartnerOrders(): PartnerOrderList
    {
        return $this->partnerOrders;
    }

    /**
     * @param PartnerOrderList $partnerOrders
     * @return OrdersMake
     */
    public function setPartnerOrders(PartnerOrderList $partnerOrders): OrdersMake
    {
        $this->partnerOrders = $partnerOrders;
        return $this;
    }
}