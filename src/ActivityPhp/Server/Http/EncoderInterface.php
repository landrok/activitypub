<?php

namespace ActivityPhp\Server\Http;

interface EncoderInterface
{
    /**
     * @param array $normalizedData
     * @return string
     */
    public function encode(array $normalizedData): string;
}