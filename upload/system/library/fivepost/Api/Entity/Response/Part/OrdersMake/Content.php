<?php
namespace Ipol\Fivepost\Api\Entity\Response\Part\OrdersMake;

use Ipol\Fivepost\Api\Entity\AbstractEntity;
use Ipol\Fivepost\Api\Entity\Response\Part\AbstractResponsePart;

/**
 * Class Content
 * @package Ipol\Fivepost\Api\Entity\Response\Part
 */
class Content extends AbstractEntity
{
    use AbstractResponsePart;

    /**
     * @var bool
     */
    protected $created;

    /**
     * @var string|null uuid
     */
    protected $orderId;

    /**
     * @var string
     */
    protected $senderOrderId;

    /**
     * @var CargoList
     */
    protected $cargoes;

    /**
     * @var ErrorList|null
     */
    protected $errors;

    /**
     * @return bool
     */
    public function isCreated(): bool
    {
        return $this->created;
    }

    /**
     * @param bool $created
     * @return Content
     */
    public function setCreated(bool $created): Content
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOrderId(): ?string
    {
        return $this->orderId;
    }

    /**
     * @param string|null $orderId
     * @return Content
     */
    public function setOrderId(?string $orderId): Content
    {
        $this->orderId = $orderId;
        return $this;
    }

    /**
     * @return string
     */
    public function getSenderOrderId(): string
    {
        return $this->senderOrderId;
    }

    /**
     * @param string $senderOrderId
     * @return Content
     */
    public function setSenderOrderId(string $senderOrderId): Content
    {
        $this->senderOrderId = $senderOrderId;
        return $this;
    }

    /**
     * @return CargoList
     */
    public function getCargoes(): CargoList
    {
        return $this->cargoes;
    }

    /**
     * @param array $array
     * @return Content
     */
    public function setCargoes(array $array): Content
    {
        $collection = new CargoList();
        $this->cargoes = $collection->fillFromArray($array);
        return $this;
    }

    /**
     * @return ErrorList|null
     */
    public function getErrors(): ?ErrorList
    {
        return $this->errors;
    }

    /**
     * @param array $array
     * @return Content
     */
    public function setErrors(array $array): Content
    {
        $collection = new ErrorList();
        $this->errors = $collection->fillFromArray($array);
        return $this;
    }
}