<?php

declare(strict_types=1);

namespace ActivityPhp\Server\Http;

final class JsonEncoder implements EncoderInterface
{

    /**
     * @var int
     */
    private $options;

    /**
     * @param int $options
     */
    public function __construct(int $options = 0)
    {
        $this->options = $options;
    }

    /**
     * @param array $normalizedData
     * @return string
     */
    public function encode(array $normalizedData): string
    {
        return json_encode($normalizedData, $this->options);
    }
}
