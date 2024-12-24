<?php


namespace Ipol\Fivepost\Api\Entity\Response\Part;

trait AbstractResponsePart
{
    /**
     * AbstractResponsePart constructor.
     * @param array $fields
     */
    public function __construct($fields = [])
    {
        parent::__construct();
        $this->setFields($fields);
    }
}