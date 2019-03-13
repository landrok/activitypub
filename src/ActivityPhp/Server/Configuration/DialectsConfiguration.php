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

    /**
     * Create cache pool instance
     * 
     * @return \Psr\Cache\CacheItemPoolInterface
     */
    public function createPool()
    {
        if (!class_exists($this->pool)) {
            throw new Exception(
                "Cache pool driver does not exist. Given='{$this->pool}'"
            );
        }

        if (!$this->enabled) {
            return;
        }


        // Create a filesystem pool
        if ($this->type == 'filesystem') {
            $filesystemAdapter = new Local($this->stream);
            $filesystem        = new Filesystem($filesystemAdapter);
            $pool = new $this->pool($filesystem);
            return $pool;
        }
    }

    /**
     * Get TTL value
     * 
     * @return int
     */
    public function getTtl()
    {
        return $this->ttl;
    }
}
