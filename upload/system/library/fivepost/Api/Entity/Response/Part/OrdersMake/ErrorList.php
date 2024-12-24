<?php
namespace Ipol\Fivepost\Api\Entity\Response\Part\OrdersMake;

use Ipol\Fivepost\Api\Entity\AbstractCollection;

/**
 * Class ErrorList
 * @package Ipol\Fivepost\Api
 * @subpackage Response
 * @method Error getFirst
 * @method Error getNext
 * @method Error getLast
 */
class ErrorList extends AbstractCollection
{
    protected $Errors;

    public function __construct()
    {
        parent::__construct('Errors');
    }
}