<?php


namespace Ipol\Fivepost\Core\Order;


use Ipol\Fivepost\Core\Entity\Collection;

/**
 * Class OrderCollection
 * @package Ipol\Fivepost\Core
 * @subpackage Order
 * @method false|Order getFirst
 * @method false|Order getNext
 * @method false|Order getLast
 */
class OrderCollection extends Collection
{
    /**
     * @var array
     */
    protected $orders;

    /**
     * OrderCollection constructor.
     */
    public function __construct()
    {
        parent::__construct('orders');
    }

}