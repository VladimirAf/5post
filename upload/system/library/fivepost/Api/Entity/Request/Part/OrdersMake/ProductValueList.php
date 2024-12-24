<?php
namespace Ipol\Fivepost\Api\Entity\Request\Part\OrdersMake;

use Ipol\Fivepost\Api\Entity\AbstractCollection;

/**
 * Class ProductValueList
 * @package Ipol\Fivepost\Api
 * @subpackage Request
 * @method ProductValue getFirst
 * @method ProductValue getNext
 * @method ProductValue getLast
 */
class ProductValueList extends AbstractCollection
{
    protected $ProductValues;

    public function __construct()
    {
        parent::__construct('ProductValues');
    }
}