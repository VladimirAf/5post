<?php


namespace Ipol\Fivepost\Api\Entity\Request;


use Ipol\Fivepost\Api\Entity\Request\Part\CreateOrder\PartnerOrderList;

/**
 * Class CreateOrder
 * @package Ipol\Fivepost\Api\Entity\Request
 */
class CreateOrder extends AbstractRequest
{
    /**
     * @var PartnerOrderList
     */
    protected $partnerOrders;

    /**
     * @return PartnerOrderList
     */
    public function getPartnerOrders()
    {
        return $this->partnerOrders;
    }

    /**
     * @param PartnerOrderList $partnerOrders
     * @return CreateOrder
     */
    public function setPartnerOrders($partnerOrders)
    {
        $this->partnerOrders = $partnerOrders;
        return $this;
    }

}