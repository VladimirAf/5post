<?php


namespace Ipol\Fivepost\Api\Entity\Response\Part\GetOrderHistory;


use Ipol\Fivepost\Api\Entity\AbstractCollection;

class HistoryElementList extends AbstractCollection
{
    protected $HistoryElements;

    public function __construct()
    {
        parent::__construct('HistoryElements');
    }

    /**
     * @return HistoryElement
     */
    public function getFirst(){
        return parent::getFirst();
    }

    /**
     * @return HistoryElement
     */
    public function getNext(){
        return parent::getNext();
    }
}