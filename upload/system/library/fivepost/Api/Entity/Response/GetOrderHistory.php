<?php


namespace Ipol\Fivepost\Api\Entity\Response;


use Ipol\Fivepost\Api\Entity\Response\Part\GetOrderHistory\HistoryElementList;
use Ipol\Fivepost\Api\Tools;

/**
 * Class GetOrderHistory
 * @package Ipol\Fivepost\Api\Entity\Response
 */
class GetOrderHistory extends AbstractResponse
{
    /**
     * @var HistoryElementList
     */
    protected $history;

    /**
     * @return HistoryElementList
     */
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * @param array $array
     * @return GetOrderHistory
     * @throws \Exception
     */
    public function setHistory($array)
    {
        if(Tools::isSeqArr($array))
        {
            $collection = new HistoryElementList();
            $this->history = $collection->fillFromArray($array);
            return $this;
        }
        else
        {
            throw new \Exception(__FUNCTION__.' requires parameter to be SEQUENTIAL array. '. gettype($array). ' given.');
        }
    }

    public function setDecoded($decoded)
    {
        if(Tools::isSeqArr($decoded))
        {
            parent::setDecoded(['history' => $decoded]);
        }
        else{
            parent::setDecoded($decoded);
        }

        return $this;
    }

}