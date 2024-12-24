<?php


namespace Ipol\Fivepost\Api\Entity\Request\Part\CreateOrder;


use Ipol\Fivepost\Api\Entity\AbstractCollection;

class BarcodeList extends AbstractCollection
{
    protected $Barcodes;

    public function __construct()
    {
        parent::__construct('Barcodes');
    }

    /**
     * @return Barcode
     */
    public function getFirst(){
        return parent::getFirst();
    }

    /**
     * @return Barcode
     */
    public function getNext(){
        return parent::getNext();
    }
}