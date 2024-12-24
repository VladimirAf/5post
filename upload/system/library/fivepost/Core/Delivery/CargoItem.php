<?php


namespace Ipol\Fivepost\Core\Delivery;


use Ipol\Fivepost\Core\Entity\FieldsContainer;
use Ipol\Fivepost\Core\Entity\Money;

/**
 * Class CargoItem
 * @package Ipol\Fivepost\Core
 * @subpackage Delivery
 * Description of basic product (ware, goods) (length, width, height, quantity)
 * l,w,h - mm
 * w - g
 * v - m3
 */
class CargoItem
{
    use FieldsContainer;

    /**
     * @var int - mm
     */
    protected $length;
    /**
     * @var int - mm
     */
    protected $width;
    /**
     * @var int - mm
     */
    protected $height;
    /**
     * @var float - m^3
     */
    protected $volume;
    /**
     * @var - int gram
     */
    protected $weight;
    /**
     * @var int
     */
    protected $quantity = 1;
    /**
     * @var null|Money price to be payed for item
     */
   protected $price;
    /**
     * @var null|Money estimated cost for insurance
     */
   protected $cost;
    /**
     * @var bool
     */
    protected $overSize = false;

    /**
     * @return null|Money price to be payed for item
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param Money $price price to be payed for item
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return null|Money estimated cost for insurance
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param Money $cost estimated cost for insurance
     * @return $this
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     * @return $this
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

   public function giveVolume()
   {
       return ($this->getVolume()) ? : ($this->getHeight() * $this->getWidth() * $this->getLength());
   }

    /**
     * @return float
     */
    public function getVolume()
    {
        return $this->volume;
    }

    /**
     * @param float $volume
     * @return $this
     */
    public function setVolume($volume)
    {
        $this->volume = $volume;

        return $this;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     * @return $this
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param int $weight
     * @return $this
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param int $length
     * @return $this
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     * @return $this
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return bool
     */
    public function ready()
    {
       if(!$this->getWeight())
           return false;
       if(!$this->giveVolume())
           return false;
       return true;
   }

   public function setGabs($length, $width, $height)
   {
       $this->setLength($length);
       $this->setWidth($width);
       $this->setHeight($height);

       return $this;
   }

    /**
     * @return mixed
     */
    public function getOverSize()
    {
        return $this->overSize;
    }

    /**
     * @param mixed $overSize
     */
    public function setOverSize($overSize)
    {
        $this->overSize = $overSize;
    }

}