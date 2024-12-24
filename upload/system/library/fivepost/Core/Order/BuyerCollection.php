<?php


namespace Ipol\Fivepost\Core\Order;


use Ipol\Fivepost\Core\Entity\Collection;

/**
 * Class BuyerCollection
 * @package Ipol\Fivepost\Core
 * @subpackage Order
 * @method false|Buyer getFirst
 * @method false|Buyer getNext
 * @method false|Buyer getLast
 */
class BuyerCollection extends Collection
{
    /**
     * @var array
     */
    protected $receivers;

    /**
     * BuyerCollection constructor.
     */
    public function __construct()
    {
        parent::__construct('buyers');
    }

}