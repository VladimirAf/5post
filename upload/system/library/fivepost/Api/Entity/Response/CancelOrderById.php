<?php


namespace Ipol\Fivepost\Api\Entity\Response;


/**
 * Class CancelOrderById
 * @package Ipol\Fivepost\Api\Entity\Response
 */
class CancelOrderById extends AbstractResponse
{
    /**
     * @var string
     */
    protected $error;

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param string $error
     * @return CancelOrderById
     */
    public function setError($error)
    {
        $this->error = $error;
        return $this;
    }

}