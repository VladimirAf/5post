<?php
namespace Ipol\Fivepost\Fivepost\Controller;

use Ipol\Fivepost\Api\Entity\Request\OrderLabelsById;
use Ipol\Fivepost\Api\Entity\Request\OrderLabelsByNumber;
use Ipol\Fivepost\Fivepost\Entity\OrderLabelsResult;
use Ipol\Fivepost\Fivepost\FivepostApplication;

/**
 * Class OrderLabels
 * @package Ipol\Fivepost\Fivepost
 * @subpackage Controller
 */
class OrderLabels extends AutomatedCommonRequest
{
    /**
     * OrderLabels constructor.
     * @param string[] $orderNumbers
     * @param string $type
     */
    public function __construct(array $orderNumbers, string $type)
    {
        parent::__construct(new OrderLabelsResult());

        if (!is_array($orderNumbers))
            $orderNumbers = [$orderNumbers];

        switch ($type) {
            case FivepostApplication::ORDER_ID_TYPE_5P:
                $data = new OrderLabelsById($orderNumbers);
                break;
            case FivepostApplication::ORDER_ID_TYPE_CMS:
                $data = new OrderLabelsByNumber($orderNumbers);
                break;
        }
        $this->setRequestObj($data);
    }
}