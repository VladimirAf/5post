<?php


namespace Ipol\Fivepost\Fivepost;


use Exception;
use Ipol\Fivepost\Api\Entity\Response\ErrorResponse;

class ErrorResponseException extends Exception
{
    /**
     * ErrorResponseException constructor.
     */
    public function __construct(ErrorResponse $errorResponse)
    {
        parent::__construct($errorResponse->getMessage() . $errorResponse->getErrorMsg(), $errorResponse->getErrorCode());
    }
}