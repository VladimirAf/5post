<?php
namespace Ipol\Fivepost\Fivepost\Controller;

use Error;
use Exception;
use Ipol\Fivepost\Api\BadResponseException;
use Ipol\Fivepost\Api\Entity\Response\ErrorResponse;
use Ipol\Fivepost\Api\Methods\AbstractMethod;
use Ipol\Fivepost\Fivepost\AppLevelException;
use Ipol\Fivepost\Fivepost\Entity\AbstractResult;
use Ipol\Fivepost\Fivepost\ErrorResponseException;
use ReflectionClass;

/**
 * Class AutomatedCommonRequest
 * @package Ipol\Fivepost\Fivepost
 * @subpackage Controller
 */
class AutomatedCommonRequest extends RequestController
{
    /**
     * BasicController constructor.
     * @param AbstractResult|mixed $resultObj
     */
    public function __construct($resultObj)
    {
        $this->resultObject = $resultObj;
    }

    /**
     * @return $this|mixed
     */
    public function convert()
    {
        return $this;
    }

    /**
     * @return AbstractResult|mixed
     */
    public function execute()
    {
        $result = $this->getResultObject();

        try {
            if ($this->getRequestObj()) {
                $requestProcess = $this->getSdk()->{$this->getSdkMethodName()}($this->getRequestObj());
            } else {
                $requestProcess = $this->getSdk()->{$this->getSdkMethodName()}();
            }

            /**@var $requestProcess AbstractMethod*/
            $result->setSuccess($requestProcess->getResponse()->getSuccess())
                ->setResponse($requestProcess->getResponse());
            if ($result->isSuccess()) {
                $result->parseFields();
            } elseif (is_a($requestProcess->getResponse(), ErrorResponse::class)) {
                    $result->setError(new ErrorResponseException($requestProcess->getResponse()));
                }
        } catch (BadResponseException | AppLevelException $e) {
            $result->setSuccess(false)
                ->setError($e);
        } catch (Exception | Error $e) {
            // Handling errors such as argument types mismatch
            $result->setSuccess(false)->setError(new Exception($e->getMessage()));
        } finally {
            return $result;
        }
    }

    public function getSelfHash(): string
    {
        $extended = new ReflectionClass(get_class($this)); //real running classname - extension-class

        if ($extended->getMethod('convert')->getDeclaringClass()->name === get_class($this) &&
            get_class($this) !== __CLASS__) {
            throw new Error('Default getSelfHash() method is not suitable for converted requests. Declare custom method in extended class.');
        }
        return md5($this->getSelfHashByRequestObj());
    }

    protected function getSelfHashByRequestObj(): string
    {
        if (!is_null($this->getRequestObj())) {
            $resString = get_class($this->getRequestObj());
            $resString .= serialize($this->getRequestObj()->getFields());
        } else {
            $resString = get_class($this->getResultObject());
        }

        return $resString;
    }
}