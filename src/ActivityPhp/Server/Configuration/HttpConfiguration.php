<?php

/*
 * This file is part of the ActivityPhp package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPhp\Server\Configuration;

use ActivityPhp\Server;

/**
 * Server HTTP configuration stack
 */ 
class HttpConfiguration extends AbstractConfiguration
{
    /**
     * @var string default HTTP scheme
     */
    protected $scheme = 'https';

    /**
     * @var float|int HTTP timeout for request
     */
    protected $timeout = 10.0;

    /**
     * @var string The User Agent.
     */
    protected $agent = '';

    /**
     * Dispatch configuration parameters
     * 
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        parent::__construct($params);
    }
}
