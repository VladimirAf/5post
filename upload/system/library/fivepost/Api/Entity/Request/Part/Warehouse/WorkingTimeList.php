<?php


namespace Ipol\Fivepost\Api\Entity\Request\Part\Warehouse;


use Ipol\Fivepost\Api\Entity\AbstractCollection;

class WorkingTimeList extends AbstractCollection
{
    protected $WorkingTimes;

    public function __construct()
    {
        parent::__construct('WorkingTimes');
    }

    /**
     * @return WorkingTime
     */
    public function getFirst(){
        return parent::getFirst();
    }

    /**
     * @return WorkingTime
     */
    public function getNext(){
        return parent::getNext();
    }
}