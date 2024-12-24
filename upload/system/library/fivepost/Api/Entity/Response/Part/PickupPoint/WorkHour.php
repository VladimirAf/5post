<?php


namespace Ipol\Fivepost\Api\Entity\Response\Part\PickupPoint;

use Ipol\Fivepost\Api\Entity\AbstractEntity;
use Ipol\Fivepost\Api\Entity\Response\Part\AbstractResponsePart;


/**
 * Class WorkHours
 * @package Ipol\Fivepost\Api\Entity\Response\Part\PickupPoint
 */
class WorkHour extends AbstractEntity
{
    use AbstractResponsePart;

    /**
     * @var string
     */
    protected $day;
    /**
     * @var \DateTime (hh:mm)
     */
    protected $opensAt;
    /**
     * @var \DateTime (hh:mm)
     */
    protected $closesAt;
    /**
     * @var string
     */
    protected $timezone;
    /**
     * @var string
     */
    protected $timezoneOffset;

    /**
     * @return string
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param string $day
     * @return WorkHours
     */
    public function setDay($day)
    {
        $this->day = $day;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getOpensAt()
    {
        return $this->opensAt;
    }

    /**
     * @param \DateTime $opensAt
     * @return WorkHours
     */
    public function setOpensAt($opensAt)
    {
        $this->opensAt = $opensAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getClosesAt()
    {
        return $this->closesAt;
    }

    /**
     * @param \DateTime $closesAt
     * @return WorkHours
     */
    public function setClosesAt($closesAt)
    {
        $this->closesAt = $closesAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * @param string $timezone
     * @return WorkHours
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
        return $this;
    }

    /**
     * @return string
     */
    public function getTimezoneOffset()
    {
        return $this->timezoneOffset;
    }

    /**
     * @param string $timezoneOffset
     * @return WorkHours
     */
    public function setTimezoneOffset($timezoneOffset)
    {
        $this->timezoneOffset = $timezoneOffset;
        return $this;
    }

}