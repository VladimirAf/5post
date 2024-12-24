<?php
namespace Ipol\Fivepost\Api\Entity\Response\Part\OrderLabels;

use Ipol\Fivepost\Api\Entity\AbstractCollection;

/**
 * Class OrderLabelsResultList
 * @package Ipol\Fivepost\Api
 * @subpackage Entity\Response
 * @method OrderLabelsResult getFirst
 * @method OrderLabelsResult getNext
 * @method OrderLabelsResult getLast
 */
class OrderLabelsResultList extends AbstractCollection
{
    protected $OrderLabelsResults;

    public function __construct()
    {
        parent::__construct('OrderLabelsResults');
    }
}