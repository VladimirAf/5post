<?php


namespace Ipol\Fivepost\Fivepost\Entity;


use Ipol\Fivepost\Core\Entity\BasicResponse;

/**
 * Class GenerateBarcodeResult
 * @package Ipol\Fivepost\Fivepost
 * @subpackage Entity
 * @method BasicResponse getResponse()
 */
class GenerateBarcodeResult extends BasicResponse
{
    /**
     * @var string
     */
    protected $barcode;
    /**
     * @var int
     */
    protected $increment;

    /**
     * @return string
     */
    public function getBarcode(): string
    {
        return $this->barcode;
    }

    /**
     * @param string $barcode
     * @return GenerateBarcodeResult
     */
    public function setBarcode(string $barcode): GenerateBarcodeResult
    {
        $this->barcode = $barcode;
        return $this;
    }

    /**
     * @return int
     */
    public function getIncrement(): int
    {
        return $this->increment;
    }

    /**
     * @param int $increment
     * @return GenerateBarcodeResult
     */
    public function setIncrement(int $increment): GenerateBarcodeResult
    {
        $this->increment = $increment;
        return $this;
    }

}