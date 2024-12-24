<?php
namespace Ipol\Fivepost\Api\Entity\Request\Part\OrdersMake;

use Ipol\Fivepost\Api\Entity\AbstractCollection;

/**
 * Class PartnerOrderList
 * @package Ipol\Fivepost\Api
 * @subpackage Request
 * @method PartnerOrder getFirst
 * @method PartnerOrder getNext
 * @method PartnerOrder getLast
 */
class PartnerOrderList extends AbstractCollection
{
    protected $PartnerOrders;

    public function __construct()
    {
        parent::__construct('PartnerOrders');
    }
}