<?php
namespace Ipol\Fivepost\Api\Entity\Response\Part\OrderLabels;

use Ipol\Fivepost\Api\Entity\AbstractEntity;

/**
 * Class OrderLabelsResult
 * @package Ipol\Fivepost\Api\Entity\Response
 */
class OrderLabelsResult extends AbstractEntity
{
    /**
     * @var string|null uuid
     */
    protected $orderId;

    /**
     * @var string|null CMS order number
     */
    protected $senderOrderId;

    /**
     * @var string 'SUCCESS' | 'FAILED'
     */
    protected $result;

    /**
     * @var string|null
     */
    protected $fileName;

    /**
     * @var string|null Error text
     */
    protected $reason;

    // --- Non result.txt fields ---

    /**
     * @var string|null
     */
    protected $fileContent;

    /**
     * @var bool Sets to true if file successfully requested
     */
    protected $isSuccess;

    /**
     * @return string|null
     */
    public function getOrderId(): ?string
    {
        return $this->orderId;
    }

    /**
     * @param string|null $orderId
     * @return OrderLabelsResult
     */
    public function setOrderId(?string $orderId): OrderLabelsResult
    {
        $this->orderId = $orderId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSenderOrderId(): ?string
    {
        return $this->senderOrderId;
    }

    /**
     * @param string|null $senderOrderId
     * @return OrderLabelsResult
     */
    public function setSenderOrderId(?string $senderOrderId): OrderLabelsResult
    {
        $this->senderOrderId = $senderOrderId;
        return $this;
    }

    /**
     * @return string
     */
    public function getResult(): string
    {
        return $this->result;
    }

    /**
     * @param string $result
     * @return OrderLabelsResult
     */
    public function setResult(string $result): OrderLabelsResult
    {
        $this->result = $result;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * @param string|null $fileName
     * @return OrderLabelsResult
     */
    public function setFileName(?string $fileName): OrderLabelsResult
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }

    /**
     * @param string|null $reason
     * @return OrderLabelsResult
     */
    public function setReason(?string $reason): OrderLabelsResult
    {
        $this->reason = $reason;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFileContent(): ?string
    {
        return $this->fileContent;
    }

    /**
     * @param string|null $fileContent
     * @return OrderLabelsResult
     */
    public function setFileContent(?string $fileContent): OrderLabelsResult
    {
        $this->fileContent = $fileContent;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }

    /**
     * @param bool $isSuccess
     * @return OrderLabelsResult
     */
    public function setIsSuccess(bool $isSuccess): OrderLabelsResult
    {
        $this->isSuccess = $isSuccess;
        return $this;
    }
}