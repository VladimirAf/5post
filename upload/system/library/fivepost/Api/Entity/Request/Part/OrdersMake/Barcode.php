<?php
namespace Ipol\Fivepost\Api\Entity\Request\Part\OrdersMake;

use Ipol\Fivepost\Api\Entity\AbstractEntity;

/**
 * Class Barcode
 * @package Ipol\Fivepost\Api
 * @subpackage Request
 */
class Barcode extends AbstractEntity
{
    /**
     * @var string
     */
    protected $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        parent::__construct();
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return Barcode
     */
    public function setValue(string $value): Barcode
    {
        $this->value = $value;
        return $this;
    }
}