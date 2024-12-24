<?php
namespace Ipol\Fivepost\Api\Entity\Response\Part\OrdersMake;

use Ipol\Fivepost\Api\Entity\AbstractEntity;
use Ipol\Fivepost\Api\Entity\Response\Part\AbstractResponsePart;

/**
 * Class Cargo
 * @package Ipol\Fivepost\Api\Entity\Response\Part
 */
class Cargo extends AbstractEntity
{
    use AbstractResponsePart;

    /**
     * @var string|null (uuid)
     */
    protected $cargoId;

    /**
     * @var string
     */
    protected $senderCargoId;

    /**
     * @var string
     */
    protected $barcode;

    /**
     * @return string|null
     */
    public function getCargoId(): ?string
    {
        return $this->cargoId;
    }

    /**
     * @param string|null $cargoId
     * @return Cargo
     */
    public function setCargoId(?string $cargoId): Cargo
    {
        $this->cargoId = $cargoId;
        return $this;
    }

    /**
     * @return string
     */
    public function getSenderCargoId(): string
    {
        return $this->senderCargoId;
    }

    /**
     * @param string $senderCargoId
     * @return Cargo
     */
    public function setSenderCargoId(string $senderCargoId): Cargo
    {
        $this->senderCargoId = $senderCargoId;
        return $this;
    }

    /**
     * @return string
     */
    public function getBarcode(): string
    {
        return $this->barcode;
    }

    /**
     * @param string $barcode
     * @return Cargo
     */
    public function setBarcode(string $barcode): Cargo
    {
        $this->barcode = $barcode;
        return $this;
    }
}