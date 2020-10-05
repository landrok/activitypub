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

use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

/**
 * Cache configuration stack
 */
class CacheConfiguration extends AbstractConfiguration
{
    /**
     * @var string Cache type
     */
    protected $type = 'filesystem';

    /**
     * @var bool
     */
    protected $enabled = true;

    /**
     * @var string Cache class name
     */
    protected $pool = '\Symfony\Component\Cache\Adapter\FilesystemAdapter';

    /**
     * @var string Cache stream
     */
    protected $stream;

    /**
     * @var int Time To live in seconds
     */
    protected $ttl = 3600;

    /**
     * Dispatch configuration parameters
     *
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        parent::__construct($params);

        if (!isset($params['stream'])) {
            $this->stream = getcwd();
        }

        // Custom driver

    }

    /**
     * Create cache pool instance
     *
     * @return \Psr\Cache\CacheItemPoolInterface
     */
    public function createPool(): ?CacheItemPoolInterface
    {
        if (!class_exists($this->pool)) {
            throw new Exception(
                "Cache pool driver does not exist. Given='{$this->pool}'"
            );
        }

        if (!$this->enabled) {
            return null;
        }

        // Create a filesystem pool
        if ($this->type == 'filesystem') {
            return new $this->pool($this->ttl, 0, $this->stream);
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
