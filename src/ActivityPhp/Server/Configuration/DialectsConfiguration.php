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

use ActivityPhp\Type\Dialect;

/**
 * Dialects configuration stack
 */ 
class DialectsConfiguration extends AbstractConfiguration
{
    /**
     * Dispatch configuration parameters
     * 
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        foreach ($params as $dialect => $definitions) {
            Dialect::add($dialect, $definitions);
        }    
    }
}
