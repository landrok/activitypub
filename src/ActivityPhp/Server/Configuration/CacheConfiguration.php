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
     * @const default driver name
     */
    const DEFAULT_DRIVER = '\Symfony\Component\Cache\Adapter\FilesystemAdapter';

    /**
     * @var string Cache type
     */
    protected $type = 'filesystem';

    /**
     * @var bool
     */
    protected $enabled = true;

    /**
     * @var string|CacheItemPoolInterface A cache pool or a driver name
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
            $this->stream = getcwd() . '/cache';
        }
    }

    /**
     * Create cache pool instance
     *
     * @return \Psr\Cache\CacheItemPoolInterface
     */
    public function createPool(): ?CacheItemPoolInterface
    {
        if (is_string($this->pool) && !class_exists($this->pool)) {
            throw new Exception(
                "Cache pool driver does not exist. Given='{$this->pool}'"
            );
        }

        if (!$this->enabled) {
            return null;
        }

        // Create a filesystem pool
        if ($this->type == 'filesystem' && $this->pool == self::DEFAULT_DRIVER) {
            return new $this->pool($this->ttl, 0, $this->stream);
        }

        // Instanciate a pool with a custom driver name
        if (is_string($this->pool)) {
            return new $this->pool();
        }

        // An instanciated pool has been given as parameter
        if ($this->pool instanceof CacheItemPoolInterface) {
            return $this->pool;
        }

        // An instanciated pool has been given as parameter but is not
        // Psr\Cache compliant
        if (is_object($this->pool)
            && !($this->pool instanceof CacheItemPoolInterface)
        ) {
            $message = sprintf(
                "Given cache instance '%s' does not respect '%s' definition.",
                get_class($this->pool),
                CacheItemPoolInterface::class
            );

            throw new Exception($message);
        }

        // Finally throw an exception because cache configuration does
        // not satisfy requirements
        $message = sprintf(
            "Cache pool has not been instanciated. Given parameter '%s'",
            $this->pool
        );

        throw new Exception($message);
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
