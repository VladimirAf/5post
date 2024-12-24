<?php


namespace Ipol\Fivepost\Api\Entity\Response\Part\PickupPoint;

use Exception;
use Ipol\Fivepost\Api\Entity\AbstractEntity;
use Ipol\Fivepost\Api\Entity\Response\Part\AbstractResponsePart;
use Ipol\Fivepost\Api\Tools;


/**
 * Class Content
 * @package Ipol\Fivepost\Api\Entity\Response\Part\PickupPoint
 */
class Content extends AbstractEntity
{
    use AbstractResponsePart;

    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $partnerName;
    /**
     * @var string
     */
    protected $type;
    /**
     * @var string
     */
    protected $additional;
    /**
     * @var WorkHourList
     */
    protected $workHours;
    /**
     * @var string
     */
    protected $fullAddress;
    /**
     * @var Address
     */
    protected $address;
    /**
     * @var CellLimits
     */
    protected $cellLimits;
    /**
     * @var bool
     */
    protected $returnAllowed;
    /**
     * @var string
     */
    protected $timezone;
    /**
     * @var string
     */
    protected $phone;
    /**
     * @var bool
     */
    protected $cashAllowed;
    /**
     * @var bool
     */
    protected $cardAllowed;
    /**
     * @var bool
     */
    protected $loyaltyAllowed;
    /**
     * @var string
     */
    protected $extStatus;
    /**
     * @var string
     */
    protected $localityFiasCode;
    /**
     * @var DeliverySLList
     */
    protected $deliverySL;
    /**
     * @var RateList
     */
    protected $rate;
    /**
     * @var LastMileWarehouse
     */
    protected $lastMileWarehouse;

    /**
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Content
     */
    public function setId(string $id): Content
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Content
     */
    public function setName(string $name): Content
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPartnerName(): ?string
    {
        return $this->partnerName;
    }

    /**
     * @param string $partnerName
     * @return Content
     */
    public function setPartnerName(string $partnerName): Content
    {
        $this->partnerName = $partnerName;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Content
     */
    public function setType(string $type): Content
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdditional(): ?string
    {
        return $this->additional;
    }

    /**
     * @param string $additional
     * @return Content
     */
    public function setAdditional(string $additional): Content
    {
        $this->additional = $additional;
        return $this;
    }

    /**
     * @return WorkHourList
     */
    public function getWorkHours(): ?WorkHourList
    {
        return $this->workHours;
    }

    /**
     * @param array $workHours
     * @return Content
     * @throws Exception
     */
    public function setWorkHours($workHours): Content
    {
        if (Tools::isSeqArr($workHours))
        {
            $collection = new WorkHourList();
            $this->workHours = $collection->fillFromArray($workHours);
            return $this;
        }
        else
        {
            throw new Exception(__FUNCTION__ . ' requires parameter to be SEQUENTIAL array. ' . gettype($workHours) . ' given.');
        }
    }

    /**
     * @return string
     */
    public function getFullAddress(): ?string
    {
        return $this->fullAddress;
    }

    /**
     * @param string $fullAddress
     * @return Content
     */
    public function setFullAddress(string $fullAddress): Content
    {
        $this->fullAddress = $fullAddress;
        return $this;
    }

    /**
     * @return Address
     */
    public function getAddress(): ?Address
    {
        return $this->address;
    }

    /**
     * @param array $address
     * @return Content
     */
    public function setAddress($address): Content
    {
        $this->address = new Address($address);
        return $this;
    }

    /**
     * @return CellLimits
     */
    public function getCellLimits(): ?CellLimits
    {
        return $this->cellLimits;
    }

    /**
     * @param CellLimits $cellLimits
     * @return Content
     */
    public function setCellLimits($cellLimits): Content
    {
        $this->cellLimits = new CellLimits($cellLimits);
        return $this;
    }

    /**
     * @return bool
     */
    public function isReturnAllowed(): ?bool
    {
        return $this->returnAllowed;
    }

    /**
     * @param bool $returnAllowed
     * @return Content
     */
    public function setReturnAllowed(bool $returnAllowed): Content
    {
        $this->returnAllowed = $returnAllowed;
        return $this;
    }

    /**
     * @return string
     */
    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    /**
     * @param string $timezone
     * @return Content
     */
    public function setTimezone(string $timezone): Content
    {
        $this->timezone = $timezone;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return Content
     */
    public function setPhone(string $phone): Content
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCashAllowed(): ?bool
    {
        return $this->cashAllowed;
    }

    /**
     * @param bool $cashAllowed
     * @return Content
     */
    public function setCashAllowed(bool $cashAllowed): Content
    {
        $this->cashAllowed = $cashAllowed;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCardAllowed(): ?bool
    {
        return $this->cardAllowed;
    }

    /**
     * @param bool $cardAllowed
     * @return Content
     */
    public function setCardAllowed(bool $cardAllowed): Content
    {
        $this->cardAllowed = $cardAllowed;
        return $this;
    }

    /**
     * @return bool
     */
    public function isLoyaltyAllowed(): ?bool
    {
        return $this->loyaltyAllowed;
    }

    /**
     * @param bool $loyaltyAllowed
     * @return Content
     */
    public function setLoyaltyAllowed(bool $loyaltyAllowed): Content
    {
        $this->loyaltyAllowed = $loyaltyAllowed;
        return $this;
    }

    /**
     * @return string
     */
    public function getExtStatus(): ?string
    {
        return $this->extStatus;
    }

    /**
     * @param string $extStatus
     * @return Content
     */
    public function setExtStatus(string $extStatus): Content
    {
        $this->extStatus = $extStatus;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocalityFiasCode(): ?string
    {
        return $this->localityFiasCode;
    }

    /**
     * @param string $localityFiasCode
     * @return Content
     */
    public function setLocalityFiasCode(string $localityFiasCode): Content
    {
        $this->localityFiasCode = $localityFiasCode;
        return $this;
    }

    /**
     * @return DeliverySLList
     */
    public function getDeliverySL(): ?DeliverySLList
    {
        return $this->deliverySL;
    }

    /**
     * @param array $deliverySL
     * @return Content
     * @throws Exception
     */
    public function setDeliverySL(array $deliverySL): Content
    {
        if (Tools::isSeqArr($deliverySL))
        {
            $collection = new DeliverySLList();
            $this->deliverySL = $collection->fillFromArray($deliverySL);
            return $this;
        }
        else
        {
            throw new Exception(__FUNCTION__ . ' requires parameter to be SEQUENTIAL array. ' . gettype($deliverySL) . ' given.');
        }

    }

    /**
     * @return RateList
     */
    public function getRate(): ?RateList
    {
        return $this->rate;
    }

    /**
     * @param array $rate
     * @return Content
     * @throws Exception
     */
    public function setRate(array $rate): Content
    {
        if (Tools::isSeqArr($rate))
        {
            $collection = new RateList();
            $this->rate = $collection->fillFromArray($rate);
            return $this;
        }
        else
        {
            throw new Exception(__FUNCTION__ . ' requires parameter to be SEQUENTIAL array. ' . gettype($rate) . ' given.');
        }
    }

    /**
     * @return LastMileWarehouse
     */
    public function getLastMileWarehouse(): ?LastMileWarehouse
    {
        return $this->lastMileWarehouse;
    }

    /**
     * @param array $lastMileWarehouse
     * @return Content
     */
    public function setLastMileWarehouse($lastMileWarehouse): Content
    {
        $this->lastMileWarehouse = new LastMileWarehouse($lastMileWarehouse);
        return $this;
    }


}