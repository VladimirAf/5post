<?php
namespace Ipol\Fivepost\Api\Entity\UniversalPart;

use Ipol\Fivepost\Api\Entity\AbstractCollection;

/**
 * Class WorkingTimeList
 * @package Ipol\Fivepost\Api
 * @subpackage Entity\UniversalPart
 * @method WorkingTime getFirst
 * @method WorkingTime getNext
 * @method WorkingTime getLast
 */
class WorkingTimeList extends AbstractCollection
{
    protected $WorkingTimes;

    public function __construct()
    {
        parent::__construct('WorkingTimes');
    }
}