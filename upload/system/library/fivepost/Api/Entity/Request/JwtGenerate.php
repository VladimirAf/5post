<?php

namespace Ipol\Fivepost\Api\Entity\Request;


/**
 * Class JwtGenerate
 * @package Ipol\Fivepost\Api\Entity\Request
 */
class JwtGenerate extends AbstractRequest
{
    /**
     * @var string
     */
    protected $subject='OpenAPI';
    /**
     * @var string
     */
    protected $audience='A122019!';
    /**
     * @var string
     */
    protected $apikey;

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     * @return JwtGenerate
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return string
     */
    public function getAudience()
    {
        return $this->audience;
    }

    /**
     * @param string $audience
     * @return JwtGenerate
     */
    public function setAudience($audience)
    {
        $this->audience = $audience;
        return $this;
    }

    /**
     * @return string
     */
    public function getApikey()
    {
        return $this->apikey;
    }

    /**
     * @param string $apikey
     * @return JwtGenerate
     */
    public function setApikey($apikey)
    {
        $this->apikey = $apikey;
        return $this;
    }

}