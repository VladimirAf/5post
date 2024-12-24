<?php


namespace Ipol\Fivepost\Fivepost\Controller;


use \Ipol\Fivepost\Api\Entity\Request\PickupPoints as PointsRequest;
use Ipol\Fivepost\Fivepost\Entity\PickupPointsResult;

/**
 * Class PickupPoints
 * @package Ipol\Fivepost\Fivepost
 * @subpackage Controller
 */
class PickupPoints extends AutomatedCommonRequest
{
    /**
     * PickupPoints constructor.
     * @param int $pageNum
     * @param int $pageSize
     */
    public function __construct(int $pageNum = 0, int $pageSize = 1000)
    {
        parent::__construct(new PickupPointsResult());
        $data = new PointsRequest();
        $data->setPageNumber($pageNum)
            ->setPageSize($pageSize);

        $this->setRequestObj($data);
    }

}
