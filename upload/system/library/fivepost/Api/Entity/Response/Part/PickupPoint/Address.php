<?php


namespace Ipol\Fivepost\Api\Entity\Response\Part\PickupPoint;

use Ipol\Fivepost\Api\Entity\AbstractEntity;
use Ipol\Fivepost\Api\Entity\Response\Part\AbstractResponsePart;


/**
 * Class WorkHours
 * @package Ipol\Fivepost\Api\Entity\Response\Part\PickupPoint
 */
class Address extends AbstractEntity
{
    use AbstractResponsePart;

    /**
     * @var string
     */
    protected $country;
    /**
     * @var int
     */
    protected $zipCode;
    /**
     * @var string
     */
    protected $region;
    /**
     * @var string
     */
    protected $regionType;
    /**
     * @var string
     */
    protected $city;
    /**
     * @var string
     */
    protected $cityType;
    /**
     * @var string
     */
    protected $street;
    /**
     * @var string
     */
    protected $house;
    /**
     * @var string
     */
    protected $building;
    /**
     * @var int
     */
    protected $lat;
    /**
     * @var int
     */
    protected $lng;
    /**
     * @var string
     */
    protected $metroStation;

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return Address
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return int
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param int $zipCode
     * @return Address
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
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
     * @return Address
     */
    public function setRegion($region)
    {
        $this->region = $region;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegionType()
    {
        return $this->regionType;
    }

    /**
     * @param string $regionType
     * @return Address
     */
    public function setRegionType($regionType)
    {
        $this->regionType = $regionType;
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
     * @return Address
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getCityType()
    {
        return $this->cityType;
    }

    /**
     * @param string $cityType
     * @return Address
     */
    public function setCityType($cityType)
    {
        $this->cityType = $cityType;
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
     * @return Address
     */
    public function setStreet($street)
    {
        $this->street = $street;
        return $this;
    }

    /**
     * @return string
     */
    public function getHouse()
    {
        return $this->house;
    }

    /**
     * @param string $house
     * @return Address
     */
    public function setHouse($house)
    {
        $this->house = $house;
        return $this;
    }

    /**
     * @return string
     */
    public function getBuilding()
    {
        return $this->building;
    }

    /**
     * @param string $building
     * @return Address
     */
    public function setBuilding($building)
    {
        $this->building = $building;
        return $this;
    }

    /**
     * @return int
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param int $lat
     * @return Address
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
        return $this;
    }

    /**
     * @return int
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * @param int $lng
     * @return Address
     */
    public function setLng($lng)
    {
        $this->lng = $lng;
        return $this;
    }

    /**
     * @return string
     */
    public function getMetroStation()
    {
        return $this->metroStation;
    }

    /**
     * @param string $metroStation
     * @return Address
     */
    public function setMetroStation($metroStation)
    {
        $this->metroStation = $metroStation;
        return $this;
    }


}