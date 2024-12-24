<?php


namespace Ipol\Fivepost\Api\Entity\Response\Part\PickupPoint;

use Ipol\Fivepost\Api\Entity\AbstractEntity;
use Ipol\Fivepost\Api\Entity\Response\Part\AbstractResponsePart;


/**
 * Class WorkHours
 * @package Ipol\Fivepost\Api\Entity\Response\Part\PickupPoint
 */
class Rate extends AbstractEntity
{
    use AbstractResponsePart;

    /**
     * @var string|null
     */
    protected $zone;
    /**
     * @var string|null
     */
    protected $rateType;
    /**
     * @var float|null
     */
    protected $rateValue;
    /**
     * @var float|null
     */
    protected $rateExtraValue;
    /**
     * @var string|null
     */
    protected $rateCurrency;
    /**
     * @var int|null
     */
    protected $vat;
    /**
     * @var float|null
     */
    protected $rateValueWithVat;
    /**
     * @var float|null
     */
    protected $rateExtraValueWithVat;

    /**
     * @return int|null
     */
    public function getVat(): ?int
    {
        return $this->vat;
    }

    /**
     * @param int $vat
     * @return Rate
     */
    public function setVat(int $vat): Rate
    {
        $this->vat = $vat;
        return $this;
    }

    /**
     * @return float
     */
    public function getRateValueWithVat(): ?float
    {
        return $this->rateValueWithVat;
    }

    /**
     * @param float $rateValueWithVat
     * @return Rate
     */
    public function setRateValueWithVat(float $rateValueWithVat): Rate
    {
        $this->rateValueWithVat = $rateValueWithVat;
        return $this;
    }

    /**
     * @return float
     */
    public function getRateExtraValueWithVat(): ?float
    {
        return $this->rateExtraValueWithVat;
    }

    /**
     * @param float $rateExtraValueWithVat
     * @return Rate
     */
    public function setRateExtraValueWithVat(float $rateExtraValueWithVat): Rate
    {
        $this->rateExtraValueWithVat = $rateExtraValueWithVat;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getZone(): ?string
    {
        return $this->zone;
    }

    /**
     * @param string|null $zone
     * @return Rate
     */
    public function setZone(string $zone): Rate
    {
        $this->zone = $zone;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRateType(): ?string
    {
        return $this->rateType;
    }

    /**
     * @param string|null $rateType
     * @return Rate
     */
    public function setRateType(string $rateType): Rate
    {
        $this->rateType = $rateType;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getRateValue(): ?float
    {
        return $this->rateValue;
    }

    /**
     * @param float|null $rateValue
     * @return Rate
     */
    public function setRateValue(float $rateValue): Rate
    {
        $this->rateValue = $rateValue;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getRateExtraValue(): ?float
    {
        return $this->rateExtraValue;
    }

    /**
     * @param float|null $rateExtraValue
     * @return Rate
     */
    public function setRateExtraValue(float $rateExtraValue): Rate
    {
        $this->rateExtraValue = $rateExtraValue;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRateCurrency(): ?string
    {
        return $this->rateCurrency;
    }

    /**
     * @param string|null $rateCurrency
     * @return Rate
     */
    public function setRateCurrency(string $rateCurrency): Rate
    {
        $this->rateCurrency = $rateCurrency;
        return $this;
    }

}