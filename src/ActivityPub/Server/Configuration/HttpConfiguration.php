<?php

/*
 * This file is part of the ActivityPub package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPub\Server\Configuration;

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
     * Dispatch configuration parameters
     * 
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        parent::__construct($params);
    }
}
