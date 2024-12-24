<?php
namespace Ipol\Fivepost\Api\Entity\Request\Part\OrdersMake;

use Ipol\Fivepost\Api\Entity\AbstractEntity;

/**
 * Class Cargo
 * @package Ipol\Fivepost\Api
 * @subpackage Request
 */
class Cargo extends AbstractEntity
{
    /**
     * @var BarcodeList|null
     */
    protected $barcodes;

    /**
     * @var string
     */
    protected $senderCargoId;

    /**
     * @var float mm > 0
     */
    protected $height;

    /**
     * @var float mm > 0
     */
    protected $length;

    /**
     * @var float mm > 0
     */
    protected $width;

    /**
     * @var float milligram > 0
     */
    protected $weight;

    /**
     * @var float estimated cost with VAT included
     */
    protected $price;

    /**
     * @var string (Alpha-3)
     */
    protected $currency;

    /**
     * @var ProductValueList
     */
    protected $productValues;

    /**
     * @return BarcodeList|null
     */
    public function getBarcodes(): ?BarcodeList
    {
        return $this->barcodes;
    }

    /**
     * @param BarcodeList|null $barcodes
     * @return Cargo
     */
    public function setBarcodes(?BarcodeList $barcodes): Cargo
    {
        $this->barcodes = $barcodes;
        return $this;
    }

    /**
     * @return string
     */
    public function getSenderCargoId(): string
    {
        return $this->senderCargoId;
    }

    /**
     * @param string $senderCargoId
     * @return Cargo
     */
    public function setSenderCargoId(string $senderCargoId): Cargo
    {
        $this->senderCargoId = $senderCargoId;
        return $this;
    }

    /**
     * @return float
     */
    public function getHeight(): float
    {
        return $this->height;
    }

    /**
     * @param float $height
     * @return Cargo
     */
    public function setHeight(float $height): Cargo
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @return float
     */
    public function getLength(): float
    {
        return $this->length;
    }

    /**
     * @param float $length
     * @return Cargo
     */
    public function setLength(float $length): Cargo
    {
        $this->length = $length;
        return $this;
    }

    /**
     * @return float
     */
    public function getWidth(): float
    {
        return $this->width;
    }

    /**
     * @param float $width
     * @return Cargo
     */
    public function setWidth(float $width): Cargo
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return float
     */
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     * @return Cargo
     */
    public function setWeight(float $weight): Cargo
    {
        $this->weight = $weight;
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
     * @return Cargo
     */
    public function setPrice(float $price): Cargo
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return Cargo
     */
    public function setCurrency(string $currency): Cargo
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return ProductValueList
     */
    public function getProductValues(): ProductValueList
    {
        return $this->productValues;
    }

    /**
     * @param ProductValueList $productValues
     * @return Cargo
     */
    public function setProductValues(ProductValueList $productValues): Cargo
    {
        $this->productValues = $productValues;
        return $this;
    }
}