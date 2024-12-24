<?php


namespace Ipol\Fivepost\Fivepost\Controller;

use DateTime;
use Ipol\Fivepost\Api\BadResponseException;
use Ipol\Fivepost\Api\Entity\Request\CreateOrder;
use Ipol\Fivepost\Api\Entity\Request\Part\CreateOrder\Barcode;
use Ipol\Fivepost\Api\Entity\Request\Part\CreateOrder\BarcodeList;
use Ipol\Fivepost\Api\Entity\Request\Part\CreateOrder\Cargo;
use Ipol\Fivepost\Api\Entity\Request\Part\CreateOrder\CargoList;
use Ipol\Fivepost\Api\Entity\Request\Part\CreateOrder\Cost;
use Ipol\Fivepost\Api\Entity\Request\Part\CreateOrder\PartnerOrder;
use Ipol\Fivepost\Api\Entity\Request\Part\CreateOrder\PartnerOrderList;
use Ipol\Fivepost\Api\Entity\Request\Part\CreateOrder\ProductValue;
use Ipol\Fivepost\Api\Entity\Request\Part\CreateOrder\ProductValueList;
use Ipol\Fivepost\Api\Entity\Response\ErrorResponse;
use Ipol\Fivepost\Core\Order\OrderCollection;
use Ipol\Fivepost\Fivepost\AppLevelException;
use Ipol\Fivepost\Fivepost\Entity\OrderResult;
use Ipol\Fivepost\Fivepost\ErrorResponseException;


/**
 * Class Order
 * @package Ipol\Fivepost\Fivepost
 * @subpackage Controller
 */
class Order extends AutomatedCommonRequest
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
        parent::__construct(new OrderResult());
        $this->coreOrderCollection = $coreOrderCollection;
        $this->requestObj = new CreateOrder();
    }

    public function getSelfHash(): string
    {
        $order = $this->coreOrderCollection->getFirst();
        return md5($order->getNumber()); //order sending is not cached actually
    }


    /**
     * @return $this
     * @throws AppLevelException
     */
    public function convert(): Order
    {
        $orders = $this->coreOrderCollection->reset();

        $obOrderCollection = new PartnerOrderList();
        while ($order = $orders->getNext()) {
            $receiver = $order->getReceivers()->getFirst();
            $obOrder = new PartnerOrder();
            $obOrder
                ->setClientEmail($receiver->getEmail())
                ->setSenderCreateDate($order->getField('createDate'))
                ->setSenderOrderId($order->getNumber())
                ->setBrandName(($order->getSender()) ? $order->getSender()->getCompany() : $order->getField('brandName'))
                ->setClientOrderId($order->getField('track'))
                ->setClientName($receiver->getFullName())
                ->setClientPhone($receiver->getPhone())
                ->setReceiverLocation($order->getField('receiverLocation'))
                ->setSenderLocation($order->getField('senderLocation'))
                ->setUndeliverableOption($order->getField('undeliverableOption'))
                ->setPlannedReceiveDate($order->getField('plannedReceiveDate'))
                ->setShipmentDate($order->getField('shipmentDate'));

            $obCargoCollection = new CargoList();
            $obCargo = new Cargo();
            $obProductCollection = new ProductValueList();
            $items = $order->getItems()->reset();
            while ($item = $items->getNext()) {
                $obProduct = new ProductValue();
                $obProduct
                    ->setBarcode($item->getBarcode())
                    ->setVendorCode($item->getArticul())
                    ->setName($item->getName())
                    ->setPrice($item->getPrice()->getAmount())
                    ->setCurrency($item->getPrice()->getCurrency())
                    ->setValue($item->getQuantity())
                    ->setVat($item->getVatRate())
                    ->setOriginCountry($item->getField('oc'))
                    ->setCodeGTD($item->getField('ccd'))
                    ->setCodeTNVED($item->getField('tnved'));
                $obProductCollection->add($obProduct);
            }
            $obBarcodeCollection = new BarcodeList();
            $barcodesArray = is_array($order->getField('barcodes')) ?
                $order->getField('barcodes') :
                [$order->getField('barcodes')];
            foreach ($barcodesArray as $barcodeString) {
                $obBarcodeCollection->add(new Barcode($barcodeString));
            }
            $obCargo
                ->setProductValues($obProductCollection)
                ->setBarcodes($obBarcodeCollection)
                ->setCurrency($order->getField('currency'))
                ->setPrice($order->getPayment()->getCost()->getAmount())
                ->setLength($order->getGoods()->getLength())
                ->setWidth($order->getGoods()->getWidth())
                ->setHeight($order->getGoods()->getHeight())
                ->setWeight($order->getGoods()->getWeight() * 1000) //Core gram to API !milligram!
                ->setSenderCargoId($order->getField('senderCargoId'))
                ->setVat($order->getPayment()->getNdsDefault());

            $obCargoCollection->add($obCargo);
            $obOrder->setCargoes($obCargoCollection);

            $obCost = new Cost();
            $obCost
                ->setDeliveryCost($order->getPayment()->getDelivery()->getAmount())
                ->setDeliveryCostCurrency($order->getPayment()->getDelivery()->getCurrency())
                ->setPaymentCurrency($order->getPayment()->getGoods()->getCurrency())
                ->setPaymentType($this->convertPaymentType($order->getPayment()->getType()))
                ->setPaymentValue($order->getPayment()->getPrice()->getAmount())
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
                throw new AppLevelException('Payment type not set.'); //TODO BadRequestException
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