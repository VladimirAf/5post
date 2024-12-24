<?php


namespace Ipol\Fivepost\Fivepost\Controller;


use Ipol\Fivepost\Fivepost\Entity\CancelOrderByNumberResult;
use Ipol\Fivepost\Api\Entity\Request\CancelOrderByNumber as CancelRequest;

/**
 * Class CancelOrderByNumber
 * @package Ipol\Fivepost\Fivepost
 * @subpackage Controller
 */
class CancelOrderByNumber extends AutomatedCommonRequest
{
    /**
     * CancelOrderByNumber constructor.
     * @param string $number
     */
    public function __construct(string $number)
    {
        $this->setRequestObj(new CancelRequest($number));
        parent::__construct(new CancelOrderByNumberResult());
    }
}