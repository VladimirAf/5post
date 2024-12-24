<?php


namespace Ipol\Fivepost\Api\Methods;

use Ipol\Fivepost\Api\Adapter\CurlAdapter;
use Ipol\Fivepost\Api\ApiLevelException;
use Ipol\Fivepost\Api\BadResponseException;
use Ipol\Fivepost\Api\Entity\EncoderInterface;
use Ipol\Fivepost\Api\Entity\Request\AbstractRequest;
use Ipol\Fivepost\Api\Entity\Response\AbstractResponse;
use Ipol\Fivepost\Api\Entity\Response\ErrorResponse;


/**
 * Class GeneralUrlImplementedMethod
 * @package Ipol\Fivepost\Api
 * @subpackage Methods
 * @method AbstractResponse|mixed|ErrorResponse getResponse
 */
class GeneralUrlImplementedMethod extends GeneralMethod
{
    /**
     * GetOrderHistory constructor.
     * @param AbstractRequest|mixed|null $data
     * @param CurlAdapter $adapter
     * @param string $responseClass
     * @param EncoderInterface|null $encoder
     * @throws BadResponseException
     */
    public function __construct(
        $data,
        CurlAdapter $adapter,
        string $responseClass,
        string $urlImplement,
        ?EncoderInterface $encoder = null
    ) {
        $this->setUrlImplement($this->encodeFieldToAPI($urlImplement));
        parent::__construct($data, $adapter, $responseClass, $encoder);
    }

}