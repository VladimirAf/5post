<?php
namespace Ipol\Fivepost\Api\Entity\UniversalPart;

use Ipol\Fivepost\Api\Entity\AbstractEntity;

/**
 * Class WarehouseEntity
 * @package Ipol\Fivepost\Api\Entity\UniversalPart
 */
class WarehouseEntity extends AbstractEntity
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return WarehouseEntity
     */
    public function setName(string $name): WarehouseEntity
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountryId(): string
    {
        return $this->countryId;
    }

    /**
     * @param string $countryId
     * @return WarehouseEntity
     */
    public function setCountryId(string $countryId): WarehouseEntity
    {
        $this->countryId = $countryId;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegionCode(): string
    {
        return $this->regionCode;
    }

    /**
     * @param string $regionCode
     * @return WarehouseEntity
     */
    public function setRegionCode(string $regionCode): WarehouseEntity
    {
        $this->regionCode = $regionCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getFederalDistrict(): string
    {
        return $this->federalDistrict;
    }

    /**
     * @param string $federalDistrict
     * @return WarehouseEntity
     */
    public function setFederalDistrict(string $federalDistrict): WarehouseEntity
    {
        $this->federalDistrict = $federalDistrict;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegion(): string
    {
        return $this->region;
    }

    /**
     * @param string $region
     * @return WarehouseEntity
     */
    public function setRegion(string $region): WarehouseEntity
    {
        $this->region = $region;
        return $this;
    }

    /**
     * @return string
     */
    public function getIndex(): string
    {
        return $this->index;
    }

    /**
     * @param string $index
     * @return WarehouseEntity
     */
    public function setIndex(string $index): WarehouseEntity
    {
        $this->index = $index;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return WarehouseEntity
     */
    public function setCity(string $city): WarehouseEntity
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @param string $street
     * @return WarehouseEntity
     */
    public function setStreet(string $street): WarehouseEntity
    {
        $this->street = $street;
        return $this;
    }

    /**
     * @return string
     */
    public function getHouseNumber(): string
    {
        return $this->houseNumber;
    }

    /**
     * @param string $houseNumber
     * @return WarehouseEntity
     */
    public function setHouseNumber(string $houseNumber): WarehouseEntity
    {
        $this->houseNumber = $houseNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getCoordinates(): string
    {
        return $this->coordinates;
    }

    /**
     * @param string $coordinates
     * @return WarehouseEntity
     */
    public function setCoordinates(string $coordinates): WarehouseEntity
    {
        $this->coordinates = $coordinates;
        return $this;
    }

    /**
     * @return string
     */
    public function getContactPhoneNumber(): string
    {
        return $this->contactPhoneNumber;
    }

    /**
     * @param string $contactPhoneNumber
     * @return WarehouseEntity
     */
    public function setContactPhoneNumber(string $contactPhoneNumber): WarehouseEntity
    {
        $this->contactPhoneNumber = $contactPhoneNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getTimeZone(): string
    {
        return $this->timeZone;
    }

    /**
     * @param string $timeZone
     * @return WarehouseEntity
     */
    public function setTimeZone(string $timeZone): WarehouseEntity
    {
        $this->timeZone = $timeZone;
        return $this;
    }

    /**
     * @return WorkingTimeList
     */
    public function getWorkingTime(): WorkingTimeList
    {
        return $this->workingTime;
    }

    /**
     * @param array $array
     * @return WarehouseEntity
     */
    public function setWorkingTime(array $array): WarehouseEntity
    {
        $collection = new WorkingTimeList();
        $this->workingTime = $collection->fillFromArray($array);
        return $this;
    }

    /**
     * @return string
     */
    public function getPartnerLocationId(): string
    {
        return $this->partnerLocationId;
    }

    /**
     * @param string $partnerLocationId
     * @return WarehouseEntity
     */
    public function setPartnerLocationId(string $partnerLocationId): WarehouseEntity
    {
        $this->partnerLocationId = $partnerLocationId;
        return $this;
    }
}