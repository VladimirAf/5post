<?php


namespace Ipol\Fivepost\Fivepost\Controller;


use Ipol\Fivepost\Api\Entity\Request\JwtGenerate;
use Ipol\Fivepost\Fivepost\Entity\RequestJwtResult;

/**
 * Class RequestJwt
 * @package Ipol\Fivepost\Fivepost
 * @subpackage Controller
 */
class RequestJwt extends AutomatedCommonRequest
{
    /**
     * RequestJwt constructor.
     * @param string $apiKey
     */
    public function __construct(string $apiKey)
    {
        parent::__construct(new RequestJwtResult());
        $data = new JwtGenerate();
        $data->setApikey($apiKey);

        $this->setRequestObj($data);
    }

}