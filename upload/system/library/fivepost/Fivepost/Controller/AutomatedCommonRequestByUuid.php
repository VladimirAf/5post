<?php
namespace Ipol\Fivepost\Fivepost\Controller;

use Error;
use Exception;
use Ipol\Fivepost\Api\BadResponseException;
use Ipol\Fivepost\Api\Entity\Response\ErrorResponse;
use Ipol\Fivepost\Fivepost\AppLevelException;
use Ipol\Fivepost\Fivepost\Entity\AbstractResult;
use Ipol\Fivepost\Fivepost\ErrorResponseException;

/**
 * Class AutomatedCommonRequestByUuid
 * @package Ipol\Fivepost\Fivepost
 * @subpackage Controller
 */
class AutomatedCommonRequestByUuid extends AutomatedCommonRequest
{
    /**
     * @var string
     */
    protected $uuid;

    /**
     * BasicController constructor.
     * @param AbstractResult|mixed $resultObj
     */
    public function __construct($resultObj, string $uuid)
    {
        parent::__construct($resultObj);
        $this->uuid = $uuid;
    }

    /**
     * @return AbstractResult|mixed
     */
    public function execute()
    {
        $result = $this->getResultObject();

        try {
            $requestProcess = $this->getSdk()->{$this->getSdkMethodName()}($this->uuid);

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
}