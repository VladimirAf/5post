<?php


namespace Ipol\Fivepost\Core\Order;


use Ipol\Fivepost\Core\Entity\Collection;

/**
 * Class ItemCollection
 * @package Ipol\Fivepost\Core
 * @subpackage Order
 * @method false|Item getFirst
 * @method false|Item getNext
 * @method false|Item getLast
 */
class ItemCollection extends Collection
{
    /**
     * @var array
     */
    protected $items;

    /**
     * ItemCollection constructor.
     */
    public function __construct()
    {
        parent::__construct('items');
    }

}