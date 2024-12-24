<?php
namespace Ipol\Fivepost\Api\Entity\Response\Part\OrdersMake;

use Ipol\Fivepost\Api\Entity\AbstractCollection;

/**
 * Class ContentList
 * @package Ipol\Fivepost\Api
 * @subpackage Response
 * @method Content getFirst
 * @method Content getNext
 * @method Content getLast
 */
class ContentList extends AbstractCollection
{
    protected $Contents;

    public function __construct()
    {
        parent::__construct('Contents');
    }
}