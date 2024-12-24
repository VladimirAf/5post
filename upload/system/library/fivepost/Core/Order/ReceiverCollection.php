<?php


namespace Ipol\Fivepost\Core\Order;


use Ipol\Fivepost\Core\Entity\Collection;

/**
 * Class ReceiverCollection
 * @package Ipol\Fivepost\Core
 * @subpackage Order
 * @method false|Receiver getFirst
 * @method false|Receiver getNext
 * @method false|Receiver getLast
 */
class ReceiverCollection extends Collection
{
    /**
     * @var array
     */
    protected $receivers;

    /**
     * ReceiverCollection constructor.
     */
    public function __construct()
    {
        parent::__construct('receivers');
    }

}