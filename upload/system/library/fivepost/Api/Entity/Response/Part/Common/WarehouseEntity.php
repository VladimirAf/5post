<?php
namespace Ipol\Fivepost\Api\Entity\Response\Part\Common;

use Ipol\Fivepost\Api\Entity\Response\Part\AbstractResponsePart;

class WarehouseEntity extends \Ipol\Fivepost\Api\Entity\UniversalPart\WarehouseEntity
{
    use AbstractResponsePart;

    /**
     * @var string warehouse UUID
     */
    protected $id;

    /**
     * @var string always 'PARTNER_WAREHOUSE'
     */
    protected $type;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var string format 2020-10-09T12:08:59.419719+03:00
     */
    protected $createDate;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return WarehouseEntity
     */
    public function setId(string $id): WarehouseEntity
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return WarehouseEntity
     */
    public function setType(string $type): WarehouseEntity
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return WarehouseEntity
     */
    public function setStatus(string $status): WarehouseEntity
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreateDate(): string
    {
        return $this->createDate;
    }

    /**
     * @param string $createDate
     * @return WarehouseEntity
     */
    public function setCreateDate(string $createDate): WarehouseEntity
    {
        $this->createDate = $createDate;
        return $this;
    }
}