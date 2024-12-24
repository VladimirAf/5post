<?php
namespace Ipol\Fivepost\Api\Entity\Response;

use Ipol\Fivepost\Api\Entity\Response\Part\CreateOrder\CargoList;
use Ipol\Fivepost\Api\Tools;

/**
 * Class CreateOrder
 * @package Ipol\Fivepost\Api\Entity\Response
 */
class CreateOrder extends AbstractResponse
{
    /**
     * @var string UUID
     */
    protected $orderId;

    /**
     * @var string
     */
    protected $senderOrderId;

    /**
     * @var int
     */
    protected $code;

    /**
     * @var string|null
     */
    protected $message;

    /**
     * @var CargoList
     */
    protected $cargoes;

    /**
     * @var bool
     */
    protected $alreadyCreated;

    /**
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param string $orderId
     * @return CreateOrder
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
        return $this;
    }

    /**
     * @return string
     */
    public function getSenderOrderId()
    {
        return $this->senderOrderId;
    }

    /**
     * @param string $senderOrderId
     * @return CreateOrder
     */
    public function setSenderOrderId($senderOrderId)
    {
        $this->senderOrderId = $senderOrderId;
        return $this;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     * @return CreateOrder
     */
    public function setCode(int $code): CreateOrder
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     * @return CreateOrder
     */
    public function setMessage(?string $message): CreateOrder
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return CargoList
     */
    public function getCargoes()
    {
        return $this->cargoes;
    }

    /**
     * @param array $array
     * @return CreateOrder
     * @throws \Exception
     */
    public function setCargoes($array)
    {
        if(Tools::isSeqArr($array))
        {
            $collection = new CargoList();
            $this->cargoes = $collection->fillFromArray($array);
            return $this;
        }
        else
        {
            throw new \Exception(__FUNCTION__.' requires parameter to be SEQUENTIAL array. '. gettype($array). ' given.');
        }
    }

    /**
     * @return bool
     */
    public function isAlreadyCreated()
    {
        return $this->alreadyCreated;
    }

    /**
     * @param bool $alreadyCreated
     * @return CreateOrder
     */
    public function setAlreadyCreated($alreadyCreated)
    {
        $this->alreadyCreated = $alreadyCreated;
        return $this;
    }

    public function setDecoded($decoded)
    {
        if(Tools::isSeqArr($decoded))  //TODO remake into Response structure!
            parent::setDecoded($decoded[0]);
        else
            parent::setDecoded($decoded);

        return $this;
    }
}