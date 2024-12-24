<?php
namespace Ipol\Fivepost\Api\Entity\Response\Part\OrdersMake;

use Ipol\Fivepost\Api\Entity\AbstractCollection;

/**
 * Class CargoList
 * @package Ipol\Fivepost\Api
 * @subpackage Response
 * @method Cargo getFirst
 * @method Cargo getNext
 * @method Cargo getLast
 */
class CargoList extends AbstractCollection
{
    protected $Cargoes;

    public function __construct()
    {
        parent::__construct('Cargoes');
        $this->setChildClass(Cargo::class);
    }
}