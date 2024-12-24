<?php


namespace Ipol\Fivepost\Api\Entity\Response\Part\PickupPoint;


use Ipol\Fivepost\Api\Entity\AbstractCollection;

class WorkHourList extends AbstractCollection
{
    protected $WorkHours;

    public function __construct()
    {
        parent::__construct('WorkHours');
    }

    /**
     * @return WorkHour
     */
    public function getFirst(){
        return parent::getFirst();
    }

    /**
     * @return WorkHour
     */
    public function getNext(){
        return parent::getNext();
    }
}