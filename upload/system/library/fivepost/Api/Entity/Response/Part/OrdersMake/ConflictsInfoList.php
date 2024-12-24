<?php
namespace Ipol\Fivepost\Api\Entity\Response\Part\OrdersMake;

use Ipol\Fivepost\Api\Entity\AbstractCollection;

/**
 * Class ConflictsInfoList
 * @package Ipol\Fivepost\Api
 * @subpackage Response
 * @method ConflictsInfo getFirst
 * @method ConflictsInfo getNext
 * @method ConflictsInfo getLast
 */
class ConflictsInfoList extends AbstractCollection
{
    protected $ConflictsInfos;

    public function __construct()
    {
        parent::__construct('ConflictsInfos');
    }
}