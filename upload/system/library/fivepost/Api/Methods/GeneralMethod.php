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
 * Class GeneralMethod
 * @package Ipol\Fivepost\Api
 * @subpackage Methods
 * @method AbstractResponse|mixed|ErrorResponse getResponse
 */
class GeneralMethod extends AbstractMethod
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
        ?EncoderInterface $encoder = null
    ) {
        parent::__construct($adapter, $encoder);

        if (!is_null($data)) {
            $this->setData($this->getEntityFields($data));
        }

        try {
            /**@var $response AbstractResponse*/
            $response = new $responseClass($this->request());
            $response->setSuccess(true);
        } catch (ApiLevelException $e) {
            $response = new ErrorResponse($e);
            $response->setSuccess(false);
        }

        $this->setResponse($this->reEncodeResponse($response));

        $this->setFields();
    }

}