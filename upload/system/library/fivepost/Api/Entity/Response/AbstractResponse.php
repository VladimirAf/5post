<?

namespace Ipol\Fivepost\Api\Entity\Response;

use Ipol\Fivepost\Api\BadResponseException;
use Ipol\Fivepost\Api\Entity\AbstractEntity;


/**
 * Class AbstractResponse
 * @package Ipol\Fivepost\Api\Entity\Response
 */
class AbstractResponse extends AbstractEntity
{
    /**
     * @var string
     */
    protected $origin;
    /**
     * @var mixed
     */
    protected $decoded;
    /**
     * @var bool - internal Api-layer flag for checking successful request
     */
    protected $requestSuccess = false;
    /**
     * @var bool - api-response field, get content directly from X5 server
     */
    protected $Success;
    /**
     * @var int
     */
    protected $ErrorCode;
    /**
     * @var string
     */
    protected $ErrorMsg;

    /**
     * AbstractResponse constructor.
     * @param $json
     * @throws BadResponseException
     */
    function __construct($json)
    {
        parent::__construct();

        $this->origin = $json;

        if(empty($json))
        {
            throw new BadResponseException('Empty server answer '.__CLASS__);
        }

        $this->setDecoded(json_decode($json));

        if(is_null($this->decoded))
        {
            throw new BadResponseException('Incorrect server answer '.__CLASS__);
        }
    }

    /**
     * @return mixed
     */
    public function getDecoded()
    {
        return $this->decoded;
    }

    /**
     * @param mixed $decoded
     * @return $this
     */
    public function setDecoded($decoded)
    {
        $this->decoded = $decoded;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRequestSuccess(): bool
    {
        return $this->requestSuccess;
    }

    /**
     * @param bool $requestSuccess
     * @return AbstractResponse
     */
    public function setRequestSuccess(bool $requestSuccess): AbstractResponse
    {
        $this->requestSuccess = $requestSuccess;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSuccess()
    {
        return $this->Success;
    }

    /**
     * @param mixed $Success
     * @return $this
     */
    public function setSuccess($Success)
    {
        if($Success === 'false')
            $Success = false;
        $this->Success = $Success;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getErrorCode()
    {
        return $this->ErrorCode;
    }

    /**
     * @param mixed $ErrorCode
     * @return $this
     */
    public function setErrorCode($ErrorCode)
    {
        $this->ErrorCode = $ErrorCode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getErrorMsg()
    {
        return $this->ErrorMsg;
    }

    /**
     * @param mixed $ErrorMsg
     * @return $this
     */
    public function setErrorMsg($ErrorMsg)
    {
        $this->ErrorMsg = $ErrorMsg;

        return $this;
    }

}