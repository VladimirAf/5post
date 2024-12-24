<?php


namespace Ipol\Fivepost\Api\Entity\Request\Part\CreateOrder;


use Ipol\Fivepost\Api\Entity\AbstractEntity;

/**
 * Class Cargo
 * @package Ipol\Fivepost\Api
 * @subpackage Request
 */
class Cargo extends AbstractEntity
{
    /**
     * @var string
     */
    protected $senderCargoId;
    /**
     * @var BarcodeList
     */
    protected $barcodes;
    /**
     * @var string (Alpha-3)
     */
    protected $currency;
    /**
     * @var float
     */
    protected $price;
    /**
     * @var float
     */
    protected $height;
    /**
     * @var float
     */
    protected $length;
    /**
     * @var float
     */
    protected $width;
    /**
     * @var float - milligram !
     */
    protected $weight;
    /**
     * if items in cargo have different VAT - use null
     * @var int|null
     */
    protected $vat;
    /**
     * @var ProductValueList
     */
    protected $productValues;

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
     * @return BarcodeList
     */
    public function getBarcodes(): BarcodeList
    {
        return $this->barcodes;
    }

    /**
     * @param BarcodeList $barcodes
     * @return Cargo
     */
    public function setBarcodes(BarcodeList $barcodes): Cargo
    {
        $this->barcodes = $barcodes;
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
     * @return int|null
     */
    public function getVat(): ?int
    {
        return $this->vat;
    }

    /**
     * @param int|null $vat
     * @return Cargo
     */
    public function setVat(?int $vat): Cargo
    {
        $this->vat = $vat;
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