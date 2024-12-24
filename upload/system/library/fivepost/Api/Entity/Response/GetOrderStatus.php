<?php


namespace Ipol\Fivepost\Api\Entity\Response;


use Ipol\Fivepost\Api\Entity\Response\Part\GetOrderStatus\OrderStatusList;
use Ipol\Fivepost\Api\Tools;

/**
 * Class GetOrderStatus
 * @package Ipol\Fivepost\Api\Entity\Response
 */
class GetOrderStatus extends AbstractResponse
{
    /**
     * @var OrderStatusList
     */
    protected $order_statuses;

    /**
     * @return OrderStatusList
     */
    public function getOrderStatuses()
    {
        return $this->order_statuses;
    }

    /**
     * @param array $array
     * @return GetOrderStatus
     * @throws \Exception
     */
    public function setOrderStatuses($array)
    {
        if(Tools::isSeqArr($array))
        {
            $collection = new OrderStatusList();
            $this->order_statuses = $collection->fillFromArray($array);
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
            parent::setDecoded(['order_statuses' => $decoded]);
        }
        else{
            parent::setDecoded($decoded);
        }

        return $this;
    }
}