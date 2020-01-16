<?php

namespace ActivityPhp\Server\Activity;

use Psr\Http\Message\ResponseInterface;

/**
 * Interface for all activity handlers
 */ 
interface HandlerInterface
{
    /**
     * @return self
     */
    public function handle(): ResponseInterface;
}
