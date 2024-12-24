<?php


namespace Ipol\Fivepost\Api\Entity\Response\Part\PickupPoint;


use Ipol\Fivepost\Api\Entity\AbstractEntity;
use Ipol\Fivepost\Api\Entity\Response\Part\AbstractResponsePart;

/**
 * Class WorkHours
 * @package Ipol\Fivepost\Api\Entity\Response\Part\PickupPoint
 */
class DeliverySL extends AbstractEntity
{
    use AbstractResponsePart;

    /**
     * @var int
     */
    protected $Sl;

    /**
     * @return int
     */
    public function getSL()
    {
        return $this->Sl;
    }

    /**
     * @param int $SL
     * @return DeliverySL
     */
    public function setSL($SL)
    {
        $this->Sl = $SL;
        return $this;
    }

}