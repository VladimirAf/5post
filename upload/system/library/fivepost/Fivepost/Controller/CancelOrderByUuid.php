<?php


namespace Ipol\Fivepost\Fivepost\Controller;


use Ipol\Fivepost\Api\Entity\Request\CancelOrderById;
use Ipol\Fivepost\Fivepost\Entity\CancelOrderByUuidResult;

/**
 * Class CancelOrderByUuid
 * @package Ipol\Fivepost\Fivepost
 * @subpackage Controller
 */
class CancelOrderByUuid extends AutomatedCommonRequest
{
    /**
     * CancelOrderByUuid constructor.
     * @param string $uuid
     */
    public function __construct(string $uuid)
    {
        parent::__construct(new CancelOrderByUuidResult());
        $this->setRequestObj(new CancelOrderById($uuid));

    }
}