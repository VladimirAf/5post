<?php
namespace Ipol\Fivepost\Api\Entity\Request\Part\OrdersMake;

use Ipol\Fivepost\Api\Entity\AbstractEntity;

/**
 * Class ProductValue
 * @package Ipol\Fivepost\Api\Entity\Request\Part\OrdersMake
 */
class ProductValue extends AbstractEntity
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var int quantity of these items in cargo
     */
    protected $value;

    /**
     * @var float product price with VAT included
     */
    protected $price;

    /**
     * @var string (Alpha-3)
     */
    protected $currency;

    /**
     *
     * @var int 0/10/20 - VAT %
     */
    protected $vat;

    /**
     * @var string|null base64-formatted marking code
     */
    protected $upiCode;

    /**
     * @var string|null
     */
    protected $vendorCode;

    /**
     * @var string|null
     */
    protected $originCountry;

    /**
     * @var string|null
     */
    protected $barcode;

    /**
     * @var string|null
     */
    protected $codeGTD;

    /**
     * @var string|null
     */
    protected $codeTNVED;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ProductValue
     */
    public function setName(string $name): ProductValue
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @param int $value
     * @return ProductValue
     */
    public function setValue(int $value): ProductValue
    {
        $this->value = $value;
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
     * @return ProductValue
     */
    public function setPrice(float $price): ProductValue
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return ProductValue
     */
    public function setCurrency(string $currency): ProductValue
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return int
     */
    public function getVat(): int
    {
        return $this->vat;
    }

    /**
     * @param int $vat
     * @return ProductValue
     */
    public function setVat(int $vat): ProductValue
    {
        $this->vat = $vat;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUpiCode(): ?string
    {
        return $this->upiCode;
    }

    /**
     * @param string|null $upiCode
     * @return ProductValue
     */
    public function setUpiCode(?string $upiCode): ProductValue
    {
        $this->upiCode = $upiCode;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVendorCode(): ?string
    {
        return $this->vendorCode;
    }

    /**
     * @param string|null $vendorCode
     * @return ProductValue
     */
    public function setVendorCode(?string $vendorCode): ProductValue
    {
        $this->vendorCode = $vendorCode;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOriginCountry(): ?string
    {
        return $this->originCountry;
    }

    /**
     * @param string|null $originCountry
     * @return ProductValue
     */
    public function setOriginCountry(?string $originCountry): ProductValue
    {
        $this->originCountry = $originCountry;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    /**
     * @param string|null $barcode
     * @return ProductValue
     */
    public function setBarcode(?string $barcode): ProductValue
    {
        $this->barcode = $barcode;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCodeGTD(): ?string
    {
        return $this->codeGTD;
    }

    /**
     * @param string|null $codeGTD
     * @return ProductValue
     */
    public function setCodeGTD(?string $codeGTD): ProductValue
    {
        $this->codeGTD = $codeGTD;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCodeTNVED(): ?string
    {
        return $this->codeTNVED;
    }

    /**
     * @param string|null $codeTNVED
     * @return ProductValue
     */
    public function setCodeTNVED(?string $codeTNVED): ProductValue
    {
        $this->codeTNVED = $codeTNVED;
        return $this;
    }
}