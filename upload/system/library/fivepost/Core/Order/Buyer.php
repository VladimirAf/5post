<?php
namespace Ipol\Fivepost\Core\Order;


/**
 * Class Buyer
 * @package Ipol\Fivepost\Core
 * @subpackage Order
 */
class Buyer extends AbstractPerson
{
    /**
     * @var null|string
     */
    protected $additionalPhone;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return null|string
     */
    public function getAdditionalPhone()
    {
        return $this->additionalPhone;
    }

    /**
     * @param string $additionalPhone
     * @return $this
     */
    public function setAdditionalPhone($additionalPhone)
    {
        $this->additionalPhone = $additionalPhone;

        return $this;
    }
}