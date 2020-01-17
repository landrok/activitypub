<?php

declare(strict_types=1);

namespace ActivityPhp\Server\Http;

interface ActivityPubClientInterface
{
    /**
     * @param string $url
     * @return array The decoded JSON content as an assoc array
     */
    public function get(string $url): array;
}