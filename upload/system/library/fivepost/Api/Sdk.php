<?php
namespace Ipol\Fivepost\Api;

use Error;
use Ipol\Fivepost\Api\Adapter\CurlAdapter;
use Ipol\Fivepost\Api\Entity\EncoderInterface;

/**
 * Class Sdk
 * @package Ipol\Fivepost\Api
 */
class Sdk
{
    /**
     * @var array
     */
    protected $map;
    /**
     * @var CurlAdapter
     */
    private $adapter;
    /**
     * @var EncoderInterface|null
     */
    private $encoder;

    /**
     * Sdk constructor.
     * @param CurlAdapter $adapter
     * @param string $jwt
     * @param EncoderInterface|null $encoder
     * @param string $mode
     * @param bool $custom
     */
    public function __construct(
        CurlAdapter $adapter,
        string $jwt = '',
        EncoderInterface $encoder = null,
        string $mode = 'API',
        bool $custom = false
    ) {
        $this->adapter = $adapter;
        if ($jwt) {
            $this->adapter->appendHeaders(['Authorization: Bearer ' . $jwt]);
        }
        $this->encoder = $encoder;
        $this->map = self::getMap($mode, $custom);

    }

    /**
     * @param string $mode
     * @param bool $custom
     * @return array
     */
    protected function getMap(string $mode, bool $custom): array
    {
        $api = 'https://api-omni.x5.ru';
        $test = 'https://api-preprod-omni.x5.ru';

        $arMap = [
//----------Auth-----------------------------------------------------------------------------------
            'jwtGenerate' => [
                'API' => $api . '/jwt-generate-claims/rs256/1',
                'TEST' => $test . '/jwt-generate-claims/rs256/1',
                'REQUEST_TYPE' => 'FORM'
            ],
//----------Points and rates-----------------------------------------------------------------------
            'pickupPoints' => [
                'API' => $api . '/api/v1/pickuppoints/query',
                'TEST' => $test . '/api/v1/pickuppoints/query',
                'REQUEST_TYPE' => 'POST'
            ],
//----------Warehouses-----------------------------------------------------------------------------
            'warehouse' => [
                'API' => $api . '/api/v1/warehouse',
                'TEST' => $test . '/api/v1/warehouse',
                'REQUEST_TYPE' => 'POST'
            ],
            'warehouseInfo' => [
                'API' => $api.'/api/v1/warehouse/', // http://api-preprod-omni.x5.ru/api/v1/warehouse/57ff194a-b7b6-8ed4-9951-ddec0c2cee60
                'TEST' => $test.'/api/v1/warehouse/',
                'REQUEST_TYPE' => 'GET'
            ],
//----------Order----------------------------------------------------------------------------------
            'createOrder' => [
                'API' => $api . '/api/v1/createOrder',
                'TEST' => $test . '/api/v1/createOrder',
                'REQUEST_TYPE' => 'POST'
            ],
            'ordersMake' => [
                'API' => $api . '/api/v3/orders',
                'TEST' => $test . '/api/v3/orders',
                'REQUEST_TYPE' => 'POST'
            ],
            'cancelOrderById' => [
                'API' => $api . '/api/v2/cancelOrder/byOrderId/',
                'TEST' => $test . '/api/v2/cancelOrder/byOrderId/',
                'REQUEST_TYPE' => 'DELETE'
            ],
            'cancelOrderByNumber' => [
                'API' => $api . '/api/v2/cancelOrder/bySenderOrderId/',
                'TEST' => $test . '/api/v2/cancelOrder/bySenderOrderId/',
                'REQUEST_TYPE' => 'DELETE'
            ],
//----------Statuses-------------------------------------------------------------------------------
            'getOrderStatus' => [
                'API' => $api . '/api/v1/getOrderStatus',
                'TEST' => $test . '/api/v1/getOrderStatus',
                'REQUEST_TYPE' => 'POST'
            ],
            'getOrderHistory' => [
                'API' => $api . '/api/v1/getOrderHistory',
                'TEST' => $test . '/api/v1/getOrderHistory',
                'REQUEST_TYPE' => 'POST'
            ],
//----------Print documents------------------------------------------------------------------------
            'orderLabelsById' => [
                'API' => $api . '/api/v1/orderLabels/byOrderId',
                'TEST' => $test . '/api/v1/orderLabels/byOrderId',
                'REQUEST_TYPE' => 'POST'
            ],
            'orderLabelsByNumber' => [
                'API' => $api . '/api/v1/orderLabels/bySenderOrderId',
                'TEST' => $test . '/api/v1/orderLabels/bySenderOrderId',
                'REQUEST_TYPE' => 'POST'
            ],
//-------------------------------------------------------------------------------------------------
        ];

        if (defined('IPOL_FIVEPOST_CUSTOM_MAP') && is_array(IPOL_FIVEPOST_CUSTOM_MAP)) {
            foreach (IPOL_FIVEPOST_CUSTOM_MAP as $method => $url) {
                $arMap[$method]['CUSTOM'] = $url;
            }
        }

        if ($mode != 'TEST' && $mode != 'API') {
            throw new Error('Unknown Api-map configuring mode');
        }

        $arReturn = array();
        foreach ($arMap as $method => $arData) {
            if ($custom && isset($arData['CUSTOM'])) {
                $url = $arData['CUSTOM'];
            } else {
                $url = $arData[$mode];
            }

            $arReturn[$method] = array(
                'URL' => $url,
                'REQUEST_TYPE' => $arData['REQUEST_TYPE']
            );
        }
        return $arReturn;
    }

    /**
     * @param string $method name of method in api-map
     */
    protected function configureRequest(string $method): void
    {
        if (array_key_exists($method, $this->map)) {
            $url = $this->map[$method]['URL'];
            $type = $this->map[$method]['REQUEST_TYPE'];
        } else {
            throw new Error('Requested method "' . $method . '" not found in module map!');
        }

        $this->adapter->setMethod($method);
        $this->adapter->setUrl($url);
        $this->adapter->setRequestType($type);
    }

    /**
     * @param Entity\Request\JwtGenerate $data
     * @return Methods\JwtGenerate
     * @throws BadResponseException
     */
    public function jwtGenerate(Entity\Request\JwtGenerate $data): Methods\JwtGenerate
    {
        $this->configureRequest(__FUNCTION__);
        return new Methods\JwtGenerate($data, $this->adapter, $this->encoder);
    }

    /**
     * @param Entity\Request\PickupPoints $data
     * @return Methods\GeneralMethod
     * @throws BadResponseException
     */
    public function pickupPoints(Entity\Request\PickupPoints $data): Methods\GeneralMethod
    {
        $this->configureRequest(__FUNCTION__);
        return new Methods\GeneralMethod(
            $data,
            $this->adapter,
            Entity\Response\PickupPoints::class,
            $this->encoder
        );
    }

    /**
     * @param Entity\Request\Warehouse $data
     * @return Methods\GeneralMethod
     * @throws BadResponseException
     */
    public function warehouse(Entity\Request\Warehouse $data): Methods\GeneralMethod
    {
        $this->configureRequest(__FUNCTION__);
        return new Methods\GeneralMethod(
            $data->getWarehouses(),
            $this->adapter,
            Entity\Response\Warehouse::class,
            $this->encoder
        );
    }

    /**
     * @param string $uuid
     * @return Methods\GeneralUrlImplementedMethod
     * @throws BadResponseException
     */
    public function warehouseInfo(string $uuid): Methods\GeneralUrlImplementedMethod
    {
        $this->configureRequest(__FUNCTION__);
        return new Methods\GeneralUrlImplementedMethod(
            null,
            $this->adapter,
            Entity\Response\WarehouseInfo::class,
            $uuid,
            $this->encoder
        );
    }

    /**
     * @deprecated
     * @param Entity\Request\CreateOrder $data
     * @return Methods\GeneralMethod
     * @throws BadResponseException
     */
    public function createOrder(Entity\Request\CreateOrder $data): Methods\GeneralMethod
    {
        $this->configureRequest(__FUNCTION__);
        return new Methods\GeneralMethod(
            $data,
            $this->adapter,
            Entity\Response\CreateOrder::class,
            $this->encoder
        );
    }

    /**
     * Actual order creation method
     * @param Entity\Request\OrdersMake $data
     * @return Methods\GeneralMethod
     * @throws BadResponseException
     */
    public function ordersMake(Entity\Request\OrdersMake $data): Methods\GeneralMethod
    {
        $this->configureRequest(__FUNCTION__);
        return new Methods\GeneralMethod(
            $data,
            $this->adapter,
            Entity\Response\OrdersMake::class,
            $this->encoder
        );
    }

    /**
     * @param Entity\Request\CancelOrderById $data
     * @return Methods\GeneralUrlImplementedMethod
     * @throws BadResponseException
     */
    public function cancelOrderById(Entity\Request\CancelOrderById $data): Methods\GeneralUrlImplementedMethod
    {
        $this->configureRequest(__FUNCTION__);
        return new Methods\GeneralUrlImplementedMethod(
            null,
            $this->adapter,
            Entity\Response\CancelOrderById::class,
            $data->getUuid(),
            $this->encoder
        );
    }

    /**
     * @param Entity\Request\CancelOrderByNumber $data
     * @return Methods\GeneralUrlImplementedMethod
     * @throws BadResponseException
     */
    public function cancelOrderByNumber(Entity\Request\CancelOrderByNumber $data): Methods\GeneralUrlImplementedMethod
    {
        $this->configureRequest(__FUNCTION__);
        return new Methods\GeneralUrlImplementedMethod(
            null,
            $this->adapter,
            Entity\Response\CancelOrderByNumber::class,
            $data->getNumber(),
            $this->encoder
        );
    }

    /**
     * @param Entity\Request\GetOrderStatus $data
     * @return Methods\GeneralMethod
     * @throws BadResponseException
     */
    public function getOrderStatus(Entity\Request\GetOrderStatus $data): Methods\GeneralMethod
    {
        $this->configureRequest(__FUNCTION__);
        return new Methods\GeneralMethod(
            $data->getOrderStatuses(), // It's a trick, but who cares
            $this->adapter,
            Entity\Response\GetOrderStatus::class,
            $this->encoder
        );
    }

    /**
     * @param Entity\Request\GetOrderHistory $data
     * @return Methods\GeneralMethod
     * @throws BadResponseException
     */
    public function getOrderHistory(Entity\Request\GetOrderHistory $data): Methods\GeneralMethod
    {
        $this->configureRequest(__FUNCTION__);
        return new Methods\GeneralMethod(
            $data,
            $this->adapter,
            Entity\Response\GetOrderHistory::class,
            $this->encoder
        );
    }

    /**
     * @param Entity\Request\OrderLabelsById $data
     * @return Methods\GeneralMethod
     * @throws BadResponseException
     */
    public function orderLabelsById(Entity\Request\OrderLabelsById $data): Methods\GeneralMethod
    {
        $this->configureRequest(__FUNCTION__);
        return new Methods\GeneralMethod(
            $data,
            $this->adapter,
            Entity\Response\OrderLabels::class,
            null // NO ENCODING due to specific API response
        );
    }

    /**
     * @param Entity\Request\OrderLabelsByNumber $data
     * @return Methods\GeneralMethod
     * @throws BadResponseException
     */
    public function orderLabelsByNumber(Entity\Request\OrderLabelsByNumber $data): Methods\GeneralMethod
    {
        $this->configureRequest(__FUNCTION__);
        return new Methods\GeneralMethod(
            $data,
            $this->adapter,
            Entity\Response\OrderLabels::class,
            null // NO ENCODING due to specific API response
        );
    }
}