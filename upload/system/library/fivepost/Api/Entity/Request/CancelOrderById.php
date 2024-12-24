<?php


namespace Ipol\Fivepost\Api\Entity\Request;


/**
 * Class CancelOrderById
 * @package Ipol\Fivepost\Api\Entity\Request
 */
class CancelOrderById extends AbstractRequest
{
    /**
     * @var string
     */
    protected $uuid;

    /**
     * CancelOrderById constructor.
     * @param string $uuid
     */
    public function __construct($uuid)
    {
        parent::__construct();
        $this->uuid = $uuid;
        return $this;
    }

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     * @return CancelOrderById
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
        return $this;
    }

}