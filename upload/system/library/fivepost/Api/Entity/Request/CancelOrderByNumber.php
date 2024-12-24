<?php


namespace Ipol\Fivepost\Api\Entity\Request;


/**
 * Class CancelOrderByNumber
 * @package Ipol\Fivepost\Api\Entity\Request
 */
class CancelOrderByNumber extends AbstractRequest
{
    /**
     * @var string
     */
    protected $number;

    /**
     * CancelOrderByNumber constructor.
     * @param string $number
     */
    public function __construct($number)
    {
        parent::__construct();
        $this->number = $number;
        return $this;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $number
     * @return CancelOrderByNumber
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

}