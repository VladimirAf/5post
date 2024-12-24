<?php
namespace Ipol\Fivepost\Api\Entity\Request\Part\OrdersMake;

use Ipol\Fivepost\Api\Entity\AbstractCollection;

/**
 * Class BarcodeList
 * @package Ipol\Fivepost\Api
 * @subpackage Request
 * @method Barcode getFirst
 * @method Barcode getNext
 * @method Barcode getLast
 */
class BarcodeList extends AbstractCollection
{
    protected $Barcodes;

    public function __construct()
    {
        parent::__construct('Barcodes');
    }
}