<?php


namespace Ipol\Fivepost\Api\Entity\Request\Part\Warehouse;


use Ipol\Fivepost\Api\Entity\AbstractEntity;

/**
 * Class WorkingTime
 * @package Ipol\Fivepost\Api\Entity\Request\Part
 */
class WorkingTime extends AbstractEntity
{
    /**
     * @var string
     */
    protected $dayNumber;
    /**
     * @var string
     */
    protected $timeFrom;
    /**
     * @var string
     */
    protected $timeTill;

    /**
     * @return string
     */
    public function getDayNumber()
    {
        return $this->dayNumber;
    }

    /**
     * @param string $dayNumber
     * @return WorkingTime
     */
    public function setDayNumber($dayNumber)
    {
        $this->dayNumber = $dayNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getTimeFrom()
    {
        return $this->timeFrom;
    }

    /**
     * @param string $timeFrom
     * @return WorkingTime
     */
    public function setTimeFrom($timeFrom)
    {
        $this->timeFrom = $timeFrom;
        return $this;
    }

    /**
     * @return string
     */
    public function getTimeTill()
    {
        return $this->timeTill;
    }

    /**
     * @param string $timeTill
     * @return WorkingTime
     */
    public function setTimeTill($timeTill)
    {
        $this->timeTill = $timeTill;
        return $this;
    }



}