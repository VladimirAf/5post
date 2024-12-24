<?php


namespace Ipol\Fivepost\Core\Delivery;


use Exception;
use Ipol\Fivepost\Core\Entity\Collection;
use Ipol\Fivepost\Core\Entity\Money;
use Ipol\Fivepost\Core\Entity\Packing\MebiysDimMerger;

/**
 * Class Cargo
 * @package Ipol\Fivepost\Core
 * @subpackage Delivery
 * Cargo description, consist of basic goods
 * @method false|CargoItem getFirst
 * @method false|CargoItem getNext
 * @method false|CargoItem getLast
 */
class Cargo extends Collection
{
    /**
     * @var array
     */
    protected $Items;
    /**
     * @var MebiysDimMerger|mixed
     */
    protected $packer;

    /**
     * Cargo constructor.
     * @param false $packer
     */
    public function __construct($packer = false)
    {
        parent::__construct('Items');
        $this->packer = $packer? new $packer : new MebiysDimMerger();
    }

    /**
     * @param CargoItem $item
     * @return $this
     * @throws Exception
     */
    public function add($item)
    {
        if($item->ready()) {
            parent::add($item);
        }else
            throw new Exception('CargoItem is not ready in '.get_class());

        return $this;
    }

    /**
     * @return array (L, W, H)
     */
    public function getDimensions()
    {
        $arGabs = array();

        $this->reset();
        while($obItem = $this->getNext())
        {
            $arGabs[] = array($obItem->getLength(), $obItem->getWidth(), $obItem->getHeight(), $obItem->getQuantity());
        }

        return $this->packer::getSumDimensions($arGabs);
    }

    /**
     * @return float
     */
    public function getVolume()
    {
        $volume = 0;

        $this->reset();
        while($obItem = $this->getNext())
        {
            $volume += $obItem->giveVolume() * $obItem->getQuantity();
        }

        return $volume;
    }

    /**
     * @return int
     */
    public function getWeight()
    {
        $weight = 0;

        $this->reset();
        while($obItem = $this->getNext())
        {
            $weight += $obItem->getWeight() * $obItem->getQuantity();
        }

        return $weight;
    }

    /**
     * @return array
     */
    public function getGabs()
    {
        return array('W'=>$this->getWeight(), 'V'=>$this->getVolume(), 'G'=>$this->getDimensions());
    }

    /**
     * @return Money total price to be payed for items
     */
    public function getTotalPrice()
    {
        $price = new Money(0);

        $this->reset();
        while($obItem = $this->getNext())
        {
            if($obItem->getPrice())
                $price = Money::sum($price, Money::multiply($obItem->getPrice(), $obItem->getQuantity()));
        }

        return $price;
    }

    /**
     * @return Money total estimated cost for insurance
     */
    public function getTotalCost()
    {
        $cost = new Money(0);

        $this->reset();
        while($obItem = $this->getNext())
        {
            if($obItem->getCost())
                $cost = Money::sum($cost, Money::multiply($obItem->getCost(), $obItem->getQuantity()));
        }

        return $cost;
    }

    /**
     * @return bool
     */
    public function checkOverSize()
    {
        $this->reset();
        while($obItem = $this->getNext())
        {
            if($obItem->getOverSize())
                return true;
        }

        return false;
    }
}