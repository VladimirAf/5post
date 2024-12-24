<?php


namespace Ipol\Fivepost\Fivepost\Entity;


use Ipol\Fivepost\Api\Entity\Response\ErrorResponse;
use Ipol\Fivepost\Api\Entity\Response\JwtGenerate as ObjResponse;

/**
 * Class RequestJwtResult
 * @package Ipol\Fivepost\Fivepost
 * @subpackage Entity
 * @method ObjResponse|ErrorResponse getResponse()
 */
class RequestJwtResult extends AbstractResult
{
    /**
     * @var string
     */
    protected $jwt;

    /**
     * @return string
     */
    public function getJwt(): string
    {
        return $this->jwt;
    }

    /**
     * @return void
     */
    public function parseFields(): void
    {
        $this->jwt = $this->getResponse()->getJwt();
    }
}