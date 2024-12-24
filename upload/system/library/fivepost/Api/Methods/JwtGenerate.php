<?php


namespace Ipol\Fivepost\Api\Methods;

use Ipol\Fivepost\Api\Adapter\CurlAdapter;
use Ipol\Fivepost\Api\BadResponseException;
use Ipol\Fivepost\Api\Entity\EncoderInterface;
use Ipol\Fivepost\Api\Entity\Request\JwtGenerate as ObjRequest;
use Ipol\Fivepost\Api\Entity\Response\ErrorResponse;
use Ipol\Fivepost\Api\Entity\Response\JwtGenerate as ObjResponse;


/**
 * Class JwtGenerate
 * @package Ipol\Fivepost\Api
 * @subpackage Methods
 * @method ObjResponse|ErrorResponse getResponse
 */
class JwtGenerate extends GeneralMethod
{
    /**
     * JwtGenerate constructor.
     * @param ObjRequest $data
     * @param CurlAdapter $adapter
     * @param EncoderInterface|null $encoder
     * @throws BadResponseException
     */
    public function __construct(ObjRequest $data, CurlAdapter $adapter, ?EncoderInterface $encoder)
    {
        $this->setDataGet($this->encodeFieldToAPI(['apikey' => $data->getApikey()]));
        $this->setDataPost($this->encodeFieldToAPI([
            'subject' => $data->getSubject(),
            'audience' => $data->getAudience(),
        ]));

        parent::__construct(null, $adapter, ObjResponse::class, $encoder);

    }

}