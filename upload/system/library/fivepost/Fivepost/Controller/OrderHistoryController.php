<?php


namespace Ipol\Fivepost\Fivepost\Controller;


use Ipol\Fivepost\Api\Entity\Request\GetOrderHistory;
use Ipol\Fivepost\Fivepost\Entity\OrderHistoryResult;

/**
 * Class OrderHistoryController
 * @package Ipol\Fivepost\Fivepost
 * @subpackage Controller
 */
class OrderHistoryController extends AutomatedCommonRequest
{
    /**
     * OrderHistoryController constructor.
     * @param string $id
     * @param string $type
     */
    public function __construct(string $id, string $type)
    {
        parent::__construct(new OrderHistoryResult());

        $data = new GetOrderHistory();
        switch ($type) {
            case "uuid":
                $data->setOrderId($id);
                break;
            case "senderOrderId":
                $data->setSenderOrderId($id);
                break;
        }
        $this->setRequestObj($data);
    }
}