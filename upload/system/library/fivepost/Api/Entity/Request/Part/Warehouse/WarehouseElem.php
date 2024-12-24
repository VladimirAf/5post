<?php


namespace Ipol\Fivepost\Api\Entity\Request\Part\Warehouse;


use Ipol\Fivepost\Api\Entity\AbstractEntity;

/**
 * Class WarehouseElem
 * @package Ipol\Fivepost\Api\Entity\Request\Part\Warehouse
 */
class WarehouseElem extends AbstractEntity
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string (2)
     */
    protected $countryId;
    /**
     * @var string (2)
     */
    protected $regionCode;
    /**
     * @var string
     */
    protected $federalDistrict;
    /**
     * @var string
     */
    protected $region;
    /**
     * @var string
     */
    protected $index;
    /**
     * @var string
     */
    protected $city;
    /**
     * @var string
     */
    protected $street;
    /**
     * @var string
     */
    protected $houseNumber;
    /**
     * @var string
     */
    protected $coordinates;
    /**
     * @var string
     */
    protected $contactPhoneNumber;
    /**
     * @var string
     */
    protected $timeZone;
    /**
     * @var WorkingTimeList
     */
    protected $workingTime;
    /**
     * @var string
     */
    protected $partnerLocationId;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return WarehouseElem
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountryId()
    {
        return $this->countryId;
    }

    /**
     * @param string $countryId
     * @return WarehouseElem
     */
    public function setCountryId($countryId)
    {//TODO: walidation?
        $this->countryId = $countryId;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegionCode()
    {
        return $this->regionCode;
    }

    /**
     * @param string $regionCode
     * @return WarehouseElem
     */
    public function setRegionCode($regionCode)
    {//TODO: walidation?
        $this->regionCode = $regionCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getFederalDistrict()
    {
        return $this->federalDistrict;
    }

    /**
     * @param string $federalDistrict
     * @return WarehouseElem
     */
    public function setFederalDistrict($federalDistrict)
    {
        $this->federalDistrict = $federalDistrict;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param string $region
     * @return WarehouseElem
     */
    public function setRegion($region)
    {
        $this->region = $region;
        return $this;
    }

    /**
     * @return string
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param string $index
     * @return WarehouseElem
     */
    public function setIndex($index)
    {
        $this->index = $index;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return WarehouseElem
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $street
     * @return WarehouseElem
     */
    public function setStreet($street)
    {
        $this->street = $street;
        return $this;
    }

    /**
     * @return string
     */
    public function getHouseNumber()
    {
        return $this->houseNumber;
    }

    /**
     * @param string $houseNumber
     * @return WarehouseElem
     */
    public function setHouseNumber($houseNumber)
    {
        $this->houseNumber = $houseNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * @param string $coordinates
     * @return WarehouseElem
     */
    public function setCoordinates($coordinates)
    {
        $this->coordinates = $coordinates;
        return $this;
    }

    /**
     * @return string
     */
    public function getContactPhoneNumber()
    {
        return $this->contactPhoneNumber;
    }

    /**
     * @param string $contactPhoneNumber
     * @return WarehouseElem
     */
    public function setContactPhoneNumber($contactPhoneNumber)
    {
        $this->contactPhoneNumber = $contactPhoneNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getTimeZone()
    {
        return $this->timeZone;
    }

    /**
     * @param string $timeZone
     * @return WarehouseElem
     */
    public function setTimeZone($timeZone)
    {
        $this->timeZone = $timeZone;
        return $this;
    }

    /**
     * @return WorkingTimeList
     */
    public function getWorkingTime()
    {
        return $this->workingTime;
    }

    /**
     * @param WorkingTimeList $workingTime
     * @return WarehouseElem
     */
    public function setWorkingTime($workingTime)
    {
        $this->workingTime = $workingTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getPartnerLocationId()
    {
        return $this->partnerLocationId;
    }

    /**
     * @param string $partnerLocationId
     * @return WarehouseElem
     */
    public function setPartnerLocationId($partnerLocationId)
    {
        $this->partnerLocationId = $partnerLocationId;
        return $this;
    }
}