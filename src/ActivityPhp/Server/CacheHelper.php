<?php

/*
 * This file is part of the ActivityPhp package.
 *
 * Copyright (c) landrok at github.com/landrok
 *
 * For the full copyright and license information, please see
 * <https://github.com/landrok/activitypub/blob/master/LICENSE>.
 */

namespace ActivityPhp\Server;

use ActivityPhp\Server\Configuration\CacheConfiguration;
use Exception;

/**
 * \ActivityPhp\Server\CacheHelper provides global helper methods for 
 * cache manipulation.
 */
abstract class CacheHelper
{
    /**
     *@var null|\Psr\Cache\CacheItemPoolInterface
     */
    protected static $pool;

    /**
     * @var null|\ActivityPhp\Server\Configuration\CacheConfiguration
     */
    protected static $config;

    /**
     * Inject a cache pool
     * 
     * @param  \ActivityPhp\Server\Configuration\CacheConfiguration $config
     */
    public static function setPool(CacheConfiguration $config)
    {
        self::$config = $config;
        self::$pool = $config->createPool();
    }

    /**
     * Set a cache item
     * 
     * @param  string $key
     * @param  mixed $value
     */
    public static function set(string $key, $value)
    {
        if (!is_null(self::$pool)) {
            $item = self::$pool->getItem(
                self::key($key)
            );
            $item->set($value);
            $item->expiresAfter(
                self::$config->getTtl()
            );
            self::$pool->save($item);
        }
    }

    /**
     * Get a cache item content
     * 
     * @return mixed
     */
    public static function get(string $key)
    {
        if (!is_null(self::$pool)) {
            $item = self::$pool->getItem(
                self::key($key)
            );
            return $item->get();
        }
    }

    /**
     * Check that a cache item exists
     * 
     * @return bool
     */
    public static function has(string $key)
    {
        if (!is_null(self::$pool)) {
            return self::$pool->has(
                self::key($key)
            );
        }
    }

    /**
     * Normalize hash keys
     * 
     * @param  string $value
     * @return string
     */
    private static function key(string $value)
    {
        return md5($value);
    }
}
