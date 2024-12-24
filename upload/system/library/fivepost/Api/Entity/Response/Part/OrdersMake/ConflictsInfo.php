<?php
namespace Ipol\Fivepost\Api\Entity\Response\Part\OrdersMake;

use Ipol\Fivepost\Api\Entity\AbstractEntity;
use Ipol\Fivepost\Api\Entity\Response\Part\AbstractResponsePart;

/**
 * Class ConflictsInfo
 * @package Ipol\Fivepost\Api\Entity\Response\Part
 */
class ConflictsInfo extends AbstractEntity
{
    use AbstractResponsePart;

    /**
     * @var string
     */
    protected $conflicedField;

    /**
     * @var string
     */
    protected $conflictedValue;

    /**
     * @var string uuid
     */
    protected $existedOrderId;

    /**
     * @var string
     */
    protected $existedSenderOrderId;

    /**
     * @var string|null uuid
     */
    protected $existedCargoId;

    /**
     * @var string|null
     */
    protected $existedSenderCargoId;

    /**
     * @var string|null
     */
    protected $requestedSenderCargoId;

    /**
     * @return string
     */
    public function getConflicedField(): string
    {
        return $this->conflicedField;
    }

    /**
     * @param string $conflicedField
     * @return ConflictsInfo
     */
    public function setConflicedField(string $conflicedField): ConflictsInfo
    {
        $this->conflicedField = $conflicedField;
        return $this;
    }

    /**
     * @return string
     */
    public function getConflictedValue(): string
    {
        return $this->conflictedValue;
    }

    /**
     * @param string $conflictedValue
     * @return ConflictsInfo
     */
    public function setConflictedValue(string $conflictedValue): ConflictsInfo
    {
        $this->conflictedValue = $conflictedValue;
        return $this;
    }

    /**
     * @return string
     */
    public function getExistedOrderId(): string
    {
        return $this->existedOrderId;
    }

    /**
     * @param string $existedOrderId
     * @return ConflictsInfo
     */
    public function setExistedOrderId(string $existedOrderId): ConflictsInfo
    {
        $this->existedOrderId = $existedOrderId;
        return $this;
    }

    /**
     * @return string
     */
    public function getExistedSenderOrderId(): string
    {
        return $this->existedSenderOrderId;
    }

    /**
     * @param string $existedSenderOrderId
     * @return ConflictsInfo
     */
    public function setExistedSenderOrderId(string $existedSenderOrderId): ConflictsInfo
    {
        $this->existedSenderOrderId = $existedSenderOrderId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getExistedCargoId(): ?string
    {
        return $this->existedCargoId;
    }

    /**
     * @param string|null $existedCargoId
     * @return ConflictsInfo
     */
    public function setExistedCargoId(?string $existedCargoId): ConflictsInfo
    {
        $this->existedCargoId = $existedCargoId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getExistedSenderCargoId(): ?string
    {
        return $this->existedSenderCargoId;
    }

    /**
     * @param string|null $existedSenderCargoId
     * @return ConflictsInfo
     */
    public function setExistedSenderCargoId(?string $existedSenderCargoId): ConflictsInfo
    {
        $this->existedSenderCargoId = $existedSenderCargoId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRequestedSenderCargoId(): ?string
    {
        return $this->requestedSenderCargoId;
    }

    /**
     * @param string|null $requestedSenderCargoId
     * @return ConflictsInfo
     */
    public function setRequestedSenderCargoId(?string $requestedSenderCargoId): ConflictsInfo
    {
        $this->requestedSenderCargoId = $requestedSenderCargoId;
        return $this;
    }
}