<?php
namespace Ipol\Fivepost\Fivepost\Controller;

use DateTime;
use Ipol\Fivepost\Api\BadResponseException;
use Ipol\Fivepost\Api\Entity\Request\OrdersMake as RequestObj;
use Ipol\Fivepost\Api\Entity\Request\Part\OrdersMake\Barcode;
use Ipol\Fivepost\Api\Entity\Request\Part\OrdersMake\BarcodeList;
use Ipol\Fivepost\Api\Entity\Request\Part\OrdersMake\Cargo;
use Ipol\Fivepost\Api\Entity\Request\Part\OrdersMake\CargoList;
use Ipol\Fivepost\Api\Entity\Request\Part\OrdersMake\Cost;
use Ipol\Fivepost\Api\Entity\Request\Part\OrdersMake\PartnerOrder;
use Ipol\Fivepost\Api\Entity\Request\Part\OrdersMake\PartnerOrderList;
use Ipol\Fivepost\Api\Entity\Request\Part\OrdersMake\ProductValue;
use Ipol\Fivepost\Api\Entity\Request\Part\OrdersMake\ProductValueList;
use Ipol\Fivepost\Api\Entity\Response\ErrorResponse;
use Ipol\Fivepost\Core\Order\OrderCollection;
use Ipol\Fivepost\Fivepost\AppLevelException;
use Ipol\Fivepost\Fivepost\Entity\OrdersMakeResult as ResultObj;
use Ipol\Fivepost\Fivepost\ErrorResponseException;


/**
 * Class OrdersMake
 * @package Ipol\Fivepost\Fivepost
 * @subpackage Controller
 */
class OrdersMake extends AutomatedCommonRequest
{
    /**
     * @var OrderCollection
     */
    protected $coreOrderCollection;

    /**
     * @param OrderCollection $coreOrderCollection
     */
    public function __construct(OrderCollection $coreOrderCollection)
    {
        parent::__construct(new ResultObj());
        $this->coreOrderCollection = $coreOrderCollection;
        $this->requestObj = new RequestObj();
    }

    public function getSelfHash(): string
    {
        $order = $this->coreOrderCollection->getFirst();
        return md5($order->getNumber()); // Order sending is not cached actually
    }

    /**
     * @return $this
     * @throws AppLevelException
     */
    public function convert(): OrdersMake
    {
        $orders = $this->coreOrderCollection->reset();

        $obOrderCollection = new PartnerOrderList();
        while ($order = $orders->getNext()) {
            $receiver = $order->getReceivers()->getFirst();
            $obOrder = new PartnerOrder();
            $obOrder
                ->setSenderOrderId($order->getNumber())
                ->setClientOrderId($order->getField('track'))
                ->setBrandName(($order->getSender()) ? $order->getSender()->getCompany() : $order->getField('brandName'))
                ->setClientName($receiver->getFullName())
                ->setClientPhone($receiver->getPhone())
                ->setClientEmail($receiver->getEmail())
                ->setSenderLocation($order->getField('senderLocation'))
                ->setReturnLocation($order->getField('returnLocation'))
                ->setReceiverLocation($order->getField('receiverLocation'))
                ->setReceiverLocationMDM($order->getField('receiverLocationMDM'))
                ->setUndeliverableOption($order->getField('undeliverableOption'))
                ->setSenderCreateDate($order->getField('createDate'))
                ->setShipmentDate($order->getField('shipmentDate'))
                ->setPlannedReceiveDate($order->getField('plannedReceiveDate'))
                ->setRateTypeCode($order->getField('rateTypeCode'));

            $obCargoCollection   = new CargoList();
            $obCargo             = new Cargo();
            $obProductCollection = new ProductValueList();
            $items = $order->getItems()->reset();
            while ($item = $items->getNext()) {
                $obProduct = new ProductValue();
                $obProduct
                    ->setName($item->getName())
                    ->setValue($item->getQuantity())
                    ->setPrice($item->getPrice()->getAmount())
                    ->setCurrency($item->getPrice()->getCurrency())
                    ->setVat($item->getVatRate())
                    ->setUpiCode($item->getField('upiCode')) // base64-formatted marking code
                    ->setVendorCode($item->getArticul())
                    ->setOriginCountry($item->getField('oc'))
                    ->setBarcode($item->getBarcode())
                    ->setCodeGTD($item->getField('ccd'))
                    ->setCodeTNVED($item->getField('tnved'));

                $obProductCollection->add($obProduct);
            }

            if (!empty($order->getField('barcodes'))) {
                $obBarcodeCollection = new BarcodeList();
                $barcodesArray = is_array($order->getField('barcodes')) ?
                    $order->getField('barcodes') :
                    [$order->getField('barcodes')];
                foreach ($barcodesArray as $barcodeString) {
                    $obBarcodeCollection->add(new Barcode($barcodeString));
                }

                $obCargo->setBarcodes($obBarcodeCollection);
            }

            $obCargo
                ->setSenderCargoId($order->getField('senderCargoId'))
                ->setHeight($order->getGoods()->getHeight())
                ->setLength($order->getGoods()->getLength())
                ->setWidth($order->getGoods()->getWidth())
                ->setWeight($order->getGoods()->getWeight() * 1000) // Core gram to API !milligram!
                ->setPrice($order->getPayment()->getCost()->getAmount())
                ->setCurrency($order->getField('currency'))
                ->setProductValues($obProductCollection);

            $obCargoCollection->add($obCargo);
            $obOrder->setCargoes($obCargoCollection);

            $obCost = new Cost();
            $obCost
                ->setDeliveryCost($order->getPayment()->getDelivery()->getAmount())
                ->setDeliveryCostCurrency($order->getPayment()->getDelivery()->getCurrency())
                ->setPaymentValue($order->getPayment()->getPrice()->getAmount())
                ->setPaymentCurrency($order->getPayment()->getGoods()->getCurrency())
                ->setPaymentType($this->convertPaymentType($order->getPayment()->getType()))
                ->setPrice($order->getPayment()->getCost()->getAmount())
                ->setPriceCurrency($order->getPayment()->getCost()->getCurrency());
            $obOrder->setCost($obCost);

            $obOrderCollection->add($obOrder);
        }

        $this->requestObj->setPartnerOrders($obOrderCollection);

        return $this;
    }

    /**
     * @param string $coreType
     * @return string
     * @throws AppLevelException
     */
    private function convertPaymentType(string $coreType): string
    {
        switch ($coreType) {
            case 'Cash':
                return 'CASH';
            case 'Card':
                return 'CASHLESS';
            case 'Bill':
                return 'PREPAYMENT';
            default:
                throw new AppLevelException('Payment type not set.'); // TODO BadRequestException
        }
    }

    /**
     * @param DateTime|null $dateTime
     * @return string|null
     */
    private function dateTimeToApiFormat(?DateTime $dateTime): ?string
    {
        return $dateTime ? $dateTime->format('Y-m-d\TH:i:sO') : null;
    }
}