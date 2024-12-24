<?php
namespace Ipol\Fivepost\Api\Entity\UniversalPart;

use Ipol\Fivepost\Api\Entity\AbstractEntity;

/**
 * Class WorkingTime
 * @package Ipol\Fivepost\Api\Entity\UniversalPart
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
    public function getDayNumber(): string
    {
        return $this->dayNumber;
    }

    /**
     * @param string $dayNumber
     * @return WorkingTime
     */
    public function setDayNumber(string $dayNumber): WorkingTime
    {
        $this->dayNumber = $dayNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getTimeFrom(): string
    {
        return $this->timeFrom;
    }

    /**
     * @param string $timeFrom
     * @return WorkingTime
     */
    public function setTimeFrom(string $timeFrom): WorkingTime
    {
        $this->timeFrom = $timeFrom;
        return $this;
    }

    /**
     * @return string
     */
    public function getTimeTill(): string
    {
        return $this->timeTill;
    }

    /**
     * @param string $timeTill
     * @return WorkingTime
     */
    public function setTimeTill(string $timeTill): WorkingTime
    {
        $this->timeTill = $timeTill;
        return $this;
    }
}