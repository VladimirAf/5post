<?php
namespace Ipol\Fivepost\Api\Entity\Response\Part\OrdersMake;

use Ipol\Fivepost\Api\Entity\AbstractEntity;
use Ipol\Fivepost\Api\Entity\Response\Part\AbstractResponsePart;

/**
 * Class Error
 * @package Ipol\Fivepost\Api\Entity\Response\Part
 */
class Error extends AbstractEntity
{
    use AbstractResponsePart;

    /**
     * @var int
     */
    protected $code;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var ConflictsInfoList|null
     */
    protected $conflictsInfo;

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     * @return Error
     */
    public function setCode(int $code): Error
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return Error
     */
    public function setMessage(string $message): Error
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return ConflictsInfoList|null
     */
    public function getConflictsInfo(): ?ConflictsInfoList
    {
        return $this->conflictsInfo;
    }

    /**
     * @param array $array
     * @return Error
     */
    public function setConflictsInfo(array $array): Error
    {
        $collection = new ConflictsInfoList();
        $this->conflictsInfo = $collection->fillFromArray($array);
        return $this;
    }
}