<?php


namespace Ipol\Fivepost\Api\Entity\Request\Part\CreateOrder;


use Ipol\Fivepost\Api\Entity\AbstractCollection;

class PartnerOrderList extends AbstractCollection
{
    protected $PartnerOrders;

    public function __construct()
    {
        parent::__construct('PartnerOrders');
    }

    /**
     * @return PartnerOrder
     */
    public function getFirst(){
        return parent::getFirst();
    }

    /**
     * @return PartnerOrder
     */
    public function getNext(){
        return parent::getNext();
    }
}