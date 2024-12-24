<?php


namespace Ipol\Fivepost\Api\Entity\Response\Part\PickupPoint;

use Ipol\Fivepost\Api\Entity\AbstractEntity;
use Ipol\Fivepost\Api\Entity\Response\Part\AbstractResponsePart;

/**
 * Class WorkHours
 * @package Ipol\Fivepost\Api\Entity\Response\Part\PickupPoint
 */
class CellLimits extends AbstractEntity
{
    use AbstractResponsePart;

    /**
     * @var int
     */
    protected $maxCellWidth;
    /**
     * @var int
     */
    protected $maxCellHeight;
    /**
     * @var int
     */
    protected $maxCellLength;
    /**
     * @var int
     */
    protected $maxWeight;

    /**
     * @return int
     */
    public function getMaxCellWidth()
    {
        return $this->maxCellWidth;
    }

    /**
     * @param int $maxCellWidth
     * @return CellLimits
     */
    public function setMaxCellWidth($maxCellWidth)
    {
        $this->maxCellWidth = $maxCellWidth;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxCellHeight()
    {
        return $this->maxCellHeight;
    }

    /**
     * @param int $maxCellHeight
     * @return CellLimits
     */
    public function setMaxCellHeight($maxCellHeight)
    {
        $this->maxCellHeight = $maxCellHeight;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxCellLength()
    {
        return $this->maxCellLength;
    }

    /**
     * @param int $maxCellLength
     * @return CellLimits
     */
    public function setMaxCellLength($maxCellLength)
    {
        $this->maxCellLength = $maxCellLength;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxWeight()
    {
        return $this->maxWeight;
    }

    /**
     * @param int $maxWeight
     * @return CellLimits
     */
    public function setMaxWeight($maxWeight)
    {
        $this->maxWeight = $maxWeight;
        return $this;
    }

}