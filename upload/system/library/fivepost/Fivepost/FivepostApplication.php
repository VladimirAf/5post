<?php
namespace Ipol\Fivepost\Fivepost;

use Error;
use Exception;
use Ipol\Fivepost\Api\Adapter\CurlAdapter;
use Ipol\Fivepost\Api\Entity\EncoderInterface;
use Ipol\Fivepost\Api\Entity\Request\Part\Warehouse\WarehouseElemList;
use Ipol\Fivepost\Api\Logger\Psr\Log\LoggerInterface;
use Ipol\Fivepost\Api\Sdk;
use Ipol\Fivepost\Fivepost\Entity\OptionsInterface;
use Ipol\Fivepost\Core\Entity\CacheInterface;
use Ipol\Fivepost\Core\Entity\Collection;
use Ipol\Fivepost\Core\Order\OrderCollection;
use Ipol\Fivepost\Fivepost\Controller\AutomatedCommonRequest;
use Ipol\Fivepost\Fivepost\Controller\AutomatedCommonRequestByUuid;
use Ipol\Fivepost\Fivepost\Controller\CancelOrderByNumber;
use Ipol\Fivepost\Fivepost\Controller\CancelOrderByUuid;
use Ipol\Fivepost\Fivepost\Controller\Order;
use Ipol\Fivepost\Fivepost\Controller\OrderLabels;
use Ipol\Fivepost\Fivepost\Controller\OrdersMake;
use Ipol\Fivepost\Fivepost\Controller\OrderHistoryController;
use Ipol\Fivepost\Fivepost\Controller\OrderStatusController;
use Ipol\Fivepost\Fivepost\Controller\PickupPoints;
use Ipol\Fivepost\Fivepost\Controller\RequestController;
use Ipol\Fivepost\Fivepost\Controller\RequestJwt;
use Ipol\Fivepost\Fivepost\Controller\Warehouse;
use Ipol\Fivepost\Fivepost\Entity\AbstractResult;
use Ipol\Fivepost\Fivepost\Entity\GenerateBarcodeResult;
use Ipol\Fivepost\Fivepost\Handler\Tools;

/**
 * Class FivepostApplication
 * @package Ipol\Fivepost\Fivepost
 */
class FivepostApplication
{
    // Order ID types
    const ORDER_ID_TYPE_5P  = 'uuid';
    const ORDER_ID_TYPE_CMS = 'senderOrderId';

    /**
     * @var string
     */
    protected $apiKey;
    /**
     * @var string Auth bearer token
     */
    protected $jwt = '';
    /**
     * @var bool - shows if api mode is test or productive
     */
    protected $testMode = false;
    /**
     * @var bool - true if using custom URL for requests is allowed
     */
    protected $customAllowed = false;
    /**
     * @var EncoderInterface|null
     */
    protected $encoder;
    /**
     * @var LoggerInterface|null
     */
    protected $logger;
    /**
     * @var OptionsInterface|null
     */
    protected $options;
    /**
     * @var integer
     */
    protected $timeout;
    /**
     * @var CacheInterface|null
     */
    protected $cache;
    /**
     * @var array
     * saves results of calculation via hash
     */
    protected $abyss;
    /**
     * @var bool
     * set - data won't get into the abyss
     */
    protected $blockAbyss = true;
    /**
     * @var string
     * shows how was made last request: via cache, taken from abyss or by actual request to server
     */
    protected $lastRequestType = '';
    /**
     * @var string
     */
    protected $hash;
    /**
     * @var Collection empty if no errors occurred
     */
    protected $errorCollection;
    /**
     * @var bool
     * Indicates if the method was already called (for recurrent calls for dead jwt)
     */
    protected $recursionFlag = false;

    /**
     * FivepostApplication constructor.
     * @param string $apiKey
     * @param false $isTest
     * @param int $timeout
     * @param EncoderInterface|null $encoder
     * @param CacheInterface|null $cache
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        string $apiKey,
        bool $isTest = false,
        int $timeout = 6,
        ?EncoderInterface $encoder = null,
        ?CacheInterface $cache = null,
        ?LoggerInterface $logger = null
    ) {
        $this->setApiKey($apiKey)
            ->setTestMode($isTest)
            ->setTimeout(($timeout > 0)? $timeout : 6)
            ->setEncoder($encoder)
            ->setCache($cache)
            ->setLogger($logger);

        $this->abyss = array();
        $this->errorCollection = new Collection('errors');
        try {
            $this->getJwt();
        } catch (AppLevelException $e) {
            $this->addError($e);
            //$this->addError(new AppLevelException("No token, and failed to request new one."));
        }
    }

    /**
     * @param AutomatedCommonRequest|mixed $controller
     * @param bool $useCache
     * @param int $cacheTTL
     * @return AbstractResult|mixed
     */
    private function genericCall($controller, bool $useCache = false, int $cacheTTL = 3600)
    {
        $resultObj = $controller->getResultObject();
        $this->setHash($controller->getSelfHash());
        if ($this->checkAbyss()) {
            $this->lastRequestType = 'abyss';
            return $this->abyss[$this->getHash()];
        } else {
            if ($useCache && $this->getCache() && $this->getCache()->setLife($cacheTTL)->checkCache($this->getHash())) {
                $this->lastRequestType = 'cache';
                return $this->getCache()->getCache($this->getHash());
            } else {
                $this->lastRequestType = 'direct';

                try {
                    $this->configureController($controller);
                } catch (Exception $e) {
                    $this->addError($e);
                    return $resultObj;
                }
                $controller->convert()
                    ->execute();

                if ($resultObj->getError()) {
                    if (($resultObj->getError()->getCode() == 401) && !$this->recursionFlag) {
                        $this->setJwt('');
                        $this->recursionFlag = true; //blocking further recursive calls
                        try {
                            $this->getJwt(true); //forcing token-request
                        } catch (AppLevelException $e) {
                            $this->addError($e);
                            return $resultObj;
                        }
                        return $this->genericCall($controller, $useCache, $cacheTTL);
                    } else {
                        $this->addError($resultObj->getError());
                    }
                } else {
                    $this->toAbyss($resultObj);
                    if ($useCache) {
                        $this->toCache($resultObj, $this->getHash());
                    }
                }
            }
        }
        return $resultObj;
    }

    /**
     * @param bool $force
     * @return string
     * @throws AppLevelException
     */
    public function getJwt(bool $force = false): string
    {
        if ($this->jwt) {
            return $this->jwt;
        }

        if (!$force && $this->getCache() && $this->getCache()->checkCache(md5($this->getApiKey() . 'jwt'))) {
            $this->setJwt($this->getCache()->getCache(md5($this->getApiKey() . 'jwt')));
        } else {
            $newJwt = $this->requestJwt($this->getApiKey());
            if (!$newJwt->isSuccess()) {
                if ($newJwt->getError()) {
                    $this->addError($newJwt->getError());
                }
                throw new AppLevelException("Fail to get jwt-token!");
            } else {
                $this->setJwt($newJwt->getJwt());
                if ($this->getCache()) {
                    $this->getCache()->setLife(3600);
                    $this->getCache()->setCache(md5($this->getApiKey() . 'jwt'), $this->jwt);
                }
            }
        }
        return $this->jwt;
    }

    /**
     * @param string $jwt
     * @return FivepostApplication
     */
    public function setJwt(string $jwt): FivepostApplication
    {
        $this->jwt = $jwt;
        return $this;
    }

    /**
     * @param string $apiKey
     * @return Entity\RequestJwtResult
     */
    public function requestJwt(string $apiKey): Entity\RequestJwtResult
    {
        $this->lastRequestType = 'direct';
        $controller = new RequestJwt($apiKey);
        $controller->setSdkMethodName('jwtGenerate');
        try {
            $adapter = new CurlAdapter($this->getTimeout());
            if ($this->getLogger()) {
                $adapter->setLog($this->getLogger());
            }

            $sdk = new Sdk(
                $adapter,
                '',
                $this->getEncoder(),
                $this->testMode? 'TEST': 'API',
                $this->customAllowed
            );

            return $controller->setSdk($sdk)->execute();
        } catch (Exception $e) {
            $this->addError($e);
            $result = new Entity\RequestJwtResult();
            $result->setSuccess(false);
            return $result;
        }
    }

    /**
     * @param int $pageSize - amount points in one request. 1000 is recommended by documentation
     * @return Collection of \Ipol\Fivepost\Api\Entity\Response\Part\PickupPoint\Content
     */
    public function getAllPickupPoints(int $pageSize = 1000): Collection
    {
        $this->setHash(md5('allPickupPoints'));

        if ($this->checkAbyss()) {
            $this->lastRequestType = 'abyss';
            $return = $this->abyss[$this->getHash()];
        } else {
            if ($this->getCache() && $this->getCache()->checkCache($this->getHash())) {
                $this->lastRequestType = 'cache';
                $return = $this->getCache()->getCache($this->getHash());
            } else {
                $this->lastRequestType = 'direct';

                $pageNum = 0;
                $lastPage = 1;
                $return = new Collection('points');

                try {
                    while ($pageNum++ < $lastPage) {
                        $res = $this->getPickupPoints($pageNum, $pageSize);
                        $lastPage = $res->getResponse()->getTotalPages();
                        while ($point = $res->getResponse()->getContent()->getNext()) {
                            $return->add($point);
                        }
                    }

                    $this->toCache($return)
                        ->toAbyss($return);
                } catch (Exception $e) {//TODO redo
                    $this->addError(new AppLevelException('allPoints request failed on page ' . $pageNum . ' because ' . $e->getMessage()));
                }
            }
        }
        return $return;
    }

    /**
     * @param int $pageNum
     * @param int $pageSize
     * @return Entity\PickupPointsResult
     */
    public function getPickupPoints(int $pageNum, int $pageSize)
    {
        $controller = new PickupPoints($pageNum, $pageSize);
        $controller->setSdkMethodName('pickupPoints');
        return $this->genericCall($controller);
    }

    /**
     * @param WarehouseElemList $warehouse
     * @return Entity\WarehouseResult
     */
    public function createWarehouse(WarehouseElemList $warehouse)
    {
        $controller = new Warehouse($warehouse);
        $controller->setSdkMethodName('warehouse');
        return $this->genericCall($controller);
    }

    /**
     * @param string $uuid Warehouse UUID
     * @return Entity\WarehouseInfoResult
     */
    public function warehouseInfo(string $uuid)
    {
        $controller = new AutomatedCommonRequestByUuid(new Entity\WarehouseInfoResult(), $uuid);
        $controller->setSdkMethodName(__FUNCTION__);
        return $this->genericCall($controller);
    }

    /**
     * @deprecated
     * @param OrderCollection $orders
     * @return Entity\OrderResult
     */
    public function sendOrders(OrderCollection $orders)
    {
        $controller = new Order($orders);
        $controller->setSdkMethodName('createOrder');
        return $this->genericCall($controller);
    }

    /**
     * @param OrderCollection $orders
     * @return Entity\OrdersMakeResult
     */
    public function ordersMake(OrderCollection $orders)
    {
        $controller = new OrdersMake($orders);
        $controller->setSdkMethodName(__FUNCTION__);
        return $this->genericCall($controller);
    }

    /**
     * @param string $uuid - order uuid from fivepost API
     * @return Entity\CancelOrderByUuidResult
     */
    public function cancelOrderByUuid(string $uuid)
    {
        $controller = new CancelOrderByUuid($uuid);
        $controller->setSdkMethodName('cancelOrderById');
        return $this->genericCall($controller);
    }

    /**
     * @param string $number - order number in CMS
     * @return Entity\CancelOrderByNumberResult
     */
    public function cancelOrderByNumber(string $number)
    {
        $controller = new CancelOrderByNumber($number);
        $controller->setSdkMethodName(__FUNCTION__);
        return $this->genericCall($controller);
    }

    /**
     * @param array $arNumbers - array with numbers of orders
     * @param string $type - "uuid" if arNumbers has fivepost api uuids of orders
     *                          or "senderOrderId" if it has CMS order numbers
     * @return Entity\OrderStatusResult
     */
    public function getOrderStatus(array $arNumbers, string $type = 'uuid')
    {
        if ($type != 'uuid' && $type != 'senderOrderId') {
            throw new Error("Wrong order id type ($type) for method " . __METHOD__ . '!');
        }

        $controller = new OrderStatusController($arNumbers, $type);
        $controller->setSdkMethodName(__FUNCTION__);
        return $this->genericCall($controller);
    }

    /**
     * @param string $id - number of order
     * @param string $type - "uuid" if id is fivepost api uuid of order
     *                          or "senderOrderId" if it is CMS order number
     * @return Entity\OrderHistoryResult
     */
    public function getOrderHistory(string $id, string $type = 'uuid')
    {
        if ($type != 'uuid' && $type != 'senderOrderId') {
            throw new Error("Wrong order id type ($type) for method " . __METHOD__ . '!');
        }

        $controller = new OrderHistoryController($id, $type);
        $controller->setSdkMethodName(__FUNCTION__);
        return $this->genericCall($controller);
    }

    /**
     * @param string[] $orderNumbers - array with numbers of orders
     * @param string $type - FivepostApplication::ORDER_ID_TYPE_5P - $orderNumbers are Fivepost API uuids
     *                       FivepostApplication::ORDER_ID_TYPE_CMS - $orderNumbers are CMS order numbers
     * @return Entity\OrderLabelsResult
     */
    public function getOrderLabels(array $orderNumbers, string $type = self::ORDER_ID_TYPE_5P)
    {
        if ($type !== self::ORDER_ID_TYPE_5P && $type !== self::ORDER_ID_TYPE_CMS) {
            throw new Error("Wrong order id type ($type) for method " . __METHOD__ . '!');
        }

        $controller = new OrderLabels($orderNumbers, $type);
        $controller->setSdkMethodName(($type === self::ORDER_ID_TYPE_5P) ? 'orderLabelsById' : 'orderLabelsByNumber');
        return $this->genericCall($controller);
    }

    /**
     * @return Entity\GenerateBarcodeResult
     */
    public function generateBarcode(): GenerateBarcodeResult
    {
        try {
            return Tools::barcodeGenerate($this->getOptions());
        } catch (Exception $e) {
            $this->addError($e);
            $failResult = new GenerateBarcodeResult();
            $failResult->setSuccess(false);
            return $failResult;
        }
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     * @return FivepostApplication
     */
    public function setApiKey(string $apiKey): FivepostApplication
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTestMode(): bool
    {
        return $this->testMode;
    }

    /**
     * @param bool $testMode
     * @return FivepostApplication
     */
    public function setTestMode(bool $testMode): FivepostApplication
    {
        $this->testMode = $testMode;
        return $this;
    }

    /**
     * @return $this
     */
    public function allowCustom(): FivepostApplication
    {
        $this->customAllowed = true;
        return $this;
    }

    /**
     * @return $this
     */
    public function disallowCustom(): FivepostApplication
    {
        $this->customAllowed = false;
        return $this;
    }

    /**
     * @return bool|EncoderInterface
     */
    public function getEncoder()
    {
        return $this->encoder;
    }

    /**
     * @param false|EncoderInterface $encoder
     * @return FivepostApplication
     */
    public function setEncoder($encoder): FivepostApplication
    {
        $this->encoder = $encoder;
        return $this;
    }

    /**
     * @return LoggerInterface|null
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface|null $logger
     * @return FivepostApplication
     */
    public function setLogger($logger): FivepostApplication
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @return OptionsInterface|null
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param OptionsInterface $options
     * @return FivepostApplication
     */
    public function setOptions(OptionsInterface $options): FivepostApplication
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return int|false
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @param int|false $timeout
     * @return FivepostApplication
     */
    public function setTimeout($timeout): FivepostApplication
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * @return bool|CacheInterface
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @param bool|CacheInterface $cache
     * @return FivepostApplication
     */
    public function setCache($cache): FivepostApplication
    {
        $this->cache = $cache;
        return $this;
    }

    /**
     * @param mixed $data
     * @param string $hash
     * @param int $ttl
     * @return FivepostApplication
     */
    public function toCache($data, int $ttl = 3600, string $hash = ""): FivepostApplication
    {
        if(!$hash)
            $hash = $this->getHash();
        if(!$hash || $data === null || !$this->getCache())
            return $this;

        $this->getCache()->setLife($ttl);
        $this->getCache()->setCache($hash, $data);
        return $this;
    }

    /**
     * @return array
     */
    public function getAbyss()
    {
        return $this->abyss;
    }

    /**
     * @param array $abyss
     * @return FivepostApplication
     */
    public function setAbyss($abyss): FivepostApplication
    {
        $this->abyss = $abyss;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAbyssLocked(): bool
    {
        return $this->blockAbyss;
    }

    /**
     * @param bool $blockAbyss
     * @return FivepostApplication
     */
    public function setAbyssLock(bool $blockAbyss): FivepostApplication
    {
        $this->blockAbyss = $blockAbyss;
        return $this;
    }

    /**
     * @param bool|string $hash
     * @return bool|mixed
     * checks whether same request was already done
     */
    public function checkAbyss($hash = false)
    {
        $hash = ($hash) ? $hash : $this->getHash();
        if(!$this->isAbyssLocked() &&
            $hash &&
            array_key_exists($hash, $this->abyss)
        ){
            return $this->abyss[$hash];
        }
        return false;
    }

    /**
     * @param $val
     * @param bool $hash
     * @return $this
     * returns saved request
     */
    public function toAbyss($val, $hash = false): FivepostApplication
    {
        $hash = ($hash)? $hash : $this->getHash();
        if(!$this->isAbyssLocked() && $hash)
            $this->abyss[$hash] = $val;

        return $this;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @param mixed $hash
     * @return FivepostApplication
     */
    public function setHash($hash): FivepostApplication
    {
        $this->hash = $hash;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getErrorCollection(): Collection
    {
        return $this->errorCollection;
    }

    /**
     * @param mixed $error - throwable (Exceptions)
     * @return $this
     */
    protected function addError($error): FivepostApplication
    {
        $this->errorCollection->add($error);
        return $this;
    }

    /**
     * @return string
     * @deprecated - use getErrorCollection()->getLast() instead
     */
    public function getLastError()
    {
        if($lastError = $this->getErrorCollection()->getLast())
        {
            /**@var Exception $lastError*/
            return $lastError->getMessage();
        }
        else
            return '';
    }

    /**
     * @return string
     */
    public function getLastRequestType(): string
    {
        return $this->lastRequestType;
    }

    /**
     * @param RequestController $controller
     * sets sdk
     * @throws Exception
     */
    protected function configureController($controller)
    {
        $controller->setSdk($this->getSDK());
    }

    /**
     * @return Sdk
     * get the sdk-controller
     * ! timeout sets only here: later it wouldn't be changed !
     * @throws Exception
     */
    public function getSDK(): Sdk
    {
        $adapter = new CurlAdapter($this->timeout);
        if ($this->getLogger()) {
            $adapter->setLog($this->getLogger());
        }

        return new Sdk(
            $adapter,
            $this->getJwt(),
            $this->encoder,
            $this->testMode? 'TEST': 'API',
            $this->customAllowed
        );
    }

}