<?php


namespace Ipol\Fivepost\Fivepost\Controller;


use Ipol\Fivepost\Api\Entity\Request\GetOrderStatus;
use Ipol\Fivepost\Api\Entity\Request\Part\GetOrderStatus\OrderStatus;
use Ipol\Fivepost\Api\Entity\Request\Part\GetOrderStatus\OrderStatusList;
use Ipol\Fivepost\Fivepost\Entity\OrderStatusResult;

/**
 * Class OrderStatusController
 * @package Ipol\Fivepost\Fivepost
 * @subpackage Controller
 */
class OrderStatusController extends AutomatedCommonRequest
{
    /**
     * OrderStatusController constructor.
     * @param array $arNumbers
     * @param string $type
     */
    public function __construct(array $arNumbers, string $type)
    {
        parent::__construct(new OrderStatusResult());

        $data = new GetOrderStatus();
        $orderCollection = new OrderStatusList();
        switch ($type) {
            case "uuid":
                foreach ($arNumbers as $uuid) {
                    $order = new OrderStatus();
                    $order->setOrderId($uuid);
                    $orderCollection->add($order);
                }
                break;
            case "senderOrderId":
                foreach ($arNumbers as $number) {
                    $order = new OrderStatus();
                    $order->setSenderOrderId($number);
                    $orderCollection->add($order);
                }
                break;
        }
        $data->setOrderStatuses($orderCollection);
        $this->setRequestObj($data);
    }

}