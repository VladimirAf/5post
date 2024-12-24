<?php
namespace Ipol\Fivepost\Api\Entity\Request\Part\OrdersMake;

use Ipol\Fivepost\Api\Entity\AbstractEntity;

/**
 * Class Cost
 * @package Ipol\Fivepost\Api\Entity\Request\Part\OrdersMake
 */
class Cost extends AbstractEntity
{
    /**
     * @var float|null
     */
    protected $deliveryCost;

    /**
     * @var string|null (Alpha-3)
     */
    protected $deliveryCostCurrency;

    /**
     * @var float
     */
    protected $paymentValue;

    /**
     * @var string (Alpha-3)
     */
    protected $paymentCurrency;

    /**
     * @var string (CASH | CASHLESS | PREPAYMENT)
     */
    protected $paymentType;

    /**
     * @var float
     */
    protected $price;

    /**
     * @var string (Alpha-3)
     */
    protected $priceCurrency;

    /**
     * @return float|null
     */
    public function getDeliveryCost(): ?float
    {
        return $this->deliveryCost;
    }

    /**
     * @param float|null $deliveryCost
     * @return Cost
     */
    public function setDeliveryCost(?float $deliveryCost): Cost
    {
        $this->deliveryCost = $deliveryCost;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDeliveryCostCurrency(): ?string
    {
        return $this->deliveryCostCurrency;
    }

    /**
     * @param string|null $deliveryCostCurrency
     * @return Cost
     */
    public function setDeliveryCostCurrency(?string $deliveryCostCurrency): Cost
    {
        $this->deliveryCostCurrency = $deliveryCostCurrency;
        return $this;
    }

    /**
     * @return float
     */
    public function getPaymentValue(): float
    {
        return $this->paymentValue;
    }

    /**
     * @param float $paymentValue
     * @return Cost
     */
    public function setPaymentValue(float $paymentValue): Cost
    {
        $this->paymentValue = $paymentValue;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentCurrency(): string
    {
        return $this->paymentCurrency;
    }

    /**
     * @param string $paymentCurrency
     * @return Cost
     */
    public function setPaymentCurrency(string $paymentCurrency): Cost
    {
        $this->paymentCurrency = $paymentCurrency;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentType(): string
    {
        return $this->paymentType;
    }

    /**
     * @param string $paymentType
     * @return Cost
     */
    public function setPaymentType(string $paymentType): Cost
    {
        $this->paymentType = $paymentType;
        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return Cost
     */
    public function setPrice(float $price): Cost
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return string
     */
    public function getPriceCurrency(): string
    {
        return $this->priceCurrency;
    }

    /**
     * @param string $priceCurrency
     * @return Cost
     */
    public function setPriceCurrency(string $priceCurrency): Cost
    {
        $this->priceCurrency = $priceCurrency;
        return $this;
    }
}