<?php

declare(strict_types=1);

namespace ActivityPhp\Server\Http;

interface ActivityPubClientInterface
{
    public function get(string $url): string;
}