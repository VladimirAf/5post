<?php
namespace Ipol\Fivepost\Api\Entity\Response;

use Ipol\Fivepost\Api\Entity\Response\Part\OrdersMake\ContentList;

/**
 * Class OrdersMake
 * @package Ipol\Fivepost\Api\Entity\Response
 */
class OrdersMake extends AbstractResponse
{
    /**
     * @var ContentList
     */
    protected $contentList;

    /**
     * @return ContentList
     */
    public function getContentList(): ContentList
    {
        return $this->contentList;
    }

    /**
     * @param array $array
     * @return OrdersMake
     */
    public function setContentList(array $array): OrdersMake
    {
        $collection = new ContentList();
        $this->contentList = $collection->fillFromArray($array);
        return $this;
    }

    public function setFields($fields): OrdersMake
    {
        return parent::setFields(['contentList' => $fields]);
    }
}