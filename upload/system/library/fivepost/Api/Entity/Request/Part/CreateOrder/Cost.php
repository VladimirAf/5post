<?php


namespace Ipol\Fivepost\Api\Entity\Request\Part\CreateOrder;


use Ipol\Fivepost\Api\Entity\AbstractEntity;

/**
 * Class Cost
 * @package Ipol\Fivepost\Api\Entity\Request\Part\CreateOrder
 */
class Cost extends AbstractEntity
{
    /**
     * @var float
     */
    protected $deliveryCost;
    /**
     * @var string (Alpha-3)
     */
    protected $deliveryCostCurrency;
    /**
     * @var float
     */
    protected $paymentValue;
    /**
     * @var string (CASH | CASHLESS | PREPAYMENT)
     */
    protected $paymentType;
    /**
     * @var string (Alpha-3)
     */
    protected $paymentCurrency;
    /**
     * @var float
     */
    protected $price;
    /**
     * @var string (Alpha-3)
     */
    protected $priceCurrency;

    /**
     * @return float
     */
    public function getDeliveryCost()
    {
        return $this->deliveryCost;
    }

    /**
     * @param float $deliveryCost
     * @return Cost
     */
    public function setDeliveryCost($deliveryCost)
    {
        $this->deliveryCost = $deliveryCost;
        return $this;
    }

    /**
     * @return string
     */
    public function getDeliveryCostCurrency()
    {
        return $this->deliveryCostCurrency;
    }

    /**
     * @param string $deliveryCostCurrency
     * @return Cost
     */
    public function setDeliveryCostCurrency($deliveryCostCurrency)
    {
        $this->deliveryCostCurrency = $deliveryCostCurrency;
        return $this;
    }

    /**
     * @return float
     */
    public function getPaymentValue()
    {
        return $this->paymentValue;
    }

    /**
     * @param float $paymentValue
     * @return Cost
     */
    public function setPaymentValue($paymentValue)
    {
        $this->paymentValue = $paymentValue;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * @param string $paymentType
     * @return Cost
     */
    public function setPaymentType($paymentType)
    {
        $this->paymentType = $paymentType;
        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentCurrency()
    {
        return $this->paymentCurrency;
    }

    /**
     * @param string $paymentCurrency
     * @return Cost
     */
    public function setPaymentCurrency($paymentCurrency)
    {
        $this->paymentCurrency = $paymentCurrency;
        return $this;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return Cost
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return string
     */
    public function getPriceCurrency()
    {
        return $this->priceCurrency;
    }

    /**
     * @param string $priceCurrency
     * @return Cost
     */
    public function setPriceCurrency($priceCurrency)
    {
        $this->priceCurrency = $priceCurrency;
        return $this;
    }

}