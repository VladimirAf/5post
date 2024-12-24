<?php

namespace Ipol\Fivepost\Api\Entity;

/**
 * Interface EncoderInterface
 * @package Ipol\Fivepost\Others
 * Encodes handle from API-server into cms encoding
 */
interface EncoderInterface
{
    public function encodeToAPI($handle);

    public function encodeFromAPI($handle);
}