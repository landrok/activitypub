<?php

/*
 * This file is part of the ActivityPhp package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

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
