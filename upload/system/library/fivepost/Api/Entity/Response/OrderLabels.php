<?php
namespace Ipol\Fivepost\Api\Entity\Response;

use stdClass;
use ZipArchive;
use Ipol\Fivepost\Api\BadResponseException;
use Ipol\Fivepost\Api\Entity\Response\Part\OrderLabels\OrderLabelsResultList;

/**
 * Class OrderLabels
 * @package Ipol\Fivepost\Api\Entity\Response
 */
class OrderLabels extends AbstractResponse
{
    /**
     * @var OrderLabelsResultList
     */
    protected $orderLabelsResults;

    /**
     * OrderLabels constructor.
     * @param $data
     * @throws BadResponseException
     */
    public function __construct($data)
    {
        // NO PARENT CONSTRUCTOR called due to specific API answer: raw file content instead of JSON

        $this->origin = $data;

        if (empty($data)) {
            throw new BadResponseException('Empty server answer '.__CLASS__);
        }

        $orderLabelsResult = [];
        $errors            = [];
        if (class_exists('ZipArchive')) {
            if ($tmpFile = tempnam(sys_get_temp_dir(), md5(uniqid(microtime(true))))) {
                if (file_put_contents($tmpFile, $data) !== false) {
                    $zip = new ZipArchive;
                    $openResult = $zip->open($tmpFile);

                    if ($openResult === true) {
                        // Deal with results in JSON file
                        $resultContent = $zip->getFromName('results.txt');
                        $resultContent = trim($resultContent);

                        if (!empty($resultContent)) {
                            $results = json_decode(trim($resultContent), true);

                            if (is_null($results) || json_last_error() !== JSON_ERROR_NONE) {
                                $errors[] = 'File results.txt structure has broken';
                            } else {
                                if (array_key_exists('results', $results) && is_array($results['results'])) {
                                    // Make API-like decoded answer array
                                    foreach ($results['results'] as $val) {
                                        $tmp = new stdClass();
                                        $tmp->orderId       = ($val['orderId']) ?: null; // uuid
                                        $tmp->senderOrderId = ($val['senderOrderId']) ?: null; // CMS number
                                        $tmp->result        = $val['result'];
                                        $tmp->fileName      = ($val['fileName']) ?: null;
                                        $tmp->reason        = ($val['reason']) ?: null;
                                        $tmp->fileContent   = null;
                                        $tmp->isSuccess     = false;

                                        if ($tmp->result === 'SUCCESS' && $tmp->fileName) {
                                            $resultPDFContent = $zip->getFromName($tmp->fileName);
                                            if (!empty($resultPDFContent)) {
                                                $tmp->fileContent = $resultPDFContent;
                                                $tmp->isSuccess   = true;
                                            } else {
                                                $tmp->reason = 'Error while extracting file '.$tmp->fileName;
                                            }
                                        }

                                        $orderLabelsResult[] = $tmp;
                                    }
                                } else {
                                    $errors[] = 'File results.txt miss results data';
                                }
                            }
                        } else {
                            $errors[] = 'File results.txt are empty';
                        }

                        $zip->close();
                    } else {
                        $errors[] = 'Error code '.$openResult.' raised while opening archive '.$tmpFile;
                    }

                    unlink($tmpFile);
                } else {
                    $errors[] = 'Error while writing order labels archive to temporary file '.$tmpFile;
                }
            } else {
                $errors[] = 'Can not create temporary file for order labels';
            }
        } else {
            $errors[] = 'PHP Zip extension required';
        }

        if (!empty($errors)) {
            throw new BadResponseException(implode(', ', $errors));
        }

        $this->setDecoded($orderLabelsResult);
    }

    /**
     * @return OrderLabelsResultList
     */
    public function getOrderLabelsResults(): OrderLabelsResultList
    {
        return $this->orderLabelsResults;
    }

    /**
     * @param array $array
     * @return OrderLabels
     */
    public function setOrderLabelsResults(array $array): OrderLabels
    {
        $collection = new OrderLabelsResultList();
        $this->orderLabelsResults = $collection->fillFromArray($array);
        return $this;
    }

    public function setFields($fields): OrderLabels
    {
        return parent::setFields(['orderLabelsResults' => $fields]);
    }
}