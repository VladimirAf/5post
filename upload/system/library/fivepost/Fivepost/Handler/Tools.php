<?php
namespace Ipol\Fivepost\Fivepost\Handler;

use Exception;
use Ipol\Fivepost\Fivepost\Entity\OptionsInterface;
use Ipol\Fivepost\Fivepost\Entity\GenerateBarcodeResult;

/**
 * Class Tools
 * @package Ipol\Fivepost\Fivepost\Handler
 */
class Tools
{
    /**
     * @param int $uniqueOmniId
     * @param int $uniqueItemCode
     * @return string always 14 unit long can be converted to int
     * @throws Exception
     */
    public static function barcodeGlueSubLogic(int $uniqueOmniId, int $uniqueItemCode)
    {
        if($uniqueOmniId < 0)
            throw new Exception('barcodeGenerate failed! Invalid uniqueOmniId: '. $uniqueOmniId);
        if((strlen($uniqueOmniId) > 4))
            throw new Exception('barcodeGenerate failed! Unacceptable uniqueOmniId (longer then 4 units): '. $uniqueOmniId);
        if($uniqueOmniId < 0)
            throw new Exception('barcodeGenerate failed! Invalid uniqueItemCode: '. $uniqueItemCode);
        if((strlen($uniqueItemCode) > 9))
            throw new Exception('barcodeGenerate failed! Unacceptable uniqueItemCode (longer then 10 units) : '. $uniqueItemCode);

        return str_pad($uniqueOmniId, 4, "0", STR_PAD_LEFT) . str_pad($uniqueItemCode, 10, "0", STR_PAD_LEFT);
    }

    /**
     * @param OptionsInterface $options
     * @return GenerateBarcodeResult
     * @throws Exception
     */
    public static function barcodeGenerate(OptionsInterface $options): GenerateBarcodeResult
    {
        $counter = $options::fetchOption('barkCounter');
        $objBarcodeResult = new GenerateBarcodeResult();
        $objBarcodeResult->setBarcode(self::barcodeGlueSubLogic($options::fetchOption('barkID'), $counter))
        ->setIncrement((strlen($counter) > 10)? 0 : ++$counter)
        ->setSuccess(true);

        return $objBarcodeResult;
    }
}